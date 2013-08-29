<?php require $_SERVER['DOCUMENT_ROOT'].'/monkcms.php'; ?>
<?php
$pageInfo = getContent(
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
    <title><?php echo $MCMS_SITENAME ?></title>

    <meta name="description" content="<?php echo $description ?>" />
    <meta name="keywords" content="portland, church, churches, oregon, christian, family, worship, contemporary, assemblies, God, assembly, kids, youth, adults, class" />

    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/head.php") ?>

    <!-- <script type="text/javascript" src="/_js/swfobject.js"></script> -->
    <!-- <script language="javascript" type="text/javascript" src="http://www.onbile.com/websites/d35a8e54dd99cf1928da0979c246bdd5"></script> -->
    <script type="text/javascript">
    //<![CDATA[
    //window.console.log(navigator.userAgent);
    if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/android/i)) || (navigator.userAgent.match(/Palm/i))) {
    if (typeof(localStorage) != 'undefined' ) {
    	try {
    	    if(localStorage.getItem("redirectmobile") != "off"){
    	        window.location = "/mobile/#Welcome";
    	    }
    	    localStorage.setItem("redirectmobile","");
    	} catch (e) {
    		if (e == QUOTA_EXCEEDED_ERR) {
    			//window.console.log("Quota Exceeded");
    		}
    	}
    }
    }
    // ]]>
    </script>
    <link href="https://plus.google.com/112486934550972064158" rel="publisher" />
  </head>
  <body id="home">
<!--  Ministry Index -->
<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/ministry-guide.php") ?>

    <div id="container">
    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/header.php") ?>
      <div id="container-inner" class="clearfix">
        <div id="content-wrap">
          <div id="content">
            <div id="billboard">
            <div id="rotator">
             <? $arry = getContent(
						"linklist",
						"display:links",
						"find:9796",
						"show:__id__",
						"show:||",
						"show:__name__",
						"show:||",
						"show:__url__",
						"show:||",
						"show:__imageurl width='700' height='446'__",
						"show:||__embed__",
						"show:~~~",
						"noecho"
						);
						$ar = explode("~~~", $arry);
						 for($i=0;$i<count($ar)-1;$i++){
            				$tarr = explode("||",$ar[$i]);
						 	if($tarr[4]){
								 echo("<div class='video' id='".$tarr[0]."'><a rel='".$tarr[4]."' href='#'><img src='".$tarr[3]."'/></a><div style='display:none;'><div class='video-popup' id='video-popup-".$tarr[0]."'>".$tarr[4]."</div></div></div>\n");
							}else{
								echo("<div><a href='".$tarr[2]."' id='".$tarr[0]."'><img src='".$tarr[3]."'/></a></div>\n");
							}
						}
					?>
            </div></div><!-- #billboard -->
            <div id="homeTabs">
              <div class="links">
                <ul>
<? getContent(
   "linklist",
   "display:links",
   "find:home-page-social-media-links",
   "show:<li class='sm-__id__'>",
   "show:<a href=\"__url__\" ",
   "show:title=\"__description__\"",
   "show:>__name__</a>",
   "show:</li>"
   );
?>
                </ul>
              </div><!-- .links -->
              <div class="latestSermon">

<?php getContent(
  "sermon",
  "display:list",
  "howmany:1",
  "order:recent",
  "show:<h5><strong>Latest Sermon: </strong>__date format='F j, Y'__</h5>",
  "show:<h4>__titlelink__</h4>"
  );
?>
              </div><!-- .latestSermon -->
              <div id="tabs">
<?php $thirdTab = getContent(
   "section",
   "display:detail",
   "label:Third Tab",
   "find:".$_GET['nav'],
   "show:__title__",
   "show:||",
   "show:__text__",
   "noecho"
   );

list($thirdTabTitle,$thirdTabText) = explode("||", $thirdTab);

?>

              	<ul>
              		<li><a href="#tabs-1">News &amp; Events</a></li>
              		<!--<li><a href="#tabs-2">Pastor Ray's Blog</a></li> -->
<?php if($thirdTabTitle){
	echo "<li class='newsletter'><a href='#tabs-3'>Email Newsletter Signup</a></li>";
  /* echo "<li><a href='#tabs-3'>$thirdTabTitle</a></li>"; */
}?>
              	</ul>
              	<div id="tabs-1">
<?php getContent(
  "article",
  "display:list",
  "howmany:2",
  "order:recent",
  "show:<div class=\"articleBox\">",
  "show:\n<h3>__titlelink__</h3>",
  "show:\n<p class=\"meta\">Posted: __date format='F j'__</p>",
  "show:\n<p class=\"content\">__preview limit='280'__</p>",
  "show:\n</div>"
  );
?>
<? getContent(
   "linklist",
   "display:links",
   "find:home-page-view-all-news--events",
   "show:<a href=\"__url__\"",
   "show: title=\"__description__\"",
   "show: class=\"moreNews\">",
   "show:__name__</a>"
   );
?>
              	</div><!-- #tabs-1 -->
              	<div id="tabs-2">
<?php
 $readMore = getContent(
  "blog",
  "display:list",
  "howmany:1",
  "order:recent",
  "name:pastor-rays-blog",
  "show_postlist:\" <a class='more' href='__blogtitlelink__' title='Read more about __blogposttitle__'>read more...</a>\"",
  "noecho"
  );

