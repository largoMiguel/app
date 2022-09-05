<?php
include("../data-base.php");

$database = new Database('user-desktop');
$connection = $database->getConnection();
$username = $_POST['username'];
$id_vehicle = $_POST['id_vehicle'];

switch ($_SERVER['REQUEST_URI']) {
    case '/desktop/parking.php/active':
        $id_location = $_POST['id_location'];
        $input_hour = $_POST['input_hour'];
        active($connection, $username, $id_vehicle, $id_location, $input_hour);
        break;
    case '/desktop/parking.php/inactive':
        inactive($connection, $id_vehicle, $username);
        break;
    default:
        echo 'error route';

}


function active($connection, $username, $id_vehicle, $id_location, $input_hour)
{
    $response = mysqli_query($connection, "SELECT v.state_vehicle FROM easypark.vehicle v
                                                    WHERE v.id_vehicle = '$id_vehicle'");
    $row = $response->fetch_array(MYSQLI_NUM);
    if ($row[0] == 'active') {
        echo 'active';
        return;
    }
    mysqli_query($connection, "UPDATE easypark.vehicle v, easypark.vehicle_has_users vu
                                                    SET vu.input_hour = '$input_hour'
                                                    WHERE vu.vehicle_id_vehicle = '$id_vehicle'
                                                    AND vu.users_username = '$username';");

    mysqli_query($connection, "UPDATE easypark.vehicle v
                                                    SET v.ubicacion_idUbicacion = '$id_location',
                                                        v.state_vehicle        = 'active',
                                                        v.username = '$username'
                                                    WHERE v.id_vehicle = '$id_vehicle'");
    $connection->close();
    echo true;
}

function inactive($connection, $id_vehicle, $username)

{
    $response = mysqli_query($connection, "SELECT v.state_vehicle FROM easypark.vehicle v
                                                    WHERE v.id_vehicle = '$id_vehicle'");
    $row = $response->fetch_array(MYSQLI_NUM);
    if ($row[0] != 'active') {
        echo 'inactive';
        return;
    }
    $response = mysqli_query($connection, "SELECT v.username FROM easypark.vehicle v
                                                    WHERE v.id_vehicle = '$id_vehicle'");
    $row = $response->fetch_array(MYSQLI_NUM);
    if ($row[0] != $username) {
        echo 'invalid';
        return;
    }
    mysqli_query($connection, "UPDATE easypark.vehicle v, easypark.vehicle_has_users vu
                                                    SET vu.input_hour = '00:00'
                                                    WHERE vu.vehicle_id_vehicle = '$id_vehicle'
                                                    AND vu.users_username = '$username';");

    mysqli_query($connection, "UPDATE easypark.vehicle v
                                                    SET v.ubicacion_idUbicacion = '0',
                                                        v.state_vehicle        = 'inactive',
                                                        v.username = '0'
                                                    WHERE v.id_vehicle = '$id_vehicle'");
    $connection->close();
    echo true;
}