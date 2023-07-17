var index = 0;
var last = $('.sendto_js_av_lang').val();
var falseOrder = parseInt(last) + 2;

$(function () {
  $('#langconfig-table').DataTable({
    "scrollX": true,
    "processing": true,
    "serverSide": true,
    "info": true,
    "lengthMenu": [[15, 35, 75, 100], [15, 35, 75, 100]],
    "ajax": {
      url: "ajax/ajax.langconfig.php",
      data: { action: "getlanguage" },
      type: "post",
      error: function () {
        $(".langconfig-table-error").html("");
        $("#langconfig-table").append('<tbody class="profile-grid-error"><tr><th colspan="5">No data found in the server</th></tr></tbody>');
        $("#langconfig-table_processing").css("display", "none");
      }
    },
    "language": {
      "lengthMenu": "แสดง _MENU_ รายการ",
      "search": "ค้นหา : ",
      "info": "แสดงหน้า _PAGE_ จากทั้งหมด _PAGES_ หน้า",
      "paginate": {
        "first": "หน้าแรก",
        "last": "หน้าสุดท้าย",
        "next": "ถัดไป",
        "previous": "ย้อนกลับ"
      }
    },
    "columnDefs": [{
      targets: [falseOrder],
      orderable: false,
    }],
    "order": [[0, "asc"]]
  });
});

$(document).on('click', '.edit-lang', function () {

  var last = $('.sendto_js_av_lang').val();
  var langId = $(this).data("id");
  /*game comment*/
  /*var data = {
      action: "editlanguage",
      id: langId,
      defaults: $("#defaults-" + langId).val(),
      th: $("#th-" + langId).val(),
      en: $("#en-" + langId).val()
  };*/
  var data = {
    action: "editlanguage",
    id: langId,
    defaults: $("#defaults-" + langId).val()
  };
  for (index = 0; index < last; index++) {
    data[$('.av_lang_langconfig:eq(' + index + ')').data('type')] = $("#" + $('.av_lang_langconfig:eq(' + index + ')').data('type') + "-" + langId).val();
  }
  //console.log(data);
  edit_language(data);
});

function edit_language(data) {
  var url = url_ajax_request + "ajax/ajax.langconfig.php",
    dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function (msg) {
      var obj = jQuery.parseJSON(msg);
      if (obj[0].message === 'OK') {
        $.confirm({
          theme: 'modern',
          type: 'green',
          icon: 'fa fa-check',
          title: 'บันทึกเรียบร้อย',
          content: '',
          buttons: {
            somethingElse: {
              text: 'ตกลง',
              keys: ['enter']
            }
          }
        });
      } else {
        $.confirm({
          theme: 'modern',
          type: 'red',
          icon: 'fa fa-times',
          title: 'บันทึกข้อมูลไม่สำเร็จ',
          content: 'กรุณาลองใหม่อีกครั้ง',
          buttons: {
            somethingElse: {
              text: 'ตกลง',
              keys: ['enter']
            }
          }
        });
      }
    }
  });
}

//Add
$("#add-lang").on("click", function () {
  var data = {
    action: "addlanguage",
    parameter: $("#add-parameter").val(),
    defaults: $("#add-default").val()
  };


  var last = $('.sendto_js_av_lang').val();
  for (index = 0; index < last; index++) {
    data[$('.add_lang:eq(' + index + ')').data('type')] = $("#add-" + $('.add_lang:eq(' + index + ')').data('type')).val();
  }

  /*  th: ,
    en: $("#add-en").val()*/
  //console.log(data);
  add_language(data);
});

function add_language(data) {
  var url = url_ajax_request + "ajax/ajax.langconfig.php",
    dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function (msg) {
      var obj = jQuery.parseJSON(msg);
      console.log(obj);
      if (obj.message === 'OK') {
        $.confirm({
          theme: 'modern',
          type: 'green',
          icon: 'fa fa-check',
          title: 'บันทึกเรียบร้อย',
          content: '',
          buttons: {
            somethingElse: {
              text: 'ตกลง',
              keys: ['enter'],
              action: function () {
                location.reload();
              }
            }
          }
        });
      } else {
        $.confirm({
          theme: 'modern',
          type: 'red',
          icon: 'fa fa-times',
          title: 'บันทึกข้อมูลไม่สำเร็จ',
          content: 'กรุณาลองใหม่อีกครั้ง',
          buttons: {
            somethingElse: {
              text: 'ตกลง',
              keys: ['enter'],
              action: function () {
                //location.reload();
              }
            }
          }
        });
      }
    }
  });
}

$('#modal-language').on('hidden.bs.modal', function (e) {
  if (e.namespace == 'bs.modal') {
    $("#add-parameter").val("");
    $("#add-default").val("");
    $("#add-th").val("");
    $("#add-en").val("");
  }
});