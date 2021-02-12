/*!
    * Start Bootstrap - SB Admin v6.0.2 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2020 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    (function($) {
    "use strict";

    // Add active state to sidbar nav links
    /*var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
        $("#layoutSidenav_nav .sb-sidenav a.nav-link").each(function() {
            if (this.href === path) {
                $(this).addClass("active");
            }
        });*/

    // Toggle the side navigation
    $("#sidebarToggle").on("click", function(e) {
        e.preventDefault();
        $("body").toggleClass("sb-sidenav-toggled");
    });
})(jQuery);

$("body").addClass("sb-nav-fixed");

function toggleDarkTheme(){
  $('body').toggleClass('dark-theme');

  let dat = new FormData();
  dat.append("mode",$("body").hasClass("dark-theme"));
  getAjax("admin_setDarkMode",dat,(r) => {});
}

$('.dropdown').on('show.bs.dropdown', function() {
  $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
});

$('.dropdown').on('hide.bs.dropdown', function() {
  $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
});

function login(us,pw,msg){
  let dat = new FormData();
  dat.append("username", $("#" + us).val());
  dat.append("password", $("#" + pw).val());

  getAjax("admin_login",dat,(r) => {
    showAjaxResponse(r.success, r.msg, "#" + msg);
    if(r.success) setTimeout(() => location.reload(), 1000);
  });
}

function logout(){
  let dat = new FormData();
  getAjax("admin_logout",dat,(r) => {location.reload();});
}

function showAjaxResponse(succ, msg, elem){
  if(!$(elem).is(':empty')){
    if(succ){
      $(elem).addClass("blinkGreen");
      setTimeout(() => $(elem).removeClass('blinkGreen'), 750);
    } else {
      $(elem).addClass("blinkRed");
      setTimeout(() => $(elem).removeClass('blinkRed'), 750);
    }
  }
  $(elem).html(msg);
}
