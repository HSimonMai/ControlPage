<?php
abstract class AbstractMapper
{
    // 🔹 Configuración de conexión
    private string $usuario = "root";
    private string $contrasena = "2901";
    private string $servidor = "localhost";
    private string $basededatos = "control";

    // 🔹 Conexión disponible para todas las clases DAL
    protected mysqli $conexion;

    // 🔹 Consulta actual (para FindAll, FindOne, etc.)
    protected string $consulta = "";

    // 🔹 Constructor: se conecta automáticamente
    public function __construct()
    {
        $this->conexion = new mysqli(
            $this->servidor,
            $this->usuario,
            $this->contrasena,
            $this->basededatos
        );

        if ($this->conexion->connect_error) {
            die("❌ Error de conexión: " . $this->conexion->connect_error);
        }

        $this->conexion->set_charset("utf8mb4");
    }

    // 🔹 Permite que las clases hijas definan su consulta
    protected function setConsulta(string $consulta): void
    {
        $this->consulta = $consulta;
    }

    // 🔹 Carga los datos de una fila (las clases hijas implementan este método)
    abstract protected function doLoad(array $fila);

    // 🔹 Busca todos los registros según la consulta definida
    protected function findAll(array $params = []): array
    {
        if (empty($this->consulta)) {
            throw new Exception("⚠️ No se definió ninguna consulta SQL en el mapper.");
        }

        $resultado = $this->executeQuery($this->consulta, $params);
        $lista = [];

        while ($fila = $resultado->fetch_assoc()) {
            $lista[] = $this->doLoad($fila);
        }

        return $lista;
    }

    // 🔹 Método para ejecutar INSERT/UPDATE/DELETE
    protected function executeNonQuery(string $sql, array $params = []): bool|int
    {
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            die("❌ Error al preparar la consulta: " . $this->conexion->error);
        }

        if (!empty($params)) {
            $tipos = "";
            $valores = [];

            foreach ($params as $param) {
                $tipos .= match (gettype($param)) {
                    "integer" => "i",
                    "double"  => "d",
                    default   => "s",
                };
                $valores[] = $param;
            }

            $stmt->bind_param($tipos, ...$valores);
        }

        if (!$stmt->execute()) {
            die("❌ Error al ejecutar la consulta: " . $stmt->error);
        }

        $id = $stmt->insert_id;
        $stmt->close();

        return $id > 0 ? $id : true;
    }

    // 🔹 Método para ejecutar SELECT
    protected function executeQuery(string $sql, array $params = []): mysqli_result
    {
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) {
            die("❌ Error al preparar la consulta: " . $this->conexion->error);
        }

        if (!empty($params)) {
            $tipos = "";
            $valores = [];

            foreach ($params as $param) {
                $tipos .= match (gettype($param)) {
                    "integer" => "i",
                    "double"  => "d",
                    default   => "s",
                };
                $valores[] = $param;
            }

            $stmt->bind_param($tipos, ...$valores);
        }

        if (!$stmt->execute()) {
            die("❌ Error al ejecutar la consulta: " . $stmt->error);
        }

        return $stmt->get_result();
    }

    // 🔹 Destructor: cierra la conexión
    public function __destruct()
    {
        if ($this->conexion) {
            $this->conexion->close();
        }
    }
}
