<h1 class="titulo-login">Crear Nueva Cita</h1>

<?php include_once __DIR__ . "/../templates/barra.php"; ?>

<?php date_default_timezone_set('America/Mexico_City'); ?>

<p class="text-center">Elige tus servicios a continuación</p>

<div id="app">

    <nav class="tabs">
        <button type="button" class="actual" data-paso="1">Servicios</button>
        <button type="button" data-paso="2">Información Cita</button>
        <button type="button" data-paso="3">Resumen</button>
    </nav>

    <div id="paso-1" class="seccion">
        <h2>Servicios</h2>
        <p class="text-center">Elige tus servicios a continuación</p>
        <div id="servicios" class="listado-servicios"></div>
    </div>
    <div id="paso-2" class="seccion">
        <h2>Tus Datos y Cita</h2>
        <p class="text-center">Coloca tus datos y fecha de la cita</p>

        <form class="formulario">

            <div class="campo">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" placeholder="Escriba su nombre" name="nombre"
                    value="<?php echo s($nombre); ?>" disabled>
            </div>

            <div class="campo">
                <label for="fecha">Fecha:</label>
                <input type="date" id="fecha" name="fecha" min="<?php echo date("Y-m-d",strtotime("+1 day")); ?>">
            </div>

            <div class="campo">
                <label for="hora">Hora:</label>
                <input type="time" id="hora" name="hora">
            </div>

            <input type="hidden" name="usuario_id" id="usuario_id" value="<?php echo s($usuario_id); ?>">

        </form>

    </div>
    <div id="paso-3" class="seccion contenido-resumen">
    </div>

    <div class="paginacion">
        <button id="anterior" class="boton-azul">
            &laquo; Anterior
        </button>

        <button id="siguiente" class="boton-azul">
            Siguiente &raquo;
        </button>
    </div>

</div>

<?php

$script = "
    <script src='build/js/app.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
";

?>