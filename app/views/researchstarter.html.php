<?php
	$results = $results = $_SESSION['results'];
	$queryStringUrl = $results['queryString'];
	$encodedQuery = http_build_query(array('query'=>$_REQUEST['query']));
	$encodedHighLigtTerm = http_build_query(array('highlight'=>$_REQUEST['highlight']));
?>
<div id="toptabcontent">
    <div class ="topbar">
       <div style="padding-top: 6px; float: left" ><a style="color: #ffffff;margin-left: 15px;" href="results.php?<?php echo $encodedQuery;?>&fieldcode=<?php echo $_REQUEST['fieldcode'];?>&<?php echo $queryStringUrl; ?>&back=y"> << Back to Results</a></div>

    </div>
 <?php 
	if($debug=='y'){
		echo '<div style="float:right; padding-bottom: 10px"><a target="_blank" href="debug.php?record=y">Retrieve response XML</a></div>';
	} ?>
	
	
    <div class="record table">
<?php
 
	if ($error) { 
		echo '<div class="error">'.$error.'</div>';
	} 
	
	if( !(isset($_SESSION['login']) || (validAuthIP("Config.xml")==true)) && $result['AccessLevel']==1){ ?>
         <p>This record from <b>[<?php echo $result['DbLabel']; ?>]</b> cannot be displayed to guests.<br><a href="login.php?path=record&db=<?php echo $_REQUEST['db']?>&an=<?php echo $_REQUEST['an']?>&<?php echo $encodedHighLigtTerm;?>&resultId=<?php echo $_REQUEST['resultId'] ?>&recordCount=<?php echo $_REQUEST['recordCount'] ?>&<?php echo $encodedQuery;?>&fieldcode=<?php echo $_REQUEST['fieldcode']; ?>">Login</a> for full access.</p>
<?php 
	}
	else
	{ ?>     
      
         <div>
             <div class="table-cell floatleft">                 
				<?php 
					if(!empty($result['PLink'])){?>
						 <ul class="table-cell-box">
							  <li>
								  <a href="<?php echo $result['PLink'] ?>" target="_blank">View in EDS</a>
							  </li>
						  </ul>
                 <?php } 

					if(!empty($result['PDF'])||$result['HTML']==1){?>
                     <ul class="table-cell-box">
						<label>Full Text:</label><hr/>
                     
                     <?php 
						if(!empty($result['PDF'])){?>
						  <li>
							  <a target="_blank" class="icon pdf fulltext" href="PDF.php?an=<?php echo $result['An']?>&db=<?php echo $result['DbId']?>">
								PDF full text</a>
						  </li>
                      <?php 
						} 
					  
						if($result['HTML']==1){ 
							if((!isset($_SESSION['login']))&&$result['AccessLevel']==2){ ?> 
							  <li>
								 <a target="_blank" class="icon html fulltext" href="login.php?path=HTML&an=<?php echo $_REQUEST['an']; ?>&db=<?php echo $_REQUEST['db']; ?>&<?php echo $encodedHighLigtTerm ?>&resultId=<?php echo $_REQUEST['resultId'];?>&recordCount=<?php echo $_REQUEST['recordCount']?>&<?php echo $encodedQuery;?>&fieldcode=<?php echo $_REQUEST['fieldcode']; ?>">
								HTML full text
								</a>
							  </li>
							  <?php 
							} 
							else
							{?>
							  <li>
								  <a class="icon html fulltext" href="#html">HTML Full Text</a>                       
							  </li>                      
							 <?php 
							} 
						} ?>
                      </ul>
                      <?php } 

					  if (!empty($result['CustomLinks'])) { ?>                     
						  <ul class="table-cell-box">
							  <label>Custom Links:</label><hr/>
								<?php foreach ($result['CustomLinks'] as $customLink) { ?>
									<li>
										<a  target="_blank" href="<?php echo $customLink['Url']; ?>" title="<?php echo $customLink['MouseOverText']; ?>"><img src="<?php echo $customLink['Icon']?>" /> <?php echo $customLink['Text']; ?></a>
									</li>
								<?php } ?>
						   </ul>
                      <?php } 
					  
					  if (!empty($result['FullTextCustomLinks'])) { ?>                     
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
 
        <?php 
			if(!empty($result['htmllink'])){?>
				 <div id="html" style="margin-top:30px">
					 <?php 
						$s=substr($result['htmllink'],stripos($result['htmllink'],"<h2>"));
						echo $s; 
					 ?>
				 </div>
		<?php } ?>
		
         </div>
		 

		 
        </div>
      <?php } ?>  
    </div>
</div>


