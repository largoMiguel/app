<?php
include("../data-base.php");

$database = new Database('user-desktop');
$connection = $database->getConnection();
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $database = new Database("user-app");
        $connection = $database->getConnection();
        $response = mysqli_query($connection, "SELECT date, hour, id_vehicle, username, state FROM reports ");
        $rows = array();
        while ($r = $response->fetch_array(MYSQLI_NUM)) {
            $rows[] = $r;
        }
        $connection->close();
        echo json_encode($rows);
        break;

    case 'POST':
        $id_vehicle = $_POST['username'];
        $username = $_POST['id_vehicle'];
        $date = $_POST['date'];
        $input_hour = $_POST['input_hour'];
        $state = $_POST['state'];
        $database = new Database("user-app");
        $connection = $database->getConnection();
        $response = mysqli_query($connection, "INSERT INTO reports (username, id_vehicle, date, hour, state) VALUES ('$id_vehicle', '$username', '$date', '$input_hour', $state); ");
        $connection->close();
        echo json_encode(true);
        break;
}

