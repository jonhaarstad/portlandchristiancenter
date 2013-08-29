<?php require $_SERVER['DOCUMENT_ROOT'].'/monkcms.php';
// This code block tests to see if the sermon page is the Sermon Home.
// If it is, it displays the custom output with the Featured Sermon and the offset list
// If not, it reverts to the Auto-displayed getContent

// Get current url path, minus domain
$wildcard = $_GET['wildcard'];

// Break it up into the main navigation segment and the additional filter segments
list($navSlug,$filterSelect)=split(":",$wildcard);

$filterPieces=explode("/",$filterSelect);
if($filterPieces[2] == 'preacher'){
  $filterPieces[2] = 'speaker';
}
if($filterPieces[2]){
  $titleText="Sermons by ".ucfirst($filterPieces[2]);
}
else{$titleText="Sermons";}

$pageInfo = getContent(
  "sermon",
  "display:auto",
  "howmany:1",
  "before_show_list:$titleText",
  "show_detail:__title__",
  "show_detail:||",
  "show_detail:__preview limit='100'__",
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
  <body id="sermons">
    <!--  Ministry Index -->
    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/ministry-guide.php") ?>

        <div id="container">
    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/header.php") ?>
    <div id="container-inner" class="clearfix">
        <div id="content-wrap">
          <div id="content">
<?php $headerImg = getContent(
   "section",
   "display:detail",
   "find:sermon-header-image",
   "show:__text__",
   "noecho"
   );
$showDetail = getContent(
  "sermon",
  "display:auto",
  "show_detail:showDetail",
  "noecho"
  );

if ($showDetail == "") {
  $headerImg = str_replace("<p>", "", $headerImg);
  $headerImg = str_replace("</p>", "", $headerImg);
  echo "<div id='imgHeader'>".$headerImg."<span>&nbsp;</span></div>";
}
?>

<?php
// Test to see if current page is the Sermon Home Page
if ($filterSelect == "//") {
   $sermonHome=1;
   $offset=1;
}
?>

<?php
   if ($sermonHome==1) {

$filterType = "";
getContent(
   "sermon",
   "display:list",
   "order:recent",
   "howmany:1",
   "show:<div id='latest-sermon'>",
   "show:<h3>Latest Sermon</h3>",
   "show:<ul class=\"sermon-buttons\">",
   "show:<li class=\"play\">__audioplayer__</li>",
   "show:<li class=\"dl\"><a href=\"__audio__\">Download</a></li>",
   "show:<li class=\"notes\"><a href=\"__notes__\">Study Guide</a></li>",
   "show:</ul><!-- .sermon-buttons -->",
   "show:<h4>__titlelink__</h4>",
   "show:<p class=\"sermon-meta\">__date format='M jS'__ &nbsp; ",
   "show:| &nbsp; __preacher__ &nbsp;",
   "show:| &nbsp; Scripture: __passage__",
   "show: </p>",
   "show:</div>"
   );
}

getContent(
  "sermon",
  "display:auto",
  "page:".$_GET['page'],
  "order:recent",
  "offset:$offset",
  "howmany:10",
  "before_show_list:<h2>$filterType</h2>",
  "group_show_list:<h3 class=\"sermongroup\">__titlelink__</h3>",
  "show_list:<div class=\"sermon\">",
  "show_list:<ul class=\"sermon-buttons\">",
  "show_list:<li class=\"play\">__audioplayer__</li>",
  "show_list:<li class=\"dl\"><a href=\"__audio__\">Download</a></li>",
  "show_list:<li class=\"notes\"><a href=\"__notes__\">Study Guide</a></li>",
  "show_list:</ul><!-- .sermon-buttons -->",
  "show_list:<h5>__titlelink__</h5>",
  "show_list:<p class=\"sermon-meta\">__date format='M jS Y'__ &nbsp; ",
  "show_list:| &nbsp; __preachertitlelink__ &nbsp;",
  "show_list:| &nbsp; Scripture: __passagelink__",
  "show_list: </p>",
  "show_list:</div><!-- .sermon -->",
  "after_show_list:<div class=\"paginatewrap\"><p class=\"paginate\">Page: </p> __pagination__</div>",
  "show_detail:<h2>__title__</h2>",
  "show_detail:<div id=\"sermon-wrap\">",
  "show_detail:<div id=\"sermon-info\" class=\"sermon\">",
  "show_detail:<ul class=\"sermon-buttons\">",
  "show_detail:<li class=\"play\">__audioplayer__</li>",
  "show_detail:<li class=\"dl\"><a href=\"__audio__\">Download</a></li>",
  "show_detail:<li class=\"notes\"><a href=\"__notes__\">Study Guide</a></li>",
  "show_detail:</ul><!-- .sermon-buttons -->",
  "show_detail:<p class=\"sermon-meta\">__date format='M jS Y'__ &nbsp; ",
  "show_detail:| &nbsp; __preachertitlelink__ &nbsp;",
  "show_detail:| &nbsp; Series: __seriestitlelink__",
  "show_detail: </p>",
  "show_detail:</div><!-- .sermon -->",
  "show_detail:</div><!-- #sermon-wrap -->",
  "show_detail:<div id=\"text\">",
  "show_detail:__text__",
  "show_detail:</div>"
  );

if ($sermonHome !=1) {
   echo "<p id=\"backtoarchive\"><a href=\"$navSlug/\" title=\"return to full sermon list\">&laquo; Back to All Sermons</a></p>";
}
?>
            </div> <!-- #content -->
            <div id="sidebar">

<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/sidebar_nav.php") ?>

<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/sidebar_sermons.php") ?>
          </div><!-- #sidebar -->
        </div> <!-- #content-wrap -->
        <script type="text/javascript" src="http://w.sharethis.com/button/sharethis.js#publisher=008ebced-20f1-48de-a827-895e5680b250&amp;type=website&amp;linkfg=%23594a42"></script>

      </div> <!-- #container-inner -->

<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/footer.php") ?>

    </div> <!-- #container -->

    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/scripts.php") ?>
    <script type="text/javascript" charset="utf-8">
      $(document).ready(function(){
        $("#sermonLists option[value$=/preacher/]").html('Speaker');
        $("#latest-sermon .sermon-buttons li:last").addClass("last");
        $(".sermon").each(function(){
          $(this).find(".sermon-buttons li:last").addClass("last");
        })
        $("h3.sermongroup:first").addClass("first");
      });
    </script>
  </body>
</html>