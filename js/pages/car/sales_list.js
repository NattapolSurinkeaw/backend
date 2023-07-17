let salesTable;
let replyTable;
$(function () {
    //ตาราง sales
    salesTable = $('#sales-grid').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.sales.php",
            data: { action: "get_salesList" },
            type: "post",
            error: function () {
             
            }
        },
        "columnDefs": [{
            targets: [0, 7],
            orderable: false,
        }],
        "order": [[5, "desc"]],
    });


    //คลิกดูรายละเอียดของ Sales มุมมองก่อนพิมพ์
    $(document).on('click', '.bt-view', function () {
        $('#sales_name_view').html($(this).data('sales'));
        let sales_id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "ajax/ajax.sales.php",
            dataType: 'html',
            data: {
                action: "get_sales_print",
                id: sales_id
            },
            success: function (response) {
              $('#table-view').html(response);
              $('#modal-view').modal('show');
            }
        });
    });

    $('#printOut').click(function (e) {
        e.preventDefault();
        console.log(url_ajax_request + 'css/print/style-car-print.css');
        mywindow = PopupCenter('','','1000','700');  
        mywindow.document.write('<html><head><title>ผู้แนะนำ</title>');
        mywindow.document.write('<link href="https://fonts.googleapis.com/css?family=Sarabun:200,300,400,500,600,700,800&display=swap" rel="stylesheet">');
        mywindow.document.write(`
            <style> 
                body {
                    font-family: 'Sarabun', sans-serif;
                }
                p{
                    margin: 0;
                }
    
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                    
                }
                table, th, td {
                    border: 1px solid #e3e3e3;
                }
                
    
                table > tbody > tr:first-child > td{
                    // background: rgba(0,0,0,0.5);
                }
                table > tbody > tr:first-child > td > center{
                    // color: white;
                    line-height: 50px;
                    // border: 1px solid #e3e3e3;
                    text-decoration: underline;
                    font-weight: bold;
                }
                table > tbody > tr {
                    text-align:center;
                    line-height: 30px;
                }
                
                table > tbody > tr:nth-child(2) > th{
                    // background: steelblue;
                    width: 50% !important;
                }
                
                table > tbody > tr:not(:first-child) > th{
                    font-weight: lighter !important;
                    text-align: left;
                    padding-left: 20px;
                }
                
                table > tbody > tr:not(:first-child) > td {
                    text-align:center;
                    text-indent: 5%;
                    font-weight: 300 !important;
                    font-size: 15px !important;
                }
            
            </style>
       `);
        mywindow.document.write('</head><body>');
        mywindow.document.write($('#table-view').html());
        mywindow.document.write('</body></html>');
       // mywindow.document.close();
        setTimeout(() => {
            mywindow.print();
            mywindow.close();
    
        }, 1000);
    
        return false;
    });
    

    // ========= แก้ไข ข้อมูล Sales =========
    //คลิกปุ่มแก้ไขข้อมูลของพนักงานฝ่ายขาย Sales
    $(document).on('click', '.bt-edit', function () {

        var sales_id = $(this).data("id");
        $('#sales_id_edit').val(sales_id);
        $.ajax({
            type: "POST",
            url: "ajax/ajax.sales.php",
            dataType: 'json',
            data: {
                action: "get_sales",
                id: sales_id
            },
            beforeSend: function () { },
            success: function (response) {
                $('#titleNameSale').val(response.title);
                $('#nameSale').val(response.name);
                $('#lineSale').val(response.line);
                $('#phoneSale').val(response.phone);
                $("#saleBrand option[value=" + response.brand + "]").attr('selected', 'selected');
                $('#nameWorkplaceSale').val(response.workplace);
                $('#workplaceBranchSale').val(response.branch);
                $("#workplaceProvinceSale option[value=" + response.province + "]").attr('selected', 'selected');

                $("#statusSale option[value=" + response.status + "]").attr('selected', 'selected');
                $('#img-profile').css('background-image', 'url(' + root_url + response.profile + ')');
                $('#img-profile').css('background-size', 'cover');

                $('#img-card').css('background-image', 'url(' + root_url + response.businesscards + ')');
                $('#img-card').css('background-size', 'cover');

                $('#modal-editSale').modal('toggle');
            }
        });
    });


    //validations sales
    $("#form-edit-sales").validate({
        invalidHandler: function (form, validator) {
            $(validator.errorList[0].element).focus();
        },
        errorElement: "label",
        ignore: ".ignore",
        rules: {},
        errorPlacement: function (error, element) {
            let name = element.attr('name');
            $('.' + name + '-error').show();
        },
        highlight: function (element, errorClass, validClass) {
            $(element).closest(".form-group").addClass("has-error");
        },
        unhighlight: function (element, errorClass, validClass) {
            $('.' + $(element).attr('name') + '-error').hide();
            $(element).closest(".form-group").removeClass("has-error");
        }
    });

    //คลิกบันทึกแก้ไขข้อมูล Sales
    $("#save-edit-sales").on("click", function () {
        //validation
        if (!$("#form-edit-sales").valid()) {
            return false;
        } else {
            $.ajax({
                url: "ajax/ajax.sales.php",
                type: 'post',
                dataType: 'json',
                data: $('#form-edit-sales').serialize(),
                beforeSend: function () { },
                complete: function (response) {
                    if (response.status == 200) {
                        $.confirm({
                            title: 'เสร็จสิ้น',
                            content: 'ทำการอัพเดตข้อมูลพนักงานฝ่ายขายเรียบร้อย',
                            theme: 'modern',
                            icon: 'fa fa-check',
                            type: 'green',
                            typeAnimated: true,
                            buttons: {
                                tryAgain: {
                                    text: 'ตกลง',
                                    btnClass: 'btn-green',
                                    action: function () {
                                      //  location.reload();
                                      $('#modal-editSale').modal('toggle');
                                      reloadTable();
                                    }
                                }
                            }
                        });
                    }
                }
            });
        }
    });

    //  จบ แก้ไข ข้อมูล Sales ==========


    //คลิกดูแบบกลับของพนักงานขาย
    $(document).on('click', '.bt-view-table', function () {
        $('#sales_name').html($(this).data('sales'));
        $('#sales_id').val($(this).data('id'));
        $('#modal-reply-table').modal('toggle');
    });


    //ตรวจสอบการเปิด Popup
    $('#modal-reply-table').on('shown.bs.modal', function () {
        if (replyTable != null) { replyTable.destroy(); }
        replyTable = $('#custoemrReply').DataTable({
            "language": { "url": 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Thai.json' },
            "scrollX": true,
            "pageLength": 10,
            "lengthChange": false,
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "ajax/ajax.sales.php",
                data: { action: "get_customer_reply", id: $('#sales_id').val() },
                type: "post"
            },
            "columnDefs": [{
                targets: [5],
                orderable: false,
            }],
            "order": [[0, "desc"]]
        });
    });


    //คลิกปุ่มดูรายละเอียดแบบตอบกลับลูกค้า
    $(document).on('click', '.bt-view-reply', function () {
        console.log("VIEW REPLY PRINT");
        $('#sales_name2').html($('#sales_name').html());
        let reply_id = $(this).data('id');
        
        //ดึงข้อมูลสำหรับพิมพ์
        $.ajax({
            type: "POST",
            url: "ajax/ajax.sales.php",
            dataType: 'json',
            data: {
                action: "get_reply_print",
                id: reply_id
            },
            success: function (response) {
                /*
                $('#text_name').html(response.title + response.name);
                $('#text_phone').html(response.phone);
                $('#text_line').html(response.line);
                $('#text_brandCar').html(response.car_brand);
                $('#text_company').html(response.workplace);
                $('#text_branch').html(response.brand);
                $('#text_province').html(response.province_name);
                $('#text_status').html(response.status);
                $('#img-view-profile').attr('src', root_url + response.profile);
                $('#img-view-personalCard').attr('src', root_url + response.businesscards);
                */

                $('#modal-customerReply').modal('toggle');
            }
        });
    });

    // ลบข้อมูลตอบกลับของลูกค้าคนนั้น =================
    $(document).on('click', '.bt-delete-reply', function () {
        console.log("DELTE REPLY");
        let customerName = $(this).data('customer');
        var data = {
            action: "delete_reply",
            id: $(this).data("id")
        };
        $.confirm({
            title: 'ยืนยัน?',
            content: 'ลบข้อเสนอของ <b>'+customerName+' </b> ใช่หรือไม่?',
            theme: 'material',
            icon: 'fa fa-warning',
            type: 'red',
            draggable: false,
            buttons: {
                confirm: {
                    btnClass: 'btn-red',
                    text: 'ยืนยัน',
                    action: function () {
                        delete_reply(data);
                    }
                },
                formCancel: {
                    text: 'ยกเลิก'
                }
            }
        });
    });
    // จบ ลบข้อมูลตอบกลับของลูกค้าคนนั้น ===============



    // ลบข้อมูล Sales =================
    $(document).on('click', '.bt-delete', function () {
        console.log("DELTE");
        var data = {
            action: "delete_sales",
            id: $(this).data("id")
        };
        $.confirm({
            title: 'ยืนยัน?',
            content: 'คุณต้องการลบพนักงานขายใช่หรือไม่?',
            theme: 'material',
            icon: 'fa fa-warning',
            type: 'red',
            draggable: false,
            buttons: {
                confirm: {
                    btnClass: 'btn-red',
                    text: 'ยืนยัน',
                    action: function () {
                        delete_sales(data);
                    }
                },
                formCancel: {
                    text: 'ยกเลิก'
                }
            }
        });
    });

    // จบ ลบข้อมูล Sales =================


});

