$('#btnHandleSearchDetailProduct').on('click',function(e){

    $('#DetailProduct-price').remove();
    $('#DetailProduct-price-wrapper').append(`<canvas id="DetailProduct-price"></canvas>`);

    $('#DetailProduct-qty').remove();
    $('#DetailProduct-qty-wrapper').append(`<canvas id="DetailProduct-qty"></canvas>`);

    let _data = {
        action: 'getOrder_By_productID',
        pid : $('#SelectProduct_product').val(),
        year : $('#SelectYear_product').val(),
    }


    $.ajax({
        url: "ajax/ajax.chart_order.php",
        type: "post",
        dataType: "json",
        data: _data,
        success: function (data) {
            // console.log(data)
            
            if(data.Message == "OK"){
                var productDetailPrice = document.getElementById('DetailProduct-price').getContext('2d');
                var productDetailQty = document.getElementById('DetailProduct-qty').getContext('2d');
                ChartOrderYear_(productDetailPrice, "bar", `ยอดขายของแต่ละเดือน`, data.Year, data.Price, '')
                ChartOrderYear_(productDetailQty, "bar", `ยอดจำนวนที่ขายได้ของแต่ละเดือน`, data.Year, data.Qty, '')
            }
            
        }
    })

    
})