<?php
session_start();
include "rest/EBSCOAPI.php";

$api =  new EBSCOAPI();
$Info = $api->getInfo();
$results = $_SESSION['results'];
$queryStringUrl = $results['queryString'];

$addLimiterActions=array();
$removeLimiterActions=array();

/*
 * Check which expander check boxes are checked, which are not checked
 * if is checked add the action to addExpanderActions
 * if is not checked, add remove action to removeExpanderActions when the expander is found in applied expanders
 * or do nothing when not found in applied expanders.
 */
$i = 1;
foreach($Info['limiters'] as $limiter){
    if($limiter['Id'] != 'DT1'){
        if(isset($_REQUEST[$limiter['Id']])){
            $addLimiterActions['action['.$i.']'] = str_replace('value', 'y',$limiter['Action']);
            $i++;
        }else{
            foreach($results['appliedLimiters'] as $filter){
                if($filter['Id']==$limiter['Id']){
                    $removeLimiterActions['action['.$i.']'] = str_replace('value', 'y',$filter['removeAction']);
                    $i++;
                }
            }
        }
    }
    else{
        $addLimiterActions['action['.$i.']'] = $_REQUEST['DT1'];
        $i++;
    }
    
}
$searchTerm = $_REQUEST['query'];
$fieldCode = $_REQUEST['fieldcode'];
$params = array(
    'refine'=>'y',
    'query' => $searchTerm,
    'fieldcode'=>$fieldCode,
);
$params = array_merge($params,$addLimiterActions);
$params = array_merge($params,$removeLimiterActions);
$url = 'results.php?'.http_build_query($params).'&'.$queryStringUrl;

header("location: {$url}");    
?>
