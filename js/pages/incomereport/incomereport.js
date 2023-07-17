let incomedeal;

$(function() {
    //ตาราง Members
    incomedeal = $('#incomereport-grid').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.incomereport.php",
            type: "post",
            dataType: 'json',
            data: function(d) { 
                d.action = "get_incomereport"; 
                d.start_date =  $("#add-date-display-start").val();
                d.expire_date =  $("#add-date-display-expire").val();
                if($("#slc_action").val() !== "all"){
                    d.method = $("#slc_action").val();
                } 
            },
            complete: function(response){  
                if(response['responseJSON']['netpay'] !== undefined){
                   let netpay =  response['responseJSON']['netpay'];
                    $(".register_total").html(netpay['register_total']);
                    $(".postpaid_total").html(netpay['post_total']);
                    $(".buypaid_total").html(netpay['buy_total']);
                    $(".netpay").html(netpay['netpay']);  
                }
            }, 
            error: function() { 
                
            }
        },
        "columnDefs": [{
            targets: [5],
            orderable: false,
        }], 
        "order": [
            [6, "DESC"]
        ],
        "pageLength": 50, 
        // complete table  total_netpay to the top
    });

    incomeregister = $('#members-income-grid').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.incomereport.php",
            type: "post",
            dataType: 'json',
            data: function(d) { 
                d.start_date =  $("#add-date-display-start").val();
                d.expire_date =  $("#add-date-display-expire").val();
                d.action = "get_incomedeal_report"; 
                if($("#slc_action").val() !== "all"){
                    d.method = $("#slc_action").val();
                } 
            },
            complete: function(response){   
                if(response['responseJSON']['netpay'] !== undefined){
                    let netpay =  response['responseJSON']['netpay'];
                     $(".register_total").html(netpay['register_total']);
                     $(".postpaid_total").html(netpay['post_total']);
                     $(".buypaid_total").html(netpay['buy_total']);
                     $(".netpay").html(netpay['netpay']); 
                 }
            }, 
            error: function() {  
            }
        },
        "columnDefs": [{
            targets: [5,6],
            orderable: false,
        }],
        
        "order": [
            [2, "DESC"]
        ],
        "pageLength": 50, 
        // complete table  total_netpay to the top
    });
});

$("#slc_action").on('change',function(){
       $(".box-body").hide(); 
     if($(this).val() == "incomedeal"){
        $("#incomedeal").show();
        incomedeal.ajax.reload(null, false);
     } else if($(this).val() == "incomeregister"){
        $("#incomeregister").show(); 
        incomeregister.ajax.reload(null, false);
    }
}); 

function reloadTable() {
    incomedeal.ajax.reload(null, false);
} 
  
/** 
 * @param {*} e 
 * @param {*} _id  
 */
 
function approvePayments(e, _id, _status) {
    e = e || window.event;
    e.preventDefault();
    console.log('delMembers ' + _id)
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
                        url: "ajax/ajax.incomereport.php",
                        type: "post",
                        dataType: "json",
                        data: { action: "approvePaymentsRequest", id: _id },
                        success: function(response) {
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
                        url: "ajax/ajax.incomereport.php",
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
 

// datepicker
$('#add-date-display-start').datepicker({
    format: 'dd/mm/yyyy',
    autoclose: true,
    language: 'th',
    todayHighlight: true
  }).on('changeDate', function (e) {
    $('#add-date-display-start-hidden').val(e.format('yyyy-mm-dd'));
    reloadTables();
  }); 

  // datepicker
  $('#add-date-display-expire').datepicker({ 
    format: 'dd/mm/yyyy',
    autoclose: true,
    language: 'th',
    todayHighlight: true,
    minDate: 0 ,
  }).on('changeDate', function (e) {
    $('#add-date-display-expire-hidden').val(e.format('yyyy-mm-dd'));
    reloadTables();
});

$(".input-group.date").on("keyup","input.inputDate",function(){     
    reloadTables();
});

function reloadTables(){
    if($("#slc_action").val() == "incomedeal"){
        incomedeal.ajax.reload(null, false); 
    } else {
        incomeregister.ajax.reload(null, false); 
    }
} 
 
$(".income-report .config-title").on("click","label",function(){ 
    $.ajax({
        url: "ajax/ajax.incomereport.php",
        type: 'POST',
        dataType: 'json',
        data: {action: "get_income_config"},
        success: function(response){ 
            /* start ajax response function */ 
            Swal.mixin({
                input: 'text', 
                confirmButtonText: 'ถัดไป &rarr;',
                cancelButtonText: 'ยกเลิก', 
                showCancelButton: true,
                progressSteps: ['1', '2', '3'], 
            }).queue([
                {
                    title: 'ค่าธรรมเนียมรายปี',
                    text: 'อัตราค่าธรรมเนียมสมาชิกต่อปี',
                    inputValue: response.register
                },  
                {
                    title: 'การเติมเงิน (ครั้งแรก)',
                    text: 'อัตราการเติมเงินขั้นต่ำครั้งแรก',
                    inputValue: response.first_time
                },
                {
                    title: 'การเติมเงินขั้นต่ำ',
                    text: 'อัตราการเติมเงินขั้นต่ำ',
                    inputValue: response.minimum
                } 
            ]).then((result) => { 
                if (result.value) {
                    // const answers = JSON.stringify(result.value) 
                    let answers = result.value;
                    Swal.fire({
                        title: 'ตั้งค่าอัตราค่าบริการ!',
                        html: ` 
                        <pre style="background-color: #0e1a29; color:white;font-size: 1em;"> 
                            <div><code>ค่าธรรมเนียมรายปี: ${result.value[0]} บาท</code></div> 
                            <div><code>การเติมเงินครั้งแรก: ${result.value[1]} บาท</code></div> 
                            <div><code>การเติมเงินขั้นต่ำ: ${result.value[2]} บาท</code></div>  
                        </pre>
                        `,
                        confirmButtonText: 'บันทึก!',  
                        showCancelButton: "no",  
                    }).then((result)=>{ 
                        if(result.value){ 
                            param = {
                                action: "update_config", 
                                register: answers[0],
                                first_time: answers[1],
                                minimum: answers[2]
                            }
                            $.ajax({
                                url: "ajax/ajax.incomereport.php",
                                type: 'POST',
                                dataType: 'json',
                                data: param,
                                success: function(response){
                                    if(response['status'] == 200){
                                        Swal.fire({
                                            position: 'top-center',
                                            icon: 'success',
                                            title: 'บันทึกข้อมูลเสร็จสิ้น!',
                                            showConfirmButton: false,
                                            timer: 1500
                                        }) 
                                        $(".register-msg").html(answers[0]+" บาท");
                                        $(".fist-time-msg").html(answers[1]+" บาท");
                                        $(".minimum-msg").html(answers[2]+" บาท");
                                    } 
                                }
                            })
                        }

                    /* end of update_config */
                    }); 
                }
            })


        /* end of ajax response function */
        } 
    })
});
 
