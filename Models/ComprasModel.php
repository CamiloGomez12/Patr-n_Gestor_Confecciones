<?php
class ComprasModel extends Query{
    private $nombre, $estado;
    public function __construct()
    {
        parent::__construct();
    }
    public function getClientes(){
        $sql = "SELECT * FROM clientes WHERE estado = 1";
        $data =  $this->selectAll($sql);
        return $data;
    }
    public function getProCod(string $cod){
         $sql = "SELECT * FROM productos WHERE codigo = '$cod'";
         $data =  $this->select($sql);
         return $data;
    }
    public function getProductos(int $id){
        $sql = "SELECT * FROM productos WHERE id  = $id ";
        $data =  $this->select($sql);
        return $data;
    }
    public function registrarDetalle(string $table, int $id_producto, int $id_usuario, string $precio, int $cantidad, string $sub_total){
            $sql = "INSERT INTO $table(id_producto, id_usuario, precio, cantidad, sub_total) values (?,?,?,?,?)";
            $datos = array($id_producto, $id_usuario, $precio, $cantidad, $sub_total);
            $data = $this->save($sql, $datos);
            if($data == 1){
                $res = "ok";
            }else{
                $res = "error";
            }
            return $res;
        }
        public function getDetalle(string $table, int $id){
            $sql  = "SELECT d.*, p.id AS id_pro, p.descripcion FROM $table d INNER JOIN productos p ON d.id_producto = p.id WHERE d.id_usuario = $id ";
            $data = $this->selectAll($sql);
            return $data; 
        }
        public function calcularCompra(string $table, int $id_usuario){
            $sql  = "SELECT sub_total, SUM(sub_total) AS total FROM $table WHERE id_usuario = $id_usuario";
            $data = $this->select($sql);
            return $data; 
        }
        public function deleteDetalle(string $table, int $id){
            $sql  = "DELETE FROM $table WHERE id = ?";
            $datos = array($id);
            $data = $this->save($sql, $datos);
            if($data == 1){
                $res = "ok";
            }else{
                $res = "error";
            }
            return $res; 
        }
        public function consultarDetalle(string $table, int $id_producto, int $id_usuario){
            $sql  = "SELECT * FROM $table WHERE id_producto = $id_producto AND id_usuario = $id_usuario";
            $data = $this->select($sql);
            return $data; 
        }
        public function actualizarDetalle(string $table, string $precio, int $cantidad, string $sub_total, int $id_producto, int $id_usuario){
            $sql = "UPDATE $table SET precio = ?, cantidad = ?, sub_total = ? WHERE id_producto = ? AND id_usuario = ? ";
            $datos = array($precio, $cantidad, $sub_total, $id_producto, $id_usuario);
            $data = $this->save($sql, $datos);
            if($data == 1){
                $res = "modificado";
            }else{
                $res = "error";
            }
            return $res;
        }
        public function registrarCompra(string $total){
            $sql = "INSERT INTO compras (total) VALUES (?)";
            $datos = array($total);
            $data = $this->save($sql, $datos);
            if($data == 1){
                $res = "ok";
            }else{
                $res = "error";
            }
            return $res;
        }
        public function getId(string $table){
           $sql = "SELECT MAX(id) AS id FROM $table"; 
           $data = $this->select($sql);
           return $data;
        }
        public function registrarDetalleCompra(int $id_compra,  int $id_pro, int $cantidad, string $precio, string $sub_total){
            $sql = "INSERT INTO detalle_compras (id_compra, id_producto, cantidad, precio, sub_total) VALUES (?,?,?,?,?)";
            $datos = array($id_compra, $id_pro, $cantidad, $precio, $sub_total);
            $data = $this->save($sql, $datos);
            if($data == 1){
                $res = "ok";
            }else{
                $res = "error";
            }
            return $res;
        }
        public function registrarDetalleVenta(int $id_venta,  int $id_pro, int $cantidad, string $desc, string $precio, string $sub_total){
            $sql = "INSERT INTO detalle_ventas (id_venta, id_producto, cantidad, descuento, precio, sub_total) VALUES (?,?,?,?,?,?)";
            $datos = array($id_venta, $id_pro, $cantidad, $desc, $precio, $sub_total);
            $data = $this->save($sql, $datos);
            if($data == 1){
                $res = "ok";
            }else{
                $res = "error";
            }
            return $res;
        }
        public function registrarDetalleEncargo(int $id_encargo,  int $id_pro, int $cantidad, string $precio, string $sub_total){
            $sql = "INSERT INTO detalle_encargos (id_encargo, id_producto, cantidad, precio, sub_total) VALUES (?,?,?,?,?)";
            $datos = array($id_encargo, $id_pro, $cantidad, $precio, $sub_total);
            $data = $this->save($sql, $datos);
            if($data == 1){
                $res = "ok";
            }else{
                $res = "error";
            }
            return $res;
        }
        public function getEmpresa(){
            $sql = "SELECT * FROM configuracion";
            $data = $this->select($sql);
            return($data);
        }
        public function vaciarDetalle(string $table, int $id_usuario){
            $sql = "DELETE FROM $table WHERE id_usuario = ?";
            $datos = array($id_usuario);
            $data = $this->save($sql, $datos);
            if($data == 1){
                $res = "ok";
            }else{
                $res = "error";
            }
            return $res;
        }
        public function getProCompra(int $id_compra){
            $sql = "SELECT c.*, d.*, p.id, p.descripcion FROM compras c INNER JOIN detalle_compras d ON c.id = d.id_compra INNER JOIN productos p ON p.id = d.id_producto WHERE c.id = $id_compra";
            $data = $this->selectAll($sql);
            return $data; 

        }
        public function getProVenta(int $id_venta){
            $sql = "SELECT v.*, d.*, p.id, p.descripcion FROM ventas v INNER JOIN detalle_ventas d ON v.id = d.id_venta INNER JOIN productos p ON p.id = d.id_producto WHERE v.id = $id_venta";
            $data = $this->selectAll($sql);
            return $data; 

        }
        public function getProEncargo(int $id_encargo){
            $sql = "SELECT v.*, d.*, p.id, p.descripcion FROM encargos v INNER JOIN detalle_encargos d ON v.id = d.id_encargo INNER JOIN productos p ON p.id = d.id_producto WHERE v.id = $id_encargo";
            $data = $this->selectAll($sql);
            return $data; 

        }
        public  function getHistorialCompras(){
            $sql = "SELECT * FROM compras ";
            $data = $this->selectAll($sql);
            return $data;
        }
        public  function getHistorialVentas(){
            $sql = "SELECT c.id, c.nombre, v.* FROM clientes c INNER JOIN ventas v ON v.id_cliente = c.id ";
            $data = $this->selectAll($sql);
            return $data;
        }
        public  function getHistorialEncargos(){
            $sql = "SELECT c.id, c.nombre, v.* FROM clientes c INNER JOIN encargos v ON v.id_cliente = c.id ";
            $data = $this->selectAll($sql);
            return $data;
        }
        public function actualizarStock(int $cantidad, int $id_pro){
            $sql = "UPDATE productos SET cantidad = ? WHERE id = ?";
            $datos = array($cantidad, $id_pro);
            $data = $this->save($sql, $datos);
            if($data == 1){
            return $data;
            }
        }
        public function registrarVenta(int $id_user, int $id_cliente, string $total){
            $sql = "INSERT INTO ventas (id_usuario, id_cliente, total) VALUES (?,?, ?)";
            $datos = array($id_user, $id_cliente, $total);
            $data = $this->save($sql, $datos);
            if($data == 1){
                $res = "ok";
            }else{
                $res = "error";
            }
            return $res;
        }
        public function clientesVenta(int $id){
            $sql = "SELECT v.id, v.id_cliente, c.* FROM ventas v INNER JOIN clientes c ON c.id = v.id_cliente WHERE v.id = $id ";
            $data = $this->select($sql);
            return $data;
        }
        public function clientesEncargo(int $id){
            $sql = "SELECT v.id, v.id_cliente, c.* FROM encargos v INNER JOIN clientes c ON c.id = v.id_cliente WHERE v.id = $id ";
            $data = $this->select($sql);
            return $data;
        }
        //error al ingresar el registro de fecha de plazo
        public function registrarEncargo(int $id_cliente, string $total){
            $sql = "INSERT INTO encargos (id_cliente, total) VALUES (?,?)";
            $datos = array($id_cliente, $total);
            $data = $this->save($sql, $datos);
            if($data == 1){
                $res = "ok";
            }else{
                $res = "error";
            }
            return $res;
        }
        public function verificarDescuento(int $id){
            $sql = "SELECT * FROM detalle_temp WHERE id = $id ";
            $data = $this->select($sql);
            return $data;
        }
        public function actualizarDescuento(string $desc, string $sub_total, int $id){
            $sql = "UPDATE detalle_temp SET descuento = ?, sub_total = ? WHERE id = ?";
            $datos = array($desc, $sub_total, $id);
            $data = $this->save($sql, $datos);
            if($data == 1){
                $res = "ok";
            }else{
                $res = "error";
            }
            return $res;
        }
        public function getDescuento(int $id_venta){
            $sql  = "SELECT descuento, SUM(descuento) AS total FROM detalle_ventas WHERE id_venta = $id_venta";
            $data = $this->select($sql);
            return $data; 
        }
        public function getAnularCompra(int $id_compra){
            $sql = "SELECT c.*, d.* FROM compras c INNER JOIN detalle_compras d ON c.id = d.id_compra WHERE c.id = $id_compra";
            $data = $this->selectAll($sql);
            return $data;
        }
        public function getAnularVenta(int $id_venta){
            $sql = "SELECT c.*, d.* FROM ventas c INNER JOIN detalle_ventas d ON c.id = d.id_venta WHERE c.id = $id_venta";
            $data = $this->selectAll($sql);
            return $data;
        }
        public function getAnularEncargo(int $id_encargo){
            $sql = "SELECT c.*, d.* FROM encargos c INNER JOIN detalle_encargos d ON c.id = d.id_encargo WHERE c.id = $id_encargo";
            $data = $this->selectAll($sql);
            return $data;
        }
        public function getAnular(int $id_compra){
            $sql = "UPDATE compras SET estado = ? WHERE id = ?";
            $datos = array(0, $id_compra);
            $data = $this->save($sql, $datos);
            if($data == 1){
                $res = "ok";
            }else{
                $res = "error";
            }
            return $res;
        }
        public function getAnularV(int $id_venta){
            $sql = "UPDATE ventas SET estado = ? WHERE id = ?";
            $datos = array(0, $id_venta);
            $data = $this->save($sql, $datos);
            if($data == 1){
                $res = "ok";
            }else{
                $res = "error";
            }
            return $res;
        }
        public function getAnularE(int $id_encargo){
            $sql = "UPDATE encargos SET estado = ? WHERE id = ?";
            $datos = array(0, $id_encargo);
            $data = $this->save($sql, $datos);
            if($data == 1){
                $res = "ok";
            }else{
                $res = "error";
            }
            return $res;
        }
        public function verificarPermiso(int $id_user, string $nombre){
            $sql = "SELECT p.id, p.permiso, d.id, d.id_usuario, d.id_permiso FROM permisos p INNER JOIN detalle_permisos d ON p.id = d.id_permiso WHERE d.id_usuario = $id_user AND p.permiso = '$nombre'";
            $data = $this->selectAll($sql);
            return $data;
        }
        
    }
?>