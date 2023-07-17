var  orderTable ='';

$(function () {
    orderTable = $('#purchase-grid').DataTable({
    "scrollX": true,
    "processing": true,
    "serverSide": true,
    "ajax": {
        url: "ajax/ajax.purchase.php",
        "data": function(d) {
          d.type = 'publish';
          d.date = $('#add-date-display').val();
          d.status = $('.btnp-order span.active').data('type');
          d.action = "orderListTable";
        },
        type: "post",
        error: function(){
          $(".employee-grid-error").html("");
          $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3"></th></tr></tbody>');
          $("#employee-grid_processing").css("display","none");
        }
    },
    "columnDefs": [{
      targets: [2,4,5,6],
      orderable: false
    }],
    "pageLength": 50,
    "columns": [
      { "width": "16%", "targets": 0 },
      { "width": "14%", "targets": 1 },
      { "width": "14%", "targets": 2 },
      { "width": "14%", "targets": 3 },
      { "width": "14%", "targets": 4 },
      { "width": "14%", "targets": 5 },
      { "width": "14%", "targets": 6 }
    ],
    "fixedColumns": true,
    "order": [[ 0, "desc" ]]
  });
});


$('.page_purchaseOrderData #add-date-deliver').datepicker({
  format: 'dd/mm/yyyy',
  autoclose: true,
  language: 'th',
  todayHighlight: true
}).on('changeDate', function(e) {
  console.log('tset')
  $('.page_purchaseOrderData #add-date-deliver-hidden').val(e.format('yyyy-mm-dd'));
});


 $("#add-date-deliver").on('change',function(){
  $('.purchaseAction .switch.displayPurchase').removeClass('onblock');
 });

$('#add-date-display').datepicker({
  format: 'dd/mm/yyyy',
  autoclose: true,
  language: 'th',
  todayHighlight: true
  }).on('changeDate', function(e) {
    $('#add-date-display-hidden').val(e.format('yyyy-mm-dd'));
});

$('.page_purchaseOrderData').on('click','.agentFormClose',function(){
  $('.purchaseAction').hide();
});

$('.page_purchaseOrderData').on('click','.squared-ems',function(){
    if($('.squared-ems').prop('checked') == true ){
      var send = 'EMS';
    }else{
      var send = 'NO';
    }
    data = {
      type: send,
      id: $('input#add-date-deliver').data('id'),
      action: 'switchSendType'
    }
    $.ajax({
      url: 'ajax/ajax.purchase.php',
      type: "POST",
      dataType:'json',
      data: data,
      success: function(item){
      }
    });
});

function reloadTable(){
    orderTable.ajax.reload( null,false);
}
$('#slc-agent').on('change',function(){
   reloadTable()
})
$('#add-date-display').on('change',function(){
   reloadTable()
})
$('#add-date-display').on('keyup',function(){
    reloadTable()
})

$('.page_purchaseOrderData').on('click','.btnDelOrder',function(){
    data = {
      id: $(this).data('id'),
      action: 'delOrder'
    }
    $.confirm({
      title: 'Are you sure?',
      content: 'You want to delete this order.',
      theme: 'material',
      icon: 'fa fa-warning',
      type: 'red',
      draggable: false,
      buttons: {
        confirm:  {
          text: 'Yes, delete it!',
          btnClass: 'btn-red',
          action: function(){
            $.ajax({
              url: 'ajax/ajax.purchase.php',
              type: 'POST',
              dataType: 'json',
              data: data,
              success: function(msg){
                confirmAction(msg);
              }
            });
          }
        },
        formCancel: {
          text: 'Cancel',
          cancel: function () {}
        }
      }
    });
});


