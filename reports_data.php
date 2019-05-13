<?php
session_start();
/*$servername = "localhost";
$username = "1159098";
$password = "xm8au1tab";
$dbname = "1159098";
*/
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "geopostit"; 
$con = new mysqli($servername, $username, $password, $dbname);
if ($con->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$reports_data=[];
$new_data=[];
$urlPost=0;
if(isset($_POST)&&count($_POST)>0){ 
    if(isset($_POST['report_id'])){
        $urlPost=1;
        $sql="SELECT report_id,user_id,share_option,latitude,longitude,title,info,image FROM reports WHERE latitude<=".$_POST['NElat']." AND latitude >=".$_POST['SWlat']." AND longitude <=".$_POST['NElng']." AND longitude >=".$_POST['SWlng']." OR report_id=".$_POST['report_id']; 
    }else if(isset($_POST['category'])||isset($_POST['search_query'])){
        $urlPost=2;
        $sql="SELECT report_id,user_id,share_option,latitude,longitude,title,info,image FROM reports WHERE latitude<=".$_POST['NElat']." AND latitude >=".$_POST['SWlat']." AND longitude <=".$_POST['NElng']." AND longitude >=".$_POST['SWlng'];
        if(isset($_POST['category'])&&$_POST['category']!='all')$sql.=" AND category='".$_POST['category']."'";
        if(isset($_POST['search_query'])) $sql.=" AND (UPPER(info) LIKE UPPER('%".$_POST['search_query']."%') OR UPPER(title) LIKE UPPER('%".$_POST['search_query']."%'))";
    }else{
        $sql="SELECT report_id,user_id,share_option,latitude,longitude,title,info,image FROM reports WHERE latitude<=".$_POST['NElat']." AND latitude >=".$_POST['SWlat']." AND longitude <=".$_POST['NElng']." AND longitude >=".$_POST['SWlng']; 
    }
    
    $con->query("SET NAMES 'utf8'");
    $reports_data=mysqli_query($con, $sql);
    $reports_data=mysqli_fetch_all($reports_data,MYSQLI_ASSOC);
    foreach($reports_data as $key => $row){
        if(isset($_SESSION['user_data']['user_id'])){
            if($_SESSION['user_data']['user_id']==$row['user_id']||$row['share_option']==1){
                $new_data[$key]['latitude']=$row['latitude'];
                $new_data[$key]['longitude']=$row['longitude'];
                $new_data[$key]['user_id']=$row['user_id'];
                $new_data[$key]['title']=$row['title'];
                $new_data[$key]['report_id']=$row['report_id'];
                $new_data[$key]['content']='
                <div class="row" style="margin:10px;">
                <h1 class="col-12">'.$row['title'].'</h1></div>
                <div class="row" style="margin:10px;"><p class="col-12">'.$row['info'].'</p></div>';

                if($row['image']!=null) $new_data[$key]['content'].='<div class="row" style="margin:10px;"><img class="col-12" src="/report_images/'.$row['image'].'" style="width:300px;height:auto;"></img></div>';
                $new_data[$key]['content'].='<div class="row" style="margin:10px;"><div class="col-12">
                <button class="btn fa fa-copy" onClick="copyURLToClipBoard()"></button>
                <button type="button" class="btn btn-default" onClick="navigate(\'https://www.google.com/maps/search/?api=1&query='.$row['latitude'].','.$row['longitude'].'\')">Navigate</button></div></div>';
                if(isset($_SESSION['user_data']['user_id'])){
                    if($_SESSION['user_data']['user_id']==$row['user_id']){
                        $new_data[$key]['content'].='
                        <div class="row" style="margin:10px;"><div class="col-12"><form>
                        <button class="btn btn-success btn-edit-post" value="'.$row['report_id'].'">Koreguoti</button>
                        <button class="btn btn-danger btn-edit-post" value="'.$row['report_id'].'">Trinti</button></form></div></div>';
                    }
                }      
            }
        }else{
            if($row['share_option']==1){
                $new_data[$key]['latitude']=$row['latitude'];
                $new_data[$key]['longitude']=$row['longitude'];
                $new_data[$key]['user_id']=$row['user_id'];
                $new_data[$key]['title']=$row['title'];
                $new_data[$key]['report_id']=$row['report_id'];
                $new_data[$key]['content']='
                <div class="row" style="margin:10px;">
                <h1 class="col-12">'.$row['title'].'</h1></div>
                <div class="row" style="margin:10px;"><p class="col-12">'.$row['info'].'</p></div>';
                if($row['image']!=null) $new_data[$key]['content'].='<div class="row" style="margin:10px;"><img class="col-12" src="/report_images/'.$row['image'].'" style="width:300px;height:auto;"></img></div>';
                $new_data[$key]['content'].='<div class="row" style="margin:10px;"><div class="col-12">
                <button class="btn fa fa-copy" onClick="copyURLToClipBoard()"></button>
                <button type="button" class="btn btn-default" onClick="navigate(\'https://www.google.com/maps/search/?api=1&query='.$row['latitude'].','.$row['longitude'].'\')">Navigate</button></div></div>';
            }
        }
    }
    if($urlPost==1&&$new_data==null){
        $new_data['status_code']=2;
        $new_data['status_message']='Post does not exist or is private';
    }
    if($urlPost==2&&$new_data==null){
        $new_data['status_code']=2;
        $new_data['status_message']='Posts does not exist or are private';
    }
}else{
    $new_data['status_code']=1;
    $new_data['status_message']='Error occured';
}
$con->close();
echo json_encode($new_data);
die();
?>