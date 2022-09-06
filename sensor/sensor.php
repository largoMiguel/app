<?php
include("../data-base.php");
$database = new Database('user-app');

$name = $_GET['name'];
$state = $_GET['state'];

$connection = $database->getConnection();

mysqli_query($connection, "UPDATE easypark.ubicacion u
                                    SET u.estadoUbicacion = '$state'
                                    WHERE u.idUbicacion = '$name';");