function confirmAction(msg){

  if(msg['msg'] == 'OK'){
    $.confirm({
      title: 'บันทึกรายการสำเร็จ',
      content: 'ทำรายการเสร็จสิ้น',
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
            orderTable.ajax.reload( null,false);
          }
        },
      },
    });

  }else{
    $.confirm({
      title: 'บันทึกรายการไม่สำเร็จ',
      content: 'บันทึกรายการไม่สำเร็จ',
      type: 'red',
      typeAnimated: true,
      buttons:{
        close: {
          text: 'ปิด'
        }
      }
    });
  }
}

$('.box-head-action').on('click','.input-group-addon',function(){
  $('#add-date-display').val('');
  orderTable.ajax.reload( null,false);
});


$('span.order-success').on('click',function(){
  $('span.order-success').addClass('active');
  $('span.order-unsuccess').removeClass('active');
  orderTable.ajax.reload( null,false);

});
$('span.order-unsuccess').on('click',function(){
  $('span.order-unsuccess').addClass('active');
  $('span.order-success').removeClass('active');
  orderTable.ajax.reload( null,false);

});

function date_delivery(){

  data = {
    id: $('#add-date-deliver').data('id'),
    date: $('#add-date-deliver').val(),
    action: 'updateDateDeliver'
  }
  $.ajax({
    url: 'ajax/ajax.purchase.php',
    type: 'POST',
    dataType:'json',
    data: data,
    success: function(msg){
      location.reload();
    }
  });
}

     /* check action confirm */
$('.actPurchase').on('click',function(e){
    let id = $(this).data('id');
    $.confirm({
      title: 'อัพเดทข้อมูล!',
      content: 'โปรดยืนยันการปรับเปลี่ยนสถานะรายการ',
      type: 'blue',
      typeAnimated: true,
      buttons: {
          confirm: {
              text: 'YES',
              btnClass: 'btn-info',
              action: function(){
                updateEMSORDER(id);
              }
          },
          close: function() {
           if($('#displayPurchase').prop('checked')){
               $('#displayPurchase').prop('checked', false);
            }else{
              $('#displayPurchase').prop('checked', true);
            }

          }
      }
    });
});

$('.completed_edit').on('click',function(){
  let id = $('.price_phone').data('id');
  $.confirm({
    title: 'อัพเดทข้อมูล!',
    content: 'โปรดยืนยันการปรับเปลี่ยนสถานะรายการ',
    type: 'blue',
    typeAnimated: true,
    buttons: {
        confirm: {
            text: 'YES',
            btnClass: 'btn-info',
            action: function(){

              updateEMSORDER(id);
            }
        },
        close: function() {

        }
    }
  });
})


function updateEMSORDER(id){
    date = $('input#add-date-deliver').val().split('/');
    if($('#add-date-deliver').val() != '' && date.length >= 3 ){
      if($("#displayPurchase").prop('checked')){
        state = 'YES';
      }else{
        state = 'NO';
      }

      if($('.squared-ems').prop('checked') == true ){
        var send = 'EMS';
      }else{
        var send = 'NO';
      }

      data = {
        id,
        display: state,
        send,
        action: 'updateStatusOrder'
      }
      if(!$('.text_ems').hasClass('inactive')){
        $('.displayPurchase').removeClass('onblock');
      }

      $.ajax({
        url: 'ajax/ajax.purchase.php',
        type: 'POST',
        dataType: 'json',
        data: data,
        success: function(){
          orderTable.ajax.reload( null,false);
          $('#add-date-deliver').css('border-color',' #61dc61')
      }
    });
    date_delivery();
  }else{

    $('#add-date-deliver').css('border-color','red')
  }
}

$('.purchaseAction').on('keypress','.text_ems',function(event){
    var keyCode = event.keyCode;
    if( keyCode == 13){
       updateEMSdata($(this).data('id'));
    }
});

$('.purchaseAction').on('click','i.fa-plus',function(){
     updateEMSdata($(this).data('id'));
});

$('.purchaseAction').on('click','input.text_ems',function(){
  $('input.text_ems[data-id="'+$(this).data('id')+'"]').removeClass('active');
  $('input.text_ems[data-id="'+$(this).data('id')+'"]').removeClass('inactive');

});

