<?php
$con = mysqli_connect("localhost", "root", "", "exam-api");

if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    $uri = $_SERVER["REQUEST_URI"];
    $uriArray = explode("/", $uri);
    $id = end($uriArray);
    // $newUserName = $_POST["newUser"];
    // $newEmail = $_POST["newEmail"];
    $data=json_decode(file_get_contents("php://input"));

    $newUserName=$data->user_name;
        $newEmail=$data->email;
    $errors = [];

    if (strlen($newUserName) > 10) {
        $errors[] = "max len 10";
    } elseif (is_numeric($newUserName)) {
        $errors[] = "name must be string";
    } elseif (empty($newUserName)) {
        $errors[] = "new user is required";
    }

    if (empty($newEmail)) {

        $errors[] = "email is required";
    }

    elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {

        $errors[] = "MUSTE BE EMAIL";
    } 



    if (empty($errors)) {

        $query = "SELECT * FROM `users` WHERE id=$id";
        $runquery = mysqli_query($con, $query);
        if (mysqli_num_rows($runquery) > 0) 
        {

            $query_email = "SELECT * FROM `users` where email='$newEmail' ";
            $runquery_email = mysqli_query($con, $query_email);
            if (!mysqli_num_rows($runquery_email) > 0) 
            {
                $query_update = "UPDATE `users` SET `user_name`='$newUserName', `email`='$newEmail' Where id=$id ";
                $runquery_uddate = mysqli_query($con, $query_update);
                echo json_encode(["msg" => "update succes"]);
            } else {
                $query_update = "UPDATE `users` SET `user_name`='$newUserName',  Where id=$id ";
                $runquery_uddate = mysqli_query($con, $query_update);
                echo json_encode(["msg" => "update succes"]);
            }
        } else {

            echo json_encode(["msg" => "not found"]);
        }
    }else{

foreach($errors as $value)
{

    echo json_encode(["msg"=>"$value"]);
}

    }
} else {
    http_response_code(404);
}
