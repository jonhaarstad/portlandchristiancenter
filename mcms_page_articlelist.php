<?php require $_SERVER['DOCUMENT_ROOT'].'/monkcms.php'; ?>
<?php $pageInfo = getContent(
  "page",
  "find:".$_GET['nav'],
  "show:__title__",
  "show:||",
  "show:__description__",
  "noecho"
  );
list($title,$description) = explode("||", $pageInfo);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title><?php echo $title ." | ". $MCMS_SITENAME  ?></title>
    <meta name="description" content="<?php echo $description; ?>" />
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

<? getContent(
   "page",
   "find:".$_GET['nav'],
   /* "show:<h2>__title__</h2>", */
   "show:<div id=\"text\">__text__</div>"
   );
?>
                  <div id="latest-sermon">
                     <h3>Latest Article</h3>

<?php getContent(
   "article",
   "display:list",
   "order:recent",
   "hide_category:staff,elders",
   "howmany:1",
   "show:<h4>__titlelink__</h4>",
   "show:<p class=\"sermon-meta\">Posted __date format='M jS, Y'__ &nbsp; ",
   "show:| &nbsp; <a href='/articles-by-__authorslug__/' title='View All Articles by __author__'>__author__</a> &nbsp;",
   "show:| &nbsp; <strong>Series:</strong> __series__ &nbsp;",
   "show: </p>"
   );
?>

                  </div><!-- #latest-sermon -->
                  <h4>More Recent Articles</h4>
<?php getContent(
   "article",
   "display:list",
   "order:recent",
   "offset:1",
   "howmany:8",
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

                  </div><!-- #sermonlist -->

       </div> <!-- #content -->

       <div id="sidebar">

<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/sidebar_nav.php") ?>

          <div class="sep"></div>

<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/sidebar_times.php") ?>
          
       </div>

      </div> <!-- #content-wrap -->
      <script type="text/javascript" src="http://w.sharethis.com/button/sharethis.js#publisher=008ebced-20f1-48de-a827-895e5680b250&amp;type=website&amp;linkfg=%23594a42"></script>

    </div> <!-- #container-inner -->
  
  <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/footer.php") ?>
  
  </div> <!-- #container -->

  <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/scripts.php") ?>
</body>
</html>