function updateEMSdata(id){
  data = {
    id,
    ems: $('.text_ems[data-id="'+id+'"]').val(),
    action: 'updateEMS'
  }
  $.ajax({
    url: 'ajax/ajax.purchase.php',
    type: 'POST',
    dataType: 'json',
    data: data ,
    success: function(msg){
        set_order_actPurchase(id,msg);
    }
  })
}

function set_order_actPurchase(id,msg){
  if(msg['status'] == 200){
    $('input.text_ems[data-id="'+id+'"]').addClass('active');
    if(! $('.purchaseAction .switch.displayPurchase').hasClass('inactive')){
      $('.purchaseAction .switch.displayPurchase').removeClass('onblock');
    }
  }else{
    $('input.text_ems[data-id="'+id+'"]').addClass('inactive');
    $('.purchaseAction .switch.displayPurchase').addClass('onblock');
  }
}





$('.page_purchaseOrderData').on('click','#getapi',function(){

  data ={
    key: $('.txt_emsapi').val(),
    action : 'updateEMSTOken'

  }
  $.ajax({
    url: 'ajax/ajax.purchase.php',
    type: 'POST',
    dataType: 'json',
    data: data,
    success: function(msg){
      if(msg['status'] == 200){
        $.confirm({
          title: 'Successfully!',
          content: msg['key'],
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
                $('.txt_emsapi').val('');
                getAPIitemKey(data.key);
              }
            }
          },
        });

      }else{

      }

    }
  })

})

 function getAPIitemKey(tokenAccount){
  let endpointToken = 'https://trackapi.thailandpost.co.th/post/api/v1/authenticate/token';
  $.ajax({
    url:endpointToken,
    type:"POST",
    dataType:"json",
    headers:{
      "Content-type":"application/json",
      "Authorization": `Token ${tokenAccount}`
    },
    success:function(data){
      getToken = data.token;
      updateitemkey(getToken);
    }
  })
 }
 function updateitemkey(token){
   data = {
     token,
     action: 'updateApiGetitem'
   }
  $.ajax({
    url: 'ajax/ajax.purchase.php',
    type: "POST",
    dataType: "json",
    data: data,
    success:function(data){

    }
  })
 }


 function updateproduct_edit(param){
    $.ajax({
      url: 'ajax/ajax.purchase.php',
      type: 'POST',
      dataType: 'json',
      data: param,
      success:function(data){
        if(data['status']['status'] != 200){
          alert('edit was not success');
        }
      }
    });
 }

 $('#modalOrderDetails').on('keypress','.txt_name',function(e){
  var keycode = e.keyCode;
  if(keycode == 13){ //&& !$(this).hasClass('active')
    $(this).addClass('active');
     param = {
       id: $('.order_detail_id').data('conid'),
       val: $(this).val(),
       set: 'name',
       section: 'contact',
       action: 'updateproduct_edit'
     }
     updateproduct_edit(param);
  }
});

$('#modalOrderDetails').on('keypress','.txt_lastname',function(e){
  var keycode = e.keyCode;
  if(keycode == 13){ //&& !$(this).hasClass('active')
    $(this).addClass('active');
     param = {
       id: $('.order_detail_id').data('conid'),
       val: $(this).val(),
       set: 'lastname',
       section: 'contact',
       action: 'updateproduct_edit'
     }
     updateproduct_edit(param);
  }
});

$('#modalOrderDetails').on('keypress','.txt_email',function(e){
  var keycode = e.keyCode;
  if(keycode == 13){ //&& !$(this).hasClass('active')
    $(this).addClass('active');
     param = {
       id: $('.order_detail_id').data('conid'),
       val: $(this).val(),
       set: 'email',
       section: 'contact',
       action: 'updateproduct_edit'
     }
     updateproduct_edit(param);
  }
});

