<?php
// Mostrar errores (quitar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Config DB — ajusta a tu entorno
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "2901";
$dbName = "control";

$conexion = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$plainPasswordAll = "123";


$userIdToHash = null;          
$userEmailToHash = null;    

// ------------------------

try {
    // Backup recomendado — no lo hacemos aquí, solo recordatorio
    // Inicio transacción
    $conexion->begin_transaction();

    // 1) Actualizar todas las contraseñas a "123" (texto plano)
    $stmtAll = $conexion->prepare("UPDATE usuarios SET Contrasena = ?");
    if (!$stmtAll) throw new Exception("Error al preparar update masivo: " . $conexion->error);

    $stmtAll->bind_param('s', $plainPasswordAll);
    if (!$stmtAll->execute()) throw new Exception("Error al ejecutar update masivo: " . $stmtAll->error);
    $filasAfectadas = $conexion->affected_rows;
    $stmtAll->close();

    // 2) Si se indicó un usuario, generar hash y actualizar SOLO esa fila con el hash
    if (!empty($userIdToHash) || !empty($userEmailToHash)) {
        $hash = password_hash($plainPasswordAll, PASSWORD_DEFAULT);
        if ($hash === false) throw new Exception("Error al generar el hash de la contraseña.");

        if (!empty($userIdToHash)) {
            $stmtSingle = $conexion->prepare("UPDATE usuarios SET Contrasena = ? WHERE idUsuarios = ?");
            if (!$stmtSingle) throw new Exception("Error al preparar update por id: " . $conexion->error);
            $stmtSingle->bind_param('si', $hash, $userIdToHash);
        } else {
            // Asegúrate de que la columna de email se llame 'Email' o cámbiala aquí
            $stmtSingle = $conexion->prepare("UPDATE usuarios SET Contrasena = ? WHERE Email = ?");
            if (!$stmtSingle) throw new Exception("Error al preparar update por email: " . $conexion->error);
            $stmtSingle->bind_param('ss', $hash, $userEmailToHash);
        }

        if (!$stmtSingle->execute()) {
            $stmtSingle->close();
            throw new Exception("Error al actualizar usuario individual: " . $stmtSingle->error);
        }

        $filasHash = $stmtSingle->affected_rows;
        $stmtSingle->close();
    } else {
        $filasHash = 0;
    }

    // Commit
    $conexion->commit();

    echo "✅ Update masivo realizado. Filas afectadas por update masivo: $filasAfectadas\n";
    if ($filasHash > 0) {
        echo "🔒 Usuario individual actualizado con hash. Filas afectadas: $filasHash\n";
    } else {
        echo "ℹ️ No se especificó usuario individual para hashear (userIdToHash y userEmailToHash son nulos o vacíos).\n";
    }

} catch (Exception $e) {

    if ($conexion->in_transaction) $conexion->rollback();
    echo "❌ Ocurrió un error: " . $e->getMessage() . "\n";
}

$conexion->close();
?>
