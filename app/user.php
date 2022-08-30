<?php
include("../data-base.php");
$user = new UserDatabase();
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $username = $_GET['u'];
        $password = $_GET['p'];
        echo json_encode($user->select($username, $password));
        break;
    case 'POST':
        $username = $_POST['username'];
        $name = $_POST['name'];
        $lastname = $_POST['lastname'];
        $password = $_POST['password'];
        echo json_encode($user->insert($username, $name, $lastname, $password));
        break;
    case 'PUT':
        break;
}

class UserDatabase
{
    public function select($username, $password)
    {
        $database = new Database("user-app");
        $connection = $database->getConnection();
        $response = mysqli_query($connection, "SELECT * FROM users WHERE username = '$username' and password_user = '$password'");
        $row = $response->fetch_array(MYSQLI_NUM);
        $connection->close();
        return $row;
    }

    public function insert($username, $name, $lastname, $password)
    {
        try {
            $database = new Database("user-app");
            $connection = $database->getConnection();
            mysqli_query($connection, "INSERT INTO users (username, name_user, lastname_user, password_user) VALUES ($username, '$name', '$lastname','$password')");
            $connection->close();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

}