$('#modalOrderDetails').on('keypress','.txt_address',function(e){
  var keycode = e.keyCode;
  if(keycode == 13){
    $(this).addClass('active');
     param = {
       id: $('.order_detail_id').data('conid'),
       val: $(this).val(),
       set: 'address',
       section: 'contact',
       action: 'updateproduct_edit'
     }
     updateproduct_edit(param);
  }
});

$('#modalOrderDetails').on('keypress','.txt_tel',function(e){
  var keycode = e.keyCode;
  if(keycode == 13 && !$(this).hasClass('active') ){
    $(this).addClass('active');
     param = {
       id: $('.order_detail_id').data('conid'),
       val: $(this).val(),
       set: 'tel',
       section: 'contact',
       action: 'updateproduct_edit'
     }
     updateproduct_edit(param);
  }
});

$('#modalOrderDetails').on('keypress','.phone_number_txt',function(e){
  var keycode = e.keyCode;
  if(keycode == 13){ //&& !$(this).hasClass('active')
    $(this).addClass('active');
     param = {
       id: $(this).data('id'),
       val: $(this).val(),
       set: 'phone',
       section: 'product',
       action: 'updateproduct_edit'
     }
     updateproduct_edit(param);
  }
});

$('#modalOrderDetails').on('keypress','.price_phone',function(e){
  var keycode = e.keyCode;
  if(keycode == 13 ){ //&& !$(this).hasClass('active')
    $(this).addClass('active');
     param = {
       id: $(this).data('id'),
       phone: $(this).closest('.detail_EMS').children('.d-number').children('input.phone_number_txt').val(),
       val: $(this).val(),
       set: 'price',
       section: 'product',
       action: 'updateproduct_edit'
     }
     updateproduct_edit(param);
  }
});

 $(".purchaseAction").on('click','input',function(){
    $(this).removeClass('active');
 });


/* **************  berhoro ************** */
$(".page_purchaseOrderData").on("click",".btn-order",function(){
    $(".btn-order").removeClass("active")
    $(this).addClass("active");
    reloadTable()
});

function editPurchaseOrder(_id){
   $.ajax({
    url: 'ajax/ajax.purchase.php',
    type:"POST",
    dataType:"json",
    data: { action: 'get_purchase_order_by_id',id:_id },
    success: function(response){
        swalEditOrder(response);
        $(".page_purchaseOrderData .txt_zipcode").keyup();
    },
    error: function(){
        console.log('edit error');
    }
   });
}

function delPurchaseOrder(_id){
    data = {
      id: $(this).data('id'),
      action: 'delOrder'
    }
      $.confirm({
        title: 'Are you sure?',
        content: 'You want to delete this order.',
        theme: 'material',
        icon: 'fa fa-warning',
        type: 'red',
        draggable: false,
        buttons: {
          confirm:  {
            text: 'Yes, delete it!',
            btnClass: 'btn-red',
            action: function(){
              delPurchaseOrderDo(_id)
            }
          },
          formCancel: {
            text: 'Cancel',
            cancel: function () {}
          }
        }
      });
}

function delPurchaseOrderDo(_id){
  $.ajax({
    url: 'ajax/ajax.purchase.php',
    type:"POST",
    dataType:"json",
    data: { action: 'delete_purchase_order_by_id',id:_id },
    success: function(response){
      $(".notify_number").html(response['total']);
      if(response['message'] == "OK"){
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'ลบรายการสำเร็จ!',
          showConfirmButton: false,
          timer: 1000
        })
      }else {
        Swal.fire({
          position: 'center',
          icon: 'error',
          title: 'ลบรายการไม่สำเร็จ!',
          showConfirmButton: false,
          timer: 1000
        })
      }
      reloadTable();

    },
    error: function(){
        console.log('delete error');
    }
   });
}

