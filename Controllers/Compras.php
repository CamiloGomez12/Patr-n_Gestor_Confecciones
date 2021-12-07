<?php
    class Compras extends Controller{
        public function __construct(){
            session_start();
            parent::__construct();

        }
        public function index(){
            $id_user = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_user, 'nueva_compra');
            if (!empty($verificar) || $id_user == 1 ) {
                $this->views->getView($this, "index"); 
            } else {
                header('Location: '.base_url. 'Errors/permisos');
            }

           
        } 
        public function ventas(){

            $id_user = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_user, 'nueva_venta');
            if (!empty($verificar) || $id_user == 1 ) {
                $data = $this->model->getClientes();
            $this->views->getView($this, "ventas", $data); 
            } else {
                header('Location: '.base_url. 'Errors/permisos');
            }

            
        } 
        public function encargos(){
            $id_user = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_user, 'nuevo_encargo');
            if (!empty($verificar) || $id_user == 1 ) {
                $data = $this->model->getClientes();
                $this->views->getView($this, "encargos", $data); 
            } else {
                header('Location: '.base_url. 'Errors/permisos');
            }

           
        } 
        public function historial_ventas(){
            $id_user = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_user, 'historial_ventas');
            if (!empty($verificar) || $id_user == 1 ) {
                $this->views->getView($this, "historial_ventas"); 
            } else {
                header('Location: '.base_url. 'Errors/permisos');
            }

            
        }
        public function historial_encargos(){

            $id_user = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_user, 'historial_encargos');
            if (!empty($verificar) || $id_user == 1 ) {
                $this->views->getView($this, "historial_encargos"); 
            } else {
                header('Location: '.base_url. 'Errors/permisos');
            }
            
        }
        public function buscarCodigo($cod){
               $data = $this->model->getProCod($cod);
               echo json_encode($data, JSON_UNESCAPED_UNICODE);
               die();
        }
        
        public function ingresar(){
            $id = $_POST['id'];
           $datos = $this->model->getProductos($id); 
           $id_producto = $datos['id'];
           $id_usuario = $_SESSION['id_usuario'];
           $precio = $datos['precio_compra'];
           $cantidad = $_POST['cantidad'];
          
           $comprobar = $this->model->consultarDetalle('detalle',$id_producto, $id_usuario);
           if(empty($comprobar)){
            $sub_total = $precio * $cantidad;
            $data = $this->model->registrarDetalle('detalle',$id_producto, $id_usuario, $precio, $cantidad, $sub_total );
                if($data == "ok"){
                        $msg = array('msg' => 'Producto ingresado a la compra', 'icono' => 'success' );
                }else{
                    $msg = array('msg' => 'Error al ingresar el producto compra', 'icono' => 'error' );
                }
            }else{
                $total_cantidad = $comprobar['cantidad'] + $cantidad;
                $sub_total = $total_cantidad * $precio;
            $data = $this->model->actualizarDetalle('detalle', $precio, $total_cantidad, $sub_total, $id_producto, $id_usuario);
                if($data == "modificado"){
                        $msg = array('msg' => 'Producto actualizado en la compra', 'icono' => 'success' );
                }else{
                    $msg = array('msg' => 'Error al actualizar el producto ', 'icono' => 'error' );
                }
            }
                echo json_encode($msg, JSON_UNESCAPED_UNICODE);
                 die();

                
           }
           public function ingresarVenta(){
            $id = $_POST['id'];
           $datos = $this->model->getProductos($id); 
           $id_producto = $datos['id'];
           $id_usuario = $_SESSION['id_usuario'];
           $precio = $datos['precio_venta'];
           $cantidad = $_POST['cantidad'];
          
           $comprobar = $this->model->consultarDetalle('detalle_temp',$id_producto, $id_usuario);
           if(empty($comprobar)){
               if($datos['cantidad'] >= $cantidad){
                $sub_total = $precio * $cantidad;
                $data = $this->model->registrarDetalle('detalle_temp',$id_producto, $id_usuario, $precio, $cantidad, $sub_total );
                    if($data == "ok"){
                            $msg = array('msg' => 'Producto ingresado a la venta', 'icono' => 'success' );
                    }else{
                        $msg = array('msg' => 'Error al ingresar el producto venta', 'icono' => 'error' );
                    }
               }else{
                $msg = array('msg' => 'Stock no disponible: '. $datos['cantidad'], 'icono' => 'warning' );

               }
           
            }else{
                $total_cantidad = $comprobar['cantidad'] + $cantidad;
                $sub_total = $total_cantidad * $precio;
                if($datos['cantidad'] < $total_cantidad){
                    $msg = array('msg' => 'Stock no disponible', 'icono' => 'warning' );

                }else{
                   $data = $this->model->actualizarDetalle('detalle_temp', $precio, $total_cantidad, $sub_total, $id_producto, $id_usuario);
                if($data == "modificado"){
                        $msg = array('msg' => 'Producto actualizado en la venta', 'icono' => 'success' );
                }else{
                    $msg = array('msg' => 'Error al actualizar el producto ', 'icono' => 'error' );
                    } 
                }
            
            }
                echo json_encode($msg, JSON_UNESCAPED_UNICODE);
                 die();

                
           }
           public function ingresarEncargo(){
            $id = $_POST['id'];
           $datos = $this->model->getProductos($id); 
           $id_producto = $datos['id'];
           $id_usuario = $_SESSION['id_usuario'];
           $precio = $datos['precio_venta'];
           $cantidad = $_POST['cantidad'];
          
           $comprobar = $this->model->consultarDetalle('detalle_temp_encargo',$id_producto, $id_usuario);
           if(empty($comprobar)){
            if($datos['cantidad'] >= $cantidad){
              $sub_total = $precio * $cantidad;
            $data = $this->model->registrarDetalle('detalle_temp_encargo',$id_producto, $id_usuario, $precio, $cantidad, $sub_total );
                if($data == "ok"){
                        $msg = array('msg' => 'Producto ingresado al encargo', 'icono' => 'success' );
                }else{
                    $msg = array('msg' => 'Error al ingresar el producto encargo', 'icono' => 'error' );
                }  
            }else{
                $msg = array('msg' => 'Stock no disponible: '. $datos['cantidad'], 'icono' => 'warning' );

            }
            
            }else{
                $total_cantidad = $comprobar['cantidad'] + $cantidad;
                $sub_total = $total_cantidad * $precio;
                if($datos['cantidad'] < $total_cantidad){
                    $msg = array('msg' => 'Stock no disponible', 'icono' => 'warning' );

                }else{
                    $msg = array('msg' => 'Stock no disponible', 'icono' => 'warning' );
            $data = $this->model->actualizarDetalle('detalle_temp_encargo', $precio, $total_cantidad, $sub_total, $id_producto, $id_usuario);
                if($data == "modificado"){
                        $msg = array('msg' => 'Producto actualizado en el encargo', 'icono' => 'success' );
                }else{
                    $msg = array('msg' => 'Error al actualizar el producto ', 'icono' => 'error' );
                }
            }
        }
                echo json_encode($msg, JSON_UNESCAPED_UNICODE);
                 die();

                
           }

           
        public function listar($table){
            $id_usuario = $_SESSION['id_usuario'];
            $data['detalle'] = $this->model->getDetalle($table,$id_usuario);
            $data['total_pagar'] = $this->model->calcularCompra($table, $id_usuario);
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            die();
        }
        public function delete($id){
           $data =  $this->model->deleteDetalle('detalle', $id);
           if($data == "ok"){
                $msg = 'ok';
           }else{
               $msg = 'error';
           }
           echo json_encode($msg);
           die();
        }
        public function deleteVenta($id){
            $data =  $this->model->deleteDetalle('detalle_temp', $id);
            if($data == "ok"){
                 $msg = 'ok';
            }else{
                $msg = 'error';
            }
            echo json_encode($msg);
            die();
         }
         public function deleteEncargo($id){
            $data =  $this->model->deleteDetalle('detalle_temp_encargo', $id);
            if($data == "ok"){
                 $msg = 'ok';
            }else{
                $msg = 'error';
            }
            echo json_encode($msg);
            die();
         }
        public function registrarCompra(){
            $id_usuario = $_SESSION['id_usuario'];
            $total = $this->model->calcularCompra('detalle', $id_usuario);
           $data = $this->model->registrarCompra($total['total']); 
           if($data == 'ok'){
            $detalle = $this->model->getDetalle('detalle', $id_usuario);
            $id_compra = $this->model->getId('compras');
            foreach($detalle as $row){
                $cantidad = $row['cantidad'];
                $precio = $row['precio'];
                $id_pro = $row['id_producto'];
                $sub_total = $cantidad * $precio;
                $this->model->registrarDetalleCompra($id_compra['id'], $id_pro, $cantidad, $precio, $sub_total);
                $stock_actual = $this->model->getProductos($id_pro);
                $stock = $stock_actual['cantidad'] + $cantidad;
                $this->model->actualizarStock($stock, $id_pro);
            }
            $vaciar = $this->model->vaciarDetalle('detalle',$id_usuario);
            if($vaciar == 'ok'){
                $msg = array ('msg' => 'ok', 'id_compra' => $id_compra['id']);
            }
           
           }else{
               $msg = 'Error al realizar la compra';
           }
           echo json_encode($msg);
           die();
        }
        public function registrarVenta($id_cliente){
            $id_usuario = $_SESSION['id_usuario'];
            $total = $this->model->calcularCompra('detalle_temp', $id_usuario);
           $data = $this->model->registrarVenta($id_usuario, $id_cliente, $total['total']); 
           if($data == 'ok'){
            $detalle = $this->model->getDetalle('detalle_temp', $id_usuario);
            $id_venta = $this->model->getId('ventas');
            foreach($detalle as $row){
                $cantidad = $row['cantidad'];
                $desc = $row['descuento'];
                $precio = $row['precio'];
                $id_pro = $row['id_producto'];
                $sub_total = ($cantidad * $precio) - $desc;
                $this->model->registrarDetalleVenta($id_venta['id'], $id_pro, $cantidad, $desc, $precio, $sub_total);
                $stock_actual = $this->model->getProductos($id_pro);
                $stock = $stock_actual['cantidad'] - $cantidad;
                $this->model->actualizarStock($stock, $id_pro);
            }
            $vaciar = $this->model->vaciarDetalle('detalle_temp',$id_usuario);
            if($vaciar == 'ok'){
                $msg = array ('msg' => 'ok', 'id_venta' => $id_venta['id']);
            }
           
           }else{
               $msg = 'Error al realizar la venta';
           }
           echo json_encode($msg);
           die();
        }
        public function registrarEncargo($id_cliente){
            $id_usuario = $_SESSION['id_usuario'];
            $total = $this->model->calcularCompra('detalle_temp_encargo', $id_usuario);
           $data = $this->model->registrarEncargo($id_cliente, $total['total']); 
           if($data == 'ok'){
            $detalle = $this->model->getDetalle('detalle_temp_encargo', $id_usuario);
            $id_encargo = $this->model->getId('encargos');
            foreach($detalle as $row){
                $cantidad = $row['cantidad'];
                $precio = $row['precio'];
                $id_pro = $row['id_producto'];
                $sub_total = $cantidad * $precio;
                $this->model->registrarDetalleEncargo($id_encargo['id'], $id_pro, $cantidad, $precio, $sub_total);
                $stock_actual = $this->model->getProductos($id_pro);
                $stock = $stock_actual['cantidad'] - $cantidad;
                $this->model->actualizarStock($stock, $id_pro);
            }
            $vaciar = $this->model->vaciarDetalle('detalle_temp_encargo',$id_usuario);
            if($vaciar == 'ok'){
                $msg = array ('msg' => 'ok', 'id_encargo' => $id_encargo['id']);
            }
           
           }else{
               $msg = 'Error al realizar el encargo';
           }
           echo json_encode($msg);
           die();
        }
        public function generarPdf($id_compra){
            $empresa = $this->model->getEmpresa();
            $productos = $this->model->getProCompra($id_compra);
            require('Libraries/fpdf/fpdf.php');

            $pdf = new FPDF('P','mm', 'A4');
            $pdf->AddPage();
            $pdf->SetMargins(5, 0, 0);
            $pdf->SetTitle('Reporte Compra');
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(65,10, utf8_decode($empresa['nombre']), '0','1','C');
            $pdf ->Image(base_url . 'Assets/img/log.png', 160, 10, 40, 10 );
            $pdf->SetFont('Arial','B',11);
            $pdf ->Cell(30, 8, 'Ruc: ', '12','0','L');
            $pdf->SetFont('Arial','',11);
            $pdf ->Cell(30, 8, $empresa['ruc'], '12','1','L');
            
            $pdf->SetFont('Arial','B',11);
            $pdf ->Cell(30, 8, utf8_decode('Teléfono: '), '12','0','L');
            $pdf->SetFont('Arial','',11);
            $pdf ->Cell(30, 8, $empresa['telefono'], '12','1','L');

            $pdf->SetFont('Arial','B',11);
            $pdf ->Cell(30, 8, utf8_decode('Dirección: '), '12','0','L');
            $pdf->SetFont('Arial','',11);
            $pdf ->Cell(30, 8, utf8_decode($empresa['direccion']), '12','1','L');

            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(30, 8, utf8_decode('N° Compra: '), '12','0','L');
            $pdf->SetFont('Arial','',11);
            $pdf->Cell(20, 8, $id_compra, '12','1','L');
            $pdf->Ln();
            //Encabezado de productos 
            $pdf->SetFillColor(0, 179, 197 );
            $pdf->SetTextColor(0, 0, 0 );
            $pdf -> Cell(30, 8, 'Cantidad', 0, 0, 'L',true);
            $pdf -> Cell(100, 8, utf8_decode('Descripción'), 0, 0, 'L', true);
            $pdf -> Cell(30, 8, 'Precio', 0, 0, 'L', true);
            $pdf -> Cell(30, 8, 'Sub Total', 0, 1, 'L', true);
            $pdf->SetTextColor(0,0,0);
            $total = 0.00;
            foreach($productos as $row){
                $total = $total + $row['sub_total'];
                $pdf -> Cell(30, 8, $row['cantidad'], 0, 0, 'L');
                $pdf -> Cell(100, 8, utf8_decode($row['descripcion']), 0, 0, 'L');
                $pdf -> Cell(30, 8, number_format($row['precio'],0,'.',','), 0, 0, 'L');
                $pdf -> Cell(30, 8, number_format($row['sub_total'], 0, '.', ','), 0, 1, 'L');
            }
            $pdf->Ln();
            $pdf->Cell(165, 5, 'Total a pagar', 0, 1, 'R');
            $pdf->Cell(165, 5, number_format($total , 0, '.', ','), 0, 1, 'R');
            $pdf->Output();
        }
        public function historial(){
            $id_user = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_user, 'historial_compras');
            if (!empty($verificar) || $id_user == 1 ) {
                $this->views->getView($this, "historial"); 
            } else {
                header('Location: '.base_url. 'Errors/permisos');
            }

            
        }
        public function listar_historial(){
            $data =  $this-> model->getHistorialCompras();
            for ($i=0; $i < count($data); $i++) { 
                if ($data[$i]['estado'] == 1) {
                    $data[$i]['estado'] = '<span class="badge bg-success">Completado</span>';
                    $data[$i]['acciones'] = '<div>
                    <button class="btn btn-warning" onclick="btnAnularC(' . $data[$i]['id'] . ')"><i class="fas fa-ban"></i></button>
                    <a class="btn btn-danger" href="'.base_url. "Compras/generarPdf/" .$data[$i]['id'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
                    <div/>';
                }else {
                    $data[$i]['estado'] = '<span class="badge bg-danger">Anulado</span>';
                    $data[$i]['acciones'] = '<div>
                     <a class="btn btn-danger" href="'.base_url. "Compras/generarPdf/" .$data[$i]['id'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
                    <div/>';
                }
               
            }
           
           echo json_encode($data, JSON_UNESCAPED_UNICODE);
           die();
        }
        public function listar_historial_venta(){
            $data =  $this-> model->getHistorialVentas();
            for ($i=0; $i < count($data); $i++) { 
                if ($data[$i]['estado'] == 1) {
                    $data[$i]['estado'] = '<span class="badge bg-success">Completado</span>';
                    $data[$i]['acciones'] = '<div>
                    <button class="btn btn-warning" onclick="btnAnularV(' . $data[$i]['id'] . ')"><i class="fas fa-ban"></i></button>
                    <a class="btn btn-danger" href="'.base_url. "Compras/generarPdfVenta/" .$data[$i]['id'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
                    <div/>';
                }else {
                    $data[$i]['estado'] = '<span class="badge bg-danger">Anulado</span>';
                    $data[$i]['acciones'] = '<div>
                     <a class="btn btn-danger" href="'.base_url. "Compras/generarPdfVenta/" .$data[$i]['id'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
                    <div/>';
                }
               
            }
           
           echo json_encode($data, JSON_UNESCAPED_UNICODE);
           die();
        }
        public function listar_historial_encargo(){
            $data =  $this-> model->getHistorialEncargos();
            for ($i=0; $i < count($data); $i++) { 
                if ($data[$i]['estado'] == 1) {
                    $data[$i]['estado'] = '<span class="badge bg-success">Completado</span>';
                    $data[$i]['acciones'] = '<div>
                    <button class="btn btn-warning" onclick="btnAnularE(' . $data[$i]['id'] . ')"><i class="fas fa-ban"></i></button>
                    <a class="btn btn-danger" href="'.base_url. "Compras/generarPdfEncargo/" .$data[$i]['id'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
                    <div/>';
                }else {
                    $data[$i]['estado'] = '<span class="badge bg-danger">Anulado</span>';
                    $data[$i]['acciones'] = '<div>
                     <a class="btn btn-danger" href="'.base_url. "Compras/generarPdfEncargo/" .$data[$i]['id'].'" target="_blank"><i class="fas fa-file-pdf"></i></a>
                    <div/>';
                }
               
            }
           
           echo json_encode($data, JSON_UNESCAPED_UNICODE);
           die();
        }
        
        public function generarPdfVenta($id_venta){
            $empresa = $this->model->getEmpresa();
            $descuento = $this->model->getDescuento($id_venta);
            $productos = $this->model->getProVenta($id_venta);
            require('Libraries/fpdf/fpdf.php');

            $pdf = new FPDF('P','mm', 'A4');
            $pdf->AddPage();
            $pdf->SetMargins(5, 0, 0);
            $pdf->SetTitle('Reporte Venta');
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(65,10, utf8_decode($empresa['nombre']), '0','1','C');
            $pdf ->Image(base_url . 'Assets/img/log.png', 160, 10, 40, 10 );
            $pdf->SetFont('Arial','B',11);
            $pdf ->Cell(30, 8, 'Ruc: ', '12','0','L');
            $pdf->SetFont('Arial','',11);
            $pdf ->Cell(30, 8, $empresa['ruc'], '12','1','L');
            
            $pdf->SetFont('Arial','B',11);
            $pdf ->Cell(30, 8, utf8_decode('Teléfono: '), '12','0','L');
            $pdf->SetFont('Arial','',11);
            $pdf ->Cell(30, 8, $empresa['telefono'], '12','1','L');

            $pdf->SetFont('Arial','B',11);
            $pdf ->Cell(30, 8, utf8_decode('Dirección: '), '12','0','L');
            $pdf->SetFont('Arial','',11);
            $pdf ->Cell(30, 8, utf8_decode($empresa['direccion']), '12','1','L');

            $pdf->SetFont('Arial','B',11);
            $pdf ->Cell(30, 8, utf8_decode('Mensaje: '), '12','0','L');
            $pdf->SetFont('Arial','',11);
            $pdf ->Cell(30, 8, utf8_decode($empresa['mensaje']), '12','1','L');

            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(30, 8, utf8_decode('N° Venta: '), '12','0','L');
            $pdf->SetFont('Arial','',11);
            $pdf->Cell(20, 8, $id_venta, '12','1','L');
            $pdf->Ln();
            //Encabezado de clientes 
            $pdf->SetFillColor(0, 179, 197 );
            $pdf->SetTextColor(0, 0, 0 );
            $pdf->SetFont('Arial','B',10);
            $pdf -> Cell(30, 8, 'Nombre', 0, 0, 'L',true);
            $pdf -> Cell(20, 8, utf8_decode('Teléfono'), 0, 0, 'L', true);
            $pdf -> Cell(30, 8, utf8_decode('Dirección'), 0, 1, 'L', true);
            $pdf->SetTextColor(0,0,0);
            $clientes = $this->model->clientesVenta($id_venta);
            $pdf->SetFont('Arial','',9);
            $pdf -> Cell(30, 8, utf8_decode($clientes['nombre']), 0, 0, 'L');
            $pdf -> Cell(20, 8, ($clientes['telefono']), 0, 0, 'L');
            $pdf -> Cell(30, 8, utf8_decode($clientes['direccion']), 0, 1, 'L');
            
            $pdf->Ln();
            //Encabezado de productos 
            $pdf->SetFillColor(0, 179, 197 );
            $pdf->SetTextColor(0, 0, 0 );
            $pdf -> Cell(30, 8, 'Cantidad', 0, 0, 'L',true);
            $pdf -> Cell(100, 8, utf8_decode('Descripción'), 0, 0, 'L', true);
            $pdf -> Cell(30, 8, 'Precio', 0, 0, 'L', true);
            $pdf -> Cell(30, 8, 'Sub Total', 0, 1, 'L', true);
            $pdf->SetTextColor(0,0,0);
            $total = 0.00;
            foreach($productos as $row){
                $total = $total + $row['sub_total'];
                $pdf -> Cell(30, 8, $row['cantidad'], 0, 0, 'L');
                $pdf -> Cell(100, 8, utf8_decode($row['descripcion']), 0, 0, 'L');
                $pdf -> Cell(30, 8, number_format($row['precio'],0,'.',','), 0, 0, 'L');
                $pdf -> Cell(30, 8, number_format($row['sub_total'], 0, '.', ','), 0, 1, 'L');
            }
            $pdf->Ln();
            $pdf->Cell(165, 5, 'Descuento total', 0, 1, 'R');
            $pdf->Cell(165, 5, number_format($descuento['total'] , 0, '.', ','), 0, 1, 'R');
            $pdf->Cell(165, 5, 'Total a pagar', 0, 1, 'R');
            $pdf->Cell(165, 5, number_format($total , 0, '.', ','), 0, 1, 'R');
            $pdf->Output();
        }
        public function generarPdfEncargo($id_encargo){
            $empresa = $this->model->getEmpresa();
            $productos = $this->model->getProEncargo($id_encargo);
            require('Libraries/fpdf/fpdf.php');

            $pdf = new FPDF('P','mm', 'A4');
            $pdf->AddPage();
            $pdf->SetMargins(5, 0, 0);
            $pdf->SetTitle('Reporte Encargo');
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(65,10, utf8_decode($empresa['nombre']), '0','1','C');
            $pdf ->Image(base_url . 'Assets/img/log.png', 160, 10, 40, 10 );
            $pdf->SetFont('Arial','B',11);
            $pdf ->Cell(30, 8, 'Ruc: ', '12','0','L');
            $pdf->SetFont('Arial','',11);
            $pdf ->Cell(30, 8, $empresa['ruc'], '12','1','L');
            
            $pdf->SetFont('Arial','B',11);
            $pdf ->Cell(30, 8, utf8_decode('Teléfono: '), '12','0','L');
            $pdf->SetFont('Arial','',11);
            $pdf ->Cell(30, 8, $empresa['telefono'], '12','1','L');

            $pdf->SetFont('Arial','B',11);
            $pdf ->Cell(30, 8, utf8_decode('Dirección: '), '12','0','L');
            $pdf->SetFont('Arial','',11);
            $pdf ->Cell(30, 8, utf8_decode($empresa['direccion']), '12','1','L');

            $pdf->SetFont('Arial','B',11);
            $pdf ->Cell(30, 8, utf8_decode('Mensaje: '), '12','0','L');
            $pdf->SetFont('Arial','',11);
            $pdf ->Cell(30, 8, utf8_decode($empresa['mensaje']), '12','1','L');


            $pdf->SetFont('Arial','B',11);
            $pdf->Cell(30, 8, utf8_decode('N° Encargo: '), '12','0','L');
            $pdf->SetFont('Arial','',11);
            $pdf->Cell(20, 8, $id_encargo, '12','1','L');
            $pdf->Ln();
             //Encabezado de clientes 
             $pdf->SetFillColor(0, 179, 197 );
             $pdf->SetTextColor(0, 0, 0 );
             $pdf->SetFont('Arial','B',10);
             $pdf -> Cell(30, 8, 'Nombre', 0, 0, 'L',true);
             $pdf -> Cell(20, 8, utf8_decode('Teléfono'), 0, 0, 'L', true);
             $pdf -> Cell(30, 8, utf8_decode('Dirección'), 0, 1, 'L', true);
             $pdf->SetTextColor(0,0,0);
             $clientes = $this->model->clientesEncargo($id_encargo);
             $pdf->SetFont('Arial','',9);
             $pdf -> Cell(30, 8, utf8_decode($clientes['nombre']), 0, 0, 'L');
             $pdf -> Cell(20, 8, ($clientes['telefono']), 0, 0, 'L');
             $pdf -> Cell(30, 8, utf8_decode($clientes['direccion']), 0, 1, 'L');
             
             $pdf->Ln();
            //Encabezado de productos 
            $pdf->SetFillColor(0, 179, 197 );
            $pdf->SetTextColor(0, 0, 0 );
            $pdf -> Cell(30, 8, 'Cantidad', 0, 0, 'L',true);
            $pdf -> Cell(100, 8, utf8_decode('Descripción'), 0, 0, 'L', true);
            $pdf -> Cell(30, 8, 'Precio', 0, 0, 'L', true);
            $pdf -> Cell(30, 8, 'Sub Total', 0, 1, 'L', true);
            $pdf->SetTextColor(0,0,0);
            $total = 0.00;
            foreach($productos as $row){
                $total = $total + $row['sub_total'];
                $pdf -> Cell(30, 8, $row['cantidad'], 0, 0, 'L');
                $pdf -> Cell(100, 8, utf8_decode($row['descripcion']), 0, 0, 'L');
                $pdf -> Cell(30, 8, number_format($row['precio'],0,'.',','), 0, 0, 'L');
                $pdf -> Cell(30, 8, number_format($row['sub_total'], 0, '.', ','), 0, 1, 'L');
            }
            $pdf->Ln();
            $pdf->Cell(165, 5, 'Total a pagar', 0, 1, 'R');
            $pdf->Cell(165, 5, number_format($total , 0, '.', ','), 0, 1, 'R');
            $pdf->Output();
        }
        public function calcularDescuento($datos){
           $array = explode(",", $datos);
           $id = $array[0];
           $desc = $array[1]; 
           if(empty($id) || empty($desc)){
            $msg = array ('msg' => 'Error', 'icono' =>'error');
           }else{
               $descuento_actual = $this->model->verificarDescuento($id);
               $descuento_total = $descuento_actual['descuento'] + $desc;
               $sub_total = ($descuento_actual['cantidad']* $descuento_actual['precio']) - $descuento_total;
               $data = $this->model->actualizarDescuento($descuento_total, $sub_total,$id);
               if($data == "ok"){
                $msg = array ('msg' => 'Descuento aplicado', 'icono' =>'success');
               }else{
                $msg = array ('msg' => 'Error al aplicar el descuento', 'icono' =>'error');
               }

               
           }
           echo json_encode($msg);
           die();
        }
        public function anularCompra($id_compra){

            $id_user = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_user, 'anular_compra');
            if (!empty($verificar) || $id_user == 1 ) {
                $data = $this->model->getAnularCompra($id_compra);
                $anular = $this->model->getAnular($id_compra);
                foreach($data as $row){
                    $stock_actual = $this->model->getProductos($row['id_producto']);
                    $stock = $stock_actual['cantidad'] - $row['cantidad'];
                    $this->model->actualizarStock($stock, $row['id_producto']);
                }
                if($anular == 'ok'){
                    $msg = array('msg' => 'Compra Anulada', 'icono' => 'success');
                }else{
                    $msg = array('msg' => 'Error al anular la compra', 'icono' => 'error');
                }
                
            } else {
                $msg = array('msg' => 'No tienes permisos para anular una compra', 'icono' => 'warning');

            }
            echo json_encode($msg);
                die();

            

        }
        public function anularVenta($id_venta){
            $id_user = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_user, 'anular_venta');
            if (!empty($verificar) || $id_user == 1 ) {
                $data = $this->model->getAnularVenta($id_venta);
                $anular = $this->model->getAnularV($id_venta);
                foreach($data as $row){
                    $stock_actual = $this->model->getProductos($row['id_producto']);
                    $stock = $stock_actual['cantidad'] + $row['cantidad'];
                    $this->model->actualizarStock($stock, $row['id_producto']);
                }
                if($anular == 'ok'){
                    $msg = array('msg' => 'Venta Anulada', 'icono' => 'success');
                }else{
                    $msg = array('msg' => 'Error al anular la venta', 'icono' => 'error');
                }
                
            } else {
                $msg = array('msg' => 'No tienes permisos para anular una venta', 'icono' => 'warning');
               
            }
            echo json_encode($msg);
            die();

           

        }
        public function anularEncargo($id_encargo){
            $id_user = $_SESSION['id_usuario'];
            $verificar = $this->model->verificarPermiso($id_user, 'anular_encargo');
            if (!empty($verificar) || $id_user == 1 ) {
                $data = $this->model->getAnularEncargo($id_encargo);
                $anular = $this->model->getAnularE($id_encargo);
                foreach($data as $row){
                    $stock_actual = $this->model->getProductos($row['id_producto']);
                    $stock = $stock_actual['cantidad'] + $row['cantidad'];
                    $this->model->actualizarStock($stock, $row['id_producto']);
                }
                if($anular == 'ok'){
                    $msg = array('msg' => 'Encargo Anulado', 'icono' => 'success');
                }else{
                    $msg = array('msg' => 'Error al anular el encargo', 'icono' => 'error');
                }
                
            } else {
                $msg = array('msg' => 'No tienes permisos para anular un encargo', 'icono' => 'error');
               
            }
            echo json_encode($msg);
                die();
           

        }
    }
?>