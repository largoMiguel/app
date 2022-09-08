<?php
include("../data-base.php");
$database = new Database("user-app");
$connection = $database->getConnection();
$response = mysqli_query($connection, "SELECT t.nameTarifa, t.priceTarifa
                                                FROM easypark.tarifa t;");
$rows = array();
while ($r = $response->fetch_array(MYSQLI_NUM)) {
    $rows[] = $r;
}
$connection->close();
echo json_encode($rows);
