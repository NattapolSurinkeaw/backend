let replyTable;
$(function () {

    $('#printOut').click(function (e) {
        e.preventDefault();
        console.log(url_ajax_request + 'css/print/style-car-print.css');
        var mywindow = PopupCenter('','','1000','700');  
        mywindow.document.write('<html><head><title>แบบเสนอลูกค้า</title>');
        //  mywindow.document.write('<link rel="stylesheet" href="' + url_ajax_request + 'css/print/style-car-print.css?v=8" type="text/css" />');
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
            mywindow.close();
        }, 1000);

        return false;
    });

    //คลิกปุ่มดูรายละเอียดแบบตอบกลับลูกค้า
    $(document).on('click', '.bt-detail-reply', function () {
        console.log("VIEW REPLY DETAIL");
        let customer_id = $(this).data('customer-id');
        let reply_id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "ajax/ajax.reply.php",
            dataType: 'text',
            data: {
                action: "get_reply_print",
                customer_id: customer_id,
                reply_id: reply_id
            },
            beforeSend: function () { },
            success: function (response) {
                $('#table-view').html(response);
            }
        });
        $('#modal-customerReply').modal('toggle');
    });

    // ========= แก้ไข ข้อมูล reply =========


    // ลบข้อมูล reply =================
    $(document).on('click', '.bt-delete', function () {
        console.log("DELTE");
        let customer_name = '<strong>' + $(this).data('customer') + '</strong>';
        var data = {
            action: "delete_reply",
            id: $(this).data("id")
        };
        $.confirm({
            title: 'ยืนยันลบข้อมูล?',
            content: 'ต้องการลบแบบตอบกลับของ ' + customer_name + ' ใช่หรือไม่?',
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

    // จบ ลบข้อมูล reply

    //ตาราง reply
    replyTable = $('#reply-grid').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.reply.php",
            data: { action: "get_replyList" },
            type: "post",
            error: function () { }
        },
        "columnDefs": [{
            targets: [2, 5, 8],
            orderable: false,
        }],
        "order": [[0, "desc"]]
    });

    // window.open('/Export/PrintPdf');
    //คลิกปุมพิมพ์

});


//ฟังก์ชั่นลบพนักงานขาย
function delete_reply(data) {
    var url = "ajax/ajax.reply.php",
        dataSet = data;
    $.ajax({
        type: "POST",
        url: url,
        data: dataSet,
        dataType: 'json',
        success: function (response) {
            if (response.message === "OK") {
                reloadTable();
            } else { location.reload(); }
        }
    });
}

function reloadTable() {
    replyTable.ajax.reload(null, false);
}
function PopupCenter(pageURL, title,w,h) {
    var left = (screen.width/2)-(w/2);
    var top = (screen.height/2)-(h/2);
    var targetWin = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
    return targetWin;
} 