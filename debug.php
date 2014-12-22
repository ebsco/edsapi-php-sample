<?php
session_start();
//Set Content-Type to application/xml
header("Content-Type: application/xml");

if(isset($_REQUEST['result'])){
    echo $_SESSION['resultxml'];
}else if(isset($_REQUEST['record'])){
    echo $_SESSION['recordxml'];
}
?>
