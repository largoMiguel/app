<?php
include("../data-base.php");
$user = new UserParking();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $username = $_POST['username'];
        $id_vehicle = $_POST['id_vehicle'];
        $id_location = $_POST['id_location'];
        $state = $_POST['state'];
        $input_hour = $_POST['input_hour'];

        echo json_encode($user->start_stop_parking($username, $id_vehicle, $id_location, $state, $input_hour));
        break;
    case 'GET':
        break;
}

class UserParking
{
    public function start_stop_parking($username, $id_vehicle, $id_location, $state, $input_hour)
    {
        $database = new Database('user-desktop');
        $connection = $database->getConnection();

        if ($state == 'active') {
            $response = mysqli_query($connection, "SELECT v.ubicacion_idUbicacion FROM easypark.vehicle v
                                                    WHERE v.id_vehicle = '$id_vehicle'");
            $row = $response->fetch_array(MYSQLI_NUM);
            if ($row[0] != "0") {
                return $state;
            }
            mysqli_query($connection, "UPDATE easypark.vehicle v, easypark.vehicle_has_users vu
                                                    SET vu.input_hour = '$input_hour'
                                                    WHERE vu.vehicle_id_vehicle = '$id_vehicle'
                                                    AND vu.users_username = '$username';");

            $response = mysqli_query($connection, "UPDATE easypark.vehicle v
                                                    SET v.ubicacion_idUbicacion = '$id_location',
                                                        v.state_vehicle        = '$state',
                                                        v.username = '$username'
                                                    WHERE v.id_vehicle = '$id_vehicle'");
            $connection->close();
            return $response;
        } else {
            mysqli_query($connection, "UPDATE easypark.vehicle v, easypark.vehicle_has_users vu
                                                    SET vu.input_hour = '$input_hour'
                                                    WHERE vu.vehicle_id_vehicle = '$id_vehicle'
                                                    AND vu.users_username = '$username';");

            $response = mysqli_query($connection, "UPDATE easypark.vehicle v
                                                    SET v.ubicacion_idUbicacion = '$id_location',
                                                        v.state_vehicle        = '$state',
                                                        v.username = '$username'
                                                    WHERE v.id_vehicle = '$id_vehicle'");
            $connection->close();
            return $response;
        }
    }
}