async function swalEditOrder(response){
    const{ value: accept } = await Swal.fire({
        width: 360,
        customClass: {
            header: 'my-header-style',
            popup: 'my-purchase-style',
        },
        inputPlaceholder: 'Type of System',
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText:'อัพเดท',
        cancelButtonText:'ยกเลิก',
        html: response['html'],
        focusConfirm: false,
        input: 'checkbox',
        inputValue: 1,
        inputValidator: (result) => {
          $(".page_purchaseOrderData .my-purchase-style").addClass("edit");
          if(result){
                /* เช็คการเปลี่ยนแปลงของข้อมูล */
                let items = $(".dataShowORDER .detail_EMS span input");
                let arr = [];
                $.each(items, function(key, val){
                  myJson = {
                    "id": val.getAttribute('data-id'),
                    "name": val.getAttribute('data-name'),
                    "value": val.value
                  }
                  arr.push(myJson);
                });

                /* การส่งของโดย */
                if($('.page_purchaseOrderData #emsService').prop('checked')){
                  carrier = "ems";
                }else if($('.page_purchaseOrderData #kerryService').prop('checked')){
                  carrier = "kerry";
                }else {
                  carrier = "no";
                }
                let netpay =  calculateNetpay();
                let param = {
                  list: response['order']['list']
                  ,order_id: response['order']['id']
                  ,con_id: response['order']['c_id']
                  ,action:"update_purchase_order_by_id"
                  ,name: $(".page_purchaseOrderData .txt_name").val()
                  ,email: $(".page_purchaseOrderData .txt_email").val()
                  ,address: $(".page_purchaseOrderData .txt_address").val()
                  ,subdistrict: $(".page_purchaseOrderData #slc_subdistrict").val()
                  ,district: $(".page_purchaseOrderData .txt_district").val()
                  ,province: $(".page_purchaseOrderData .txt_province").val()
                  ,zipcode: $(".page_purchaseOrderData .txt_zipcode").val()
                  ,tel: $(".page_purchaseOrderData .txt_tel").val()
                  ,price: netpay
                  ,status: $(".page_purchaseOrderData #product_delivery").val()
                  ,tracking: $(".page_purchaseOrderData .txt_tracking").val()
                  ,carrier
                  ,arr
                }

                let empty_items = $(".dataShowORDER .detail_EMS.empty:not(.active) span input").length;
                if(empty_items > 0 && param.status == 'publish'){
                   /* ถ้าการอัพเดทมีเบอร์ที่ซ้ำกันถูกขายไปแล้ว ให้แจ้งรายละเอียดก่อนอัพเดท */
                   $(".page_purchaseOrderData .dataShowORDER .detail_EMS.empty").addClass('active');
                   $(".page_purchaseOrderData .swal2-confirm").html("ยืนยัน");
                   $(".page_purchaseOrderData .swal2-confirm").css("background-color: #00BCD4;")
                   return "คำเตือน: สินค้าที่ถูกขายไปแล้ว จะถูกลบออกจากรายการนี้โดยอัตโนมัติ กรุณากดยืนยันเพื่อทำรายการต่อ!"
                }
                updatePurchaseOrder(param);
          }
        }
     });
};

function updatePurchaseOrder(param){
  $.ajax({
    url: 'ajax/ajax.purchase.php',
    type: 'POST',
    dataType: 'json',
    data: param,
    success: function(response){
      $(".notify_number").html(response['total']);
      if(response['message'] == "OK"){
        console.log('Purchase ?');
          Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'ทำรายการสำเร็จแล้ว!',
            showConfirmButton: false,
            timer: 1000
          })
      }else {
        Swal.fire({
          position: 'center',
          icon: 'error',
          title: 'ทำรายการไม่สำเร็จ!',
          showConfirmButton: false,
          timer: 1000
        })
      }

      reloadTable();
    },
    error: function(){
      console.log('error')
    }
  });
}



$('.page_purchaseOrderData').on("click",".btnSwitchDelivery",function(event){
  let _this = event.target;
  _this.closest('.toggle-switch').classList.toggle('ts-active')
  let status = _this.closest('.toggle-switch').classList.value.split(" ").find((e) => e === "ts-active")
  if (status == "ts-active") {
      $('#product_delivery').val('publish')
  } else {
      $('#product_delivery').val('pending')
  }
});

