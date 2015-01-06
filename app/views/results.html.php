<?php 
$queryStringUrl = $results['queryString'];
// URL used by facets links
$refineParams = array(
    'refine' => 'y',
    'query'  => $searchTerm,
    'fieldcode' => $fieldCode
);
$refineParams = http_build_query($refineParams);
$refineSearchUrl = "results.php?".$refineParams;
$encodedSearchTerm = http_build_query(array('query'=>$searchTerm));
$encodedHighLigtTerm = http_build_query(array('highlight'=>$searchTerm));
?>
<div id="toptabcontent">
    <div class="topSearchBox">
        <form action="results.php">
    <p>
        <input type="text" name="query" style="width: 350px;" id="lookfor" value="<?php echo $searchTerm ?>"/>  
        <input type="hidden" name="expander" value="fulltext" />
        <?php 
        $selected1 = '';
        $selected2 = '';
        $selected3 = '';
        if($fieldCode == 'keyword'){
            $selected1 = "selected = 'selected'";
        } 
        if($fieldCode == 'AU'){
            $selected2 = "selected = 'selected'";
        }
        if($fieldCode == 'TI'){
            $selected3 = "selected = 'selected'";
        } ?>
        <select name="fieldcode">
			<option id="type-keyword" name="fieldcode" value="keyword" <?php echo $selected1 ?> >Keyword</option>
            <option id="type-author" name="fieldcode" value="AU"<?php echo $selected2; ?> >Author</option>
            <option id="type-title" name="fieldcode" value="TI"<?php echo $selected3 ?> >Title</option>                
        </select>
        <input type="submit" value="Search" />
        
    </p>
    </form>
    </div>
<div class="table">
    <div class="table-row">
        <div class="table-cell">         
            <div><h4>Refine Search</h4></div>
            
