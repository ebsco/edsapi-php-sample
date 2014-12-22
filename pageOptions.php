<?php
session_start();

$results = $_SESSION['results'];
$queryStringUrl = $results['queryString'];

$sort = isset($_REQUEST['sort'])? array('action'=>$_REQUEST['sort']):array();
$resultsperpage = isset($_REQUEST['resultsperpage'])? array('action'=>$_REQUEST['resultsperpage']):array();
$pagenumber = isset($_REQUEST['pagenumber'])? array('action'=>$_REQUEST['pagenumber']):array();
$view = isset($_REQUEST['view'])? array('view'=>$_REQUEST['view']):array();

$searchTerm = $_REQUEST['query'];
$fieldCode = $_REQUEST['fieldcode'];
$params = array(
    'query'=>$searchTerm,
    'fieldcode'=>$fieldCode,
    'option'=>'y'
);
$params = array_merge($params,$sort);
$params = array_merge($params,$resultsperpage);
$params = array_merge($params,$pagenumber);
$params = array_merge($params,$view);
$params = http_build_query($params);
$url = "results.php?".$queryStringUrl.'&'.$params;

header("location: {$url}");    
?>
