var url = window.location.href;
var param = getAllUrlParams(url).page;
var subpage = getAllUrlParams(url).subpage;

if (param != null) {
  $("#" + param).addClass("active");
  if (subpage != null) {
    $('#' + param).children('.treeview-menu').show();
    $("#" + subpage).addClass("active");
  }
}else {
  $("#dashboard").addClass("active");
}

$("#sign-out").click(function() {
  $.ajax({
    type: "POST",
    url: url_ajax_request + "ajax/ajax.login.php",
    data: {action: "destroy_session"},
    success: function(data){
        window.location.href = site_url;
    }
  });
});

$(".select-language").on("click", function(){
  
  var language = $(this).data("language");
  var param = url.split('?');
  var language_param = getParameterByName('backend_language',url);
  /*
  console.log(param);
  
  if (language != language_param) {
    if (param.length > 1) {
      var new_url = decodeURIComponent(getQueryVariable(param['1'], 'backend_language', language));
      ChangeUrl(language, new_url);
    }else {
      var new_url = decodeURIComponent(getQueryVariable('', 'backend_language', language));
      ChangeUrl(language, new_url);
    }
  }
  */
  window.location.search = jQuery.query.set("backend_language", language );
});


$(".treeview-menu").find(".active a").css({"color": "#004773"});
$(".treeview-menu").find(".active i").removeClass("fa-circle-o");
$(".treeview-menu").find(".active i").addClass("fa-circle");