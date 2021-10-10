<?php
$con = mysqli_connect("localhost", "root", "", "exam-api");
if($_SERVER["REQUEST_METHOD"]=="PUT")
{
    $uri = $_SERVER["REQUEST_URI"];
    $uriArray = explode("/", $uri);
    $id = end($uriArray);
    $data=json_decode(file_get_contents("php://input"));
$newPassword=$data->newPassword;
$confirmNewPassword=$data->confirmNewPassword;
$eroors=[];

if(empty($newPassword))
{
    $eroors[]="new password is required";
}elseif(!is_numeric($newPassword))
{
    $eroors[]="new password must be number";
}
if(empty($confirmNewPassword))
{
    $eroors[]="confirm new password is required";
}elseif(!is_numeric($confirmNewPassword))
{
$eroors[]="confirm password must be number";

}
if($newPassword != $confirmNewPassword)
{
    $eroors[]="check your new pass and confirm new pass ";
}
if(empty($eroors))
{

    $newPassword_hash = password_hash($confirmNewPassword, PASSWORD_BCRYPT);
    $query="UPDATE `users` set `password`='$newPassword_hash' where id=$id";
$runquery=mysqli_query($con,$query);
echo json_encode(["msg"=>"update sucess"]);




}else{

foreach($eroors as $value)
{

echo json_encode(["msg"=>"$value"]);

}
}



}else
{

    http_response_code(404);
}





?>