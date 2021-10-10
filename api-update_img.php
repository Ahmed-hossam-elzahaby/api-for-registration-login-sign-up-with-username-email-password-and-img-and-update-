<?php
$con = mysqli_connect("localhost", "root", "", "exam-api");
if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $uri = $_SERVER["REQUEST_URI"];
    $uriArray = explode("/", $uri);
    $id = end($uriArray);
    $img = $_FILES["img"];
    $imgname = $img["name"];
    $imgtype = $img["type"];
    $imgtypename = $img["tmp_name"];
    $imgerror = $img["error"];
    $imgsize = $img["size"];
    $ext = pathinfo($imgname, PATHINFO_EXTENSION);
    $imgsizemb = $imgsize / (1024 ** 2);
    $errors=[];
    if ($imgerror > 0) {

        $eroors[] = "error while uploding or empty  ";
    } elseif (!in_array($ext, ["png", "jpg", "gif"])) {
        $eroors[] = "must be img";
    } elseif ($imgsizemb > 1) {
        $eroors[] = "muste be 1 mb";
    }
    if (empty($eroors)) {
         
            

            $randstr = uniqid();
            $IMGNEWNAME = "$randstr.$ext";
            move_uploaded_file($imgtypename, "uplode/$IMGNEWNAME");
            $query = "UPDATE `users` SET `img`='$IMGNEWNAME' Where id=$id ";
            $runquery = mysqli_query($con, $query);
        }else
        {
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