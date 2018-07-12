<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Search</title>
        <link rel="stylesheet" href="web/styles.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="web/pubtype-icons.css" />
        <link rel="shortcut icon" href="web/favicon.ico" />
        <script type="text/javascript" src="web/placard.js" ></script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js" ></script>
        <?php
        // if autocomplete is desired call in jQueryUI.js & jQueryUI.css
        if(isset($_SESSION['autocomplete']) && $_SESSION['autocomplete'] == 'y'){
            echo '<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>';
            echo '<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">';
        }
        ?>     
    </head>

    <body>
        <div class="container">
        <div class="header">
            <div><a id="logo" href="index.php"></a></div>
            <?php if(!(isset($_SESSION['login'])||isset($login) || (validAuthIP("Config.xml")==true))){ ?>
            <div class="guestbox"><div>Hello, Guest. 
               <?php if(isset($_REQUEST['db'])&&isset($_REQUEST['an'])&&isset($_REQUEST['resultId'])){ 
                   $params = array(
                            'path'=>'record',
                            'db'=>$_REQUEST['db'],
                            'an'=>$_REQUEST['an'],
                            'highlight'=>$_REQUEST['highlight'],
                            'resultId'=>$_REQUEST['resultId'],
                            'recordCount'=>$_REQUEST['recordCount'],
                            'query' => $_REQUEST['query'],
                            'fieldcode'=>$_REQUEST['fieldcode']
                        );
                    $params = http_build_query($params);
                   ?>   
                   <a href="login.php?<?php echo $params; ?>">Login</a>         
               <?php }else if(isset($refineSearchUrl)){
                      $params = array(
                          'path'=>'results',
                          'query'=>$searchTerm,
                          'fieldcode'=>$fieldCode
                      );
                      $params = http_build_query($params);
                   ?>
                    <a href="login.php?<?php echo $params;?>">Login</a>    
                    <?php }else{ ?>
                    <a href="login.php?path=index">Login</a> 
                    <?php } ?>
                    for full access.</div></div>
             <?php } ?>
            <?php if(isset($_SESSION['login'])||isset($login)){ ?>                     
                    <div class="login"><a href="logout.php">Logout</a></div>                             
                   <?php } else { ?>
                    <?php if(isset($_REQUEST['db'])&&isset($_REQUEST['an'])&&isset($_REQUEST['resultId'])){
                        $params = array(
                            'path'=>'record',
                            'db'=>$_REQUEST['db'],
                            'an'=>$_REQUEST['an'],
                            'highlight'=>$_REQUEST['highlight'],
                            'resultId'=>$_REQUEST['resultId'],
                            'recordCount'=>$_REQUEST['recordCount'],
                            'query' => $_REQUEST['query'],
                            'fieldcode'=>$_REQUEST['fieldcode']
                        );
                        $params = http_build_query($params);
                        ?>
                    <div class="login"><a href="login.php?<?php echo $params?>">Login</a></div>     
                    <?php }else if(isset($refineSearchUrl)){ 
                        $params = array(
                          'path'=>'results',
                          'query'=>$searchTerm,
                          'fieldcode'=>$fieldCode
                      );
                      $params = http_build_query($params);
                        ?>
                     <div class="login"><a href="login.php?<?php echo $params;?>">Login</a></div>     
                    <?php }else{ ?>
                    <div class="login"><a href="login.php?path=index">Login</a></div>     
                    <?php } ?>
                    
               <?php } ?>
        </div>

        <div class="content">
            <?php echo $content; ?>
        </div>
<?php 
$xml ="Config.xml";
$dom = new DOMDocument();
$dom->load($xml);  
$version = $dom ->getElementsByTagName('Version')->item(0)->nodeValue;
?>
        <div class="footer">        
            <div class="span-5">
               <table cellspacing="20px">               
              <tr>
              <td>
              <strong>Need Help?</strong>         
              </td>
              <td>
              <a href="http://vufinddemo.ebscohost.com/demo/Help/Home?topic=search" target="_blank">Search Tips</a>
              </td>
              <td>
              <a href="#">Ask a Librarian</a>
              </td>
              <td>
              <a href="#">FAQs</a>
              </td>      
              </tr>
                </table>
           </div>
            <div style="text-align: right;
    font-size: 85%; 
    color: lightgray;
    height: 10px;
    position: relative;"><?php echo $version ?></div>
        </div>
        </div>
        <?php
        if(isset($_SESSION['autocomplete']) && $_SESSION['autocomplete'] == 'y'){
        echo '<script>';
        include_once('web/autocomplete.js.php');
        echo '</script>';
        }
        ?>
    </body>
</html>