$(function(){
    let _data = {
        action: "getProductBestSale_price_10ByYear",
        year: 2019
    }
    $.ajax({
        url: "ajax/ajax.chart_order.php",
        type: "post",
        dataType: "json",
        data: _data,
        success: function (data) {
            // console.log(data)
            if(data.Count > 8){
                $('#OrderProductBest-price-wrapper').removeClass('col-md-6');
                $('#OrderProductBest-price-wrapper').addClass('col-md-12');
            }else{
                $('#OrderProductBest-price-wrapper').removeClass('col-md-12');
                $('#OrderProductBest-price-wrapper').addClass('col-md-6');
            }
            var orderBestPrice = document.getElementById('OrderProductBest-price').getContext('2d');
            ChartOrderByMemberId_(orderBestPrice, "bar", data.Name, `สินค้าที่ทำยอดขายได้ดี `, data.Price, `สินค้าที่ทำยอดขายได้ดี `)
        }
    })

    let _data2 = {
        action: "getProductBestSale_qty_10ByYear",
        year: 2019
    }
    $.ajax({
        url: "ajax/ajax.chart_order.php",
        type: "post",
        dataType: "json",
        data: _data2,
        success: function (data) {
            // console.log(data)
            var orderBestQty = document.getElementById('OrderProductBest-qty').getContext('2d');
            ChartOrderByMemberId_(orderBestQty, "doughnut", data.Name, `สินค้าที่ทำยอดขายได้ดี `, data.Qty, `สินค้าที่ทำยอดจำนวนการขายได้ดี `)
        }
    })
})

$('#SelectYearOrderProductBest').on('change',function(e){
    $('#OrderProductBest-price').remove();
    $('#OrderProductBest-price-wrapper').append(`<canvas id="OrderProductBest-price"></canvas>`);

    $('#OrderProductBest-qty').remove();
    $('#OrderProductBest-qty-wrapper').append(`<canvas id="OrderProductBest-qty"></canvas>`);

    let _data = {
        action: "getProductBestSale_price_10ByYear",
        year: $(this).val()
    }
    $.ajax({
        url: "ajax/ajax.chart_order.php",
        type: "post",
        dataType: "json",
        data: _data,
        success: function (data) {
            // console.log(data)
            if(data.Count > 8){
                $('#OrderProductBest-price-wrapper').removeClass('col-md-6');
                $('#OrderProductBest-price-wrapper').addClass('col-md-12');
            }else{
                $('#OrderProductBest-price-wrapper').removeClass('col-md-12');
                $('#OrderProductBest-price-wrapper').addClass('col-md-6');
            }
            var orderBestPrice = document.getElementById('OrderProductBest-price').getContext('2d');
            ChartOrderByMemberId_(orderBestPrice, "bar", data.Name, `สินค้าที่ทำยอดขายได้ดี `, data.Price, `สินค้าที่ทำยอดขายได้ดี `)
        }
    })

    let _data2 = {
        action: "getProductBestSale_qty_10ByYear",
        year: $(this).val()
    }
    $.ajax({
        url: "ajax/ajax.chart_order.php",
        type: "post",
        dataType: "json",
        data: _data2,
        success: function (data) {
            // console.log(data)
            var orderBestQty = document.getElementById('OrderProductBest-qty').getContext('2d');
            ChartOrderByMemberId_(orderBestQty, "doughnut", data.Name, `สินค้าที่ทำยอดขายได้ดี `, data.Qty, `สินค้าที่ทำยอดจำนวนการขายได้ดี `)
        }
    })
})