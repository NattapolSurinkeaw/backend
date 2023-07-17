$('#selectBestSaleOfMonth').datepicker({
    format: 'yyyy-mm',
    viewMode: "months",
    minViewMode: "months",
    autoclose: true,
    language: 'th',
    todayHighlight: true
}).on('changeDate', function (e) {
    // $('#add-date-display-hidden').val(e.format('yyyy-mm-dd'));
    getBestSaleOfMonth(e);
});

function getBestSaleOfMonth(e){

    $('#BestSaleOfMonth-price').remove();
    $('#BestSaleOfMonth-price-wrapper').append(`<canvas id="BestSaleOfMonth-price" ></canvas>`);

    $('#BestSaleOfMonth-qty').remove();
    $('#BestSaleOfMonth-qty-wrapper').append(`<canvas id="BestSaleOfMonth-qty" ></canvas>`);

    let _data = {
        action: "BestSaleOfMonth_price",
        month: $('#selectBestSaleOfMonth').val(),
    }


    $.ajax({
        url: "ajax/ajax.chart_order.php",
        type: "post",
        dataType: "json",
        data: _data,
        success: function (data) {
            // console.log(data)
            if(data.Count > 8){
                $('#BestSaleOfMonth-price-wrapper').removeClass('col-md-6');
                $('#BestSaleOfMonth-price-wrapper').addClass('col-md-12');
            }else{
                $('#BestSaleOfMonth-price-wrapper').removeClass('col-md-12');
                $('#BestSaleOfMonth-price-wrapper').addClass('col-md-6');
            }

            var bestsaleofmonth_price = document.getElementById('BestSaleOfMonth-price').getContext('2d');
            ChartOrderByMemberId_(bestsaleofmonth_price, "bar", data.Name, `สินค้าขายดีประจำเดือน`, data.Price, `สินค้าขายดีประจำเดือน `)
            // ChartOrderByMemberId_(bestsaleofmonth_qty, "doughnut", data.Name, `จำนวนที่ขายได้ ${data.QtyTotal}`, data.qty, `จำนวน ${data.QtyTotal} item`)
        }
    })

    let _data2 = {
        action: "BestSaleOfMonth_qty",
        month: $('#selectBestSaleOfMonth').val(),
    }


    $.ajax({
        url: "ajax/ajax.chart_order.php",
        type: "post",
        dataType: "json",
        data: _data2,
        success: function (data) {
            // console.log(data)
            if(data.Count > 8){
                $('#BestSaleOfMonth-qty-wrapper').removeClass('col-md-6');
                $('#BestSaleOfMonth-qty-wrapper').addClass('col-md-12');
            }else{
                $('#BestSaleOfMonth-qty-wrapper').removeClass('col-md-12');
                $('#BestSaleOfMonth-qty-wrapper').addClass('col-md-6');
            }

            var bestsaleofmonth_qty = document.getElementById('BestSaleOfMonth-qty').getContext('2d');
            ChartOrderByMemberId_(bestsaleofmonth_qty, "doughnut", data.Name, `สินค้าขายดีประจำเดือน`, data.Qty, `สินค้าขายดีประจำเดือน `)
        }
    })

}