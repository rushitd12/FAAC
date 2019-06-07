<?php
session_start();
include "admin/database.php";

$action = $_GET['action'];

switch($action)
{
    case "login":
        login();
    break;
    case "getData":
        getData();
    break;
    default:
        header("Location: index.html");
    break;
}

function getData()
{
    $response = array();
	$response["data"] = array();
    $sql = "SELECT * FROM `feedback`";
    $con = connect();
    $result = mysqli_query($con, $sql);
    while($data = mysqli_fetch_assoc($result))
    {
        extract($data);
        $feedback = array();
        $feedback['feedback'] = $text;
        if($sentiment = "P")
            $feedback['sentiment'] = "Positive";
        else
            $feedback['sentiment'] = "Negative";
        if($done = "D")
            $feedback['done'] = "Done";
        else
            $feedback['done'] = "Remaining";
        array_push($response['data'], $feedback);
    }
    echo json_encode($response);
}

function login()
{
    $con = connect();
    $email = $_GET['email'];
    $pass = $_GET['pass'];
    $sql = "SELECT * FROM `users` WHERE email='$email' AND pass='$pass'";
    $result = mysqli_query($con, $sql);
    if(mysqli_num_rows($result) == 1)
    {
        echo "1";
    }
    else
    {
        echo "0";
    }
}



?>