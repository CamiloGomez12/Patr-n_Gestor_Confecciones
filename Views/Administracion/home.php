<?php include "Views/Templates/header.php"; ?>

<div class="row">
    <div class="col-xl-3 col-md-6">
    <div class="card bg-info">
        <div class="card-body d-flex text-light" >
            Usuarios
            <p>
            <i class="fas fa-user fa-2x ml-auto"></i>
        </div>
        <div class="card-footer d-flex aling-items-center  justify-content-between">
           <a href="<?php echo base_url; ?>Usuarios" class="text-white">Ver Detalle</a>
            <span class="text-white"><?php echo $data['usuarios']['total']; ?></span>
        </div>
    </div>

    </div>
   
    <div class="col-xl-3 col-md-6">
    <div class="card bg-info">
        <div class="card-body d-flex text-white" >
            Cajas
            <p>
            <i class="fas fa-box fa-2x ml-auto"></i>
        </div>
        <div class="card-footer d-flex aling-items-center  justify-content-between">
           <a href="<?php echo base_url; ?>Cajas" class="text-white">Ver Detalle</a>
            <span class="text-white"><?php echo $data['caja']['total']; ?></span>
        </div>
    </div>

    </div>
   
    <div class="col-xl-3 col-md-6">
    <div class="card bg-info">
        <div class="card-body d-flex text-light" >
            Clientes
            <p>
            <i class="fas fa-users fa-2x ml-auto"></i>
        </div>
        <div class="card-footer d-flex aling-items-center  justify-content-between">
           <a href="<?php echo base_url; ?>Clientes" class="text-white">Ver Detalle</a>
            <span class="text-white"><?php echo $data['clientes']['total']; ?></span>
        </div>
    </div>

    </div>
  
    <div class="col-xl-3 col-md-6">
    <div class="card bg-info">
        <div class="card-body d-flex text-light" >
            Categorias
            <p>
            <i class="fas fa-clipboard-list fa-2x ml-auto"></i>
        </div>
        <div class="card-footer d-flex aling-items-center  justify-content-between">
           <a href="<?php echo base_url; ?>Categorias" class="text-white">Ver Detalle</a>
            <span class="text-white"><?php echo $data['categorias']['total']; ?></span>
        </div>
    </div>

    </div>
    
    <p>

    <div class="col-xl-3 col-md-6">
    <div class="card bg-info">
        <div class="card-body d-flex text-light ml-left" >
            Productos
            <p>
            <i class="fas fa-barcode fa-2x ml-auto"></i>
        </div>
        <div class="card-footer d-flex aling-items-center  justify-content-between">
           <a href="<?php echo base_url; ?>Productos" class="text-white">Ver Detalle</a>
            <span class="text-white"><?php echo $data['productos']['total']; ?></span>
        </div>
    </div>

    </div>

    <div class="col-xl-3 col-md-6">
    <div class="card bg-info">
        <div class="card-body d-flex text-light" >
            Historial de compras
            <p>
            <i class="fas fa-cash-register fa-2x ml-auto"></i>
        </div>
        <div class="card-footer d-flex aling-items-center  justify-content-between">
           <a href="<?php echo base_url; ?>Compras/historial" class="text-white">Ver Detalle</a>
            <span class="text-white"><?php echo $data['compras']['total']; ?></span>
        </div>
    </div>

    </div>
   
    <div class="col-xl-3 col-md-6">
    <div class="card bg-info">
        <div class="card-body d-flex text-light" >
            Ventas por día
            <p>
            <i class="fas fa-hand-holding-usd fa-2x ml-auto"></i>
        </div>
        <div class="card-footer d-flex aling-items-center  justify-content-between">
           <a href="<?php echo base_url; ?>Compras/historial_ventas" class="text-white">Ver Detalle</a>
            <span class="text-white"><?php echo $data['ventas']['total']; ?></span>
        </div>
    </div>

    </div>
   
    <div class="col-xl-3 col-md-6">
    <div class="card bg-info">
        <div class="card-body d-flex text-light" >
            Historial de encargos
            <p>
            <i class="fas fa-dolly-flatbed fa-2x ml-auto"></i>
        </div>
        <div class="card-footer d-flex aling-items-center  justify-content-between">
           <a href="<?php echo base_url; ?>Compras/historial_encargos" class="text-white">Ver Detalle</a>
            <span class="text-white"><?php echo $data['encargos']['total']; ?></span>
        </div>
    </div>

    </div>
   
</div>
 
<div class="row mt-2" >
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header bg-dark text-white">
                Productos con Stock Mínimo
            </div>
            <div class="card-body">
            <canvas id="stockMinimo" width="400" height="400"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header bg-dark text-white">
                Productos más Vendidos
            </div>
            <div class="card-body">
            <canvas id="ProductosVendidos" width="400" height="400"></canvas>
            </div>
        </div>
    </div>
</div>
<?php include "Views/Templates/footer.php"; ?>