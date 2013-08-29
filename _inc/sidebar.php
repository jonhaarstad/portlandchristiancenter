<div id="sidebar">

<? getContent(
   "event",
   "display:calendar",
   "monthid:mini-cal"
   );
?>

                  <h3>Sermons</h3>
                  <ul>
                     
<? getContent(
   "sermon",
   "display:list",
   "order:recent",
   "show:<li>__titlelink__</li>"
   );
?>

                  </ul>
                  <h3>Articles</h3>
                  <ul>
                     
<? getContent(
   "article",
   "display:list",
   "order:recent",
   "show:<li>__titlelink__</li>"
   );
?>

                  </ul>
               </div> <!-- #sidebar -->