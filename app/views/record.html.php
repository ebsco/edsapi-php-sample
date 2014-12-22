<?php
$results = $results = $_SESSION['results'];
$queryStringUrl = $results['queryString'];
$encodedQuery = http_build_query(array('query'=>$_REQUEST['query']));
$encodedHighLigtTerm = http_build_query(array('highlight'=>$_REQUEST['highlight']));
?>
<div id="toptabcontent">
    <div class ="topbar">
       <div style="padding-top: 6px; float: left" ><a style="color: #ffffff;margin-left: 15px;" href="results.php?<?php echo $encodedQuery;?>&fieldcode=<?php echo $_REQUEST['fieldcode'];?>&<?php echo $queryStringUrl; ?>&back=y"> << Back to Results</a></div>
      <div style="float: right;margin: 7px 20px 0 0;color: white">
          <?php if($_REQUEST['resultId']>1){  ?>
           <a href="recordSwich.php?<?php echo $encodedQuery;?>&fieldcode=<?php echo $_REQUEST['fieldcode'];?>&resultId=<?php echo ($_REQUEST['resultId']-1)?>&<?php echo $queryStringUrl; ?>"><span class="results-paging-previous">&nbsp;&nbsp;&nbsp;&nbsp;</span></a>
            <?php }
            echo $_REQUEST['resultId'].' of '.$_REQUEST['recordCount'];
           if($_REQUEST['resultId']<$_REQUEST['recordCount']){  ?>
           <a href="recordSwich.php?<?php echo $encodedQuery;?>&fieldcode=<?php echo $_REQUEST['fieldcode'];?>&resultId=<?php echo ($_REQUEST['resultId']+1)?>&<?php echo $queryStringUrl; ?>"><span class="results-paging-next">&nbsp;&nbsp;&nbsp;&nbsp;</span></a>
           <?php   } ?>
      </div>
    </div>
 <?php if($debug=='y'){?>
    <div style="float:right; padding-bottom: 10px"><a target="_blank" href="debug.php?record=y">Retrieve response XML</a></div>
<?php } ?>
     <div class="record table">
