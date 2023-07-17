let advisorTable;
var mywindow;
$(function () {
    // ลบข้อมูล advisor =================
    $(document).on('click', '.bt-delete', function () {
        console.log("DELTE");
        var data = {
            action: "delete_advisor",
            id: $(this).data("id")
        };
        $.confirm({
            title: 'ยืนยัน?',
            content: 'คุณต้องการลบผู้แนะนำใช่หรือไม่?',
            theme: 'material',
            icon: 'fa fa-warning',
            type: 'red',
            draggable: false,
            buttons: {
                confirm: {
                    btnClass: 'btn-red',
                    text: 'ยืนยัน',
                    action: function () {
                        delete_advisor(data);
                    }
                },
                formCancel: {
                    text: 'ยกเลิก'
                }
            }
        });

    });

    // จบ ลบข้อมูล advisor =================


    //ตาราง advisor
    advisorTable = $('#advisor-grid').DataTable({
        "scrollX": true,
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "ajax/ajax.advisor.php",
            data: { action: "get_advisorList" },
            type: "post",
            error: function () {
                $(".employee-grid-error").html("");
                $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#employee-grid_processing").css("display", "none");
            }
        },
        "columnDefs": [{
            targets: [2,4,5,6,8],
            orderable: false,
        }],
        "order": [[0, "asc"]]
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

   

});

//คลิกดูแบบกลับของพนักงานขาย
$(document).on('click', '.bt-view', function () {
    let advisor_id = $(this).data('id');
    $.ajax({
        type: "POST",
        url: "ajax/ajax.advisor.php",
        dataType: 'html',
        data: {
            action: "get_advisor_print",
            id: advisor_id
        },
        success: function (response) {
            $('#table-view').html(response);
            $('#modal-view').modal('show');

        }
    });
    // $('#modal-view').modal('toggle');
});


//ฟังก์ชั่นลบพนักงานขาย
function delete_advisor(data) {
    var url = "ajax/ajax.advisor.php",
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
    advisorTable.ajax.reload(null, false);
}

function PopupCenter(pageURL, title,w,h) {
    var left = (screen.width/2)-(w/2);
    var top = (screen.height/2)-(h/2);
    var targetWin = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
    return targetWin;
  } 