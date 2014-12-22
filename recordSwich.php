<?php
session_start();
include('rest/EBSCOAPI.php');

$results = $_SESSION['results'];
$queryStringUrl = $results['queryString'];

$api = new EBSCOAPI();

$resultId = $_REQUEST['resultId'];
$query = $_REQUEST['query'];
$fieldCode = $_REQUEST['fieldcode'];
$start = isset($_REQUEST['pagenumber']) ? $_REQUEST['pagenumber'] : 1;
$limit = isset($_REQUEST['resultsperpage'])?$_REQUEST['resultsperpage']:20;


if($resultId>$start*$limit){
    $start = $start+1;
    $url = $queryStringUrl."&pagenumber=$start";
    $results = $api->apiSearch($url);   
    $_SESSION['results'] = $results;
   
} else if($resultId<(($start-1)*$limit)+1){
    $start = $start-1;
    $url = $queryStringUrl."&pagenumber=$start";
    $results = $api->apiSearch($url);   
    $_SESSION['results'] = $results;
   
} else if(isset($_SESSION['results'])){
    
    $results = $_SESSION['results'];
    
} else {
    $results = $api->apiSearch($queryStringUrl);    
    $_SESSION['results'] = $results;
   
}


$recordCount = $results['recordCount'];

foreach($results['records'] as $record){
    if($record['ResultId']==$resultId){
        $db = $record['DbId'];
        $an = $record['An'];
        $rId = $record['ResultId'];
        $params = array(
            'db'=>$db,
            'an'=>$an,
            'highlight'=>$query,
            'resultId'=>$rId,
            'recordCount'=>$recordCount,
            'query'=>$query,
            'fieldcode'=>$fieldCode    
        );
        $params = http_build_query($params);
        header("location:record.php?".$params);
        break;
    }
}


?>

