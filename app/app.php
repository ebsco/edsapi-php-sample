<?php
session_start();


// Return the root directory relative to current PHP file
// which is /app
function root() {
     return dirname(__FILE__) . '/';
}

// Render a template
function render_template($locals, $fileName) {
    extract($locals);
    ob_start();
    include(root() . 'views/' . $fileName . '.php');
    return ob_get_clean();
}

// Render a view
function render($fileName, $templateName, $variableArray=array()) {
    $variableArray['content'] = render_template($variableArray, $fileName);
    print render_template($variableArray, $templateName);
}

// A basic pagination that displays maximum 10 pages
function paginate($recordCount, $limit, $page, $searchTerm, $fieldCode) {   
    $output = '';
    $linkCount =ceil($recordCount/$limit);
    if (!empty($page)) {
        if($page>$linkCount){
            $page = $linkCount;
        }   
    } else {
        $page = 1;
    }
    $base_url = "pageOptions.php?$searchTerm&fieldcode=$fieldCode";
    
    if($page%10 != 0){
    $f = floor($page/10);
    }
    else {
    $f=floor($page/10)-1;
    }
    $s = $page-1;
    if ($linkCount >= 1) {
        $output = '<p>';
        if($s>0){
                $output .= "<a href=\"{$base_url}&pagenumber=GoToPage({$s})\"><span class='results-paging-previous'>&nbsp;&nbsp;&nbsp;&nbsp;</span></a>";
            }
    if($f < floor($linkCount/10)){
        for ($i = $f*10; $i < $f*10+10; $i++) {
            $p = $i+1;                     
            if ($p != $page) {
                $output .= "<a href=\"{$base_url}&pagenumber=GoToPage({$p})\"><u>{$p}</u></a>";
            } else {
                $output .= '<strong>'.$p.'</strong>';
            }          
        }
    }else{
        for ($i = $f*10; $i < $linkCount; $i++) {
            $p = $i+1;                    
            if ($p != $page) {
                $output .= "<a href=\"{$base_url}&pagenumber=GoToPage({$p})\">{$p}</a>";
            } else {
                $output .= $p;
            }        
        }
    }   $p_1 = $page+1;
     if($p_1 <= $linkCount){
        $output .= "<a href=\"{$base_url}&pagenumber=GoToPage({$p_1})\"><span class='results-paging-next'>&nbsp;&nbsp;&nbsp;&nbsp;</span></a>";
     }
        $output .= '<br class="clear" /></p>';
    }
    return $output;
}

?>