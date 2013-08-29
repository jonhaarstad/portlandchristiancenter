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
  <body>
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

<?php getContent(
  "page",
  "display:detail",
  "find:".$_GET['nav'],
  "show:<h2>__title__</h2>",
  "show:__text__"
  );
?>
            <div id="calendar-outer">

<?php getContent(
   "event",
   "display:calendar",
   "enablepast:yes",
   "numberOfMonths:12",
   "recurring:yes",
   "eventTitles:inDay",
   "nextPrev:<img src=\"/_img/bigcal_next.png\" alt=\"next\" />, <img src=\"/_img/bigcal_previous.png\" alt=\"previous\" />",
   "headingletters:3"
   );
?>

            </div><!-- #calendar-outer -->

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