<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "baigiamasis";
$con = new mysqli($servername, $username, $password, $dbname);
if ($con->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql="SELECT user_name,password_hash FROM users WHERE user_name='".$_POST['user_name']."'";
$user_data=mysqli_query($con, $sql);
if($user_data){
    $user_data=mysqli_fetch_all($user_data,MYSQLI_ASSOC);
    if(password_verify($_POST['password'],$user_data[0]['password_hash']) && $_POST['user_name']==$user_data[0]['user_name']) {
        $sql="SELECT user_id,user_name,share_list FROM users WHERE user_name='".$_POST['user_name']."'";
        $user_data=mysqli_query($con, $sql);
        $user_data=mysqli_fetch_all($user_data,MYSQLI_ASSOC);
        $_SESSION['user_data']=$user_data[0];
        echo $_SESSION['user_data']['user_name'];
        header('Location: index.php');
        die();
    } 
    else{
        $_SESSION['valid']=0;
        $_SESSION['invalid_text']="Slaptažodis neteisingas";
        echo $_SESSION['invalid_text'];
        header('Location: login.php');
        die();
    } 
}else{
    $_SESSION['valid']=0;
    $_SESSION['invalid_text']="Varotojo vardas neteisingas";
    echo $_SESSION['invalid_text'];
    header('Location: login.php');
    die();
}

$con->close();
?>