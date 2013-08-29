  <div class="sermonSB">
    <p id="side-podcastlink">
      <a href="http://itunes.apple.com/us/podcast/portland-christian-center/id97913180">Podcast Link</a>
    </p>
  <div id="sermonsort">
    <p id="filter">List by:
<?php getContent(
  "sermon",
  "display:selector",
  "select:__month__ __category__ __series__ __preacher__ __passage__"
  );
?>
    </p>
  </div><!-- #sermonsort -->

<?php
if ($filterPieces[2] == ('series' || 'preacher' || 'category' || 'passage')) {
  getContent(
  "sermon",
  "display:list",
  "groupby:auto"
  );
}
else{
  getContent(
  "sermon",
  "display:list",
  "groupby:month"
  );
}
?>
  </div><!-- .sermonSB -->