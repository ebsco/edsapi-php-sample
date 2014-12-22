<?php 
include('app/app.php');
include('rest/EBSCOAPI.php');

$fail = isset($_REQUEST['fail'])?$_REQUEST['fail']:'';

if($_REQUEST['path']=="record"){
$db = $_REQUEST['db'];
$an = $_REQUEST['an'];
$highlight = $_REQUEST['highlight'];
$query = $_REQUEST['query'];
$fieldCode = $_REQUEST['fieldcode'];

$varables = array(
    'path' => 'record',
    'db' => $db,
    'an' => $an,
    'highlight'=>$highlight,
    'query'=> $query,
    'fieldCode' => $fieldCode,
    'resultId' => $_REQUEST['resultId'],
    'recordCount' => $_REQUEST['recordCount']
);
}

else if($_REQUEST['path']=="PDF"){
    $db = $_REQUEST['db'];
$an = $_REQUEST['an'];

$varables = array(
    'path' => 'PDF',
    'db' => $db,
    'an' => $an
);
}

else if($_REQUEST['path']=="HTML"){
$db = $_REQUEST['db'];
$an = $_REQUEST['an'];
$highlight = $_REQUEST['highlight'];
$query = $_REQUEST['query'];
$fieldCode = $_REQUEST['fieldcode'];

$varables = array(
    'path' => 'HTML',
    'db' => $db,
    'an' => $an,
    'highlight'=>$highlight,
    'resultId' => $_REQUEST['resultId'],
    'recordCount' => $_REQUEST['recordCount'],
    'query' => $query,
    'fieldCode'=>$fieldCode
);
}

else if($_REQUEST['path']=="results"){
   $query = $_REQUEST['query'];
   $fieldCode = $_REQUEST['fieldcode'];
   
   $varables = array(
       'path' => 'results',
       'query' => $query,
       'fieldCode'=>$fieldCode
   );
}

else {
    $varables = array(
        'path' => 'index'
    );
}

$varables['fail'] = $fail;
render('login.html', 'layout.html',$varables);
?>