<?php if(!empty($results['appliedFacets'])||!empty($results['appliedLimiters'])||!empty($results['appliedExpanders'])){ ?>
<div class="filters">
    <strong>Current filters</strong>
    <ul class="filters">
<!-- applied facets -->
        <?php if (!empty($results['appliedFacets'])) { ?>
        <?php foreach ($results['appliedFacets'] as $filter) { ?>
        <?php foreach ($filter['facetValue'] as $facetValue){ 
              $action = http_build_query(array('action'=>$facetValue['removeAction']));
        ?>
        <li>
        <a href="<?php echo $refineSearchUrl.'&'.$queryStringUrl.'&'.$action; ?>">                 
            <img  src="web/delete.png"/>                      
        </a>
        <a href="<?php echo $refineSearchUrl.'&'.$queryStringUrl.'&'.$action; ?>"><?php echo $facetValue['Id']; ?>: <?php echo $facetValue['value']; ?></a>
        </li>
        <?php } } }?>
<!-- Applied limiters -->
        <?php if (!empty($results['appliedLimiters'])) { ?>    
        <?php foreach ($results['appliedLimiters'] as $filter) {
                  $limiterLabel = '';
                  foreach($Info['limiters'] as $limiter){
                      if($limiter['Id']==$filter['Id']){
                          $limiterLabel = $limiter['Label'];
                          break;
                      }
                  }
                  $action = http_build_query(array('action'=>$filter['removeAction']));
        ?>
        <li>
        <a href="<?php echo $refineSearchUrl.'&'.$queryStringUrl.'&'.$action; ?>">                 
            <img  src="web/delete.png"/>                      
        </a>
        <a href="<?php echo $refineSearchUrl.'&'.$queryStringUrl.'&'.$action; ?>">Limiter: <?php echo $limiterLabel; ?></a>
        </li>
        <?php } }?>        
<!-- Applied expanders -->
        <?php if (!empty($results['appliedExpanders'])) { ?>
        <?php foreach ($results['appliedExpanders'] as $filter) {
                    $expanderLabel = '';
					if (isset($Info['expanders'])) {
						foreach($Info['expanders'] as $exp){
							if($exp['Id']==$filter['Id']){
								$expanderLabel = $exp['Label'];
								break;
							}
						}
						$action = http_build_query(array('action'=>$filter['removeAction']));
					}
             ?>
        <li>
        <a href="<?php echo $refineSearchUrl.'&'.$queryStringUrl.'&'.$action; ?>">                 
            <img  src="web/delete.png"/>                      
        </a>
        <a href="<?php echo $refineSearchUrl.'&'.$queryStringUrl.'&'.$action; ?>">Expander: <?php echo $expanderLabel; ?></a>
        </li>
        <?php } } ?>        
    </ul>
</div>
<?php } ?>
<?php if(!empty($Info['limiters'])){?>
<div class="facets" style="font-size: 80%">
                <dl class="facet-label">
                    <dt>Limit your results</dt>
                </dl>
                <dl class="facet-label" >
                    <form action="limiter.php" method="get">
                   <?php for($i=0;$i<3;$i++){ ?>
                   <?php   $limiter=$Info['limiters'][$i]; ?>
                     <?php if($limiter['Type'] =='select'){?>
                      <?php if(empty($results['appliedLimiters'])){ ?>
                      <dd><input type="checkbox" value="<?php echo $limiter['Action'];?>" name="<?php echo $limiter['Id']; ?>" /><?php echo $limiter['Label'] ?></dd> 
                      <?php }else{
                                 $flag = FALSE;
                                 foreach($results['appliedLimiters'] as $filter){
                                    if($limiter['Id']==$filter['Id']){ 
                                        $flag = TRUE;
                                        break;
                                    }
                                 }    
                               if($flag==TRUE){ ?>
                                      <dd><input type="checkbox" value="<?php echo $limiter['Action'];?>" name="<?php echo $limiter['Id']; ?>" checked="checked" /><?php echo $limiter['Label'] ?></dd>                               
                      <?php  }else{ ?>
                                      <dd><input type="checkbox" value="<?php echo $limiter['Action'];?>" name="<?php echo $limiter['Id']; ?>" /><?php echo $limiter['Label'] ?></dd> 
                      <?php }}}}?>
                    <input type="hidden" value="<?php echo $searchTerm;?>" name="query" />
                    <input type="hidden" value="<?php echo $fieldCode;?>"  name="fieldcode" />
                    <input type="submit" value="Update" />
                    </form>               
                </dl>              
</div>
<?php } ?>
<div class="facet" style="font-size: 80%">
                <dl class="facet-label">
                    <dt>Expand your results</dt>
                </dl>
                <dl class="facet-label">
                <form action="expander.php">
                    <?php 
					foreach($Info['expanders'] as $exp){
                       if(empty($results['appliedExpanders'])){ ?>
                           <dd><input type="checkbox" value="<?php echo $exp['Action'];?>" name="<?php echo $exp['Id']; ?>" /><?php echo $exp['Label'];?></dd>
                    <?php }else{
                        $flag = FALSE;
                        foreach($results['appliedExpanders'] as $aexp){
                            if($aexp['Id']==$exp['Id']){
                                $flag=TRUE;
                                break;
                            }
                        }
                        
                        if($flag==TRUE){ ?>
                           <dd><input type="checkbox" value="<?php echo $exp['Action'];?>" name="<?php echo $exp['Id']; ?>"  checked="checked"/><?php echo $exp['Label'];?></dd>
                   <?php }else{ ?>
                            <dd><input type="checkbox" value="<?php echo $exp['Action'];?>" name="<?php echo $exp['Id']; ?>" /><?php echo $exp['Label'];?></dd>
                   <?php   }
                    } 
                    }?>                 
                    <input type="hidden" value="<?php echo $searchTerm;?>" name="query" />
                    <input type="hidden" value="<?php echo $fieldCode;?>"  name="fieldcode" />
                    <input type="submit" value="Update"/>
                </form>
                </dl>