//ฟังก์ชั่นลบพนักงานขาย
function delete_sales(data) {
    var url = "ajax/ajax.sales.php",
        dataSet = data;
    $.ajax({
        type: "POST",
        url: url,
        data: dataSet,
        dataType: 'json',
        success: function (response) {
            if (response.message === "OK") {
                $.confirm({
                    title: 'ลบข้อมูลเรียบร้อย',
                    content: 'ข้อมูลพนักงานขายถูกลบแล้ว',
                    theme: 'modern',
                    icon: 'fa fa-check',
                    type: 'darkgreen',
                    draggable: false,
                    backgroundDismiss: true,
                    buttons: {
                        confirm: {
                            text: 'ตกลง',
                            btnClass: 'btn-darkgreen',
                            action: function () {
                                // location.reload();
                                reloadTable();
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


//ฟังก์ชั่นลบข้อเสนอลูกค้า
function delete_reply(data) {
    var url = "ajax/ajax.sales.php",
        dataSet = data;
    $.ajax({
        type: "POST",
        url: url,
        data: dataSet,
        dataType: 'json',
        success: function (response) {
            if (response.message === "OK") {
                $.confirm({
                    title: 'ลบข้อมูลเรียบร้อย',
                    content: 'ข้อมูลเสนอลูกค้าถูกลบแล้ว',
                    theme: 'modern',
                    icon: 'fa fa-check',
                    type: 'darkgreen',
                    draggable: false,
                    backgroundDismiss: true,
                    buttons: {
                        confirm: {
                            text: 'ตกลง',
                            btnClass: 'btn-darkgreen',
                            action: function () {
                                reloadTableReply();
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


function reloadTable() {
    salesTable.ajax.reload(null, false);
}

function reloadTableReply() {
    replyTable.ajax.reload(null, false);
}

function PopupCenter(pageURL, title,w,h) {
    var left = (screen.width/2)-(w/2);
    var top = (screen.height/2)-(h/2);
    var targetWin = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
    return targetWin;
  } 