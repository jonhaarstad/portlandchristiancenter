<?php require $_SERVER['DOCUMENT_ROOT'].'/monkcms.php'; ?>
<?php $title = getContent(
  "article",
  "display:list",
  "howmany:1",
  "find_author:".$_GET['authorslug'],
  "show:__author__",
  "noecho"
  );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title><?php echo $title ." | ". $MCMS_SITENAME  ?></title>
    <meta name="description" content="All articles by <?php echo $title; ?>" />
    <meta name="keywords" content="" />
    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/head.php") ?>
  </head>
<body id="articlelist">
<!--  Ministry Index -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/ministry-guide.php") ?>

  <div id="container">
  <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/header.php") ?>
  <div id="container-inner" class="clearfix">
      <div id="content-wrap">
        <div id="content">
<?php getContent(
  "media",
  "display:detail",
  "label:header",
  "find:".$_GET['nav'],
  "show:<div id='imgHeader'><img src='__imageurl__' alt='__name__' /><span>&nbsp;</span></div>"
  );
?>
<div id="sermonlist">
<h2>All Articles by <?php echo $title; ?></h2>
<?php getContent(
   "article",
   "display:list",
   "order:recent",
   "howmany:8",
   "find_author:".$_GET['authorslug'],
   "show:<div class=\"sermon\">",
   "show:<h5>__titlelink__</h5>",
   "show:<p class=\"sermon-meta\">Posted __date format='M jS, Y'__ &nbsp; ",
   "show:| &nbsp; <a href='/articles-by-__authorslug__/' title='View All Articles by __author__'>__author__</a> &nbsp;",
   "show:| &nbsp; <strong>Series:</strong> __series__ &nbsp;",
   "show: </p>",
   "show:</div>",
   "after_show_list:<div class=\"paginatewrap\"><p class=\"paginate\">Page: </p> __pagination__</div>"
   );
?>
</div>
       </div> <!-- #content -->

       <div id="sidebar">

<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/sidebar_nav-article.php") ?>

          <div class="sep"></div>

<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/sidebar_times.php") ?>
          
       </div>

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
  });
</script>
</body>
</html>