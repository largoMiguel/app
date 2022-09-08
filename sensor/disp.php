<?php
include("../data-base.php");
$database = new Database('user-app');
$connection = $database->getConnection();

$response = mysqli_query($connection, "SELECT COUNT(u.state)
                                            FROM easypark.ubicacion u
                                            WHERE u.state = 'active';");
$row = $response->fetch_array(MYSQLI_NUM);
$connection->close();
echo json_encode($row);