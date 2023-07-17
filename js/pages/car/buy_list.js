let buyTable;
$(function () {

    //ตารางซื้อลูกค้า
    buyTable = $('#buy-grid').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.buy_customer.php",
            data: { action: "get_buyList" },
            type: "post",
            error: function () { }
        },
        "columnDefs": [{
            targets: [2, 5, 8],
            orderable: false,
        }],
        "order": [[0, "desc"]],
        
    });

    //คลิกปุ่มดูรายละเอียดแบบตอบกลับลูกค้า
    $(document).on('click', '.bt-view', function () {
        let buy_id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "ajax/ajax.buy_customer.php",
            dataType: 'text',
            data: {
                action: "get_customer_print",
                id: buy_id
            },
            beforeSend: function () { },
            success: function (response) {
                $('#table-view').html(response);
                $('#modal-view').modal('show');
            }
        });
    });

    $(document).on('click', '.bt-delete', function () {
        var data = {
            action: "delete_buy",
            id: $(this).data("id")
        };
        $.confirm({
            title: 'ยืนยันลบข้อมูล?',
            content: 'ต้องการลบข้อมูลการซื้อลูกค้าใช่หรือไม่?',
            theme: 'material',
            icon: 'fa fa-warning',
            type: 'red',
            draggable: false,
            buttons: {
                confirm: {
                    btnClass: 'btn-red',
                    text: 'ยืนยัน',
                    action: function () {
                        delete_buy(data);
                    }
                },
                formCancel: {
                    text: 'ยกเลิก'
                }
            }
        });

    });


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

});



function reloadTablebuy() {
    buyTable.ajax.reload(null, false);
}

//ฟังก์ชั่นลบพนักงานขาย
function delete_buy(data) {
    var url = "ajax/ajax.buy_customer.php",
        dataSet = data;
    $.ajax({
        type: "POST",
        url: url,
        data: dataSet,
        dataType: 'json',
        success: function (response) {
            if (response.message === "OK") {
                reloadTablebuy();
            } else { location.reload(); }
        }
    });
}
