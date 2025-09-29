<?php
require_once("../Entidades/Alumno.php");
require_once("../Entidades/Tutor.php");
require_once("../Entidades/FaltaTotal.php");

require_once("../BLL/TutorBLL.php");
require_once("../BLL/AsistenciaBLL.php");
require_once("../BLL/InformeAsistenciasBLL.php");
require_once("../Entidades/InformeAsistencia.php");
require_once("../BLL/FaltaTotalBLL.php");

class Main_template
{
    private array $listaAlumnos;
    private array $listaTutores;

    public function __construct(array $listaAlumnos, array $listaTutores)
    {
        $this->listaAlumnos = $listaAlumnos;
        $this->listaTutores = $listaTutores;
    }

    public function render()
    {
        $main = '
    <main class="container d-flex justify-content-center align-items-center flex-column" style="margin-top:90px; width: 90vw;">
                    <form method="POST" action="../Controladores/asistenciaSuper.control.php" class="w-75">
                        <div class="container d-flex gap-4 mb-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <label>Taller</label>    
                                    <input id="taller" type="checkbox" name="tipoClase[]" value="1">
                                </div>
                                <div>
                                    <label>Teoría</label>    
                                    <input id="teoria" type="checkbox" name="tipoClase[]" value="2">
                                </div>
                        </div>

                        </div>
                        <div class="table-responsive">
                            <table id="CursoAlumnos" class="table table-bordered table-hover text-center" id="mytable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="bg-primary text-light" scope="col" style="width: 5%;">Nro</th>
                                        <th class="bg-primary text-light" scope="col" style="width: 5%;">DNI</th>
                                        <th class="bg-primary text-light" scope="col" style="width: 30%;">Nombre</th>
                                        <th class="bg-primary text-light" scope="col" style="width: 30%;">Apellido</th>
                                        <th class="bg-primary text-light" scope="col" style="width: 5%;">Asistencia</th>
                                        <th class="bg-primary text-light" scope="col" style="width: 20%;">Info</th>
                                    </tr>
                                </thead>
                                <tbody>';

        foreach ($this->listaAlumnos as $index => $alumno) {
            
            $tutor = $this->findTutorById($alumno->getIdTutor());
            $idCurso = $alumno->getIdCurso();
            $main .= $this->generateTableRow( $index,$alumno);
            if ($tutor) {
                $main .= $this->generateModal($alumno, $tutor);
                $main .= $this->modalInfoAsistencias($alumno);
                $main .= $this->modalInformeGeneral($index,$idCurso);
            }
        }

        $main .= $this->cierreTableMain($idCurso);
        echo $main;
    }


    private function modalInformeGeneral($index,$idCurso)
    {
        // Generar un ID único para el modal

        echo '
        <div class="modal fade" id="modalInformeGeneral' . $idCurso . '" tabindex="-1" aria-labelledby="modalLabel' . $idCurso . '" aria-hidden="true">
            <div class="modal-dialog modal-xl" >
                <div class="modal-content">
            <div class="modal-header d-flex justify-content-between">
                    <h5 class="modal-title" >Informe General del Curso</h5>
                </div>

                    <div class="modal-body">
                    
                        ' . $this->tableInformeCurso($index,$idCurso) . '
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                    </div>
                    
                </div>
            </div>
        </div>
    ';
    }
    private function generateDynamicButton($idCurso)
    {
        return '
        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalInformeGeneral' . $idCurso . '" data-bs-whatever="@mdo">
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-square-fill" viewBox="0 0 16 16">
  <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm8.93 4.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM8 5.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
</svg>
        </button>';
    }


    private function tableInformeCurso($index,$idCurso)
    {
        // Obtener el informe general del curso usando el método adaptado
        $faltaTotal = FaltaTotalBLL::getInformeCurso($idCurso);

        $tablaInforme = ''; // Inicializar fuera del bucle para acumular todas las filas
        foreach ($faltaTotal as $informe) {
            $tablaInforme .= '
            <tr>
                <td>' . $index++ . '</td>
                <td>' . $informe->getDni() . '</td>
                <td>' . $informe->getNombre() . '</td>
                <td>' . $informe->getTotalAsitencias() . '</td>
                <td>' . $informe->getMediasFaltas() . '</td>
                <td>' . $informe->getTotalFaltas() . '</td>
            </tr>
        ';
        }

        return '
        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
            <table id="tableInformeCurso" class="table table-striped table-bordered table-hover align-middle">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">Nro</th>
                        <th scope="col">DNI</th>
                        <th scope="col">Alumno</th>
                        <th scope="col">Asistencias Completas</th>
                        <th scope="col">Medias  Faltas</th>
                        <th scope="col">Faltas totales</th>
                    </tr>
                </thead>
                <tbody>
                    ' . $tablaInforme . '
                </tbody>

            </table>
        </div>';
    }




