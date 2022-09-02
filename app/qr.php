<?php
include("../data-base.php");
$database = new Database('user-app');
$id_vehicle = $_GET['v'];
$state = $_GET['s'];
$username = $_GET['u'];
$connection = $database->getConnection();

$response = mysqli_query($connection, "select id_vehicle from vehicle
                                                where username = '$username';");
$row = $response->fetch_array(MYSQLI_NUM);


if ($state == 'active') {
    if ($row != null) {
        if ($row[0] != $id_vehicle) {
            echo json_encode(1);
            return;
        }
    }

    $response = mysqli_query($connection, "SELECT v.ubicacion_idUbicacion FROM easypark.vehicle v
                                                    WHERE v.id_vehicle = '$id_vehicle'");
    $row = $response->fetch_array(MYSQLI_NUM);

    if ($row[0] != "0") {
        $response = mysqli_query($connection, "SELECT v.username FROM easypark.vehicle v
                                                    WHERE v.id_vehicle = '$id_vehicle'");
        $row = $response->fetch_array(MYSQLI_NUM);
        if ($row[0] == $username) {
            $response = mysqli_query($connection, "SELECT vu.input_hour FROM easypark.vehicle_has_users vu
                                                    WHERE vu.users_username = '$username'
                                                    AND vu.vehicle_id_vehicle = '$id_vehicle'");
            $row = $response->fetch_array(MYSQLI_NUM);
            echo json_encode($row[0]);
        } else {
            echo json_encode(false);
        }
        return;
    } else {
        isRead($connection, $id_vehicle, $state);
    }


} else {
    isRead($connection, $id_vehicle, $state);
}


function isRead($connection, $id_vehicle, $state)
{
    do {
        $response = mysqli_query($connection, "select state_vehicle from vehicle where id_vehicle = '$id_vehicle'");
        $row = $response->fetch_array(MYSQLI_NUM);
    } while ($row[0] != $state);
    echo json_encode(true);
    $connection->close();
}



