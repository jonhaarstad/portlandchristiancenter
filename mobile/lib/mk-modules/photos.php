<? require($_SERVER["DOCUMENT_ROOT"]."/monkcms.php"); 

$outarray; 
$nodes;
$id;
$json;
$output;

$id = $_GET['galleryid'];

$string = getContent(  
		"gallery",
        "display:list",
        "find_gallery:".$id,
        "order:recent",   
		"show_gallery:__title__",
		"show_gallery:||",
		"show_gallery:__description__",
		"show_gallery:||", 
		"show_gallery:__imageurl2 width='640'__",
		"show_gallery:||",
		"show_gallery:__imageurl3 width='100' height='100'__",
		"show_gallery:||",
		"show_gallery:__numberofimages__",
		"show_gallery:~~",
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
		    description => $value[1],
		    photo => $value[2],
		    thumb => $value[3],
		    number => $value[4]
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