<?php
// require_once("../Entidades/Alumno.php");
require_once(__DIR__ . "/../../Entidades/Alumno.php");
require_once(__DIR__ . "/../../Entidades/Tutor.php");
require_once(__DIR__ . "/../../Entidades/InformeAsistencia.php");
require_once(__DIR__ . "/../../Entidades/FaltaTotal.php");
require_once(__DIR__ . "/../../Entidades/Cursos.php"); // Línea adicional solicitada
require_once(__DIR__ . "/../../Entidades/TipoUsuario.php"); // Línea adicional solicitada



require_once(__DIR__ . "/../../BLL/CursoBLL.php");
require_once(__DIR__ . "/../../BLL/TutorBLL.php");
require_once(__DIR__ . "/../../BLL/AsistenciaBLL.php");
require_once(__DIR__ . "/../../BLL/InformeAsistenciasBLL.php");
require_once(__DIR__ . "/../../BLL/UsuariosBLL.php");
require_once(__DIR__ . "/../../BLL/FaltaTotalBLL.php");
require_once(__DIR__ . "/../../BLL/TiposUsuariosBLL.php");

// require_once("../BLL/TutorBLL.php");
// require_once("../BLL/CursoBLL.php");

class Main_template
{

    public function render()
{
    $idCurso = (int) $_GET["idCurso"];
    $main = '
    <main class="container d-flex justify-content-center align-items-center flex-column" style="margin-top:5rem; width: 90vw; height: 100vh;">
        <div class="table-responsive">
            <table id="tableCurso_' . $idCurso . '" class="table table-bordered table-hover text-center" style="width: 60vw;">
                <thead class="table-light">
                    <tr>
                        <th class="bg-primary text-light" scope="col" style="width: 5%;">DNI</th>
                        <th class="bg-primary text-light" scope="col" style="width: 10%;">Alumno</th>
                        <th class="bg-primary text-light" scope="col" style="width: 10%;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    ' . $this->alumnosByIdCurso($idCurso) . '
                </tbody>
            </table>
        </div>
        ' . $this->btnAdd() . '
    </main>';
    echo $main;
}



    public function alumnosByIdCurso($idCurso)
    {
        $listaAlumnos = AlumnoBLL::getAlumnosByIdCurso($idCurso);

        if($listaAlumnos != null)
        {
            $listaAlumnos = $this->tableAlumnos($listaAlumnos);
            return $listaAlumnos;
        }

    }

    public function tableAlumnos($listaAlumnos)
    {
        $itemAlumno = "";
        foreach ($listaAlumnos as $alumno) {
            $itemAlumno .=
                '
            <tr>
                <td>' . $alumno->getDni() . '</td>
                <td>' . $alumno->getNombre() . ' ' . $alumno->getApellido() . '</td>
                <td>' . $this->buttons($alumno) . '</td>
            </tr>   
            

            ';
        }
        return $itemAlumno;
    }


    private function buttons($alumno)
    {
        return $this->btnEditAlumno($alumno) .
            $this->btnDelete($alumno);
    }

    private function btnEditAlumno($alumno)
    {
        return '
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#' . $alumno->getId() . '" >
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
</svg>
            </button>

        '.$this->modalUpdate($alumno);
            
        ;
    }

    private function btnAdd()
    {
        $idCurso = (int)$_GET["idCurso"];
        return '
                                        <caption>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd" >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square-fill" viewBox="0 0 16 16">
                                        <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm6.5 4.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3a.5.5 0 0 1 1 0"/>
                                    </svg>
                                    </button>
                                    '.$this->modalAdd($idCurso).'
                                </captio>
        ';
    }

    private function btnDelete($alumno)
    {
        return '
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal' . $alumno->getId() . '">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
        </svg>
        </button>
        ' . $this->modalDelete($alumno);
    }

    private function modalAdd($idCurso)
    {
        return '
        <div class="modal fade"  tabindex="-1" aria-labelledby="modalAdd" id="modalAdd" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content text-start">
                    <div class="modal-header bg-success">
                        <h1 class="modal-title fs-5" id="modalAdd">Agregar alumno</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        '.$this->formAdd($idCurso).'
                    </div>
                
                </div>
            </div>
        </div>';
    }

    private function modalDelete($alumno)
    {
        return '
        <div class="modal fade" id="modal' . $alumno->getId() . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal' . $alumno->getId() . '">Confirmación</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                ¿Estás seguro de que deseas eliminar a ' . $alumno->getNombre() . ' ' . $alumno->getApellido() . ' ?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="../Controladores/deleteAlumno.control.php">
                    <input type="hidden" name="idAlumno" value="' . $alumno->getId() . '">
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        ';
    }
    


    private function modalUpdate($alumno) 
    {
        return '
        <div class="modal fade" id="'.$alumno->getId().'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content text-start">
                    <div class="modal-header bg-success">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Editar alumno '.$alumno->getNombre().' '.$alumno->getApellido().'</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        '.$this->formEdit($alumno).'
                    </div>
                
                </div>
            </div>
        </div>';
    }
    
