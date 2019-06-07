<?php
session_start();
include "admin/database.php";

$action = $_GET['action'];

switch($action)
{
    case "login":
        login();
    break;
    case "logout":
        logout();
    break;
    case "done":
        markDone();
    break;
    default:
        header("Location: index.html");
    break;
}

function markDone()
{
    $con = connect();
    $filter = $_GET['filter'];
    $fid = $_GET['fid'];
    $sql = "UPDATE `feedback` 
                SET done='D'
                WHERE fid='$fid'";
    mysqli_query($con, $sql);
    header("Location: home.php?filter=$filter");
}

function login()
{
    $con = connect();
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $sql = "SELECT * FROM `users` WHERE email='$email' AND pass='$pass'";
    $result = mysqli_query($con, $sql);
    if(mysqli_num_rows($result) == 1)
    {
        $data = mysqli_fetch_assoc($result);
        extract($data);
        $_SESSION['email'] = $email;
		$_SESSION['department'] = $department;
        header("Location: home.php?filter=all");
    }
    else
    {
        header("Location: index.html");
    }
}

function logout()
{
    unset($_SESSION['email']);
    header("Location: index.html");
}

?>