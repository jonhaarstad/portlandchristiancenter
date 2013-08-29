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
    <title><?php echo $MCMS_SITENAME ." | ". $title ?></title>
    <meta name="description" content="<?php echo $description; ?>" />
    <meta name="keywords" content="" />
    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/head.php") ?>
  </head>
  <body id="sermons">
    <div id="container">
    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/header.php") ?>
      <div id="container-inner">
        <div id="content-wrap">
          <div id="content">

            <h2>Test Sermon 1</h2>
              <ul id="byline">
                <li id="bl_passage">Genesis 1:1</li>
                <li id="bl_preacher"><a href="#">Test Preacher</a></li>
                <li id="bl_date">Testmonth 12, 2009</li>
                <li id="bl_series">Series: <a href="#">Test Series</a></li>
                <li id="bl_category">Categories: <a href="#">Test Category</a></li>
              </ul>

              <ul id="mediabox">
                 <li id="mb_listen"><a href="#">Listen</a></li>
                 <li id="mb_download"><a href="#">Download</a></li>
                 <li id="mb_video"><a href="#">Video</a></li>
                 <li id="mb_notes"><a href="#">Notes</a></li>
              </ul>

            <div id="text">
              <p>Test Sermon 1 Content</p>
            </div>
          
          </div> <!-- #content -->
          <div id="sidebar">

<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/sidebar_nav.php") ?>

<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/sidebar_sermons.php") ?>

          </div> <!-- #sidebar -->
        </div> <!-- #content-wrap -->
      </div> <!-- #container-inner -->
    
    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/footer.php") ?>
    
    </div> <!-- #container -->
  
    <?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/scripts.php") ?>
  </body>
</html>