    private function formEdit($alumno)
    {
        $tutor = TutorBLL::findTutorByIdAlumno($alumno->getIdTutor());
// Convertir la fecha al formato correcto si no está en 'YYYY-MM-DD'
$fechaNacimiento = date('Y-m-d', strtotime($alumno->getFechaNacimiento()));

        return '
        <form id="editForm' . $alumno->getId() . '" method="POST" action="../Controladores/updateAlumno.control.php" class="text-start"> 
            <input type="hidden" value="' . htmlspecialchars($alumno->getId()) . '" name="idAlumno">
            <input type="hidden" value="' . htmlspecialchars($alumno->getIdTutor()) . '" name="idTutor">
            <input type="hidden" value="' . htmlspecialchars($alumno->getIdCurso()) . '" name="idCurso">

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required value="' . htmlspecialchars($alumno->getNombre()) . '">
            </div>
            
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" required value="' . htmlspecialchars($alumno->getApellido()) . '">
            </div>
            
            <div class="mb-3">
                <label for="dni" class="form-label">Dni</label>
                <input type="number" class="form-control" id="dni" name="dni" required value="' . htmlspecialchars($alumno->getDni()) . '">
            </div>
            
            <div class="mb-3">
                <label for="genderSelect" class="form-label">Género</label>
                <select class="form-select" id="idSexo" name="idSexo">
                    <option value="M">Hombre</option>
                    <option value="F" >Mujer</option>
                </select>
            </div>
    
            <div class="mb-3">
                <label for="nacionalidad" class="form-label">Nacionalidad</label>
                <input type="text" class="form-control" id="nacionalidad" name="nacionalidad" required value="' . $alumno->getNacionalidad() . '">
            </div>
            
            <div class="mb-3">
                <label for="fechanacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" required value="' . $fechaNacimiento . '">
            </div>
            
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" required value="' . $alumno->getDireccion() . '">
            </div>
    
            <h4>Información del Tutor</h4>
    
            <div class="mb-3">
                <label for="nombreTutor" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="tutorNombre" name="tutorNombre" required value="' . $tutor->getNombre() . '">
            </div>
            
            <div class="mb-3">
                <label for="apellidoTutor" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="tutorApellido" name="tutorApellido" required value="' . $tutor->getApellido() . '">
            </div>
            
            <div class="mb-3">
                <label for="dniTutor" class="form-label">DNI</label>
                <input type="number" class="form-control" id="tutorDni" name="tutorDni" required value="' . $tutor->getDni() . '">
            </div>
            
            <div class="mb-3">
                <label for="emailTutor" class="form-label">Email</label>
                <input type="email" class="form-control" id="tutorEmail" name="tutorEmail" required value="' . $tutor->getEmail() . '">
            </div>
            
            <div class="mb-3">
                <label for="telefonoTutor" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="tutorTelefono" name="tutorTelefono" required value="' . $tutor->getTelefono() . '">
            </div>
                <div class="modal-footer">
                        <button type="submit"class="btn btn-primary">Guardar cambios</button>
                    </div>
        </form>';
    } 

    private function formAdd($idCurso)
    {
// Convertir la fecha al formato correcto si no está en 'YYYY-MM-DD'

        return '
        <form id="modalAdd" method="POST" action="../Controladores/addAlumnos.control.php" class="text-start"> 
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required ">
            </div>
            <input type= "hidden" id="idCurso" name="idCurso" value="'.$idCurso.'">
            <div class="mb-3">
                <label for="apellido" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="apellido" name="apellido" required>
            </div>
            
            <div class="mb-3">
                <label for="dni" class="form-label">Dni</label>
                <input type="number" class="form-control" id="dni" name="dni" required >
            </div>
            
            <div class="mb-3">
                <label for="genderSelect" class="form-label">Género</label>
                <select class="form-select" id="idSexo" name="idSexo">
                    <option value="M">Hombre</option>
                    <option value="F" >Mujer</option>
                </select>
            </div>
    
            <div class="mb-3">
                <label for="nacionalidad" class="form-label">Nacionalidad</label>
                <input type="text" class="form-control" id="nacionalidad" name="nacionalidad" required >
            </div>
            
            <div class="mb-3">
                <label for="fechanacimiento" class="form-label">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" >
            </div>
            
            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" required >
            </div>
    
            <h4>Información del Tutor</h4>
    
            <div class="mb-3">
                <label for="nombreTutor" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="tutorNombre" name="tutorNombre" required >
            </div>
            
            <div class="mb-3">
                <label for="apellidoTutor" class="form-label">Apellido</label>
                <input type="text" class="form-control" id="tutorApellido" name="tutorApellido" required >
            </div>
            
            <div class="mb-3">
                <label for="dniTutor" class="form-label">DNI</label>
                <input type="number" class="form-control" id="tutorDni" name="tutorDni" required >
            </div>
            
            <div class="mb-3">
                <label for="emailTutor" class="form-label">Email</label>
                <input type="email" class="form-control" id="tutorEmail" name="tutorEmail" required >
            </div>
            
            <div class="mb-3">
                <label for="telefonoTutor" class="form-label">Teléfono</label>
                <input type="text" class="form-control" id="tutorTelefono" name="tutorTelefono" required >
            </div>
                <div class="modal-footer">
                        <button type="submit"class="btn btn-primary">Agregar alumno</button>
                    </div>
        </form>';
    }
    

}


?>