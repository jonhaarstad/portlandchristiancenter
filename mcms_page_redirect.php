<?
   require($_SERVER["DOCUMENT_ROOT"]."/monkcms.php");
   $dest=getContent("page", "find:".$_GET['nav'], "show:__description__", "noecho");
   header("Location:".$dest);
?>