$('.page_purchaseOrderData').on('click','#emsService',function(){
  $(".page_purchaseOrderData #kerryService").removeProp("checked");
});


$('.page_purchaseOrderData').on('click','#kerryService',function(){
    $(".page_purchaseOrderData #emsService").removeProp("checked");
});

function deleteProductSold(_id){
  $.ajax({
    url: 'ajax/ajax.purchase.php',
    type: 'POST',
    dataType: 'json',
    data: { action: "del_purchase_order_soldout", id:_id },
    success: function(response){
      if(response['message'] =="OK"){
        $(".page_purchaseOrderData .detail_EMS[data-id='"+_id+"']").remove();
      }
    },
    error: function(){
      console.log('error');
    }
  })
}


$(".page_purchaseOrderData").on("keyup",".txt_price",function(e){
  let keycode = e.keyCode
  if((keycode >= 48 && keycode <= 57) || (keycode >= 96 && keycode <= 105) || (keycode == 8)){
    calculateNetpay();
  } else {
    return false;
  }
});

function number_format(number) {
    var isFloat = false;
    var main = number;
    var floatValue = '';
    if(number.indexOf('.') != -1) {
        var s = number.split('.');
        main = s[0];
        floatValue = s[1];
        isFloat = true;
    }
    var length = main.length;
    if(length > 3) {
        var format = '';
        var counter = 0;
        var rounds = 0;
        for(var i = length - 1; i >= 0; i--) {
            format += main[i];
            counter++;
            if(counter == 3) {
              rounds++;
              if((rounds * 3) != length){
                format += ",";
                counter = 0;
              }
            }
        }
        var r = format.split('').reverse().join('');
        if(isFloat) {
            r += '.' + floatValue;
        }
        return r;
    } else {
        return number;
    }
}


function insert_order_list(){
  $.ajax({
    url: 'ajax/ajax.purchase.php',
    type:"POST",
    dataType:"json",
    data: { action: 'get_form_add_order_list'},
    success: function(response){
      get_insertOrderList(response);
    },
    error: function(){
        console.log('edit error');
    }
   });
}

