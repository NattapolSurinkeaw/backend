let bookcarTable;
let defaultDistrict;
let defaultSubdistrict;
let defaultDay;
let defaultAuspicious;
$(function () {
    // ========= แก้ไข ข้อมูล bookcar =========

    //คลิกปุ่มแก้ไขข้อมูลของพนักงานฝ่ายขาย bookcar
    $(document).on('click', '.bt-edit', function () {

        var bookcar_id = $(this).data("id");
        $('#bookcar_id_edit').val(bookcar_id);
        $('#bookcar_customer_edit').html($(this).data('customer'));

        $.ajax({
            type: "POST",
            url: "ajax/ajax.bookcar.php",
            dataType: 'json',
            data: {
                action: "get_bookcar",
                id: bookcar_id
            },
            success: function (response) {
              
                defaultDistrict = response.district;
                defaultSubdistrict = response.subDistrict;

                let day_book = response.date_receive.split('-');
                defaultDay = day_book[2];
                defaultAuspicious = response.auspicious;

                $('#conditionCar').html(response.conditionCar);
                $('#titleName').val(response.titleName);
                $('#name').val(response.name);
                $('#phone').val(response.phoneNumber);
                $("#month option[value=" + day_book[1] + "]").prop('selected', true);
                $("#bank_destination option[value=" + response.bank + "]").prop('selected', true);
                $('#month').change();

                $('#address').html(response.address);
                $("#province option[value=" + response.province + "]").prop('selected', true);
                $("#carStatus  option[value=" + response.car_status + "]").prop('selected', true);
                $('#province').change();
                $('#modal-edit').modal('toggle');
            }
        });
    });



    //เลือกเดือน
    $('#month').change(function () {
        let month = $('#month option:selected').val();
        if (month != '') {
            //วันที่รับรถ
            $.ajax({
                type: "POST",
                url: "ajax/ajax.bookcar.php",
                dataType: 'json',
                data: {
                    action: "get_auspicious",
                    month: month
                },
                success: function (response) {
                    $('#day').html("<option value=''>เลือกวันที่รับรถ</option>");
                    if (response != 'no_result') {
                        $.each(response, function (key, value) {
                            let valOption = value.id + ':' + value.day_select;
                            $('#day').append("<option value='" + valOption + "'>" + value.detail + "</option>");
                        });
                        if (defaultDay != 0) {
                            $("#day option[value='" +(defaultAuspicious+':'+defaultDay) + "']").prop('selected', true);
                            defaultDay = 0;
                        }
                    }
                }
            });
        }
    });


    //เลือกจังหวัด
    $('#province').change(function () {
        let province = $('#province option:selected').val();
        if (province != '') {
            //วันที่รับรถ
            $.ajax({
                type: "POST",
                url: "ajax/ajax.bookcar.php",
                dataType: 'html',
                data: {
                    action: "get_district",
                    province: province
                },
                success: function (response) {
                    $('#district').html("<option value=''>เลือกเขต/อำเภอ</option>");
                    $('#district').append(response);
                    if (defaultDistrict != 0) {
                        $("#district option[value=" + defaultDistrict + "]").prop('selected', true);
                        defaultDistrict = 0;
                        $('#district').change();
                    }
                }
            });
        }
    });

    //เลือกอกำเภอ
    $('#district').change(function () {
        let district = $('#district option:selected').val();
        if (district != '') {
            //วันที่รับรถ
            $.ajax({
                type: "POST",
                url: "ajax/ajax.bookcar.php",
                dataType: 'html',
                data: {
                    action: "get_subdistrict",
                    district: district
                },
                success: function (response) {
                    $('#subDistrict').html("<option value=''>เลือกแขวง/ตำบล</option>");
                    $('#subDistrict').append(response);
                    if (defaultSubdistrict != 0) {
                        $("#subDistrict option[value=" + defaultSubdistrict + "]").prop('selected', true);
                        defaultSubdistrict = 0;
                        $('#subDistrict').change();
                    }
                }
            });
        }
    });

    //เลือกตำบล
    $('#subDistrict').change(function () {
        let postcode = $('#subDistrict option:selected').attr('id');
        if (subDistrict != '') {
            $('#postID').val(postcode);
            $("#form_bookcar").validate().element('#postID');
        }
    });



    //validations bookcar
    $("#form_bookcar").validate({
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

    //คลิกบันทึกแก้ไขข้อมูล bookcar
    $("#save-edit-bookcar").on("click", function () {
        //validation
        if (!$("#form_bookcar").valid()) {
            return false;
        } else {
            $.ajax({
                url: "ajax/ajax.bookcar.php",
                type: 'post',
                dataType: 'json',
                data: $('#form_bookcar').serialize(),
                beforeSend: function () { },
                complete: function (response) {
                    if (response.status == 200) {
                        $.confirm({
                            title: 'เสร็จสิ้น',
                            content: 'ทำการอัพเดตข้อมูลการจองเรียบร้อย',
                            theme: 'modern',
                            icon: 'fa fa-check',
                            type: 'green',
                            typeAnimated: true,
                            buttons: {
                                tryAgain: {
                                    text: 'ตกลง',
                                    btnClass: 'btn-green',
                                    action: function () {
                                        reloadTable();
                                        $('#modal-edit').modal('toggle');
                                    }
                                }
                            }
                        });
                    }
                }
            });
        }
    });


    //  จบ แก้ไข ข้อมูล bookcar ==========

    // ลบข้อมูล bookcar =================
    $(document).on('click', '.bt-delete', function () {
        console.log("DELTE");
        let customerName = $(this).data('customer');
        var data = {
            action: "delete_bookcar",
            id: $(this).data("id")
        };
        $.confirm({
            title: 'ยืนยัน?',
            content: 'ลบการจองรถของ<b>'+customerName+'</b>ใช่หรือไม่?',
            theme: 'material',
            icon: 'fa fa-warning',
            type: 'red',
            draggable: false,
            buttons: {
                confirm: {
                    btnClass: 'btn-red',
                    text: 'ยืนยัน',
                    action: function () {
                        delete_bookcar(data);
                    }
                },
                formCancel: {
                    text: 'ยกเลิก'
                }
            }
        });
    });

    // จบ ลบข้อมูล bookcar =================


    //ตาราง bookcar
    bookcarTable = $('#bookcar-grid').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.bookcar.php",
            data: { action: "get_bookcarList" },
            type: "post",
            error: function () {
               
            }
        },
        "columnDefs": [{
            targets: [5],
            orderable: false,
        }],
        "order": [[0, "desc"]]
    });

});

//คลิกดูแบบกลับของพนักงานขาย
$(document).on('click', '.bt-view', function () {

    var bookcar_id = $(this).data("id");
    $('#bookcar_customer').html($(this).data('customer'));
    $.ajax({
        type: "POST",
        url: "ajax/ajax.bookcar.php",
        dataType: 'html',
        data: {
            action: "get_bookcar_print",
            id: bookcar_id
        },
        beforeSend: function () { },
        success: function (response) {
            $('#table-view').html(response);
            $('#modal-view').modal('toggle');
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


//ฟังก์ชั่นลบพนักงานขาย
function delete_bookcar(data) {
    var url = "ajax/ajax.bookcar.php",
        dataSet = data;
    $.ajax({
        type: "POST",
        url: url,
        data: dataSet,
        dataType: 'json',
        success: function (response) {
            if (response.message === "OK") {
                reloadTable();
            }else{
                location.reload();
            }
        }
    });
}

function reloadTable() {
    bookcarTable.ajax.reload(null, false);
}
 
function PopupCenter(pageURL, title,w,h) {
    var left = (screen.width/2)-(w/2);
    var top = (screen.height/2)-(h/2);
    var targetWin = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
    return targetWin;
  } 