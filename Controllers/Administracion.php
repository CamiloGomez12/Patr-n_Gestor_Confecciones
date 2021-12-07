<?php
    class Administracion extends Controller{
        public function __construct() {
            session_start();
            
            parent::__construct();
        }
        public function index()
        {  
            $id_user = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_user, 'configuracion');
            if (!empty($verificar) || $id_user == 1 ) {
                $data = $this->model->getEmpresa();
                $this->views->getView($this, "index", $data);
            } else {
                header('Location: '.base_url. 'Errors/permisos');
            }
            
            
        }
        public function home()
        {   $data['usuarios'] = $this->model->getDatos('usuarios');
            $data['caja'] = $this->model->getDatos('caja');
            $data['clientes'] = $this->model->getDatos('clientes');
            $data['productos'] = $this->model->getDatos('productos');
            $data['categorias'] = $this->model->getDatos('categorias');
            $data['compras'] = $this->model->getDatos('compras');
            $data['encargos'] = $this->model->getDatos('encargos');
            $data['ventas'] = $this->model->getVentas();
            $this->views->getView($this, "home", $data);
        }
        public function modificar(){
            $nombre= $_POST['nombre'];
            $ruc= $_POST['ruc'];
            $tel = $_POST['telefono'];
            $dir = $_POST['direccion'];
            $mensaje = $_POST['mensaje'];
            $id = $_POST['id'];
           $data = $this->model->modificar($nombre, $ruc, $tel, $dir, $mensaje, $id);
           if($data == 'ok'){
            $msg = 'ok';
           }else{
            $msg = 'error';
           }
           echo json_encode($msg);
           die();
        }
        public function reporteStock(){
            $data = $this->model->getStockMinimo();
            echo json_encode($data);
            die();
        }
        public function productosVendidos(){
            $data = $this->model->getproductosVendidos();
            echo json_encode($data);
            die();
        }
    }
?>