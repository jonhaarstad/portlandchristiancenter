<? require($_SERVER["DOCUMENT_ROOT"]."/monkcms.php"); 

$outarray; 
$nodes;
$category;
$series;
$group;
$json;

$category = $_GET['category'];
$series = $_GET['series'];
$group = $_GET['group']; 

$string = getContent(  
		"sermons",
        "display:list",
        "order:recent", 
        "find_category:".$category,
        "find_series:".$series,
        "find_group:".$group,
        "howmany:25",  
		"show:__title__",
		"show:||",
		"show:__imageurl width='320' height='150'__",
		"show:||",
		"show:__preview limit='200'__",
		"show:||",
		"show:__preacher__",
		"show:||",
		"show:__date format='M d Y'__",
		"show:||",
		"show:__summary__",
		"show:||",
		"show:__slug__",
		"show:||",
		"show:__audiourl__",
		"show:||",
		"show:__videourl__",
		"show:||",
		"show:__videoembed__",
		"show:||",
		"show:__imageurl2 width='50' height='50'__",
		"show:||",
		"show:__text__",
		"show:~~",
		"noecho"
	); 
	 
   //echo($string);
	
	$prearray = explode("~~",$string);
	
	for ($i=0; $i <count($prearray)-1; $i++) { 
    	
		 $outarray[$i] = explode("||",$prearray[$i]);
    }
    
   /* function getAudio($url){
        $a = preg_match('@(url=http?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\S+(mp3|aac|wav|m4a))?)?)?)@', $url,$matches); 
		$audio = str_replace("url=","",$matches[0]);
		return $audio; 
    }
    
    function getVideo($url){
         $v = preg_match('@(url=http?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\S+(mov|mp4|m4v))?)?)?)@', $url,$matches); 
    	 $video = str_replace("url=","",$matches[0]);
    	 return $video;
    }*/
    
    $i = 0;
	foreach ($outarray as $key => $value) {
	
	     preg_match_all('/(src)=("[^"]*")/i',$value[9],$parts);
	     $type;
	     $media_id = substr($parts[0][0],5,-1);
	     $posyt = strpos($parts[0][0],"youtube");
	     $posvm = strpos($parts[0][0],"vimeo");
	       //$iframe[0][2][0] gets src value from iframe
	     if($posyt !==false){
	         $type = "youtube";
	     }else if($posvm !==false){
	         $type = "vimeo";
	     }
	     
	     $nodes[$i] = array(
	         
		    title => $value[0],
		    image => $value[1],
		    preview => $value[2],
		    author => $value[3],
		    date => $value[4],
		    summary => $value[5],
		    slug => $value[6],
		    audio => $value[7],
		    video => $value[8],
		    embed => array(
		            type => $type,
		            media_id => $media_id,
		            code => $value[9]
		        ), 
		    thmb => $value[10],
		    text => $value[11]
		    );
		    $i++;
	}
	
	$output = array( items => $nodes);
	
	//$output = array("items" => $nodes);
	
	$json = json_encode($output);
	//print_r($articles); 
	
	 $callback = $_REQUSEST['callback'];
	 
	 if($callback){
	     header('Content-type: text/javascript');
	     echo $callback . '(' . $json . ');';
	 }else{
	     header('Content-type: application/json');
	     echo $json; 
	 }
	
?>