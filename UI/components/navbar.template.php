<?php
require_once(__DIR__."/../../Entidades/Usuario.php");

class Navbar_template
{
    private Usuario $usuario;

    public function __construct(Usuario $usuario)
    {
        $this->usuario = $usuario;
    }


    public function getCursosByAno($año)
    {
        $listaCursos= CursoBLL::getAllCursos();
        $linkCurso= '';
        foreach($listaCursos as $curso)
        {
            if($curso->getAno() === (int) $año)
            {
                $linkCurso.= '
<a class="dropdown-item" href="../UI/Alumnos.php?idCurso='.$curso->getId().'">
    '.$curso->getAno().'° '.$curso->getDivision().'
</a>
            ';
            }
    

        }

        return $linkCurso;
    }


    public function render()
    {


        // $nombreCompleto = htmlspecialchars($this->usuario->getNombre() . ' ' . $this->usuario->getApellido());
    
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
                    <a class="nav-link" href="./Administrador.php">Edicion de Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./cursos.php">Edicion de Cursos</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="./gestion_profesores.php">Edición de Profesores</a>
                </li>
                
                <!-- First Dropdown for Alumnos -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="alumnosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Lista alumnos por cursos
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="alumnosDropdown">
                        
                        <!-- Nested Dropdown (Submenu) inside the first dropdown -->
                   <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#" id="submenuDropdown">4to</a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="submenuDropdown">
                                <li>
                                    '.$this->getCursosByAno(4).'
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#" id="submenuDropdown">7mo</a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="submenuDropdown">
                                <li>
                                    '.$this->getCursosByAno(7).'
                                </li>
                            </ul>
                        </li>
                      <li class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#" id="submenuDropdown">1ro</a>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="submenuDropdown">
                                <li>
                                    '.$this->getCursosByAno(1).'
                                </li>
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
// JavaScript to handle the nested dropdown
document.addEventListener("DOMContentLoaded", function () {
    // Get all dropdown-toggle elements within the submenu
    document.querySelectorAll(".dropdown-submenu .dropdown-toggle").forEach(function (element) {
        element.addEventListener("click", function (e) {
            let nextEl = element.nextElementSibling;

            // Close any other open submenus
            document.querySelectorAll(".dropdown-submenu .dropdown-menu.show").forEach(function (submenu) {
                if (submenu !== nextEl) {
                    submenu.classList.remove("show");
                }
            });

            // Toggle visibility of the clicked submenu
            if (nextEl && nextEl.classList.contains("dropdown-menu")) {
                nextEl.classList.toggle("show");
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });

    // Close any open submenus if the main dropdown is closed
    document.querySelectorAll(".dropdown").forEach(function (dropdown) {
        dropdown.addEventListener("hide.bs.dropdown", function () {
            dropdown.querySelectorAll(".dropdown-menu.show").forEach(function (submenu) {
                submenu.classList.remove("show");
            });
        });
    });

    // Handle active state for dropdown items
    document.querySelectorAll(".dropdown-item").forEach(function (item) {
        item.addEventListener("click", function () {
            // Remove 'active' class from previously selected items
            document.querySelectorAll(".dropdown-item.active").forEach(function (activeItem) {
                activeItem.classList.remove("active");
            });
            // Add 'active' class to the clicked item
            item.classList.add("active");
        });
    });
});
</script>



<style>
/* Align the submenu to the right of its parent item */
.dropdown-menu .dropdown-submenu {
    position: relative;
}

.dropdown-menu .dropdown-submenu .dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: 0;
    margin-left: 0.1rem;
}

/* CSS to highlight the active dropdown item */
.dropdown-item.active {
    background-color: #0d6efd;
    color: white;
}

</style>