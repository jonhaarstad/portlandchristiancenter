<!-- Begin Header -->
<ul id="skip" title="Accessibility options">
  <li><a href="#content">Skip to Content</a></li>
  <li><a href="#sidebar">Skip to Sidebar</a></li>
  <li><a href="#nav">Skip to Navigation</a></li>
</ul> <!-- #skip -->

<hr />
         
<div id="header" class="clearfix">
  <h1><a href="/"><?=$MCMS_SITENAME ?></a></h1>
  <p class="homeLink"><a href="/">Home</a></p>
  <div id="top_nav">
    <ul id="quick_menu">
      <li id="ministry"><a href="#">Quick Links</a></li>
<?php getContent(
   "linklist",
   "display:links",
   "find:metanav-links",
   "show:<li>",
   "show:<a href=\"__url__\" ",
   "show:title=\"__description__\"",
   "show:>__name__</a>",
   "show:</li>"
   );
?>      
      <li class="last">
<? getContent(
   "linklist",
   "display:links",
   "find:login-link",
   "show:<a href=\"__url__\" ",
   "show:title=\"__description__\"",
   "show:>__name__</a>"
   );
?>        
      </li>
    </ul>
    <div class="search">
      <form action="/search-results/" method="get" id="searchForm">
        <fieldset>
          <input type="text" id="search_term" name="keywords" value="search" class="clearClick" />
          <a id="search_go" href="#" class="mcmsSearch">Go</a>
          <input type="hidden" name="show_results" value="N%3B" /> 
        </fieldset>
      </form>
    </div>
  </div>
<?php include($_SERVER["DOCUMENT_ROOT"]."/_inc/nav.php") ?>
</div><!-- end #header -->
<!-- End Header -->

