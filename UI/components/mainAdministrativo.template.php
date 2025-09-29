<?php
require_once("../Entidades/Alumno.php");
require_once("../Entidades/Tutor.php");
require_once("../BLL/TutorBLL.php");

class Main_template
{
    private array $listaUsuarios;
    private array $listaTipos;

    public function __construct(array $listaUsuarios, array $listaTipos)
    {
        $this->listaUsuarios = $listaUsuarios;
        $this->listaTipos = $listaTipos;
    }

    public function render()
    {
        $main = '
<main class="container d-flex justify-content-center align-items-center flex-column" style="margin-top:70px; width: 100vw; height: 90vh; ">
                <form method="POST" action="../Controladores/asistencia.control.php">
                <table class="table  table-bordered border border-light-subtle rounded-5" style="width:70vw;">
                    <thead>
                        <tr>
                            <th class="bg-primary text-light" scope="col">#</th>
                            <th class="bg-primary text-light" scope="col">Nombre</th>
                            <th class="bg-primary text-light" scope="col">Apellido</th>
                            <th class="bg-primary text-light" scope="col">Cursos asignados</th>
                            <th class="bg-primary text-light" scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>';

        foreach ($this->listaUsuarios as $index => $usuario) {
            if($usuario->getIdTiposUsuarios() != 2 && $usuario->getIdTiposUsuarios() !=3)
            {
                $main .= $this->generateTableRow($index, $usuario);

            }
        }

        $main .= $this->cierreTableMain();
        $main .= $this->generateModalAdd(); // Asegúrate de llamar al modal para agregar
        echo $main;
    }

    private function cierreTableMain()
    {
        return '
                    </tbody>
                </table>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square-fill" viewBox="0 0 16 16"><path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm6.5 4.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3a.5.5 0 0 1 1 0"/>
                    </svg>
                </button>
                </form>
            </main>';
    }

    private function generateTableRow($index, $usuario): string
{
    $cursos = UsuariosBLL::obtenerCursos($usuario->getId());

    $cursoAsignado = '';

    if (!empty($cursos)) {
        foreach ($cursos as $curso) {
            $cursoAsignado .= '<button class="btn btn-outline-primary" disabled>' . htmlspecialchars($curso->getAno()) . '°' . htmlspecialchars($curso->getDivision()) .'</button>';
        }
    } else {
        $cursoAsignado = '<p>No tiene cursos asignados</p>';
    }


    // Retornamos la fila de la tabla con los cursos asignados
    
        return '
        <tr >
            <th scope="row">' . ($index + 1) . '</th>
            <td>' . htmlspecialchars($usuario->getNombre()) . '</td>
            <td>' . htmlspecialchars($usuario->getApellido()) . '</td>
            <td>
                <div class="d-flex justify-content-center gap-4">
                        ' . $cursoAsignado . '

                </div>
            
            </td>
            
            <td> 
                ' . $this->generateInfoButton($usuario) . '
                ' . $this->generateEditButton($usuario) . '
                ' . $this->generateDeleteButton($usuario) . '
            </td>
        </tr>';
    

}


    private function generateInfoButton($usuario)
    {
        return '
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#infoModal' . $usuario->getId() . '">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                </svg>                    
            </button>
            ' . $this->generateModalInfo($usuario);
    }

    private function generateEditButton($usuario)
    {
        return '
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editModal' . $usuario->getId() . '">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                </svg>
            </button>
            ' . $this->generateModalEdit($usuario);
    }

    private function generateDeleteButton($usuario)
    {
        return '
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal' . $usuario->getId() . '">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                </svg>
            </button>
            ' . $this->generateModalDelete($usuario);
    }

