<?php require $_SERVER['DOCUMENT_ROOT'].'/monkcms.php'; ?>
<?php $pageInfo = getContent(
  "event",
  "display:detail",
  "find:".$_GET['slug'],
  "show:__title__",
  "show:||",
  "show:__description__",
  "noecho"
  );
list($title,$description) = explode("||", $pageInfo);
$description = strip_tags($description);
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
  "display:detail",
  "find:".$_GET['slug'],
  "show:<div id='imgHeader'><img src='__imageurl__' alt='__title__ img' /><span>&nbsp;</span></div>"
  );
?>            

<? getContent(
 "event",
 "display:detail",
 "find:".$_GET['slug'],
 "show:<h2>__title__</h2>",
 "show:<p class=\"eventtime\">__eventtimes__</p>",
 "show:<p class=\"eventDetails\"><b>Location:</b> __location__, <a href=\"http://maps.google.com/maps?q=__fulladdress__\" title=\"map it\">__fulladdress__</a>",
 "show:<br/><b>Group:</b> __group__",
 "show:<br/><b>Cost:</b> __cost__",
 "show:<br/><b>Attendance Limit:</b> __attendance__",
 "show:<br/><b>Website:</b> <a href=\"__website__\" title=\"Visit event site\">__website__</a>",
 "show:</p>",
 "show:<p><b>Coordinator:</b> __coordname__",
 "show:, __coordphone__",
 "show: (__coordemail__)",
 "show:</p><!-- __coordname__ -->",
 "show:<div id=\"text\">__description__</div>",
 "show:<p>__registration__</p>"
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