<?php if ($error) { ?>
    <div class="error">
        <><?php echo $error; ?>
    </div>
<?php } ?>
<?php if((!isset($_COOKIE['login']))&&$result['AccessLevel']==1){ ?>
         <p>This record from <b>[<?php echo $result['DbLabel']; ?>]</b> cannot be displayed to guests.<br><a href="login.php?path=record&db=<?php echo $_REQUEST['db']?>&an=<?php echo $_REQUEST['an']?>&<?php echo $encodedHighLigtTerm;?>&resultId=<?php echo $_REQUEST['resultId'] ?>&recordCount=<?php echo $_REQUEST['recordCount'] ?>&<?php echo $encodedQuery;?>&fieldcode=<?php echo $_REQUEST['fieldcode']; ?>">Login</a> for full access.</p>
<?php }else{ ?>     
    <h1>
      <?php if (!empty($result['Items'])) { 
        echo $result['Items'][0]['Data'];
       } ?>
    </h1>       
         <div>
             <div class="table-cell floatleft">                 
                 <?php if(!empty($result['PLink'])){?>
                 <ul class="table-cell-box">
                      <li>
                          <a href="<?php echo $result['PLink'] ?>">
                        View in EDS
                        </a>
                      </li>
                  </ul>
                      <?php } ?> 
                 
                     <?php if(!empty($result['PDF'])||$result['HTML']==1){?>
                     <ul class="table-cell-box">
                     <label>Full Text:</label><hr/>
                     
                     <?php if(!empty($result['PDF'])){?>
                      <li>
                          <a target="_blank" class="icon pdf fulltext" href="PDF.php?an=<?php echo $result['An']?>&db=<?php echo $result['DbId']?>">
                        PDF full text
                        </a>
                      </li>
                      <?php } ?>
                      <?php if($result['HTML']==1){ ?>
                      <?php if((!isset($_COOKIE['login']))&&$result['AccessLevel']==2){ ?> 
                      <li>
                         <a target="_blank" class="icon html fulltext" href="login.php?path=HTML&an=<?php echo $_REQUEST['an']; ?>&db=<?php echo $_REQUEST['db']; ?>&<?php echo $encodedHighLigtTerm ?>&resultId=<?php echo $_REQUEST['resultId'];?>&recordCount=<?php echo $_REQUEST['recordCount']?>&<?php echo $encodedQuery;?>&fieldcode=<?php echo $_REQUEST['fieldcode']; ?>">
                        HTML full text
                        </a>
                      </li>
                          <?php } else{?>
                      <li>
                          <a class="icon html fulltext" href="#html">HTML Full Text</a>                       
                      </li>                      
                         <?php } ?>           
                      <?php } ?>
                      </ul>
                      <?php } ?>
                      
                      <?php if (!empty($result['CustomLinks'])) { ?>                     
                      <ul class="table-cell-box">
                          <label>Custom Links:</label><hr/>
                            <?php foreach ($result['CustomLinks'] as $customLink) { ?>
                                <li>
                                    <a href="<?php echo $customLink['Url']; ?>" title="<?php echo $customLink['MouseOverText']; ?>"><img src="<?php echo $customLink['Icon']?>" /> <?php echo $customLink['Text']; ?></a>
                                </li>
                            <?php } ?>
                       </ul>
                      <?php } ?>
                     <?php if (!empty($result['FullTextCustomLinks'])) { ?>                     
                      <ul class="table-cell-box">
                          <label>Custom Links:</label><hr/>
                            <?php foreach ($result['FullTextCustomLinks'] as $customLink) { ?>
                                <li>
                                    <a href="<?php echo $customLink['Url']; ?>" title="<?php echo $customLink['MouseOverText']; ?>"><img src="<?php echo $customLink['Icon']?>" /> <?php echo $customLink['Text']; ?></a>
                                </li>
                            <?php } ?>
                       </ul>
                      <?php } ?>                 
             </div>
             <div style="margin-left: 20px" class="table-cell span-15">
              <table>                  
       <?php if (!empty($result['Items'])) { ?>
                                         
                     <?php for($i=1;$i<count($result['Items']);$i++) { ?>
                     <tr>
                         <td style="width: 150px; vertical-align: top"><strong>
                     <?php echo $result['Items'][$i]['Label']; ?>:
                       </strong></td>
                       <td>
                     <?php if($result['Items'][$i]['Label']=='URL'){ ?> 
                           <?php echo $result['Items'][$i]['Data'] ?>
                     <?php }else{ ?>   
                     <?php echo $result['Items'][$i]['Data']; ?>
                       </td>
                       <?php } ?>
                     </tr> 
                     <?php } ?>                                   
        <?php } ?>
        <?php if(!empty($result['pubType'])){ ?> 
                     <tr>
                         <td><strong>PubType:</strong></td>
                         <td><?php echo $result['pubType'] ?></td>
                     </tr>
        <?php } ?>
        <?php if (!empty($result['DbLabel'])) { ?>
            <tr>
                <td><strong>
                    Database:
            </strong></td>
                <td>
                    <?php echo $result['DbLabel']; ?>
                </td>
            </tr>
           
        <?php } ?>   
            <?php if((!isset($_COOKIE['login']))&&$result['AccessLevel']==2){ ?>
            <tr>
                <td><br></td>
                <td><br></td>
            </tr>
             <tr>
                 <td colspan="2">This record from <b>[<?php echo $result['DbLabel']; ?>]</b> cannot be displayed to guests.<br><a href="login.php?path=record&db=<?php echo $_REQUEST['db']?>&an=<?php echo $_REQUEST['an']?>&<?php echo $encodedHighLigtTerm?>&resultId=<?php echo $_REQUEST['resultId'] ?>&recordCount=<?php echo $_REQUEST['recordCount'] ?>&<?php echo $encodedQuery;?>&fieldcode=<?php echo $_REQUEST['fieldcode']; ?>">Login</a> for full access.</td>
            </tr>
            <?php } ?>
        </table> 
         <?php if(!empty($result['htmllink'])){?>
         <div id="html" style="margin-top:30px">
             <?php echo $result['htmllink'] ?>
         </div>
         <?php } ?>
         </div>
             <div class="jacket">
                <?php if(!empty($result['ImageInfo'])) { ?>              
                 <img width="150px" height="200px" src="<?php echo $result['ImageInfo']['medium']; ?>" />             
        <?php } ?>
             </div>
        </div>
      <?php } ?>  
         </div>
</div>


