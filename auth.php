<?php
include('app/app.php');
include('rest/EBSCOAPI.php');

$query = isset($_REQUEST['query'])?$_REQUEST['query']:'';
$fieldCode = isset($_REQUEST['fieldcode'])?$_REQUEST['fieldcode']:'';
$db = isset($_REQUEST['db'])?$_REQUEST['db']:'';
$an = isset($_REQUEST['an'])?$_REQUEST['an']:'';
$highlight = isset($_REQUEST['highlight'])?$_REQUEST['highlight']:'';
$resultId = isset($_REQUEST['resultId'])?$_REQUEST['resultId']:'';
$recordCount = isset($_REQUEST['recordCount'])?$_REQUEST['recordCount']:'';

$userid = $_REQUEST['userId'];
$password = $_REQUEST['password'];
$flag = FALSE; // set flag to identify the login status
$profile = "";
$UserId = '';

$xml ="Config.xml";
      $dom = new DOMDocument();
      $dom->load($xml);      
      $clientCredentials = $dom ->getElementsByTagName('ClientCredentials')->item(0);
      $users = $clientCredentials ->getElementsByTagName('User');
      foreach($users as $user){
       $UserId = $user -> getElementsByTagName('UserId')->item(0)->nodeValue;
       $Password = $user -> getElementsByTagName('Password') -> item(0) -> nodeValue;       
       
       if($userid == $UserId && $password == $Password){          
           $flag = $UserId;
           break;       
       }
      }
   
     // Log in fail will redrect user to login page with the fail parameter as y
     // all the functions below are used to track the page which the user visited
     // and after successfully login will return the that page with loged in status
     if($flag==FALSE){
         
         $path = $_REQUEST['path'];
    
    if($path=="record"){

    $params = array(
        'path'=>'record',
        'db'=>$db,
        'an'=>$an,
        'highlight'=>$highlight,
        'query'=>$query,
        'fieldcode'=>$fieldCode,
        'resultId'=>$resultId,
        'recordCount'=>$recordCount,
        'fail'=>'y'
    );
    $params = http_build_query($params);
    header("location: login.php?$params");
    }
    
    else if($path=="PDF"){
    $params = array(
        'path'=>'PDF',
        'db'=>$db,
        'an'=>$an,
        'fail'=>'y'
    );
    $params = http_build_query($params);
    header("location: login.php?$params");
    }
    
    else if($path=="HTML"){

    $params = array(
        'path'=>'HTML',
        'db'=>$db,
        'an'=>$an,
        'highlight'=>$highlight,
        'query'=>$query,
        'fieldcode'=>$fieldCode,
        'resultId'=>$resultId,
        'recordCount'=>$recordCount,
        'fail'=>'y'
    );
    $params = http_build_query($params);
    header("location: login.php?$params");
    }
    
    else if($_REQUEST['path']=="results"){
        
    $params = array(
       'path'=>'results',
       'query'=>$query,
       'fieldcode'=>$fieldCode,
       'fail'=>'y'
    );
    $params = http_build_query($params);
   header("location: login.php?".$params); 
   }
   else{
       header("location: login.php?path=index&fail=y");
   }
 
     }else{
         
            $EDSCredentials = $dom ->getElementsByTagName('EDSCredentials')->item(0);
            $users = $EDSCredentials -> getElementsByTagName('User');
            
            foreach($users as $user){
               $userType = $user->getElementsByTagName('ClientUser')->item(0)->nodeValue;
                if($userType == $UserId){                              
                $profile = $user -> getElementsByTagName('EDSProfile')->item(0)->nodeValue;
                break;
                }
            }
                
        $api = new EBSCOAPI();
        $newSessionToken = $api->apiSessionToken($api->getAuthToken(), $profile,'n');          
        $_SESSION['sessionToken']=$newSessionToken;
        
        setcookie('login',$profile,0);       
       
       if(isset($_COOKIE['Guest'])){
           setcookie('Guest','',time()-3600); 
       }    
       
       $path = $_REQUEST['path'];
    
    if($path=="record"){
    
    $params = array(
        'db'=>$db,
        'an'=>$an,
        'highlight'=>$highlight,
        'query'=>$query,
        'fieldcode'=>$fieldCode,
        'resultId'=>$resultId,
        'recordCount'=>$recordCount,
    );
    $params = http_build_query($params);
    header("location: $path.php?$params");
    }
    
    else if($path=="PDF"){
    $params = array(
        'db'=>$db,
        'an'=>$an
    );
    $params = http_build_query($params);
    header("location: $path.php?$params");
    }
    
    else if($path=="HTML"){

    $params = array(
        'db'=>$db,
        'an'=>$an,
        'highlight'=>$highlight,
        'query'=>$query,
        'fieldcode'=>$fieldCode,
        'resultId'=>$resultId,
        'recordCount'=>$recordCount,
    );
    $params = http_build_query($params);
    header("location: record.php?$params#html");
    }
    
    else if($_REQUEST['path']=="results"){
        $params = array(
            'login'=>'y',
            'query'=>$query,
            'fieldcode'=>$fieldCode
        );
        $params = http_build_query($params);
    $queryStringUrl =$_SESSION['results']['queryString'];

   header("location: results.php?$params&$queryStringUrl");
   }
   else{
       header("location: index.php");
   }
       
   }
  
?>
