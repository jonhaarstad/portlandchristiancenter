$(document).ready(function(){
  // ministry index
  var moveBody_ministry = $("#ministry_index_holder").height();
  var ministry = false;

  $("#ministry").click(function() {
    $("#ministry_index_holder").slideToggle(1100);
    if (ministry == false) {
      $('body').animate({ backgroundPositionY: moveBody_ministry + "px" }, 1100);
      ministry = true;
    } else {
      $('body').animate({ backgroundPositionY:"0px" }, 1100);
      ministry = false;
    }
  });

  $("#ministry_index_holder a.closeBtn").click(function(){
    $("#ministry_index_holder").slideUp(1100);
    $('body').animate({ backgroundPositionY: moveBody_ministry + "px" }, 1100);
    ministry = false;
  });

  $("#imgHeader span").css("opacity", 0.6);

  $("#footerInner p:first").addClass("first");

  $("li#nav_about").append("<span>&nbsp;&nbsp;&nbsp;&nbsp;Bridging People to Christ</span>");
  $("li#nav_services").append("<span>Explore what you can expect when you visit</span>");
  $("li#nav_connect-next-steps").append("<span>Discover your Next Steps</span>");
  $("li#nav_ministries").append("<span>We have something for people of all ages</span>");
  $("li#nav_support").append("<span>Care and support for our church family</span>");
  $("li#nav_outreach").append("<span>Extending Care to our World</span>");
  $("li#nav_extras").append("<span>Engaging with Life in the Church</span>");

  $('a.ekk-login-link').attr('href','http://my.ekklesia360.com/');

});