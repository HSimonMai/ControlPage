<?php
require_once("../Entidades/Usuario.php");
require_once("../Entidades/Cursos.php");
require_once("../BLL/UsuariosBLL.php");
require_once("../BLL/CursoBLL.php");


class Main_templateCursos
{


    public function render()
    {
        $main = '
            <main class="container d-flex justify-content-center align-items-center flex-column" style="margin-top:50px; width: 90vw; height: 90vh;">
                        <div class="table-responsive">
                            <table id="tableCurso" class="table table-bordered table-hover text-center "   style="width: 60vw; ">
                                <thead class="table-light">
                                    <tr>
                                        <th class="bg-primary text-light" scope="col" style="width:20%">Curso</th>
                                        <th class="bg-primary text-light" scope="col" style="width: 50%">Preceptor</th>
                                        <th class="bg-primary text-light" scope="col" style="width: 30%">Acciones</th>

                                    </tr>
                                </thead>
                                <tbody>        
                                    ' . $this->getAllCursos() . '
                                </tbody>
                                ' . $this->btnAdd() . '
                            </table>                            
                        </div>
            </main>

        ';
        echo $main;
    }


    public function btnAdd()
    {

        return '
                                            <caption>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd" >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square-fill" viewBox="0 0 16 16">
                                            <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm6.5 4.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3a.5.5 0 0 1 1 0"/>
                                        </svg>
                                        </button>
                                        ' . $this->modalAdd() . '
                                    </captio>
            ';
    }

    public function modalAdd()
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
                        ' . $this->formAdd() . '
                    </div>
                
                </div>
            </div>
        </div>';
    }

    public function formAdd()
    {
        return '
        <form id="modalAdd" method="POST" action="../Controladores/addCurso.control.php" class="text-start"> 
            <div class="mb-3">
                <label for="ano" class="form-label">Año</label>
                <input type="number" class="form-control" id="ano" name="ano" required ">
            </div>
            <div class="mb-3">
                <label for="division" class="form-label">Division</label>
                <input type="number" class="form-control" id="division" name="division" required >
            </div>
                    <select class="form-select" id="idPreceptor" name="idPreceptor">
                        ' . $this->options() . '
                    </select>
                <div class="modal-footer">
                        <button type="submit"class="btn btn-primary">Agregar alumno</button>
                    </div>
        </form>';
    }

    public function getAllCursos()
    {
        $listaCursos = CursoBLL::getAllCursos();
        $itemCurso = '';

        foreach ($listaCursos as $curso) {
            $preceptor = UsuariosBLL::getUsuarioByIdCurso($curso->getIdUsuario());

            if ($preceptor instanceof Usuario) {
                $nombrePreceptor = htmlspecialchars($preceptor->getNombre()) . ' ' . htmlspecialchars($preceptor->getApellido());
            } else {
                $nombrePreceptor = 'No hay preceptor a cargo';
            }

            // Construir la fila de la tabla
            $itemCurso .= '
                <tr>
                    <td>' . htmlspecialchars($curso->getAno()) . ' ° ' . $curso->getDivision() . '</td>
                    <td>' . $nombrePreceptor . '</td>
                    <td>' . $this->buttons($curso) . '</td>

                </tr>';
        }

        return $itemCurso;
    }


    public function buttons($curso)
    {
        return $this->btnModify($curso) . $this->btnDelete($curso);
    }

    public function btnModify($curso)
    {
        return '
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#' . $curso->getId() . '" >
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
</svg>
            </button>

        ' . $this->modalUpdate($curso);
    }


    private function modalUpdate($curso)
    {
        return '
        <div class="modal fade" id="' . $curso->getId() . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content text-start">
                    <div class="modal-header bg-success">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Editar curso ' . $curso->getAno() . '°' . $curso->getDivision() . '</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ' . $this->formEdit($curso) . '
                    </div>
                
                </div>
            </div>
        </div>';
    }

    private function formEdit($curso)
    {
        return '
            <form id="editForm' . $curso->getId() . '" method="POST" action="../Controladores/updateCurso.control.php" class="text-start"> 
                <input type="hidden" name="idCurso" value="' . htmlspecialchars($curso->getId()) . '">
                <input type="hidden" name="idPreceptor" value="' . htmlspecialchars($curso->getIdUsuario()) . '">
    
                <div class="mb-3">
                    <label for="ano" class="form-label">Año</label>
                    <input type="text" class="form-control" id="ano" name="ano" required value="' . htmlspecialchars($curso->getAno()) . '">
                </div>
                
                <div class="mb-3">
                    <label for="division" class="form-label">División</label>
                    <input type="text" class="form-control" id="division" name="division" required value="' . htmlspecialchars($curso->getDivision()) . '">
                </div>
    
                <div class="mb-3">
                    <label for="idPreceptor" class="form-label">Preceptor a cargo</label>
                    <select class="form-select" id="idPreceptor" name="idPreceptor">
                        ' . $this->options() . '
                    </select>
                </div>
    
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </form>';
    }



    public function options()
    {
        $listaPreceptores = UsuariosBLL::ListaAlumnos();
        $opPreceptores = '';
        foreach ($listaPreceptores as $preceptor) {

            if($preceptor->getIdTiposUsuarios() == 1)
            {
                $opPreceptores .= '
                <option value="' . $preceptor->getId() . '">' . $preceptor->getNombre() . ' ' . $preceptor->getApellido() . ' </option>
                ';
            }
        }

        return $opPreceptores;
    }

    private function btnDelete($curso)
    {
        return '
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal' . $curso->getId() . '">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
        </svg>
        </button>
        ' . $this->modalDelete($curso);
    }

    private function modalDelete($curso)
    {
        return '
        <div class="modal fade" id="modal' . $curso->getId() . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal' . $curso->getId() . '">Confirmación</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                ¿Estás seguro de que deseas eliminar a ' . $curso->getAno() . '°' . $curso->getDivision() . ' ?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="../Controladores/deleteCurso.control.php">
                    <input type="hidden" name="id" value="' . $curso->getId() . '">
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </form>
              </div>
            </div>
          </div>
        </div>
        ';
    }
}
