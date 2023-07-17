let customerTable;
let replyTable;
let defaultSubBrandCar;
$(function () {

    
    $('#printOut').click(function (e) {
        e.preventDefault();
        console.log(url_ajax_request + 'css/print/style-car-print.css');
        var mywindow = window.open('', '', 'resizable=no,height=900,width=800');
        mywindow.document.write('<html><head><title>แบบเสนอลูกค้า</title>');
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
        setTimeout(() => {
            mywindow.print();

        }, 1000);

        return false;
    });

    //คลิกปุ่มดูรายละเอียดแบบตอบกลับลูกค้า
    $(document).on('click', '.bt-view', function () {
        var customer_id = $(this).data("id");
        $.ajax({
            type: "POST",
            url: "ajax/ajax.customer.php",
            dataType: 'text',
            data: {
                action: "get_customer_print",
                id: customer_id
            },
            beforeSend: function () { },
            success: function (response) {
                $('#table-view').html(response);
                $('#modal-view').modal('show');
            }
        });
    }); 

    // ========= แก้ไข ข้อมูล customer =========

    //คลิกปุ่มแก้ไขข้อมูลลูกค้า
    $(document).on('click', '.bt-edit', function () {

        var customer_id = $(this).data("id");
        $('#customer_id_edit').val(customer_id);
        $.ajax({
            type: "POST",
            url: "ajax/ajax.customer.php",
            dataType: 'json',
            data: {
                action: "get_customer",
                id: customer_id
            },
            beforeSend: function () { },
            success: function (response) {

                defaultSubBrandCar = response.car_model;

                $('#titleName').val(response.titleName);
                $('#name').val(response.name);
                $('#phoneNumber').val(response.phoneNumber);
                $('#lineID').val(response.lineID);
                $("#province option[value=" + response.province + "]").prop('selected', true);
                $("#categoryCar option[value=" + response.car_type_id + "]").prop('selected', true);
                $("#colorCar option[value=" + response.car_color + "]").prop('selected', true);
                $("#brandCar option[value=" + response.car_brand_id + "]").prop('selected', true);
                $('#downPaymentPercent').val(response.downPaymentPercent);
                $('#downPayment').val(response.downPayment);
                $('#installment').val(response.installment);
                $('#customerRequire').val(response.customerRequire);
                $("#carStatus  option[value=" + response.car_status + "]").prop('selected', true);
                $('#modal-editCustomer').modal('show');
                $('#brandCar').change();


            }
        });
    });

    $(document).on('change', '#categoryCar', function () {

        $('#brandCar option:first').prop('selected', true);
        clearCarValue();

        let car_brand = $('#brandCar option:selected').val();
        if (car_brand != '') {
            $('#brandCar').change();
        }
    }); 


    //เมื่อมีการเลือกยี่ห้อรถยนต์
    $('#brandCar').change(function () {
        let car_cat = $('#categoryCar option:selected').val();
        let car_brand = $('#brandCar option:selected').val();

        if (car_cat == "") {
            $.confirm({
                title: 'แจ้งเตือน',
                content: 'กรุณาเลือกประเภทรถยนต์',
                theme: 'modern',
                type: 'orange',
                typeAnimated: true,
                buttons: {
                    tryAgain: {
                        text: 'ตกลง',
                        btnClass: 'btn-orange'
                    }
                }
            });
        } else {

            $.ajax({
                type: "POST",
                url: "ajax/ajax.customer.php",
                dataType: 'json',
                data: {
                    action: "get_car_model",
                    'car_cat': car_cat,
                    'car_brand': car_brand
                },
                beforeSend: function () {
                    clearCarValue();
                },
                success: function (response) {
                    if (response != 'no_result') {
                        $.each(response, function (key, value) {
                            $('#subbrandCar').append("<option value='" + value.car_model_id + "' data-price='" + value.car_model_price + "'>" + value.car_model + "</option>");
                        });
                        if (defaultSubBrandCar != 0) {
                            $("#subbrandCar option[value=" + defaultSubBrandCar + "]").prop('selected', true);
                            $('#subbrandCar').change();
                            defaultSubBrandCar = 0;
                        }
                    }
                }
            });

        }
    });


    //validations customer
    $("#form-edit-customer").validate({
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

    //คลิกบันทึกแก้ไขข้อมูล customer
    $("#save-edit-customer").on("click", function () {
        //validation
        if (!$("#form-edit-customer").valid()) {
            return false;
        } else {
            $.ajax({
                url: "ajax/ajax.customer.php",
                type: 'post',
                dataType: 'json',
                data: $('#form-edit-customer').serialize(),
                beforeSend: function () { },
                complete: function (response) {
                    if (response.status == 200) {
                        $.confirm({
                            title: 'เสร็จสิ้น',
                            content: 'ทำการอัพเดตข้อมูลลูกค้าเรียบร้อย',
                            theme: 'modern',
                            icon: 'fa fa-check',
                            type: 'green',
                            typeAnimated: true,
                            buttons: {
                                tryAgain: {
                                    text: 'ตกลง',
                                    btnClass: 'btn-green',
                                    action: function () {
                                        $('#modal-editCustomer').modal('toggle');
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


    //  จบ แก้ไข ข้อมูล customer ==========

    //คัดลอกลิงค์  
    $(document).on('click', '.bt-link', function () {
        console.log("Copy Link");
        let link = $(this).data('link');
        $('#link_copy').val(link);
        $('#modal-view-link').modal('toggle');
    });

    $(document).on('click', '#bt_copy_link', function () {
        $('#copy_complete').slideDown('slow');
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($('#link_copy').val()).select();
        document.execCommand("copy");
        $temp.remove();
    });

    $('#modal-view-link').on('hide.bs.modal', function (e) {
        if (e.namespace == 'bs.modal') {
            $('#copy_complete').hide();
            $('#link_copy').val('');
        }
      }); 

    // ลบข้อมูล customer =================
    $(document).on('click', '.bt-delete', function () {
        console.log("DELTE");
        let customer_name = '<strong>' + $(this).data('customer') + '</strong>';
        var data = {
            action: "delete_customer",
            id: $(this).data("id")
        };
        $.confirm({
            title: 'ยืนยันลบข้อมูล?',
            content: 'ต้องการลบข้อมูล ' + customer_name + ' ใช่หรือไม่?',
            theme: 'material',
            icon: 'fa fa-warning',
            type: 'red',
            draggable: false,
            buttons: {
                confirm: {
                    btnClass: 'btn-red',
                    text: 'ยืนยัน',
                    action: function () {
                        delete_customer(data);
                    }
                },
                formCancel: {
                    text: 'ยกเลิก'
                }
            }
        });

    });

    // จบ ลบข้อมูล customer =================
 
 

    //ตาราง customer
    customerTable = $('#customer-grid').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.customer.php",
            data: { action: "get_customerList" },
            type: "post",
            error: function () {}
        },
        "columnDefs": [{
            targets: [6],
            orderable: false,
        }],
        "order": [[0, "asc"]]
    });
 
 
});


//ฟังก์ชั่นลบพนักงานขาย
function delete_customer(data) {
    var url = "ajax/ajax.customer.php",
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
                    content: 'ข้อมูลลูกค้าถูกลบแล้ว',
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

 

function reloadTable() {
    customerTable.ajax.reload(null, false);
}
 
function clearCarValue() {
    $('#priceCar').val('');
    $('#subbrandCar').html("<option value=''>รุ่นย่อย</option>");
}