async function get_insertOrderList(response){
  const { value: accept } = await Swal.fire({
      width: 360,
      customClass: {
          header: 'my-header-style',
          popup: 'my-purchase-style',
      },
      showCloseButton: true,
      showCancelButton: true,
      confirmButtonText:'เพิ่มรายการ',
      cancelButtonText:'ยกเลิก',
      html: response['html'],
      focusConfirm: false,
      input: 'checkbox',
      inputValue: 1,
      inputValidator: (result) => {

        if(result){
            /* เช็คการเปลี่ยนแปลงของข้อมูล */
            let items = $(".dataShowORDER .detail_EMS:not(.emp)  span input");
            let arr = [];
            let msg = "";
            $.each(items, function(key, val){
              if(val.getAttribute('data-name') == "phone"){
                if(val.value.length != 10){
                  msg = val.value;
                }
              }
              if(val.getAttribute('data-name') == "price" && val.value == ""){
                  val.value = 0;
              }
              myJson = {
                "id": val.getAttribute('data-id'),
                "name": val.getAttribute('data-name'),
                "value": val.value
              }
              arr.push(myJson);
            });
            if(msg != ""){
              return "หมายเลข "+msg+" ไม่ครบ 10 หลัก"
            }

            /* การส่งของโดย */
            if($('.page_purchaseOrderData #emsService').prop('checked')){
              carrier = "ems";
            }else if($('.page_purchaseOrderData #kerryService').prop('checked')){
              carrier = "kerry";
            }else {
              carrier = "no";
            }
            let netpay =  calculateNetpay();
            let param = {
              action:"insert_purchase_order"
              ,netpay
              ,name: $(".page_purchaseOrderData .txt_name").val()
              ,email: $(".page_purchaseOrderData .txt_email").val()
              ,address: $(".page_purchaseOrderData .txt_address").val()
              ,subdistrict: $(".page_purchaseOrderData .txt_subdistrict").val()
              ,district: $(".page_purchaseOrderData .txt_district").val()
              ,province: $(".page_purchaseOrderData .txt_province").val()
              ,zipcode: $(".page_purchaseOrderData .txt_zipcode").val()
              ,tel: $(".page_purchaseOrderData .txt_tel").val()
              ,status: $(".page_purchaseOrderData #product_delivery").val()
              ,tracking: $(".page_purchaseOrderData .txt_tracking").val()
              ,carrier
              ,arr
            }

            if(param.name.length < 1){
              return "ระบุชื่อผู้ซื้อ"
            }
            if(param.arr.length < 1){
              return "คุณยังไม่ระบุหมายเลขสินค้า"
            }

            insertOrder_List(param);
        }
      },
  });
}
function insertOrder_List(param){
  $.ajax({
    url: 'ajax/ajax.purchase.php',
    type:"POST",
    dataType:"json",
    data: param,
    success: function(response){
      $(".notify_number").html(response['total']);
      if(response['message'] == "OK"){
        Swal.fire({
          position: 'center',
          icon: 'success',
          title: 'ทำรายการสำเร็จแล้ว!',
          showConfirmButton: false,
          timer: 1000
        })
      }else {
        Swal.fire({
          position: 'center',
          icon: 'error',
          title: 'ทำรายการไม่สำเร็จ!',
          showConfirmButton: false,
          timer: 1000
        })
      }
      reloadTable();
    },
    error: function(){
        console.log('edit error');
    }
   });
}




$(".page_purchaseOrderData").on("keyup",".addOrderList .txt_phone",function(){
  let input = $(this);
  let tel = $(this).val();
  let _id = parseInt($(".page_purchaseOrderData .addOrderList .dataShowORDER .detail_EMS:not(.emp)").length) + 2;

  input.removeClass("emp");
  if(input.val().length == 10){
    let items = $(".page_purchaseOrderData .addOrderList .dataShowORDER .detail_EMS span input.active");
    let new_order_arr = new Array();
    $.each(items,function(key,val){
       if( tel == val.value){
          $(this).addClass('add-required')
        }else{
          $(this).removeClass('add-required')
        }
    });
    input.removeClass("add-required");
    if($(".page_purchaseOrderData .addOrderList .txt_phone.emp").length == 1){
      return false;
    }
    new_order_arr.push();
    input.addClass("active");
    $(".page_purchaseOrderData .addOrderList .detail_EMS").removeClass("emp")
    let blog =  '<div class="detail_EMS emp" data-id="'+_id+'">'+
                  '<span class="number body-details">'+
                      '<input type="text" class="form-control text-center txt_network" data-id="'+_id+'" data-name="network" value="" placeholder="เครือข่าย"></span>'+
                  '</span>'+
                  '<span class="body-details d-number">'+
                      '<input type="tel" maxlength="10" class="form-control text-center txt_phone emp" data-id="'+_id+'" data-name="phone" value="" placeholder="เบอร์โทรศัพท์">'+
                  '</span>'+
                  '<span class="number body-details">'+
                    '<input type="text" class="form-control txt_price" data-id="'+_id+'" data-name="price" value="0" placeholder="ราคา"></span>'+
                  '</span>'+
                '</div>';
    $(".page_purchaseOrderData .dataShowORDER").append(blog)
  }
});
$(".page_purchaseOrderData").on("keyup",".addOrderList .txt_phone.emp",function(){
    if($(this).val().length != 10 && $(this).val().length != 0){
      $(this).addClass("add-required");
    }
});
$(".page_purchaseOrderData").on("keyup",".addOrderList .txt_name",function(){
  $(this).removeClass("add-required");
});
$(".page_purchaseOrderData").on("keyup",".addOrderList .txt_network",function(){
  let value = $(this).val().toUpperCase();
  $(this).val(value)
});

