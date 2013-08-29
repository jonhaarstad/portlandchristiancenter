<? require($_SERVER["DOCUMENT_ROOT"]."/monkcms.php"); 

header('Content-type: application/json');

$outarray; 
$nodes;
$id;
$json;

$id = $_GET['id'];

$string = getContent(  
		"linklist",
        "display:links", 
        "find:".$id,
        //"howmany:20",  
		"show:__name__",
		"show:||",
		"show:__imageurl__",
		"show:||",
		"show:__url__",
		"show:||",
		"show:__description__",
		"show:||", 
		"show:__slug__",
		"show:~~",
		"noecho"
	); 
	 
  
	
	$prearray = explode("~~",$string);
	
	for ($i=0; $i <count($prearray)-1; $i++) { 
    	
		 $outarray[$i] = explode("||",$prearray[$i]);
    }
      $i = 0;
	foreach ($outarray as $key => $value) {
	     $nodes[$i] = array(
	         
		    title => $value[0],
		    image => $value[1],
		    url => $value[2],
		    text => $value[3],
		    slug => $value[4]
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