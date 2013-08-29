<? require($_SERVER["DOCUMENT_ROOT"]."/monkcms.php"); 

$outarray; 
$nodes;
$json;
$output;


$string = getContent(  
		"gallery",
        "display:list",
        "order:recent",   
		"show_galleries:__title__",
		"show_galleries:||",
		"show_galleries:__promoimageurl width='50' height='50'__",
		"show_galleries:||",
		"show_galleries:__description__",
		"show_galleries:||",
		"show_galleries:__postdate format='M d Y'__",
		"show_galleries:||",
		"show_galleries:__numberofimages__",
		"show_galleries:||",
		"show_galleries:__galleryurl__",
		"show_galleries:~~",
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
		    thmb => $value[1],
		    preview => $value[2],
		    date => $value[3],
		    number => $value[4],
		    slug => str_replace("/","",$value[5])
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