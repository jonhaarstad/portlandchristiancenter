<?php require $_SERVER['DOCUMENT_ROOT'].'/monkcms.php'; ?>
<?php $pageInfo = getContent(
  "article",
  "display:detail",
  "find:".$_GET['slug'],
  "show:__title__",
  "show:||",
  "show:__preview limit='100'__",
  "noecho"
  );
list($title,$description) = explode("||", $pageInfo);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title><?php echo $MCMS_SITENAME ." | ". $title ?></title>
    <meta name="description" content="<?php echo $description; ?>" />
    <meta name="keywords" content="" />
    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/head.php") ?>
  </head>
  <body id="article">
<!--  Ministry Index -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/ministry-guide.php") ?>

    <div id="container">
    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/header.php") ?>
    <div id="container-inner" class="clearfix">
        <div id="content-wrap">

          <div id="content">
<?php getContent(
  "article",
  "display:detail",
  "find:".$_GET['slug'],
  "show:<div id='imgHeader'><img src='__imageurl__' alt='__title__ img' /><span>&nbsp;</span></div>"
  );
?>            
            

<? getContent(
   "article",
   "display:detail",
   "find:".$_GET['sermonslug'],
   "show:<h2>__title__</h2>",
   "show:<div id=\"sermon-info\" class=\"sermon\">",
   "show:<ul class=\"sermon-buttons\">",
   "show:<li class=\"play\">__audioplayer__</li>",
   "show:<li class=\"dl\"><a href=\"__audio__\">Download</a></li>",
   "show:<li class=\"notes\"><a href=\"__notes__\">Study Guide</a></li>",
   "show:</ul><!-- .sermon-buttons -->",
   "show:<p class=\"sermon-meta\">Posted __date format='M jS, Y'__ &nbsp; ",
   "show:| &nbsp; by <a href='/articles-by-__authorslug__/' title='View All Articles by __author__'>__author__</a> &nbsp;",
   "show:| &nbsp; Series: __series__",
   "show: </p>",
   "show:</div><!-- .sermon -->",
   "show:<div id=\"text\">__text__</div>"
   )
?>

          </div> <!-- #content -->
          <div id="sidebar">

<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/sidebar_nav-article.php") ?>

            <div class="sep"></div>

<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/sidebar_times.php") ?>
            
          </div> <!-- #sidebar -->
        </div> <!-- #content-wrap -->
        <script type="text/javascript" src="http://w.sharethis.com/button/sharethis.js#publisher=008ebced-20f1-48de-a827-895e5680b250&amp;type=website&amp;linkfg=%23594a42"></script>

      </div> <!-- #container-inner -->
    
    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/footer.php") ?>
    
    </div> <!-- #container -->
  
    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/scripts.php") ?>
<script type="text/javascript" charset="utf-8">
  $(document).ready(function() {
    $('#nav li#nav_media--resources').addClass("current");
    $('#subNav li#subNav_media--resources_articles').addClass("current");

    var mb = $('#mediabox li').length;
    if (mb == '0') {$("#mediabox").hide();}

  });
</script>
    
  </body>
</html>