<?php
include("../data-base.php");
$database = new Database('user-app');
$connection = $database->getConnection();

switch ($_SERVER['REQUEST_URI']) {
    case '/desktop/parking.php':
        $name = $_GET['name'];
        $state = $_GET['state'];
        updateSensor($connection, $name, $state);
        break;
    case '/desktop/parking.php/getSensors':
        getSensors($connection);
        break;
    default:
        echo 'error route';
}


function updateSensor($connection, $name, $state)
{

    mysqli_query($connection, "UPDATE easypark.ubicacion u
                                    SET u.estadoUbicacion = '$state'
                                    WHERE u.idUbicacion = '$name';");
}

function getSensors($connection)
{
    $response = mysqli_query($connection, "SELECT u.idUbicacion
                                    FROM easypark.ubicacion u
                                    WHERE u.estadoUbicacion = '1';");
    $row = $response->fetch_array(MYSQLI_NUM);
    echo json_decode($row);
}


