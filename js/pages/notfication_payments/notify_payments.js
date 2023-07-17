let paymentsTable;
 
$(function() {
    //ตาราง Members
    paymentsTable = $('#notifypayments-grid').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.notify_payments.php",
            data: function(d) {  
                d.action = "get_notify_payments"; 
                if($("#slc_action").val() !== "all"){
                    d.method = $("#slc_action").val();
                }
                // if($("#slc_status").val() !== "all"){
                //     d.status_list = $("#slc_status").val();
                // } 
            }, 
            type: "post",
      
            error: function() { 
            },
            complete: function(response){
                let total = response['responseJSON']['recordsTotal'];
                $("#blog-payments").data('id',total);
            }
        },
        "columnDefs": [{
            targets: [0,1,4,5,6,9,10],
            orderable: false,
        }],
        "order": [
            [7, "DESC"]
        ],
        "pageLength": 50,
    });
});
$("#slc_action").on('change',function(){
    reloadTable();
});
 

function reloadTable() {
    paymentsTable.ajax.reload(null, false);
} 
  
/** 
 * @param {*} e 
 * @param {*} _id  
 */
 
function approvePayments(e, _id, _status) {
    e = e || window.event;
    e.preventDefault();
   
    $.confirm({
        title: 'อนุมัติ',
        content: 'รายการเลขที่ '+_id+' จะได้รับการอนุมัติ',
        theme: 'modern',
        icon: 'fa fa-check',
        type: 'green',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: 'ยืนยัน',
                btnClass: 'btn-green',
                action: function() { 
                    $.ajax({
                        url: "ajax/ajax.notify_payments.php",
                        type: "post",
                        dataType: "json",
                        data: { action: "approvePaymentsRequest", id: _id },
                        success: function(response) {
                            if(response['type'] == 'withdraw'){
                                withdraw_update(response);
                            } else {
                                $.confirm({
                                    title: 'สำเร็จ',
                                    content: 'ทำรายการสำเร็จ',
                                    theme: 'modern',
                                    icon: 'fa fa-check',
                                    type: 'green',
                                    typeAnimated: true,
                                    buttons: {
                                        tryAgain: {
                                            text: 'ตกลง',
                                            btnClass: 'btn-green',
                                            action: function() { 
                                                $(".notify_number").html(response['total']);
                                                reloadTable();
                                           
                                            }
                                        }
                                    }
                                });
                            }  
                        }
                    })
                }
            },
            formCancel: {
                text: 'ยกเลิก',
                btnClass: 'btn-red',
                cancel: function() {}
            }
        }
    });
    $('#email_err').text('');
} 

function withdraw_update(data){
    Swal.mixin({ 
        input: 'text',
        confirmButtonText: 'ถัดไป &rarr;',
        showCancelButton:false, 
        progressSteps: ['1']
      }).queue([ 
          {
            title: 'บันทึกข้อมูลการถอนเงิน',
            text: 'วันที่โอน',
            inputValue: data['current_date'],
            inputPlaceholder: '01-01-2020 30:10:00 (วัน-เดือน-ปี ชั่วโมง:นาที:วินาที)',
            inputAttributes: {
                class: 'input-date-update'
            },
          } 
      ]).then((result) => {
        let date_time = result.value[0];
        let id = data['id'];
        $.ajax({
            url: "ajax/ajax.notify_payments.php",
            type: 'POST',
            dataType: 'json',
            data: {action:"update_record_withdraw", date_time ,id },
            success: function(response){ 
                console.log(response);
                if (response['message'] == "OK"){  
                    Swal.fire({
                    html: ` <pre class="notification-pre">
                        <code class="noti-pre-head">บันทึกการถอนเงิน สำเร็จ!</code>
                         <code>จำนวน: ${data['b_credit']} บาท</code> 
                          <code>ชื่อบัญชีผู้รับ: ${data['name']}</code>
                          <code>ธนาคาร: ${data['b_name']}</code>
                          <code>เลขที่บัญชี: ${data['b_number']}</code>
                          <code>วันที่: ${date_time} น.</code>
                         </pre> 
                      `,
                      confirmButtonText: 'ปิด!'
                    }).then((result) => {
                        location.reload();
                    });
                  } 
            }
        }) 
      });  



    console.log(param);
}

/** 
 * @param {*} e 
 * @param {*} _id  
 */
 
function declinePayments(e, _id) {
    e = e || window.event;
    e.preventDefault();
    console.log('delMembers ' + _id)
    $.confirm({
        title: 'ไม่อนุมัติ',
        content: 'รายการเลขที่ '+_id+' จะถูกปฏิเสธการอนุมัติ',
        theme: 'modern',
        icon: 'fa fa-ban',
        type: 'red',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: 'ยืนยัน',
                btnClass: 'btn-green',
                action: function() {
                    
                    $.ajax({
                        url: "ajax/ajax.notify_payments.php",
                        type: "post",
                        dataType: "json",
                        data: { action: "declinePaymentsRequest", id: _id  },
                        success: function(response) {
                            $.confirm({
                                title: 'สำเร็จ',
                                content: 'ปฏิเสธรายการสำเร็จ',
                                theme: 'modern',
                                icon: 'fa fa-check',
                                type: 'green',
                                typeAnimated: true,
                                buttons: {
                                    tryAgain: {
                                        text: 'ตกลง',
                                        btnClass: 'btn-green',
                                        action: function() { 
                                            $(".notify_number").html(response['total']);
                                            reloadTable();
                                       
                                        }
                                    }
                                }
                            });
                        }
                    })
                }
            },
            formCancel: {
                text: 'ยกเลิก',
                btnClass: 'btn-red',
                cancel: function() {}
            }
        }
    });
    $('#email_err').text('');
}
 