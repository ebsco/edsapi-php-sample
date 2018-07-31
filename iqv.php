<?php

include('app/app.php');
include('rest/EBSCOAPI.php');

$api = new EBSCOAPI();

$db = $_REQUEST['db'];
$an = $_REQUEST['an'];
$highlight = '';
$result = $api->apiRetrieve($an, $db, $highlight);

$debug = isset($_REQUEST['debug'])? $_REQUEST['debug']:'';

// Set error
if (isset($result['error'])) {
    $error = $result['error'];
} else {
    $error = null;
}

//save debug into session
if($debug == 'y'||$debug == 'n'){
    $_SESSION['debug'] = $debug;
}
// Variables used in view
$variables = array(
    'result' => $result,   
    'error'  => $error,
    'id'     => 'record',
    'debug'  => isset($_SESSION['debug'])? $_SESSION['debug']:''
);

render('iqv.html', 'layout.html', $variables);

?>