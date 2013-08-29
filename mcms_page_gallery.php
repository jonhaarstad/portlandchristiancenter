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

    <link rel="stylesheet" type="text/css" href="/_css/prettyPhoto.css" title="default" />

    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/scripts.php") ?>
    <script type="text/javascript" src="/_js/jquery.prettyPhoto.js"></script>
    <script type="text/javascript" charset="utf-8">
    		$(document).ready(function(){
    			$("a[rel^='prettyPhoto']").prettyPhoto({
    				animationSpeed: 'normal', /* fast/slow/normal */
    				padding: 40, /* padding for each side of the picture */
    				opacity: 0.35, /* Value betwee 0 and 1 */
    				showTitle: false, /* true/false */
    				allowresize: true, /* true/false */
    				counter_separator_label: 'of ', /* The separator for the gallery counter 1 "of" 2 */
    				theme: 'dark_rounded', /* light_rounded / dark_rounded / light_square / dark_square */
    				callback: function(){}
    			});
    		});
    	</script>
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

echo $headerImg;
?>

<?php $galleryView = getContent(
  "gallery",
  "before_show_gallery:true",
  "noecho"
  );
?>

<? if ($galleryView == '') {
getContent(
   "page",
   "find:".$_GET['nav'],
   "show:<h2>__title__</h2>",
   "show:<div id=\"text\">__text__</div>"
   );
}
?>

                <div id="prettyPhoto">

<? getContent( // this gallery is using the "galleria" plugin for jquery and doesn't need "show_photo"
   "gallery",
   "order:recent",
   "show_galleries:<div class=\"gallery clearfix\">",
   "show_galleries:<a href=\"__galleryurl__\" class=\"thumb\"><img src=\"__promoimageurl__\" alt=\"__title__\" width=\"125\" /></a>",
   "show_galleries:<h3>__titlelink__</h3>",
   "show_galleries:<p><i>Posted on __postdate format='m/d/y'__</i></p>",
   "show_galleries:</div>",
   "before_show_gallery:<h2>__title__</h2>",
   "before_show_gallery:<p class=\"meta\">__description__</p>",
   "before_show_gallery:<p class=\"home-link\"><a href=\"../\">Photo Galleries Home</a></p>",
   "before_show_gallery:<ul class=\"gallery\">",
   "show_gallery:<li>",
   "show_gallery:<a href=\"__imageurl__\" rel=\"prettyPhoto[gallery]\"",
   "show_gallery: title=\"__description__\"",
   "show_gallery:>",
   "show_gallery:<img src=\"__imageurl width='118'__\" width=\"118\"",
   "show_gallery: alt=\"__title__\"",
   "show_gallery: /></a></li>",
   "after_show_gallery:</ul>"
   );
?>

            </div><!-- #prettyPhoto -->
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

  </body>
</html>