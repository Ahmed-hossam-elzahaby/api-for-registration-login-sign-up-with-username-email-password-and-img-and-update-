<?php
$con = mysqli_connect("localhost", "root", "", "exam-api");
if($_SERVER["REQUEST_METHOD"]=="POST")
{

$email=$_POST["email"];
$password=$_POST["password"];
$errors=[];
if(empty($email))
{
$errors[]="email is required";

}
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
 {

    $errors[] = "Must be email";
}
if(empty($password))
{

    $errors[]="password is required";
}
elseif (!is_numeric($password))
 {
    $errors[] = "password muste be number";
}

if(empty($errors))
{
$query="SELECT * FROM `users` WHERE email='$email' ";
$runquery=mysqli_query($con,$query);
if(mysqli_num_rows($runquery)>0)
{

$user=mysqli_fetch_assoc($runquery);
$iscorrect=password_verify($password,$user["password"]);
if($iscorrect)
{
    // print_r($user);

echo json_encode(["msg"=>"login succes",'token'=>uniqid(),'id'=>$user['id']]);

}else
{

echo json_encode(["msg"=>"wrong password"]);

}

}else
{

echo json_encode(["msg"=>"wrong email"]);

}

}else
{

foreach($errors as $value)
{
echo json_encode(["msg"=>"$value"]);
}
}
}
else
{

    http_response_code(404);
}








?>