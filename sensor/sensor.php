<?php
include("../data-base.php");
$database = new Database('user-app');
$connection = $database->getConnection();

switch ($_SERVER['REQUEST_URI']) {
    case '/sensor/sensor.php/getSensors':
        getSensors($connection);
        $connection->close();
        break;
    default:
        $name = $_GET['name'];
        $state = $_GET['state'];
        updateSensor($connection, $name, $state);
        $connection->close();
}


function updateSensor($connection, $name, $state)
{

    mysqli_query($connection, "UPDATE easypark.ubicacion u
                                    SET u.estadoUbicacion = '$state'
                                    WHERE u.idUbicacion = '$name';");
    if ($state == '1') {
        mysqli_query($connection, "UPDATE easypark.ubicacion u
                                                    SET u.state = 'active'
                                                    WHERE u.idUbicacion = '$name'");
        return;
    }
    mysqli_query($connection, "UPDATE easypark.ubicacion u
                                                    SET u.state = 'inactive'
                                                    WHERE u.idUbicacion = '$name'");
}

function getSensors($connection)
{
    $response = mysqli_query($connection, "SELECT u.idUbicacion
                                    FROM easypark.ubicacion u
                                    WHERE u.estadoUbicacion = '1'
                                    AND u.state = 'active';");
    $rows = array();
    while ($r = $response->fetch_array(MYSQLI_NUM)) {
        $rows[] = $r;
    }
    echo json_encode($rows);
}


