<?php require $_SERVER['DOCUMENT_ROOT'].'/monkcms.php'; ?>
<?php $title = getContent(
  "event",
  "display:list",
  "enablepast:yes",
  "year:".$_GET['year'],
  "month:".$_GET['month'],
  "day:".$_GET['day'],
  "beforeshow:All Events for __date format='F j, Y'__",
  "noecho"
  );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title><?php echo $MCMS_SITENAME ." | ". $title ?></title>
    <meta name="description" content="" />
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
"event",
"display:list",
"enablepast:yes",
"year:".$_GET['year'],
"month:".$_GET['month'],
"day:".$_GET['day'],
"beforeshow:<h2>All Events for __date format='F j, Y'__</h2>",
"before_show:<div class=\"text\">",
"show:<div class=\"post\">\n\t<h3><a href=\"__url__\" title=\"click for more info on __event__\">__event__</a></h3>",
"show:\t<p class=\"eventtime\">__eventtimes__</p>",
"show:\t<p>__description__</p>",
"show:</div>",
"after_show:</div>"
);
?>
        </div> <!-- #content -->

        <div id="sidebar">

<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/sidebar_nav-events.php") ?>

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
    // sidebar titles
    sbTitle = "News & Events";
    sbClass = sbTitle.toLowerCase().split(" ");
    $("#sidebar > h3").addClass(sbClass[0]).text(sbTitle);
    $('#nav li#nav_news--events').addClass("current");
    $('#subNav li#subNav_news--events_calendar').addClass("current");
  });
</script>
</body>
</html>