</div>            
<?php if (!empty($results['facets'])) { $i=0; ?>
    <div class="facets">
        <?php 
		foreach ($results['facets'] as $facet) { $i++; 
			if(!empty($facet['Label'])){ ?>
				<script type="text/javascript">            
					jQuery(document).ready(function(){             
						 jQuery("#flip<?php echo $i ?>").click(function(){              
							 jQuery("#panel<?php echo $i ?>").slideToggle("slow");
							 if(jQuery("#plus<?php echo $i ?>").html()=='[+]'){
								 jQuery("#plus<?php echo $i ?>").html('[-]');
							 }else{
								 jQuery("#plus<?php echo $i ?>").html('[+]');
							 }
						 });   
					});
				</script>
        
				<div class="facet" style="font-size: 80%">                
					<dl class="facet-label" id="flip<?php echo $i ?>">
						<dt><span style="font-weight: lighter" id="plus<?php echo $i ?>">[+]</span><?php echo $facet['Label']; ?></dt>
					</dl>
					<dl class="facet-values" id="panel<?php echo $i ?>">
	   
						<?php 
							foreach ($facet['Values'] as $facetValue) { 
								$action = http_build_query(array('action'=>$facetValue['Action']));
								echo '<dd>'
									.'	<a href="'.$refineSearchUrl.'&'.$queryStringUrl.'&'.$action.'">'.$facetValue['Value'].'</a>('.$facetValue['Count'].')'
									.'</dd>'
									;
							} 
						?>                  
					</dl>
				</div>
          <?php } ?>
        <?php } ?>
    </div>
<?php } ?>

        </div>
<div class="table-cell">
<?php 
	if($debug=='y'){
		echo '<div style="float:right"><a target="_blank" href="debug.php?result=y">Search response XML</a></div>';
	} ?>
<div class="top-menu">
    <h2>Results</h2> 
