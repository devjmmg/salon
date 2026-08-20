<h1 class="titulo-login">Panel de Administración</h1>

<?php include_once __DIR__ . "/../templates/barra.php"; ?>

<h2 class="text-center">Buscar citas</h2>

<div class="busqueda">
    <form class="formulario">

        <div class="campo">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha ?>">
        </div>

    </form>
</div>

<?php

if(count($citas) === 0){
    echo("<h2>No hay citas...</h2>");
}

?>

<div class="citas-admin">

    <ul class="citas">

        <?php 

        $id = 0;

        //$key es el indice
        foreach ($citas as $key => $cita): //Siempre el último es el que contiene el valor en este caso $cita pero si solo estuviera ($citas as $key) key tendría el valor y sería $key["id"]

            

            if($cita->id !== $id):

                $total = 0;

        ?>

                <li>
                    <p>ID: <span><?php echo $cita->id ?></span></p>
                    <p>Hora: <span><?php echo $cita->hora?> Horas</span></p>
                    <p>Cliente: <span><?php echo $cita->cliente ?></span></p>
                    <p>Telefono: <span><?php echo $cita->telefono ?></span></p>
                    <p>Correo electrónico: <span><?php echo $cita->email ?></span></p>
                </li>

                <h3>Servicios</h3>

            <?php 
                
            endif; 
                
            $id = $cita->id;

            $total += $cita->precio; //Suma de precio
                
            ?>

            <p class="servicio"><?php echo $cita->servicio . " - $" . $cita->precio; ?></p>

            <?php //if(esUltimo($id,$citas[$key+1]->id ?? 0)): ?>
            <?php if(!isset($citas[$key+1]) || $id !== $citas[$key+1]->id): ?>

            <p class="total">Total: <span>$<?php echo $total?></span></p>

            <form action="/api/eliminar" method="POST">

                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <input class="boton-rojo" type="submit" value="Eliminar">

            </form>

            <?php endif; ?>

        <?php endforeach; ?>

    </ul>

</div>

<?php

$script = "
    <script src='build/js/buscador.js'></script>
";

?>