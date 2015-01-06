<?php
$api =  new EBSCOAPI();
$Info = $api->getInfo();
?>
<div id="toptabcontent"> 
<div class="searchHomeContent">
<div class="searchHomeForm">
    <div class="searchform">
<h1>Basic Search</h1>
<form action="results.php">
    <p>
        <input type="text" name="query" style="width: 350px;" id="lookfor" /> 
        <input type="hidden" name="expander" value="fulltext" />            
        <input type="submit" value="Search" />
    </p>
    <table>
        <tr>
            <td>
                <input type="radio" id="type-keyword" name="fieldcode" value="keyword" checked="checked"/>
                <label for="type-keyword">Keyword</label>
            </td>
		<?php 
			$fieldCode="";
		    $selAuthor="";
			if(!empty($Info['search'])){ 
				foreach($Info['search'] as $searchField){
					if($searchField['Label']=='Author'){
						$selAuthor=" selected ";
					}
				}
			}
		?>
			
            <td>
                <input type="radio" id="type-author" name="fieldcode" value="AU"  <?php echo $selAuthor; ?>/>
                <label for="type-author">Author</label>
            </td>
      <?php   
			$selTitle="";
 			if(!empty($Info['search'])){ 
				
				foreach($Info['search'] as $searchField){         
					if($searchField['Label']=='Title'){
						$selTitle=" selected ";
					}
				}
			}
			?>
            <td>
                <input type="radio" id="type-title" name="fieldcode" value="TI" <?php echo $selTitle; ?>/>
                <label for="type-title">Title</label>                             
            </td>          
       
        </tr>
    </table>
</form>
</div>
</div>
</div>
</div>