    private function findTutorById($idTutor)
    {
        foreach ($this->listaTutores as $tutor) {
            if ($tutor->getId() === $idTutor) {
                return $tutor;
            }
        }
        return null;
    }

    private function cierreTableMain($idCurso)
    {
        return '
                                </tbody>
                                                <caption>
                                ' . $this->generateDynamicButton($idCurso) . '
            </caption>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <button type="submit" class="btn btn-primary">Grabar Asistencia</button>
                        </div>
                    </form>
                </section>
            </main>';
    }

    private function generateTableRow($index,$alumno)
    {
        return '
            <tr>
                <th scope="row">' .$index++ . '</th>
                <th scope="row">' .$alumno->getDni() . '</th>
                <td>' . htmlspecialchars($alumno->getNombre()) . '</td>
                <td>' . htmlspecialchars($alumno->getApellido()) . '</td>
                <td>
                    <input type="hidden" name="inasistencias[]" value="' . $alumno->getId() . '">
                    <input type="checkbox" name="idAlumno[]" value="' . $alumno->getId() . '">
                </td>
                <td> 
                    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#modal' . $alumno->getId() . '">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                        </svg>
                    </button>
                    <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#modalAsist' . $alumno->getId() . '">
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-lg" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M4.475 5.458c-.284 0-.514-.237-.47-.517C4.28 3.24 5.576 2 7.825 2c2.25 0 3.767 1.36 3.767 3.215 0 1.344-.665 2.288-1.79 2.973-1.1.659-1.414 1.118-1.414 2.01v.03a.5.5 0 0 1-.5.5h-.77a.5.5 0 0 1-.5-.495l-.003-.2c-.043-1.221.477-2.001 1.645-2.712 1.03-.632 1.397-1.135 1.397-2.028 0-.979-.758-1.698-1.926-1.698-1.009 0-1.71.529-1.938 1.402-.066.254-.278.461-.54.461h-.777ZM7.496 14c.622 0 1.095-.474 1.095-1.09 0-.618-.473-1.092-1.095-1.092-.606 0-1.087.474-1.087 1.091S6.89 14 7.496 14"/>
</svg>
                    </button>
                </td>
            </tr>
            

            ';
    }

    private function generateModal($alumno, $tutor)
    {
        return '
            <div class="modal fade" id="modal' . $alumno->getId() . '" tabindex="-1" aria-labelledby="modalLabel' . $alumno->getId() . '" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content ">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel' . $alumno->getId() . '">Información del Alumno  ' . $alumno->getNombre() . ' ' . $alumno->getApellido() . ' </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Nombre:</strong> ' . $alumno->getNombre() . ' </p>
                            <p><strong>Apellido:</strong> ' . $alumno->getApellido() . ' </p>
                            <p><strong>Dni:</strong> ' . $alumno->getDni() . ' </p>
                            <p><strong>Genero:</strong> ' . $alumno->getGenero() . ' </p>
                            <p><strong>Nacionalidad:</strong> ' . $alumno->getNacionalidad() . ' </p>
                            <p><strong>FechaNacimiento:</strong> ' . $alumno->getFechaNacimiento() . ' </p>
                            <p><strong>Direccion:</strong> ' . $alumno->getDireccion() . ' </p>

                            <p><strong>Informacion del tutor:</strong></p>
                            <ul style="list-style:none;">
                                <li><strong>Nombre:</strong> ' . $tutor->getNombre() . '</li>
                                <li><strong>Apellido:</strong> ' . $tutor->getApellido() . '</li>
                                <li><strong>Dni:</strong> ' . $tutor->getDni() . '</li>
                                <li><strong>Email:</strong> ' . $tutor->getEmail() . '</li>
                                <li><strong>Telefono:</strong> ' . $tutor->getTelefono() . '</li>

                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>';
    }


