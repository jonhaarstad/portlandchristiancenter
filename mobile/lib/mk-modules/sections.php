<? require($_SERVER["DOCUMENT_ROOT"]."/monkcms.php"); 

$id; 
//$output;
//$node;
//$json;

$id = $_GET['id'];
$text = getContent(
		"section",
		"display:detail",
		"find:".$id,
		"emailencode:no",                     
		"show:__text__",
    "noecho"
	);
  
echo $text;  
	 
 ?>      
      