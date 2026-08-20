<?php

namespace Controllers;

use Model\AdminCita;
use MVC\Router;

class AdminController {
    
    public static function admin(Router $router) {

        isAdmin();
        
        date_default_timezone_set('America/Mexico_City');

        $fecha = $_GET["fecha"] ?? date("Y-m-d");

        $partes = explode("-",$fecha);
        if(!checkdate(intval($partes[1]),$partes[2],$partes[0])) {
           header("Location: /404");
           exit();
        }
        
        $consulta = "SELECT c.id, c.hora, concat(u.nombre, ' ', u.apellido) AS cliente, u.telefono, u.email, cs.servicio_id, s.nombre AS servicio, s.precio
        FROM citas AS c
        INNER JOIN citasservicios AS cs ON cs.cita_id = c.id
        INNER JOIN servicios AS s ON s.id = cs.servicio_id
        INNER JOIN usuarios AS u ON u.id = c.usuario_id
        WHERE c.fecha = '$fecha'";
        
        $citas = AdminCita::SQL($consulta);
        
        $router->render("admin/index", [
            "nombre" => $_SESSION["nombre"],
            "citas" => $citas,
            "fecha" => $fecha
        ]);
        
    }
    
}