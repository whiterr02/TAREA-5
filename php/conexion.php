<?php
    function retornarConexion()
    {
        $server = "localhost";
        $usuario = "root";
        $clave = "wltr6969";
        $base = "base1";
        $con = mysqli_connect($server, $usuario, $clave, $base) or die("problemas");
        mysqli_set_charset($con, 'utf8');
        return $con;
    }

?>