$blogContent = getContent(
  "blog",
  "display:list",
  "howmany:1",
  "order:recent",
  "name:pastor-rays-blog",
  "show_postlist:<div class=\"articleBox\">",
  "show_postlist:\n<h3><a href=\"__blogtitlelink__\" title=\"Read more about __blogposttitle__\">__blogposttitle__</a></h3>",
  "show_postlist:\n<p class=\"meta\">Posted: __blogpostdate format='F j'__</p>",
  "show_postlist:\n__blogsummary__",
  "show_postlist:\n</div>",
  "noecho"
  );

echo $blogContent;
?>
              </div><!-- #tabs-2 -->
<?php if($thirdTabText){
	echo "<div id='tabs-3'><div class='tabBox'><h3 style='margin-top:25px;'>Signup to Receive Our Email Updates</h3>
	<p>We send out a monthly update we call <strong>The PCCToday</strong>.  We also use this to send you important updates that affect our entire church which includes everything from weather updates to all-church opportunities.</p>
	<h4 style='line-height:120%;'>Enter your email to sign up for our Email Newsletter!</h4>

	<p><link href='http://cdn-images.mailchimp.com/embedcode/slim-081711.css' rel='stylesheet\' type='text/css' /></p>
<style type='text/css'><!--
#mc_embed_signup {background:transparent;display:inline-block;width:450px;position:relative; }
	/* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
	   We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
--></style>
<div id='mc_embed_signup'><form action='http://pcctoday.us5.list-manage.com/subscribe/post?u=b3cdcaa5928a315764e7cb1c8&amp;id=5211409af0' method='post' id='mc-embedded-subscribe-form' class='validate'><label for='mce-EMAIL'>Subscribe to our mailing list</label> <input type='email' value='' name='EMAIL' id='mce-EMAIL' placeholder='email address' required='' /> <input type='submit' value='Subscribe' name='subscribe' id='mc-embedded-subscribe' class='button' /></form></div>
<p>&nbsp;</p>
<p><a href='http://pcctoday.com/extras/pcctoday-newsletter/' target='_blank'>Click here to visit the official PCCToday News page.</a></div></div>";
  /* echo "<div id='tabs-3'><div class='tabBox'>$thirdTabText</div></div>"; */
}?>
              </div><!-- #tabs -->
              <div id="upcomingEvents">
                <h4>Upcoming Events</h4>

<?php getContent(
  "event",
  "display:list",
  "howmany:5",
  "features:yes",
  "show:<div class=\"event\">",
  "show:\n<div class=\"date\">",
  "show:\n<p class=\"month\">__eventstart format='M'__</p>",
  "show:\n<p class=\"day\">__startday__</p>",
  "show:\n</div>",
  "show:<div class=\"details\">",
  "show:<h5><a href=\"__url__\" title=\"Find out more about __title__\">__title__</a></h5>",
  "show:<p>__eventstartTwo format='l, F jS'__</p>",
  "show:<p>__eventstartThree format='g:i A'__ [ <a href=\"__url__\" title=\"Find out More about __title__\">more info</a> ]</p>",
  "show:</div>",
  "show:</div>"
  );
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

              </div><!-- #upcomingEvents -->
            </div><!-- #homeTabs -->
          </div> <!-- #content -->

          <div id="sidebar">
<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/sidebar_ads.php") ?>

            <div class="quickLinks">
              <ul>
<? getContent(
   "linklist",
   "display:links",
   "find:home-page-sidebar-links",
   "show:<li>",
   "show:<a href=\"__url__\"",
   "show: title=\"__name__\"",
   "show: class=\"link-__id__\">__name__</a>",
   "show:</li>"
   );
?>
              </ul>
            </div>
<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/sidebar_times.php") ?>

<?php /*?>
	<!-- REMOVED TO PUT SCHOOL LINK ON ALL PAGES IN THE INCLUDE FILES
            <div class="sep"></div>
            <div class="schools">
            <h3>Schools</h3>
getContent(
   "section",
   "display:detail",
   "find:school-info",
   "show:__text__"
   );

-->
<?php */?>
            </div>

          </div>

        </div> <!-- #content-wrap -->
      </div> <!-- #container-inner -->

    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/footer.php") ?>

    </div> <!-- #container -->

    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/scripts.php") ?>
    <link rel="stylesheet" type="text/css" href="/_css/colorbox.css" />
    <script src="/_js/jquery.colorbox-min.js"></script>
    <script src="/_js/jquery.cycle.min.js"></script>
<script type="text/javascript">
  $(function() {
  $('#rotator').after('<div id="rotatorbtns">').cycle({
        fx:     'fade',
        speed:  'slow',
        timeout: 6000,
        pager:  '#rotatorbtns'
    });
	$(".video").click(function(){
       var embed_code = $(this).find("a").attr("rel");
       var h_i = embed_code.indexOf("height");
       var w_i = embed_code.indexOf("width");
       var s_height = embed_code.substring(h_i + 8, h_i + 11);
       var s_width = embed_code.substring(w_i + 7, w_i + 10);

       var video_id = $(this).attr("id");
       var target_id = "#video-popup-" + video_id;
       $(target_id).css("height", s_height + "px");
       $(target_id).css("width", s_width + "px");

       var box_width = parseInt(s_width, 10) + 20;
       var box_width_string = box_width + "px";

       $(this).colorbox({width:box_width_string, inline:true, href:target_id});
     });
    $("#tabs").tabs();
    $('#upcomingEvents div.event:odd').addClass('alt');
    $('#upcomingEvents div.event:last').addClass('last');
    $(".schools a").each(function(){
      $(this).addClass("schoolMore");
    });

    readMore = <?php echo $readMore; ?>;

    $("#tabs-2 .articleBox p:last").append(readMore);
  });
</script>


  </body>
</html>