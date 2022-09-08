<?php
include("../data-base.php");
$database = new Database('user-app');

$id_vehicle = $_GET['v'];
$username = $_GET['u'];
$state = $_GET['s'];

$connection = $database->getConnection();

switch ($state) {
    case 'active':
        active($connection, $username, $id_vehicle);
        break;
    case 'inactive':
        inactive($connection, $id_vehicle);
        break;
}

function active($connection, $username, $id_vehicle)
{
    $response = mysqli_query($connection, "select v.state_vehicle, v.username from vehicle v
                                                where v.id_vehicle = '$id_vehicle';");
    $row = $response->fetch_array(MYSQLI_NUM);


    if ($row[0] == 'active') {
        if ($row[1] != $username) {
            echo 'invalid';
            $connection->close();
            return;
        } else {
            $response = mysqli_query($connection, "select vu.input_hour from vehicle_has_users vu
                                                where vu.vehicle_id_vehicle = '$id_vehicle'
                                                and vu.users_username = '$username';");
            $row = $response->fetch_array(MYSQLI_NUM);

            $p = getParking($connection, $id_vehicle);
            echo "$row[0],$p";
            $connection->close();
            return;
        }
    }
    isRead($connection, $id_vehicle, 'active');
    $p = getParking($connection, $id_vehicle);
    echo "active-$p";
}

function inactive($connection, $id_vehicle)
{
    isRead($connection, $id_vehicle, 'inactive');
    echo 'inactive';
}

function isRead($connection, $id_vehicle, $state)
{
    do {
        $response = mysqli_query($connection, "select v.state_vehicle from vehicle v where v.id_vehicle = '$id_vehicle'");
        $row = $response->fetch_array(MYSQLI_NUM);
    } while ($row[0] != $state);
}

function getParking($connection, $id_vehicle)
{
    $response = mysqli_query($connection, "select v.ubicacion_idUbicacion from vehicle v
                                                where v.id_vehicle = '$id_vehicle';");
    $row = $response->fetch_array(MYSQLI_NUM);
    $connection->close();
    return $row[0];
}