<?php 

	if ($error) { 
		echo '<div class="error">'.$error.'</div>';
	} 

 if (!empty($results)) { ?>
    <div class="statistics">
        Showing <strong><?php if($results['recordCount']>0){ echo ($start - 1) * $limit + 1;} else { echo 0; } ?>  - <?php if((($start - 1) * $limit + $limit)>=$results['recordCount']){ echo $results['recordCount']; } else { echo ($start - 1) * $limit + $limit;} ?></strong>  
            of <strong><?php echo $results['recordCount']; ?></strong>
            for "<strong><?php echo $searchTerm; ?></strong>"
    </div><br>            
    <div class ="topbar-resultList">
        <div class="optionsControls">
            <ul style="margin:3px 4px 4px 4px">              
                <li class="options-controls-li">                   
                    <form action="pageOptions.php">
                        <label><b>Sort</b></label>
                        <select onchange="this.form.submit()" name="sort" > 
                            <?php foreach($Info['sort'] as $s){ 
                                  if($sortBy==$s['Id']){ ?>
                                <option selected="selected" value="<?php echo $s['Action']; ?>"><?php echo $s['Label'] ?></option>
                            <?php }else{ ?>
                                <option value="<?php echo $s['Action']; ?>"><?php echo $s['Label'] ?></option>
                            <?php }}?>
                        </select>
                        <input type="hidden" value="<?php echo $searchTerm;?>" name="query" />
                        <input type="hidden" value="<?php echo $fieldCode;?>"  name="fieldcode" />      
                    </form>
                </li>
                 <li class="options-controls-li">
                      <?php 
						$option = array(
                          'Detailed' => '',
                          'Brief' => '',
                          'Title' => '',                      
                        );
						if($amount== 'detailed'){
							$option['Detailed']= '  selected="selected"';
						}
						if($amount== 'brief'){
							$option['Brief']= '  selected="selected"';
						}
						if($amount== 'title'){
							$option['Title']= '  selected="selected"';
						}                              
                    ?>    
                    <form action="pageOptions.php">
                        <label><b>Page options</b></label>
                        <select onchange="this.form.submit()" name="view">
                            <option  <?php echo $option['Detailed']?> value="detailed">Detailed</option>
                            <option  <?php echo $option['Brief']?> value="brief">Brief</option>
                            <option  <?php echo $option['Title']?> value="title">Title Only</option>
                        </select>
                        <input type="hidden" value="<?php echo $searchTerm;?>" name="query" />
                        <input type="hidden" value="<?php echo $fieldCode;?>"  name="fieldcode" />  
                    </form>
                 </li>
                    <li class="options-controls-li">
                    
                    <?php 
						$select = array(
							'5' => '',
							'10' => '',
							'20' => '',
							'30' => '',
							'40' => '',
							'50' => ''
						);
						if($limit== 5){
						  $select['5']= '  selected="selected"';
						}
						if($limit== 10){
						  $select['10']= '  selected="selected"';
						}
						if($limit== 20){
						  $select['20']= '  selected="selected"';
						}
						if($limit== 30){
						  $select['30']= '  selected="selected"';
						}
						if($limit== 40){
						  $select['40']= '  selected="selected"';
						}
						if($limit== 50){
						  $select['50']= '  selected="selected"';
						}                          
                    ?>                          
                     <form action="pageOptions.php">
                        <label><b>Results per page</b></label>
                        <select onchange="this.form.submit()" name="resultsperpage">
                            <option <?php echo $select['5']?> value="setResultsperpage(5)">5</option>
                            <option <?php echo $select['10']?> value="setResultsperpage(10)">10</option>
                            <option <?php echo $select['20']?> value="setResultsperpage(20)">20</option>
                            <option <?php echo $select['30']?> value="setResultsperpage(30)">30</option>
                            <option <?php echo $select['40']?> value="setResultsperpage(40)">40</option>
                            <option <?php echo $select['50']?> value="setResultsperpage(50)">50</option>
                        </select>
                        <input type="hidden" value="<?php echo $searchTerm;?>" name="query" />
                        <input type="hidden" value="<?php echo $fieldCode;?>"  name="fieldcode" />  
                    </form>
                    </li>
                </ul>
        </div>
     </div>
	 
	<div style="text-align: center">
		<div class="pagination"><?php echo paginate($results['recordCount'], $limit, $start, $encodedSearchTerm, $fieldCode); ?></div>
	</div>
	 
	<!-- begin research starters -->


		<?php
			// check if the research starters are returned
			if (isset($results['relatedRecords'][0]["records"][0])) {
				$rs=$results['relatedRecords'][0]["records"][0]; // only the first RS
				echo '<img alt="" src="'.$rs["ImageInfo"].'" style="float:left;padding:5px;">';
				$rsHtml ='<div class="rsbox">'
						.'<span class="rsIntro">'.$results['relatedRecords'][0]["Label"].'</span><br/>'
						;
				foreach($rs["Items"] as $item) {
					if ($item["Group"]=="Au") {continue;}
					if ($item["Group"]=="Su") {continue;}
					if ($item["Group"]=="Src") {continue;}
					switch ($item["Label"]) {
						case "Title":
							$rsHtml.='<span class="rstitle">'.$item["Data"].'</span><br/>';
							break;						
						case "Abstract":
							$l="researchstarter.php?db=".$rs['DbId']."&an=".$rs['An']."&".$encodedHighLigtTerm."&".$encodedSearchTerm."&fieldcode=".$fieldCode;                         
                            $rsHtml.='<span>'.$item["Data"].'(<a href="'.$l.'">more</a>)</span><br/>';
							break;
						default:
							$rsHtml.='<span>'.$item["Data"].'</span><br/>';
					}
				}
				
				foreach($rs["Items"] as $item) {
					switch ($item["Group"]) {			
						case "Src":
							$rsHtml.='<span class="rsSource">'.$item["Data"].'</span><br/>';
							break;
					}
				}	
			
				$rsHtml.='<span style="clear:both"/>';
				$rsHtml.='</div><br/>';
						;
				echo $rsHtml;
				//var_dump($results['relatedRecords'][0]["records"]);
			}		
		?>

	<!-- end research starters -->

<?php } ?>