    private function modalInfoAsistencias($alumno)
    {

        echo '
            <div class="modal fade" id="modalAsist' . $alumno->getId() . '" tabindex="-1" aria-labelledby="modalLabel' . $alumno->getId() . '" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-fullscreen" > <!-- Aumentando el tamaño con max-width -->
<div class="modal-content" data-nombre="'.$alumno->getNombre().'" data-apellido="'.$alumno->getApellido().'" data-dni="'.$alumno->getDni().'" >
               <div class="modal-header d-flex justify-content-between" >
                            <h5 class="modal-title" id="modalLabel' . $alumno->getId() . '">Información del Alumno ' . $alumno->getNombre() . ' ' . $alumno->getApellido() . '</h5>
                </div>
                        <div class="modal-body">
                            ' . $this->tableAsistencia($alumno->getId()) . '
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        ';
    }

    private function tableAsistencia($idAlumno)
    {
        $listaFaltas = FaltaTotalBLL::getFaltasTotales($idAlumno);
    
        $caption = '<div>No hay datos de faltas.</div>'; // Valor por defecto
    
        foreach ($listaFaltas as $falta) {
            $caption = '
                <div>
                    Asistencia completas: ' . $falta->getTotalAsitencias() . '<br>
                </div>
                <div>
                    Medias faltas: ' . $falta->getMediasFaltas() . '<br>
                </div>
                <div>
                    Faltas : ' . $falta->getTotalFaltas() . '<br>
                </div>

            ';
        }
    
        // Obtener los informes (cada informe es una fila en la tabla)
        $listaInformes = InformeAsistenciasBLL::informeAsistencia($idAlumno);
    
        $tablaInforme = ''; // Inicializar fuera del bucle para acumular todas las filas
    
        foreach ($listaInformes as $informe) {
            // Formatea la fecha de asistencia
            $fechaAsistencia = $informe->getFechaAsistencia()->format('Y-m-d H:i:s');
    
            // Genera cada fila de la tabla y la acumula en $tablaInforme
            $tablaInforme .= '
                <tr>
                    <td class="col-fecha">' . $fechaAsistencia . '</td>
                    <td>' . $informe->getTipoClase() . '</td>
                    <td>' . 
                ($informe->getValorAsistencia() == 0 ? "Presente" : 
                ($informe->getValorAsistencia() == 0.5 ? "Media Falta" : 
                ($informe->getValorAsistencia() == 1 ? "Falta" : "Desconocido"))) . 
                '</td>
                </tr>';
        }                                  
    
        // Retornar la tabla completa con el caption y todas las filas acumuladas
        return '
            <div class=" table-responsive"border: 1px solid #ddd; padding: 10px;">
                <table id="#InformeIndividual' . $idAlumno . 'l" class=" table table-striped table-bordered table-hover align-middle h-100" >
                    <thead class=" thead-light">
                        <tr>
                            <th scope="col" class="col-fecha">Fecha de Asistencia</th> <!-- Clase para la columna de fecha -->
                            <th scope="col">Tipo de Clase</th>
                            <th scope="col">Valor de asistencia</th>
                        </tr>
                    </thead>
                    <tbody >
                        ' . $tablaInforme . '
                    </tbody>
                    <caption style="caption-side: top; font-weight: bold;" class="d-flex justify-content-between">
                        ' . $caption . '
                    </caption>
                </table>
            </div>';
    }
    


    
}
?>


<script>
    // Ejecuta el script cuando el DOM esté completamente cargado
    document.addEventListener('DOMContentLoaded', function() {
        // Selecciona los checkboxes usando querySelector
        const checkboxTaller = document.querySelector('#taller');
        const checkboxTeoria = document.querySelector('#teoria');

        // Comprueba que los elementos existan antes de añadir los eventos
        if (checkboxTaller && checkboxTeoria) {
            checkboxTaller.addEventListener('change', function() {
                if (checkboxTaller.checked) {
                    checkboxTeoria.checked = false; // Deselecciona el otro checkbox
                }
            });

            checkboxTeoria.addEventListener('change', function() {
                if (checkboxTeoria.checked) {
                    checkboxTaller.checked = false; // Deselecciona el otro checkbox
                }
            });
        } else {
            console.error('No se encontraron los checkboxes con IDs #taller o #teoria');
        }
    });
</script>