$(".page_purchaseOrderData").on("keyup",".addOrderList .txt_price",function(e){
  let keycode = e.keyCode
  if((keycode >= 48 && keycode <= 57) || (keycode >= 96 && keycode <= 105) || (keycode == 8)){

    calculateNetpay();

  } else {  return false;  }

});

$(".page_purchaseOrderData").on('change','.discount input',function(){
  let id = $(this).data('id');
  let discount = $(this).val();
  let price = $(".page_purchaseOrderData .dataShowORDER .detail_EMS .body-details .txt_price[data-id='"+id+"']").data('price');
  let total = Math.round(price - ((price * discount) /100));
  $(".page_purchaseOrderData .dataShowORDER .detail_EMS .body-details .txt_price[data-id='"+id+"']").val(total);
  calculateNetpay();
});


function calculateNetpay(){
  let arr = $(".page_purchaseOrderData .dataShowORDER .detail_EMS .txt_price");
  let total = 0;
  $.each(arr, function(key, val){
    let numb = val.value.replace(/[^0-9]/g, '');
    if(numb == ""){ numb = 0; }
    total += parseFloat(numb);
  });
  let number = number_format(String(total));
  $(".page_purchaseOrderData  .total-order-price").html(number);
  return number;
} 


$(".page_purchaseOrderData .service-charge").on('click','.below',function(){
  let val = $(".page_purchaseOrderData .service-charge input#inputServiceBelow");
   if(val.prop("disabled")){
     val.prop("disabled",false)
   }  
 })


 $(".page_purchaseOrderData .service-charge").on('keyup','input#inputServiceBelow',function(e){
   let key = e.keyCode;
   if(key == 13){
     $(this).addClass  ('border-success')
     $(this).prop('disabled',true)
     $.ajax({
       url: 'ajax/ajax.purchase.php',
       type: "POST",
       dataType:'json',
       data: {
         action: 'update_below_price',
         charge: $(this).val()
       },
       success: function(_response){
         console.log(_response['message'])
         if(_response['message'] == "OK"){
           $(".page_purchaseOrderData .service-charge input#inputServiceBelow").removeClass('border-success')
          //  $(".page_purchaseOrderData .service-charge input#inputServiceBelow").addClass('border-success-response')
         } else{
           $(".page_purchaseOrderData .service-charge input#inputServiceBelow").removeClass('border-success')
          //  $(".page_purchaseOrderData .service-charge input#inputServiceBelow").addClass('border-danger-response')
         }
       } 
     });
   }
})


$(".page_purchaseOrderData .service-charge").on('click','.charge',function(){
 let val = $(".page_purchaseOrderData .service-charge input#inputServiceCharge");
  if(val.prop("disabled")){
    val.prop("disabled",false)
  }  
})
$(".page_purchaseOrderData .service-charge").on('keyup','input#inputServiceCharge',function(e){
  let key = e.keyCode;
  if(key == 13){
    $(this).addClass  ('border-success')
    $(this).prop('disabled',true)
    $.ajax({
      url: 'ajax/ajax.purchase.php',
      type: "POST",
      dataType:'json',
      data: {
        action: 'update_service_chage',
        charge: $(this).val()
      },
      success: function(_response){
        console.log(_response['message'])
        if(_response['message'] == "OK"){
          $(".page_purchaseOrderData .service-charge input#inputServiceCharge").removeClass('border-success')
          // $(".page_purchaseOrderData .service-charge input#inputServiceCharge").addClass('border-success-response')
        } else{
          $(".page_purchaseOrderData .service-charge input#inputServiceCharge").removeClass('border-success')
          // $(".page_purchaseOrderData .service-charge input#inputServiceCharge").addClass('border-danger-response')
        }
      } 
    });
  }
 })