    private function generateModalAdd()
    {
        $listaTipos = TiposUsuariosBLL::ListaTiposUsuarios();
        $opciones = ''; // Inicializa una variable para almacenar las opciones
    
        foreach ($listaTipos as $tipo) {
            $opciones .= '<option value="' . $tipo->getId() . '">' . $tipo->getTipo() . '</option>'; // Concatenar las opciones
        }
    
        return '
        <div class="modal fade" id="add" tabindex="-1" aria-labelledby="addLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white" id="addLabel">Agregar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addForm" method="POST" action="../Controladores/add.control.php">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>       
                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                    </div>
                    <div class="mb-3">
                        <label for="dni" class="form-label">DNI</label>
                        <input type="number" class="form-control" id="dni" name="dni" required>
                    </div>
                    <div class="mb-3">
                        <label for="contrasena" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="idTipo" class="form-label">Tipo de Usuario</label>
                        <select class="form-control" id="idTipo" name="idTipo" required>
                            '.$opciones.'
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Agregar</button>
                </form>
            </div>
        </div>
    </div>
</div>
';
    }
    



    private function generateModalInfo($usuario)
    {
  
        // Construye el modal HTML
        $modal = '
        <div class="modal fade" id="infoModal' . $usuario->getId() . '" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title" id="infoModalLabel">Información del usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Nombre: ' . htmlspecialchars($usuario->getNombre()) . '</p>
                        <p>Apellido: ' . htmlspecialchars($usuario->getApellido()) . '</p>
                        <p>DNI: ' . htmlspecialchars($usuario->getDni()) . '</p>
                        <p>Email: ' . htmlspecialchars($usuario->getEmail()) . '</p>
                    </div>
                </div>
            </div>
        </div>';

        return $modal;
    }


    private function generateModalEdit($usuario): string
    {
        $listaTipos = TiposUsuariosBLL::ListaTiposUsuarios();
        $opciones = ''; // Inicializa una variable para almacenar las opciones
    
        foreach ($listaTipos as $tipo) {
            $opciones .= '<option value="' . $tipo->getId() . '">' . $tipo->getTipo() . '</option>'; // Concatenar las opciones
        }
    

        return '
        <div class="modal fade" id="editModal' . $usuario->getId() . '" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Editar usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm' . $usuario->getId() . '" method="POST" action="../Controladores/modify.control.php">
                            
                        <input type="hidden" value="' . htmlspecialchars($usuario->getId()) . '" name="id">
                        <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required value="' . $usuario->getNombre() . '">
                            </div>
                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" required value="' . $usuario->getApellido() . '">
                            </div>
                            <div class="mb-3">
                                <label for="Dni" class="form-label">Dni</label>
                                <input type="number" class="form-control" id="dni" name="dni" required value="' . $usuario->getDni() . '">
                            </div>
                            <div class="mb-3">
                                <label for="EMAIL" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required value="' . $usuario->getEmail() . '">
                            </div>
                            <div class="mb-3">
                                <label for="contrasena" class="form-label">Contrasenña</label>
                                <input type="contrasena" class="form-control" id="contrasena" name="contrasena" required value="' . $usuario->getContrasena() . '">
                            </div>
                            <div class="mb-3">
                                <label for="idTipo" class="form-label">Tipo de Usuario</label>
                                    <select class="form-control" id="idTipo" name="idTipo" required>
                                        '.$opciones.'
                                    </select>
                            </div>

                            <button type="submit" class="btn btn-success">Guardar cambios</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>';
    }

    private function generateModalDelete($usuario)
    {
        return '
        <div class="modal fade" id="deleteModal' . $usuario->getId() . '" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger  ">
                        <h5 class="modal-title" id="deleteModalLabel">Eliminar usuario</h5>
                    </div>
                    <div class="modal-body">
                        ¿Está seguro de que desea eliminar al usuario <strong>' . htmlspecialchars($usuario->getNombre()) . ' ' . htmlspecialchars($usuario->getApellido()) . '</strong>?
                    </div>
                    <div class="modal-footer">
                        <form method="POST" action="../Controladores/delete.control.php">
                            <input type="hidden" name="id" value="' . $usuario->getId() . '">
                            <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-outline-danger">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>';
    }
}
