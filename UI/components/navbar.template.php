<?php
require_once(__DIR__ . "/../../Entidades/Usuario.php");
require_once(__DIR__ . "/../../BLL/CursoBLL.php");

class Navbar_template
{
    private Usuario $usuario;

    public function __construct(Usuario $usuario)
    {
        $this->usuario = $usuario;
    }

    // 🔹 Trae los cursos por año, usando correctamente el BLL
    public function getCursosByAno($año)
    {
        $cursoBLL = new CursoBLL();
        $listaCursos = $cursoBLL->getAllCursos(); // usa DAL->getAllCursos()

        $linkCurso = '';
        foreach ($listaCursos as $curso) {
            // proteger si algún campo viene nulo
            $anoCurso = $curso->getAno() ?? 0;
            $division = $curso->getDivision() ?? "";

            if ((int)$anoCurso === (int)$año) {
                $linkCurso .= '
                    <a class="dropdown-item" href="../UI/Alumnos.php?idCurso=' . $curso->getId() . '">
                        ' . htmlspecialchars($anoCurso) . '° ' . htmlspecialchars($division) . '
                    </a>
                ';
            }
        }

        // si no hay cursos para ese año, mostrar vacío
        if (empty($linkCurso)) {
            $linkCurso = '<span class="dropdown-item text-muted">Sin cursos</span>';
        }

        return $linkCurso;
    }

    // 🔹 Renderizado del navbar completo
    public function render()
    {
        $navbar = '
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top justify-content-center">
    <div class="container-fluid">
        <span class="navbar-brand mx-auto">Administrador</span>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="./Administrador.php">Edición de Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./cursos.php">Edición de Cursos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./gestion_profesores.php">Edición de Profesores</a>
                </li>

                <!-- Dropdown principal -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="alumnosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Lista alumnos por cursos
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="alumnosDropdown">

                        <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#">1ro</a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li>' . $this->getCursosByAno(1) . '</li>
                            </ul>
                        </li>

                        <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#">4to</a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li>' . $this->getCursosByAno(4) . '</li>
                            </ul>
                        </li>

                        <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#">7mo</a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li>' . $this->getCursosByAno(7) . '</li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>

            <form method="POST" action="../Controladores/logout.control.php" class="d-flex ms-auto">
                <button type="submit" class="btn btn-outline-danger">Cerrar sesión</button>
            </form>
        </div>
    </div>
</nav>
        ';
        echo $navbar;
    }
}
?>

<script>
// Submenús anidados
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".dropdown-submenu .dropdown-toggle").forEach(function (element) {
        element.addEventListener("click", function (e) {
            let nextEl = element.nextElementSibling;
            document.querySelectorAll(".dropdown-submenu .dropdown-menu.show").forEach(function (submenu) {
                if (submenu !== nextEl) submenu.classList.remove("show");
            });
            if (nextEl && nextEl.classList.contains("dropdown-menu")) {
                nextEl.classList.toggle("show");
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
    document.querySelectorAll(".dropdown").forEach(function (dropdown) {
        dropdown.addEventListener("hide.bs.dropdown", function () {
            dropdown.querySelectorAll(".dropdown-menu.show").forEach(function (submenu) {
                submenu.classList.remove("show");
            });
        });
    });
});
</script>

<style>
.dropdown-menu .dropdown-submenu {
    position: relative;
}
.dropdown-menu .dropdown-submenu .dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: 0;
    margin-left: 0.1rem;
}
.dropdown-item.active {
    background-color: #0d6efd;
    color: white;
}
</style>
