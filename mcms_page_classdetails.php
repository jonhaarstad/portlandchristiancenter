<?php require $_SERVER['DOCUMENT_ROOT'].'/monkcms.php'; ?>
<?php $pageInfo = getContent(
  "page",
  "display:detail",
  "find:".$_GET['nav'],
  "show:__title__",
  "show:~|",
  "show:__description__",
  "show:~|",
  "show:__groupslug__",
  "noecho"
  );

list($title,$description,$groupSlug) = explode("~|", $pageInfo);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
  	<title><?php echo $title ." | ". $MCMS_SITENAME  ?></title>
    <meta name="description" content="<?php echo $description; ?>" />
    <meta name="keywords" content="<?php echo $keywords; ?>" />
    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/head.php") ?> 
       
  </head>
  <body>
<!--  Ministry Index -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/ministry-guide.php") ?>

    <div id="container">
    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/header.php") ?>
      <div id="container-inner" class="clearfix">
        <div id="content-wrap">
          <div id="content">

<?php
$headerImg = getContent(
  "media",
  "display:detail",
  "label:header",
  "find:".$_GET['nav'],
  "show:<div id='imgHeader'><img src='__imageurl__' alt='__name__' /><span>&nbsp;</span></div>",
  "noecho"
  );
  
$headerRotate = getContent(
   "section",
   "display:detail",
   "label:Header Rotator",
   "find:".$_GET['nav'],
   "show:__text__",
   "noecho"
   );

if ($headerRotate != "") {
  $headerRotate = str_replace("<p>", "", $headerRotate);
  $headerRotate = str_replace("</p>", "", $headerRotate);
  echo "<div id='imgHeader'><div class='rotator'>".$headerRotate."</div><span>&nbsp;</span></div>";
}
else{
  echo $headerImg;
}
?>

<?php getContent(
  "page",
  "display:detail",
  "find:".$_GET['nav'],
  /* "show:<h2>__title__</h2>", */
  "show:__text__"
  );
?>

          </div> <!-- #content -->
          <div id="sidebar">

<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/sidebar_nav.php") ?>

            <div class="sep"></div>

<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/sidebar_times.php") ?>
            
          </div> <!-- #sidebar -->
        </div> <!-- #content-wrap -->
        <script type="text/javascript" src="http://w.sharethis.com/button/sharethis.js#publisher=008ebced-20f1-48de-a827-895e5680b250&amp;type=website&amp;linkfg=%23594a42"></script>

      </div> <!-- #container-inner -->
    
    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/footer.php") ?>
    
    </div> <!-- #container -->
  
    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/scripts.php") ?>
    <script type="text/javascript" src="/_js/jquery.innerfade.js"></script>
    <script type="text/javascript">
    $(document).ready(
          function(){
             $('#imgHeader div.rotator').innerfade({
                speed: 'slow',
                timeout: 4000,
                type: 'random_start',
                containerheight: "202px"
             });
          });
    </script>
  </body>
</html>