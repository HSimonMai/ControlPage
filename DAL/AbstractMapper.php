<?php
abstract class AbstractMapper
{
    private $usuario = "root";
    private $contrasena = "2901";
    private $servidor = "localhost";
    private $basededatos = "control";

    protected $consulta;

    public function setConsulta($consulta): void
    {
        $this->consulta = $consulta;
    }

    public function Execute(): int|string|bool
    {
        $conexion = mysqli_connect($this->servidor, $this->usuario, $this->contrasena, $this->basededatos)
            or die("Error al conectar: " . mysqli_connect_error());

        mysqli_set_charset($conexion, 'utf8');

        $ok = mysqli_query($conexion, $this->consulta);

        if ($ok === false) {
            $error = mysqli_error($conexion);
            mysqli_close($conexion);
            die("Error en la consulta: $error");
        }

        $id = mysqli_insert_id($conexion);
        mysqli_close($conexion);

        // Si fue INSERT devuelve id, si fue UPDATE/DELETE devuelve true
        return $id > 0 ? $id : true;
    }

    public function FindAll(): array
    {
        $registros = [];
        $conexion = mysqli_connect($this->servidor, $this->usuario, $this->contrasena, $this->basededatos)
            or die("Error al conectar: " . mysqli_connect_error());

        mysqli_set_charset($conexion, 'utf8');
        $resultado = mysqli_query($conexion, $this->consulta);

        if ($resultado) {
            while ($columna = mysqli_fetch_assoc($resultado)) {
                $registros[] = $this->doLoad($columna);
            }
        }

        mysqli_close($conexion);
        return $registros;
    }

    public function Find(): mixed
    {
        $conexion = mysqli_connect($this->servidor, $this->usuario, $this->contrasena, $this->basededatos)
            or die("Error al conectar: " . mysqli_connect_error());

        mysqli_set_charset($conexion, 'utf8');
        $resultado = mysqli_query($conexion, $this->consulta);

        $objeto = null;
        if ($resultado && $columna = mysqli_fetch_assoc($resultado)) {
            $objeto = $this->doLoad($columna);
        }

        mysqli_close($conexion);
        return $objeto;
    }

    // 🔹 Cada DAL debe implementar cómo mapear filas a objetos
    abstract protected function doLoad($columna);
}
