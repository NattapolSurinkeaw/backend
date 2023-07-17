var content_url = window.location.href;
var content_param = getAllUrlParams(content_url);
var pagi = content_param.pagi;
page_amount = 10;

$(document).ready(function() {
  $('.fancybox').fancybox({
    wrapCSS: 'fancybox-custom',
    padding: 10,
    closeBtn: false,
    helpers : {
      title : {
        type: 'outside'
      }
    }
  });
});

$(function() {
  var status = $("#display-status").val();
  var topic = $("#topic").val();
  var day = $("#cate-days").val();
  var bermongkol = $("#cate-bermongkol").val();
  var pow = $("#cate-power").val();
  var promo = $("#cate-promotion").val();
  var network = $("#cate-network").val();
  var search = $("#search-hidden").val();

  $.ajax({
    type: "POST",
    url: url_ajax_request + "ajax/ajax.product.php",
    data: {
      action: "getpaginationcontent",
      day: day,
      status: status,
      topic: topic,     
      bermongkol: bermongkol,
      pow: pow,
      promo: promo,
      network: network,
      search: search
    },
    success: function(msg){
      console.log(msg);
      $(".pagination").pagination({
        items: msg,
        itemsOnPage: page_amount,
        currentPage: pagi,
        prevText: 'ย้อนกลับ',
        nextText: 'ถัดไป',
        onPageClick: function(pageNumber, event) {
          var content_url = window.location.href;
          var para_url = content_url.split('?');
          var new_url = decodeURIComponent(getQueryVariable(para_url['1'], 'pagi', pageNumber));
          ChangeUrl(pageNumber, new_url);
        }
      });
    }
  });
});

$("#search-content").on("keyup", function(event){
  if(event.keyCode == 13){
    var content_url = window.location.href;
    var para_url = content_url.split('?');

    var new_url = decodeURIComponent(getQueryVariable(para_url['1'], 'search', $("#search-content").val()));
    ChangeUrl($("#search-content").val(), new_url);
  }
});

$("#cate-days").on("change", function(){
  var content_url = window.location.href;
  var para_url = content_url.split('?');
  var param = para_url['1'].split('&');
  if ($("#cate-days").val() != '') {
    var new_url = decodeURIComponent(getQueryVariable(para_url['1'], 'day', $("#cate-days").val()));
    ChangeUrl($("#cate-days").val(), new_url);
  }else {
    window.location.href = '?'+param['0'];
  }
});

$("#cate-bermongkol").on("change", function(){
  var content_url = window.location.href;
  var para_url = content_url.split('?');
  var param = para_url['1'].split('&');
  if ($("#cate-bermongkol").val() != '') {
    var new_url = decodeURIComponent(getQueryVariable(para_url['1'], 'bermongkol', $("#cate-bermongkol").val()));
    ChangeUrl($("#cate-bermongkol").val(), new_url);
  }else {
    window.location.href = '?'+param['0'];
  }
});

$("#cate-power").on("change", function(){
  var content_url = window.location.href;
  var para_url = content_url.split('?');
  var param = para_url['1'].split('&');
  if ($("#cate-power").val() != '') {
    var new_url = decodeURIComponent(getQueryVariable(para_url['1'], 'pow', $("#cate-power").val()));
    ChangeUrl($("#cate-power").val(), new_url);
  }else {
    window.location.href = '?'+param['0'];
  }
});

$("#cate-promotion").on("change", function(){
  var content_url = window.location.href;
  var para_url = content_url.split('?');
  var param = para_url['1'].split('&');
  if ($("#cate-promotion").val() != '') {
    var new_url = decodeURIComponent(getQueryVariable(para_url['1'], 'promo', $("#cate-promotion").val()));
    ChangeUrl($("#cate-promotion").val(), new_url);
  }else {
    window.location.href = '?'+param['0'];
  }
});

$("#cate-network").on("change", function(){
  var content_url = window.location.href;
  var para_url = content_url.split('?');
  var param = para_url['1'].split('&');
  if ($("#cate-network").val() != '') {
    var new_url = decodeURIComponent(getQueryVariable(para_url['1'], 'network', $("#cate-network").val()));
    ChangeUrl($("#cate-network").val(), new_url);
  }else {
    window.location.href = '?'+param['0'];
  }
});

$("#display-status").on("change", function(){
  var content_url = window.location.href;
  var para_url = content_url.split('?');
  var param = para_url['1'].split('&');
  if ($("#display-status").val() != '') {
    var new_url = decodeURIComponent(getQueryVariable(para_url['1'], 'status', $("#display-status").val()));
    ChangeUrl($("#display-status").val(), new_url);
  }else {
    window.location.href = '?'+param['0'];
  }
});

$("#topic").on("change", function(){
  var content_url = window.location.href;
  var para_url = content_url.split('?');
  var param = para_url['1'].split('&');
  if ($("#topic").val() != '') {
    var new_url = decodeURIComponent(getQueryVariable(para_url['1'], 'topic', $("#topic").val()));
    ChangeUrl($("#topic").val(), new_url);
  }else {
    window.location.href = '?'+param['0'];
  }
});

$("#sort-by").on("change", function(){
  var content_url = window.location.href;
  var para_url = content_url.split('?');
  var new_url = decodeURIComponent(getQueryVariable(para_url['1'], 'sortby', $("#sort-by").val()));
  ChangeUrl($("#sort-by").val(), new_url);
});

(function($, window, undefined) {
    //is onprogress supported by browser?
    var hasOnProgress = ("onprogress" in $.ajaxSettings.xhr());

    //If not supported, do nothing
    if (!hasOnProgress) {
        return;
    }
    
    //patch ajax settings to call a progress callback
    var oldXHR = $.ajaxSettings.xhr;
    $.ajaxSettings.xhr = function() {
        var xhr = oldXHR.apply(this, arguments);
        if(xhr instanceof window.XMLHttpRequest) {
            xhr.addEventListener('progress', this.progress, false);
        }
        
        if(xhr.upload) {
            xhr.upload.addEventListener('progress', this.progress, false);
        }
        
        return xhr;
    };
})(jQuery, window);

$(".delete-content").on("click", function(){
  var data = {
    action: "deleteproduct",
    id: $(this).data("id")
  };
  $.confirm({
    title: 'Are you sure?',
    content: 'you want to delete this product.',
    theme: 'material',
    icon: 'fa fa-warning',
    type: 'red',
    draggable: false,
    buttons: {
      confirm:  {
        text: 'Yes, delete it!',
        btnClass: 'btn-red',
        action: function(){
          delete_content(data);
        }
      },
      formCancel: {
        text: 'Cancel',
        cancel: function () {}  
      }
    }
  });
});

function delete_content(data) {
  var url = "ajax/ajax.product.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(data){
      var obj = jQuery.parseJSON(data);
      console.log(obj);
      if (obj['message'] === "OK") {
        $.confirm({
          title: 'Deleted!',
          content: 'Successfully Deleted!',
          theme: 'modern',
          icon: 'fa fa-check',
          type: 'darkgreen',
          draggable: false,
          backgroundDismiss: true,
          buttons: {
            confirm:  {
              text: 'OK',
              btnClass: 'btn-darkgreen',
              action: function(){
                location.reload();
              }
            }
          },
          backgroundDismiss: function(){
            location.reload();
          }
        });
      }
    }
  });
}
function choose_ber(){
  var days = $(".selectDay").val();
  alert(days);
}