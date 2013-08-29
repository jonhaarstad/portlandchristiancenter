         <hr />
         <div id="footer">
           <div id="footerInner">
<? getContent(
   "section",
   "display:detail",
   "find:footer-address",
   "show:__text__"
   );
?>             
             <p>&copy;<? echo date("Y"); ?> <?php echo $MCMS_SITENAME ?>. All Rights Reserved. | 
<? getContent(
   "linklist",
   "display:links",
   "find:footer-copyright-link",
   "show:<a href=\"__url__\" ",
   "show:title=\"__description__\"",
   "show:>__name__</a>"
   );
?> | Powered by <a href="http://www.ekklesia360.com/">Ekklesia 360</a></p>
           </div>
         </div> <!-- #footer -->