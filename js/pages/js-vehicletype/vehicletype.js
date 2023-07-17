var cate_url = window.location.href;
var cate_param = getAllUrlParams(cate_url);
var pagi = cate_param.pagi;
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


$(".prev-page").on('click', function () {
  $(".pagination").pagination('prevPage');
});

$(".next-page").on('click', function () {
  $(".pagination").pagination('nextPage');
});

$(".category-menu").on('click', function () {
  var url = "?"+$(this).data("id");

  $("#search-cate").val("");

  $(".category-menu").removeClass("active");
  $(this).addClass("active");
  ChangeUrl('', url);
  pagi = 1;

  $(".category-search").hide();
  $(".category-box").show();
  $(".category-footer").show();

  if (getParameterByName('status') == 'show') {
    $(".cate-title").html('<h3 class="box-title cate-title"><i class="fa fa-eye"></i> แสดงผลบนเว็บไซต์</h3>');
  
  }else if (getParameterByName('status') == 'hidden') {
    $(".cate-title").html('<h3 class="box-title cate-title"><i class="fa fa-eye-slash"></i>  ซ่อนจากเว็บไซต์</h3>');
  
  }else {
    $(".cate-title").html('<h3 class="box-title cate-title"><i class="fa fa-bars"></i> แสดงผลบนแถบเมนู</h3>');
  
  }
  get_all_category();
  $(".pagination").pagination('redraw');
  $(".pagination").pagination('drawPage', 1);
});

if (cate_param.search == null) {
  $(".category-search").hide();
  $(".category-box").show();
  $(".category-footer").show();

  if (cate_param.status != null) {
    $("#cate-" + cate_param.status).addClass("active");
  }else {
    $("#cate-on-menu").addClass("active");
  }
}else {
  $(".category-footer").hide();
  $(".category-box").hide();
  $(".category-search").show();

  $(".category-menu").removeClass("active");
}

function get_all_category() {
  var url = url_ajax_request + "ajax/ajax.vehicletype.php",
      status = getParameterByName('status'),
      pagi = getParameterByName('pagi'),
      search = getParameterByName('search');

  $.ajax({
    type: "POST",
    url: url,
    data: {
      action: "getallcategory",
      status: status,
      pagi: pagi,
      search: search
    },
    success: function(msg){
      var obj = jQuery.parseJSON(msg);
      // console.log(obj);
      var category = '';
      if (obj.data) {
        for (var i = 0; i < obj.data.length; i++) {
          category += '\
          <div class="attachment-block clearfix">\
            <div class="content-img">\
              <img src="'+root_url+obj.data[i].thumbnail+'" alt="">\
            </div>\
            <div class="content-info">\
              <h1>'+obj.data[i].cate_name+'</h1>\
              <p class="text-datetime">\
              <i class="fa fa-folder-open-o" aria-hidden="true"></i> '+obj.data[i].parent_name+'\
              </p>\
              <p class="text-editor">\
              <i class="fa fa-globe" aria-hidden="true"></i> '+obj.data[i].lang_info.substr(1)+'\
              </p>\
            </div>\
            <div class="content-button pull-right">\
              <button type="button" class="btn btn-success margin-r-10 btn-edit-category" data-id="'+obj.data[i].cate_id+'" data-toggle="modal" data-target="#modalEditCategory"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> แก้ไข</button>\
            </div>\
          </div>\
          ';
        }
        $("#count-search").html("ผลการค้นหาทั้งหมด "+ obj.data.length +" รายการ");
      }else {
        $("#count-search").html("ผลการค้นหาทั้งหมด 0 รายการ");
        category = '\
        <div class="search-found">\
          <i class="fa fa-warning" aria-hidden"true"=""></i> ไม่พบผลลัพธ์การค้นหา\
        </div>';
      }

      $("#page-number").html("1-10/"+obj.rows+" ");
      $(".pagination").pagination('updateItems', obj.rows);

      var currentPage = $(".pagination").pagination('getCurrentPage');
      sumCateOnPage = currentPage*page_amount;
      numCate = ((currentPage-1)*page_amount)+1;
      if (obj.rows < sumCateOnPage) {
        sumCate = obj.rows;
      }else {
        sumCate = sumCateOnPage;
      }
      $("#page-number").html(numCate+"-"+sumCate+"/"+obj.rows+" ");
      $(".box-category").html(category);
    }
  });
}

$("#search-cate").on("keyup", function(event){ 
  if(event.keyCode == 13){

    // $(".category-box").hide();
    // $(".category-footer").hide();
    // $(".category-search").show();
    
    var new_url = decodeURIComponent(getQueryVariable('page=vehicletype', 'search', $("#search-cate").val()));
    ChangeUrl($("#search-cate").val(), new_url);
    $(".category-menu").removeClass("active");
    
    $(".cate-title").html('<i class="fa fa-search" aria-hidden="true"></i> ผลการค้นหาสำหรับ "'+getParameterByName('search')+'"');
    // get_all_category();

  }
});

function uploadimages(cateId,action) {
  formdata.append("action", action);
  formdata.append("id", cateId);
  $.ajax({
    url: url_ajax_request + "ajax/ajax.vehicletype.php",
    type: 'POST',
    data: formdata,
    processData: false,
    contentType: false,
    success: function(msg){
      var obj = jQuery.parseJSON(msg);
      if(obj['message'] === "OK"){
        location.reload();
      }
    }
  });
}

$(".btn-delete-category").on("click", function(){
  var data = {
    action: "deletevehicletype",
    id: $(this).data("id")
  };
  $.confirm({
    title: 'Are you sure?',
    content: 'you want to delete this type.',
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
  var url = "ajax/ajax.vehicletype.php",
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