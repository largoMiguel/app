<?php
include("../data-base.php");
$user = new VehicleDatabase();
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $username = $_GET['u'];
        echo json_encode($user->select($username));
        break;
    case 'POST':
        $value = 'delete';
        $id_vehicle = $_POST['id_vehicle'];
        $username = $_POST['username'];
        $type_vehicle = $_POST['type_vehicle'];
        if ($type_vehicle == $value) {
            echo json_encode($user->delete($id_vehicle, $username));
        } else {
            echo json_encode($user->insert($id_vehicle, $username, $type_vehicle));
            break;
        }

        class VehicleDatabase
        {
            public function select($username)
            {
                $database = new Database("user-app");
                $connection = $database->getConnection();
                $response = mysqli_query($connection, "SELECT id_vehicle FROM vehicle, hour_input WHERE id_vehicle = vehicle_id_vehicle AND users_username = '$username'");
                $rows = array();
                while ($r = $response->fetch_array(MYSQLI_NUM)) {
                    $rows[] = $r;
                }
                $connection->close();
                return $rows;
            }

            public function insert($id_vehicle, $username, $type_vehicle)
            {
                $database = new Database("user-app");
                $connection = $database->getConnection();
                $response = mysqli_query($connection, "SELECT id_vehicle FROM vehicle WHERE id_vehicle = '$id_vehicle'");
                $rows = array();
                while ($r = $response->fetch_array(MYSQLI_NUM)) {
                    $rows[] = $r;
                }
                if ($rows) {
                    $response = mysqli_query($connection, "SELECT users_username FROM hour_input WHERE users_username = '$username' AND vehicle_id_vehicle = '$id_vehicle'");
                    $rows = array();
                    while ($r = $response->fetch_array(MYSQLI_NUM)) {
                        $rows[] = $r;
                    }
                    if ($rows) {
                        return true;
                    } else {
                        $response = mysqli_query($connection, "INSERT INTO hour_input (vehicle_id_vehicle, users_username, state_vehicle, input_hour) VALUES ('$id_vehicle', '$username', 'inactive', '00:00'); ");
                    }
                } else {
                    mysqli_query($connection, "INSERT INTO vehicle (id_vehicle, type_vehicle, ubicacion_idUbicacion) VALUES ('$id_vehicle', '$type_vehicle', '0')");
                    $response = mysqli_query($connection, "INSERT INTO hour_input (vehicle_id_vehicle, users_username, input_hour) VALUES ('$id_vehicle', '$username', '00:00'); ");
                }
                $connection->close();
                return $response;
            }

            public function delete($id_vehicle, $username)
            {
                $database = new Database("user-app");
                $connection = $database->getConnection();
                mysqli_query($connection, "DELETE FROM hour_input WHERE vehicle_id_vehicle = '$id_vehicle' AND users_username = '$username';");
                $response = mysqli_query($connection, "DELETE FROM vehicle WHERE id_vehicle = '$id_vehicle';");
                $connection->close();
                return $response;
            }
        }
}