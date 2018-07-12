<?php
include('app/app.php');
require_once 'rest/EBSCOAPI.php';      
        
        $an = $_REQUEST['an'];
        $db = $_REQUEST['db'];
        
        $api = new EBSCOAPI();
        $record = $api->apiExport($an, $db);
        $filename = $_REQUEST['an'].'_'.$_REQUEST['db'].'.ris';
        header('Content-Type: application/x-research-info-systems');
        header('Content-Disposition: inline; filename="'.$filename.'"');
        echo (string)$record;
        
        
        
?>
