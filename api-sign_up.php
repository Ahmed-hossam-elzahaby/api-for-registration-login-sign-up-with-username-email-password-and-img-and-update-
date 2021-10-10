<?php
$con = mysqli_connect("localhost", "root", "", "exam-api");
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userName = $_POST["userName"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $img = $_FILES["img"];
    $imgname = $img["name"];
    $imgtype = $img["type"];
    $imgtypename = $img["tmp_name"];
    $imgerror = $img["error"];
    $imgsize = $img["size"];
    $ext = pathinfo($imgname, PATHINFO_EXTENSION);
    $imgsizemb = $imgsize / (1024 ** 2);
    $errors = [];

    $errors = [];
    if ($imgerror > 1) {
        $errors[] = "error while uploding";
    } elseif (!in_array($ext, ["png", "jpg", "gif"])) {
        $errors[] = "must be img";
    } elseif ($imgsizemb > 1) {
        $errors[] = "muste be 1 mb";
    }
    if (empty($userName)) {
        $errors[] = "user name is required";
    } elseif (is_numeric($userName)) {
        $errors[] = "name must be string";
    } elseif (strlen($userName) > 10) {
        $errors[] = "max len 10";
    }
    if (empty($email)) {
        $errors[] = "email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $errors[] = "MUSTE BE EMAIL";
    }
    if (empty($password)) {

        $errors[] = "password is required";
    } elseif (!is_numeric($password)) {
        $errors[] = "age muste be number";
    }
    if (empty($confirmPassword)) {

        $errors[] = "confirm password is required";
    } elseif (!is_numeric($password)) {
        $errors[] = "age muste be number";
    }
    if ($password != $confirmPassword) {
        $errors[] = "check your pass and confirm pass";
    }

    if (empty($errors)) {

        $quer_ex = "SELECT * FROM `users` where `email`='$email'";
        $runquery_ex = mysqli_query($con, $quer_ex);
        if (!mysqli_num_rows($runquery_ex) > 0) {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);


            $randstr = uniqid();
            $IMGNEWNAME = "$randstr.$ext";
            move_uploaded_file($imgtypename, "uplode/$IMGNEWNAME");

            $query = "INSERT INTO `users` (`user_name`,`email`,`password`,`img`) VALUES ('$userName','$email','$password_hash','$IMGNEWNAME')";
            $runquery = mysqli_query($con, $query);




            echo json_encode(["msg" => "add  sussec"]);
        } else {

            echo json_encode(["msg" => "this email all ready exsiste"]);
        }
    } else {


        foreach ($errors as $value) {

            echo $value;
        }
    }
} else {
    http_response_code(404);
}
