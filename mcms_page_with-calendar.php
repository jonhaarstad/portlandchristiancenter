<?php require $_SERVER['DOCUMENT_ROOT'].'/monkcms.php'; ?>
<?php $pageInfo = getContent(
  "page",
  "display:detail",
  "find:".$_GET['nav'],
  "show:__title__",
  "show:||",
  "show:__description__",
  "show:||",
  "show:__groupslug__",
  "noecho"
  );

list($title,$description,$groupSlug) = explode("||", $pageInfo);

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
  <body id="miniCalendar">
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
            <div id="text">
            <div id="insetCalendar">
              <div class="header">
                <h4>Upcoming Events</h4>
                <h5>Here are some Upcoming Events</h5>
                <div class="today">
<? getContent(
   "linklist",
   "display:links",
   "find:view-full-calendar",
   "show:<a href=\"__url__\" ",
   "show:title=\"__description__\"",
   "show:>__name__</a>"
   );
?>
                </div>
              </div>

<?php $eventCalendar = getContent(
  "event",
  "display:list",
  "howmany:4",
  "order:recent",
  "find_group:$groupSlug",
  "show:<div class=\"event\">",
  "show:\n<div class=\"date\">",
  "show:\n<p class=\"month\">__eventstart format='M'__</p>",
  "show:\n<p class=\"day\">__startday__</p>",
  "show:\n</div>",
  "show:<div class=\"details\">",
  "show:<h5><a href=\"__url__\" title=\"Find out more about __title__\">__title__</a></h5>",
  "show:<p>__eventstartTwo format='l, F dS'__</p>",
  "show:<p>__eventstartThree format='g:i A'__ [ <a href=\"__url__\" title=\"Find out More about __title__\">more info</a> ]</p>",
  "show:</div>",
  "show:</div>",
  "noecho"
  );

if ($eventCalendar) {
  echo $eventCalendar;
}
else{
  print "<div class='event'><p class='message'>There are no upcoming events for this ministry.  View the full calendar for a list of all events.</p></div>";
}
?>
<? getContent(
   "linklist",
   "display:links",
   "find:view-full-calendar",
   "show:<a href=\"__url__\"",
   "show: title=\"__description__\"",
   "show: class=\"moreEvents\">",
   "show:__name__</a>"
   );
?>
<? $extraLinks = getContent(
   "section",
   "display:detail",
   "emailencode:no",
   "label:Extra Links",
   "find:".$_GET['nav'],
   "show:__text__",
   "noecho"
   );

$extraLinks = str_replace("<p>", "", $extraLinks);
$extraLinks = str_replace("</p>", "", $extraLinks);
$extraLinks = str_replace("<ul>", "<ul id='extraLinks'>", $extraLinks);
if (strstr($extraLinks, "</a><br />")) {
  // I really need to learn some regex
  $extraLinks = str_replace("</a><br />", "<span>", $extraLinks);
  $extraLinks = str_replace("</li>", "</span></a></li>", $extraLinks);
  $extraLinks = str_replace("</a></span></a>", "</a>", $extraLinks);
}

echo $extraLinks;

?>

            </div><!-- #insetCal -->

<?php getContent(
  "page",
  "display:detail",
  "find:".$_GET['nav'],
  /* "show:<h2>__title__</h2>", */
  "show:__text__"
  );
?>
          </div><!-- #text -->
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
  $(document).ready(function(){
    $('#imgHeader div.rotator').innerfade({
      speed: 'slow',
      timeout: 4000,
      type: 'random_start',
      containerheight: "202px"
    });
    $(".details h5 a").each(function(){
      if ($(this).text().length > 28) {
        newTitle = ($(this).text()).substr(0, 25);
        $(this).text(newTitle + "...");
      }
    });
  });
</script>

  </body>
</html>