<div class="results table">
    <?php if (empty($results['records'])) { ?>
        <div class="result table-row">
            <div class="table-cell">
                <h2><i>No results were found.</i></h2>
            </div>
        </div>
    <?php } else { ?>
        <?php foreach ($results['records'] as $result) { ?>
            <div class="result table-row">
                <div class="record-id table-cell">
                    <?php echo $result['ResultId']; ?>.
                </div>               
                 <?php if (!empty($result['pubType'])) { ?>
                <div class="pubtype table-cell" style="text-align: center">  
                    <?php if (!empty($result['ImageInfo'])) { ?>                    
                    <a href="record.php?db=<?php echo $result['DbId']; ?>&an=<?php echo $result['An']; ?>&<?php echo $encodedHighLigtTerm ?>&resultId=<?php echo $result['ResultId'];?>&recordCount=<?php echo $results['recordCount']; ?>&<?php echo $encodedSearchTerm;?>&fieldcode=<?php echo $fieldCode; ?>">                         
                                <img src="<?php echo $result['ImageInfo']['thumb']; ?>" />                                                                       
                        </a> 
                    <?php }else{ 
                     $pubTypeId =  $result['PubTypeId'];                    
                     $pubTypeClass = "pt-".$pubTypeId;
                    ?>
                    <span class="pt-icon <?php echo $pubTypeClass?>"></span>
                    <?php } ?>
                    <div><?php echo $result['pubType'] ?></div>
                </div>     
                <?php } ?>       
                <div class="info table-cell">
                    <div style="margin-left: 10px">
                        
                        <?php 
						if(!(isset($_SESSION['login']) ||(validAuthIP("Config.xml")==true)) &&$result['AccessLevel']==1){ ?>
                            <p>This record from <b>[<?php echo $result['DbLabel'] ?>]</b> cannot be displayed to guests.<a href="login.php?path=results&<?php echo $encodedSearchTerm;?>&fieldcode=<?php echo $fieldCode; ?>">Login</a> for full access.</p>
                       <?php }
					   else{  ?>
                        <div class="title">                     
                            <?php if (!empty($result['RecordInfo']['BibEntity']['Titles'])){ ?>
                            <?php foreach($result['RecordInfo']['BibEntity']['Titles'] as $Ti){ ?> 
                            <a href="record.php?db=<?php echo $result['DbId']; ?>&an=<?php echo $result['An']; ?>&<?php echo $encodedHighLigtTerm ?>&resultId=<?php echo $result['ResultId'];?>&recordCount=<?php echo $results['recordCount']; ?>&<?php echo $encodedSearchTerm;?>&fieldcode=<?php echo $fieldCode; ?>"><?php echo  $Ti['TitleFull']; ?></a>
                           <?php } }
                            else { ?> 
                            <a href="record.php?db=<?php echo $result['DbId']; ?>&an=<?php echo $result['An']; ?>&<?php echo $encodedHighLigtTerm ?>&resultId=<?php echo $result['ResultId'];?>&recordCount=<?php echo $results['recordCount']; ?>&<?php echo $encodedSearchTerm;?>&fieldcode=<?php echo $fieldCode; ?>"><?php echo "Title is not Aavailable"; ?></a>                   
                          <?php  } ?>                
                        </div>
						
                        <?php 
						
						if(!empty($result['Items']['TiAtl'])){ 
								echo "<div>";
								foreach($result['Items']['TiAtl'] as $TiAtl){ 
									echo $TiAtl['Data']; 
								} 
								echo "</div>";
                        } 
						
						if (!empty($result['Items']['Au'])) { ?>
							<div class="authors">
								<span>
									<span style="font-style: italic;">By : </span>                                            
									<?php 
									foreach($result['Items']['Au'] as $Author){ 
										echo $Author['Data']; 
									} 
									?>
								</span>                        
							</div>                        
                        <?php 
						} 
						?>
						
                        <div class="authors">
                        <span style="font-style: italic; ">
						<?php 
							if(isset($result['RecordInfo']['BibRelationships']['IsPartOfRelationships']['Titles'])){
								foreach($result['RecordInfo']['BibRelationships']['IsPartOfRelationships']['Titles'] as $title){ 
									echo $title['TitleFull'].",";                                  
								}
							}
						?>
                        </span>
                        <?php 
						
						if(!empty($result['RecordInfo']['BibEntity']['Identifiers'])){
							foreach($result['RecordInfo']['BibEntity']['Identifiers'] as $identifier){
								$pieces = explode('-',$identifier['Type']); 
								if(isset($pieces[1])){                                       
								   echo strtoupper($pieces[0]).'-'.ucfirst( $pieces[1]);                                       
								}
								else{ 
								   echo strtoupper($pieces[0]);
								}
								echo ":".$identifier['Value'].",";                                                                
							}
						} 
						
						if(isset($result['RecordInfo']['BibRelationships']['IsPartOfRelationships']['Identifiers'])){
                            foreach($result['RecordInfo']['BibRelationships']['IsPartOfRelationships']['Identifiers'] as $identifier){
								$pieces = explode('-',$identifier['Type']);
								if(isset($pieces[1])){                                        
									echo strtoupper( $pieces[0]).'-'.ucfirst( $pieces[1]);                                       
								}
								else{ 
									echo strtoupper($pieces[0]);
								}
								echo ": ".$identifier['Value'].","; 
                            }  
                        }
						
						if(isset($result['RecordInfo']['BibRelationships']['IsPartOfRelationships']['date'])){
							foreach($result['RecordInfo']['BibRelationships']['IsPartOfRelationships']['date'] as $date){ 
								if ($date["Type"]=='published') {
									echo "Published: ".$date['M']."/".$date['D']."/".$date['Y'].",";
								}
							}
						}
							
						if(isset($result['RecordInfo']['BibRelationships']['IsPartOfRelationships']['numbering'])){ 
							foreach($result['RecordInfo']['BibRelationships']['IsPartOfRelationships']['numbering'] as $number){
								$type = str_replace('volume','Vol',$number['Type']); $type = str_replace('issue','Issue',$type); 
								echo $type.": ".$number['Value'].","; 
							} 
						} 
							
						if(!empty($result['RecordInfo']['BibEntity']['PhysicalDescription']['StartPage'])){
							 echo 'Start Page: '.$result['RecordInfo']['BibEntity']['PhysicalDescription']['StartPage'].','; 
						} 
							
						if(!empty($result['RecordInfo']['BibEntity']['PhysicalDescription']['Pagination'])){ 
                            echo 'Page Count: '.$result['RecordInfo']['BibEntity']['PhysicalDescription']['Pagination'].","; 
                        } 
						
						if(!empty($result['RecordInfo']['BibEntity']['Languages'])){ 
							foreach($result['RecordInfo']['BibEntity']['Languages'] as $language){ 
							 echo "Language: ".$language['Text'];
							} 
						}
						?>
                        </div>
                        <?php if (isset($result['Items']['Ab'])) { ?>
						
						
						 <script type="text/javascript">            
							 jQuery(document).ready(function(){             
							 jQuery("#abstract-plug<?php echo $result['ResultId']; ?>").click(function(){              
								 jQuery("#full-abstract<?php echo $result['ResultId']; ?>").show() ; 
								 jQuery("#abstract<?php echo $result['ResultId']; ?>").hide() ; 
							 }); 
							 jQuery("#full-abstract-plug<?php echo $result['ResultId']; ?>").click(function(){              
								 jQuery("#full-abstract<?php echo $result['ResultId']; ?>").hide() ; 
								 jQuery("#abstract<?php echo $result['ResultId']; ?>").show() ; 
							 });   
							});
						 </script>
			 
                        <div id="abstract<?php echo $result['ResultId'];?>" class="abstract">
                            <span>
                                <span style="font-style: italic;">Abstract: </span>                                    
								<?php 
									foreach($result['Items']['Ab'] as $Abstract){ 
										$xml ="Config.xml";
										$dom = new DOMDocument();
										$dom->load($xml);      
										$length = $dom ->getElementsByTagName('AbstractLength')->item(0)->nodeValue;      
										if($length == 'Full'){
											echo $Abstract['Data'];
										}
										else
										{
											$data = str_replace(array('<span class="highlight">','</span>'), array('',''), $Abstract['Data']);
											$data = substr($data, 0, $length).'...';
											echo $data;
										}
									} 
								?>                                  
                                <span id="abstract-plug<?php echo $result['ResultId'];?>">[+]</span>                                
                            </span>
                        </div>
                        <div id="full-abstract<?php echo $result['ResultId'];?>" class="full-abstract">
                            <span>
                                    <span style="font-style: italic;">Abstract: </span>
									<?php 
										foreach($result['Items']['Ab'] as $Abstract){ 
											echo $Abstract['Data']; 
										} 
									?>                                        
                                    <span id="full-abstract-plug<?php echo $result['ResultId'];?>">[-]</span>
                                </tr>
                            </span>
                        </div>
                      <?php } 
					  if (!empty($result['Items']['Su'])) { ?>
                        <div class="subjects">
                            <span>
								<span style="font-style: italic;">Subjects:</span>
								<?php 
									foreach($result['Items']['Su'] as $Subject){ 
										echo $Subject['Data']; 
									} 
								?> 
                            </span>
                        </div>
                      <?php } ?>
                      <div class="links">
                        <?php 
						if($result['HTML']==1){
							if  ( !(isset($_SESSION['login']) || validAuthIP("Config.xml")==true)  && $result['AccessLevel']==2){ 
								echo '<a target="_blank" class="icon html fulltext" href="login.php?path=HTML&an='.$result['An'].'&db='.$result['DbId'].'&'.$encodedHighLigtTerm.'&resultId='.$result['ResultId'].'&recordCount='.$results['recordCount'].'&'.$encodedSearchTerm.'&fieldcode='.$fieldCode.'">Full Text</a>';
							} 
							else
							{
								echo '<a target="_blank" class="icon html fulltext" href="record.php?an='.$result['An'].'&db='.$result['DbId'].'&'.$encodedHighLigtTerm.'&resultId='.$result['ResultId'].'&recordCount='.$results['recordCount'].'&'.$encodedSearchTerm.'&fieldcode='.$fieldCode.'#html">Full Text</a>';
							} 
						} 
						
						if(!empty($result['PDF'])){
							echo '<a target="_blank" class="icon pdf fulltext" href="PDF.php?an='.$result['An'].'&db='.$result['DbId'].'">Full Text</a>';
                        } 

						if (!empty($result['CustomLinks'])){  
							foreach ($result['CustomLinks'] as $customLink) { 
								echo '<a href="'.$customLink['Url'].'" title="'.$customLink['MouseOverText'].'>" target="_blank">';
								if ($customLink['Icon']!="") {
									echo '<img src="'.$customLink['Icon'].'" />';
								}
								echo $customLink['Text'].'</a>';
								
							 //echo '<a href="'.$customLink['Url'].'" title="'.$customLink['MouseOverText'].'" target="_blank"><img src="'.$customLink['Icon'].'" />'.$customLink['Text'].'</a>';
							} 
						}							
						?>                   
                      </div>                      
                      <?php 
					   
						if (!empty($result['FullTextCustomLinks'])){ 
							foreach ($result['FullTextCustomLinks'] as $customLink) { 
								echo '<a href="'.$customLink['Url'].'" title="'.$customLink['MouseOverText'].'>" target="_blank">';
								if ($customLink['Icon']!="") {
									echo '<img src="'.$customLink['Icon'].'" />';
								}
								echo $customLink['Text'].'</a>';
							}
						} 
					} ?>
                </div>
                </div>
            </div>
        <?php } 
		} ?>
</div>
<?php if (!empty($results)) { ?>
    <div style="text-align: center">
        <div class="pagination"><?php echo paginate($results['recordCount'], $limit, $start, $encodedSearchTerm, $fieldCode); ?></div>       
    </div>
<?php } ?>
        </div>
    </div>
</div>
</div>      
</div>

