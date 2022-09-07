<?php
include("../data-base.php");
$database = new Database('user-app');
$connection = $database->getConnection();

switch ($_SERVER['REQUEST_URI']) {
    case '/sensor/sensor.php/getSensors':
        getSensors($connection);
        break;
    default:
        $name = $_GET['name'];
        $state = $_GET['state'];
        updateSensor($connection, $name, $state);
}


function updateSensor($connection, $name, $state)
{

    mysqli_query($connection, "UPDATE easypark.ubicacion u
                                    SET u.estadoUbicacion = '$state'
                                    WHERE u.idUbicacion = '$name';");
    $connection->close();
}

function getSensors($connection)
{
    $response = mysqli_query($connection, "SELECT u.idUbicacion
                                    FROM easypark.ubicacion u
                                    WHERE u.estadoUbicacion = '1';");
    $rows = array();
    while ($r = $response->fetch_array(MYSQLI_NUM)) {
        $rows[] = $r;
    }
    $connection->close();
    echo json_encode($rows);
}


