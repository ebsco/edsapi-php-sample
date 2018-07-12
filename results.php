<?php
	include('app/app.php');
	include('rest/EBSCOAPI.php');

	$api = new EBSCOAPI();

	// Build  the arguments for the Search API method
	$searchTerm = str_replace('"','',$_REQUEST['query']);
	$fieldCode = $_REQUEST['fieldcode']? $_REQUEST['fieldcode'] :'';
	$start = isset($_REQUEST['pagenumber']) ? $_REQUEST['pagenumber'] : 1;
	$limit = isset($_REQUEST['resultsperpage'])?$_REQUEST['resultsperpage']:20;
	$sortBy = isset($_REQUEST['sort'])?$_REQUEST['sort']:'relevance';
	$amount = isset($_REQUEST['view'])?$_REQUEST['view']:'detailed';
	$mode = 'all';
	$expander = isset($_REQUEST['expander'])? $_REQUEST['expander']:'';
	$debug = isset($_REQUEST['debug'])? $_REQUEST['debug']:'';
	$Info = $api->getInfo();
	$autoSuggest = $api->getAutoSuggestState($Info);
	$autoCorrect = $api->getAutoCorrectState($Info);
	$imageQuickView = $api->getImageQuickViewState($Info);
	$relatedContent = $api->getRelatedContentOptions($Info);
	//only for testing
	$autoSuggest = 'y';
	$autoCorrect = 'y';
	$imageQuickView = 'y';


	//when clicking on the "original term" for an autocorrected query, we must be able to override the info method
	if (isset($_REQUEST['autocorrect']) && $_REQUEST['autocorrect'] == 'n'){
		$autoCorrect = 'n';
	}

	// If user come back from the detailed record 
	// The same search will not call API again
	if(isset($_REQUEST['back'] )&&isset($_SESSION['results'])){
		
		$results = $_SESSION['results'];
		
	}else if(isset($_REQUEST['option'])){
	// All page options will be handled here 
	// New Search or refined search will call the API
		
		$results = $_SESSION['results'];  
		$queryStringUrl = $results['queryString'];
		
		$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';
		$actions = array();
		if(!empty($action)){        
		   if(strstr($action, 'setsort(')){
			   $sortBy = str_replace(array('setsort(',')'),array('',''), $action);
			   $start = 1;
		   }
		   if(strstr($action, 'setResultsperpage(')){
			   $limit = str_replace(array('setResultsperpage(',')'),array('',''), $action);
		   }
		   if(strstr($action, 'GoToPage(')){
			$start = str_replace(array('GoToPage(',')'),array('',''), $action);
		   }
		   $actions['action'] = $action;
		}
		
		$view = isset($_REQUEST['view'])? array('view'=>$_REQUEST['view']):array();
		$params = array_merge($actions,$view);
		$url = $queryStringUrl.'&'.http_build_query($params);
		$results = $api->apiSearch($url);    
		// Will save the result into the session with the new SessionToken as index    
		$_SESSION['results'] = $results;
		
	}else if(isset($_REQUEST['refine'])||isset($_GET['login'])){
	// New Search or refined search will call the API
		if(isset($_REQUEST['action'])){
		$actions = $_REQUEST['action'];
		}else{
		$actions = '';
		}
		$refineActions = array();
		if(is_array($actions)){
			for($i=0; $i<count($actions);$i++){
				$refineActions['action-'.($i+1)]= $actions[$i+1];
			}        
		}else{
			$refineActions['action'] = $actions;
		}
		$results = $_SESSION['results'];
		$queryStringUrl = $results['queryString'];
		
		$params = http_build_query($refineActions);
		
		$url = $queryStringUrl.'&'.$params;
		$results = $api->apiSearch($url);

		$_SESSION['results'] = $results;
		
		if(isset($_REQUEST['refine']))$start = 1;
			 
	}else{

		   $query = array();

			// Basic search
			if(!empty($searchTerm)) {
				$term = urldecode($searchTerm);
				$term = str_replace('"', '', $term); // Temporary
				$term = str_replace(',',"\,",$term);
				$term = str_replace(':', '\:', $term);
				$term = str_replace('(', '\(', $term);
				$term = str_replace(')', '\)', $term);
				
				if($fieldCode!='keyword'){
				$query_str = implode(":", array($fieldCode, $term));
				}else{
				$query_str = $term;
				}
				$query["query"] = $query_str;

			// No search term, return an empty array
			} else {
				$results = array();            
			}
			   
			// Add the HTTP query params
			$params = array(
				// Specifies the sort. Valid options are:
				// relevance, date, date2
				// date = Date descending
				// date2 = Date descending
				'sort'           => $sortBy,
				// Specifies the search mode. Valid options are:
				// bool, any, all, smart
				'searchmode'     => $mode,
				// Specifies the amount of data to return with the response. Valid options are:
				// Title: title only
				// Brief: Title + Source, Subjects
				// Detailed: Brief + full abstract
				'view'           => $amount,
				// Specifies whether or not to include facets
				'includefacets'  => 'y',
				'resultsperpage' => $limit,
				'pagenumber'     => $start,
				// Specifies whether or not to include highlighting in the search results
				'highlight'      => 'y',
				'expander'       => $expander,
				//related content
				// rs = Research Starter; emp = Exact Match Placard
				'relatedcontent' => $relatedContent,
				//autosuggest 
				'autosuggest' => $autoSuggest,
				//autocorrect
				'autocorrect' => $autoCorrect,
				//Image Quick View
				'includeimagequickview' => $imageQuickView
			);
			
			$params = array_merge($params, $query);
			$params = http_build_query($params);

		$results = $api->apiSearch($params);
		
		//Cach the results for each session
		$_SESSION['results'] = $results;
	}

	// Error
	if (isset($results['error'])) {
		$error = $results['error'];
		$results =  array();
	} else {
		$error = null;
	}

	//save debug into session
	if($debug == 'y'||$debug == 'n'){
		$_SESSION['debug'] = $debug;
	}

	// Variables used in view
	$variables = array(
		'searchTerm'     => $searchTerm,
		'fieldCode'      => $fieldCode,
		'results'        => $results,
		'error'          => $error,
		'start'          => $start,
		'limit'          => $limit,
		'refineSearchUrl'=> '',
		'amount'         => $amount,
		'sortBy'         => $sortBy,
		'id'             => 'results',
		'Info'           => $Info,
		'debug'          => isset($_SESSION['debug'])? $_SESSION['debug']:''
	);

	render('results.html', 'layout.html', $variables);
?>

