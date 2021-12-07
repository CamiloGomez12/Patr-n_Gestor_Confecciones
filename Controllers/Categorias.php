<?php
class Categorias extends Controller{
    public function __construct() {
        session_start();
        
        parent::__construct();
    }
    public function index()
    {
        $id_user = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_user, 'categorias');
            if (!empty($verificar) || $id_user == 1) {
                $this->views->getView($this, "index");
            } else {
                header('Location: '.base_url. 'Errors/permisos');
            }
        if (empty($_SESSION['activo'])) {
            header("location: ".base_url);
        }

       
    }
    public function listar()
    {
        $data = $this->model->getCategorias();
        for ($i=0; $i < count($data); $i++) { 
            if ($data[$i]['estado'] == 1) {
                $data[$i]['estado'] = '<span class="badge bg-success">Activo</span>';
                $data[$i]['acciones'] = '<div>
                <button class="btn btn-primary" type="button" onclick="btnEditarCat(' . $data[$i]['id'] . ');"><i class="fas fa-edit"></i></button>
                <button class="btn btn-danger" type="button" onclick="btnEliminarCat(' . $data[$i]['id'] . ');"><i class="fas fa-trash-alt"></i></button>
                <div/>';
            }else {
                $data[$i]['estado'] = '<span class="badge bg-danger">Inactivo</span>';
                $data[$i]['acciones'] = '<div>
                <button class="btn btn-success" type="button" onclick="btnReingresarCat(' . $data[$i]['id'] . ');"><i class="fas fa-circle"></i></button>
                <div/>';
            }
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
   
    public function registrar()
    {
        $nombre = $_POST['nombre'];
        $id = $_POST['id'];
        if (empty($nombre)) {
            $msg = array('msg' => 'Todo los campos son obligatorios', 'icono' => 'warning');
        }else{
            if ($id == "") {
                    $data = $this->model->registrarCategoria($nombre);
                    if ($data == "ok") {
                        $msg = array('msg' => 'Categoria registrada con éxito', 'icono' => 'success');
                    }else if($data == "existe"){
                        $msg = array('msg' => 'La categoria ya existe', 'icono' => 'warning');
                    }else{
                        $msg = array('msg' => 'Error al registrar la categoria', 'icono' => 'error');
                    }
                
            }else{
                $data = $this->model->modificarCategoria($nombre);
                if ($data == "modificado") {
                    $msg = array('msg' => 'Categoria modificada con éxito', 'icono' => 'success');
                }else {
                    $msg = array('msg' => 'Error al modificar la categoria', 'icono' => 'error');
                }
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function editar(int $id)
    {
        $data = $this->model->editarCat($id);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function eliminar(int $id)
    {
        $data = $this->model->accionCategoria(0, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Categoria dada de baja', 'icono' => 'success');
        }else{
            $msg = array('msg' => 'Error al eliminar la categoria', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function reingresar(int $id)
    {
        $data = $this->model->accionCategoria(1, $id);
        if ($data == 1) {
            $msg = array('msg' => 'Categoria reingresada con éxito', 'icono' => 'success');
        } else {
            $msg = array('msg' => 'Error al reingresar la categoria', 'icono' => 'error');
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }
}
