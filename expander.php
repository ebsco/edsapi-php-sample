<?php
session_start();
include "rest/EBSCOAPI.php";

$api =  new EBSCOAPI();
$Info = $api->getInfo();
$results = $_SESSION['results'];
$queryStringUrl = $results['queryString'];

$addExpanderActions = array();
$removeExpanderAction = array();
/*
 * Check which expander check boxes are checked, which are not checked
 * if is checked add the action to addExpanderActions
 * if is not checked, add remove action to removeExpanderActions when the expander is found in applied expanders
 * or do nothing when not found in applied expanders.
 */
$i=1;
foreach($Info['expanders'] as $expander){
    if(isset($_REQUEST[$expander['Id']])){
        $addExpanderActions['action['.$i.']'] = $expander['Action'];
        $i++;
    }else{
        foreach($results['appliedExpanders'] as $filter){
            if($filter['Id']==$expander['Id']){
                $removeExpanderAction['action['.$i.']'] = $filter['removeAction'];
                $i++;
            }
        }
    }
}

$searchTerm = $_REQUEST['query'];
$fieldCode = $_REQUEST['fieldcode'];
$params = array(
    'refine'=>'y',
    'query' => $searchTerm,
    'fieldcode'=>$fieldCode,
);
$params = array_merge($params,$addExpanderActions);
$params = array_merge($params,$removeExpanderAction);
$url = 'results.php?'.http_build_query($params).'&'.$queryStringUrl;

header("location: {$url}");    
?>
