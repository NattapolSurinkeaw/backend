var content_url = window.location.href;
var content_param = getAllUrlParams(content_url);
var pagi = content_param.pagi;
page_amount = 10;
var data_category_tree = "";

$(document).ready(function () {

  var search = $("#search-hidden").val();
  var cate = 5;//$("#cate-id").val();
  var display = $("#display-status").val();
  $.ajax({
    type: "POST",
    url: url_ajax_request + "ajax/ajax.portfolio.php",
    data: {
      action: "getpaginationcontent",
      search: search,
      cate: cate,
      display: display
    },
    success: function (msg) {
      // console.log(msg);
      $(".pagination").pagination({
        items: msg,
        itemsOnPage: page_amount,
        currentPage: pagi,
        prevText: 'ย้อนกลับ',
        nextText: 'ถัดไป',
        onPageClick: function (pageNumber, event) {
          var content_url = window.location.href;
          var para_url = content_url.split('?');
          var new_url = decodeURIComponent(getQueryVariable(para_url['1'], 'pagi', pageNumber));
          ChangeUrl(pageNumber, new_url);
        }
      });
    }
  });


  $("#search-content").on("keyup", function (event) {
    if (event.keyCode == 13) {
      var content_url = window.location.href;
      var para_url = content_url.split('?');
      var param = para_url['1'].split('&');
      var new_url = decodeURIComponent(getQueryVariable(param['0'], 'search', $("#search-content").val()));
      ChangeUrl($("#search-content").val(), new_url);
    }
  });

  $("#cate-id").on("change", function () {
    var content_url = window.location.href;
    var para_url = content_url.split('?');
    var param = para_url['1'].split('&');
    if ($("#cate-id").val() != '') {
      var new_url = decodeURIComponent(getQueryVariable(param['0'], 'bycate', $("#cate-id").val()));
      ChangeUrl($("#cate-id").val(), new_url);
    } else {
      window.location.href = '?' + param['0'];
    }
  });

  $("#display-status").on("change", function () {
    var content_url = window.location.href;
    var para_url = content_url.split('?');
    var param = para_url['1'].split('&');
    if ($("#display-status").val() != '') {
      var new_url = decodeURIComponent(getQueryVariable(param['0'], 'status', $("#display-status").val()));
      ChangeUrl($("#display-status").val(), new_url);
    } else {
      window.location.href = '?' + param['0'];
    }
  });

  $("#sort-by").on("change", function () {
    var content_url = window.location.href;
    var para_url = content_url.split('?');
    var new_url = decodeURIComponent(getQueryVariable(para_url['1'], 'sortby', $("#sort-by").val()));
    ChangeUrl($("#sort-by").val(), new_url);
  });

  $(".delete-content").on("click", function () {
    var data = {
      action: "deletecontent",
      id: $(this).data("id")
    };
    $.confirm({
      title: 'Are you sure?',
      content: 'you want to delete this content.',
      theme: 'material',
      icon: 'fa fa-warning',
      type: 'red',
      draggable: false,
      buttons: {
        confirm: {
          text: 'Yes, delete it!',
          btnClass: 'btn-red',
          action: function () {
            delete_content(data);
          }
        },
        formCancel: {
          text: 'Cancel',
          cancel: function () { }
        }
      }
    });
  });

  $('.fancybox').fancybox({
    wrapCSS: 'fancybox-custom',
    padding: 10,
    closeBtn: false,
    helpers: {
      title: {
        type: 'outside'
      }
    }
  });

   get_categoryTree();

});

function get_categoryTree() {
  $.ajax({
    type: "POST",
    url: "ajax/ajax.portfolio.php",
    data: { action: "getcategorycontent" },
    dataType: 'json',
    async: false,
    success: function (response) {
      $("#add-blog-category-tree").jstree({
        "core": {
          "data": response,
          "check_callback": true
        },
        "checkbox": {
          "keep_selected_style": false,
          "three_state": false,
          "cascade": "up"
        },
        "plugins": ["wholerow", "checkbox"]
      }).bind('loaded.jstree', function () {
        $("#add-blog-category-tree").jstree('open_all');
      });

      /* ส่ั่งให้ jree ทำการโหลดข้อมูล */
      $("#blog-category-tree").jstree({
        "core": {
          "data": response,
          "check_callback": true
        },
        "checkbox": {
          "keep_selected_style": false,
          "three_state": false,
          "cascade": "up"
        },
        "plugins": ["wholerow", "checkbox"]
      }).bind('loaded.jstree', function () {
        $("#blog-category-tree").jstree('open_all');
      });


      setTimeout(function(){
        $('.jstree-clicked').click();
      },2000)
    }
  });
}


(function ($, window, undefined) {
  //is onprogress supported by browser?
  var hasOnProgress = ("onprogress" in $.ajaxSettings.xhr());

  //If not supported, do nothing
  if (!hasOnProgress) {
    return;
  }

  //patch ajax settings to call a progress callback
  var oldXHR = $.ajaxSettings.xhr;
  $.ajaxSettings.xhr = function () {
    var xhr = oldXHR.apply(this, arguments);
    if (xhr instanceof window.XMLHttpRequest) {
      xhr.addEventListener('progress', this.progress, false);
    }

    if (xhr.upload) {
      xhr.upload.addEventListener('progress', this.progress, false);
    }

    return xhr;
  };
})(jQuery, window);


function delete_content(data) {
  var url = "ajax/ajax.portfolio.php",
    dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function (data) {
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
            confirm: {
              text: 'OK',
              btnClass: 'btn-darkgreen',
              action: function () {
                location.reload();
              }
            }
          },
          backgroundDismiss: function () {
            location.reload();
          }
        });
      }
    }
  });
}

$('.kt-checkbox').on('click', function () {
  $(this).toggleClass('checked');
  $c = $(this).hasClass('checked')?"yes":"";

  var content_url = window.location.href;
  var para_url = content_url.split('?');
  var param = para_url['1'].split('&');

  console.log(content_url);
  console.log(para_url);
  console.log(param);

  var new_url = decodeURIComponent(getQueryVariable(param['0'], 'bypin', $c));
  ChangeUrl($("#cate-id").val(), new_url);


})