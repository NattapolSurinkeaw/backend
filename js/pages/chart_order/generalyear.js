
var ogyPrice = document.getElementById('order-general-year-price').getContext('2d');
var ogyQty = document.getElementById('order-general-year-qty').getContext('2d');

window.onload = (e) => {

    $.ajax({
        url: "ajax/ajax.chart_order.php",
        type: "post",
        dataType: "json",
        data: { action: "getOrder_Year" },
        success: function (data) {

            if (data.Message == "OK") {
                // console.log(data)
                ChartOrderYear_(ogyPrice, "bar", `ยอดขายของแต่ละเดือน ประจำปี ${data.Year} | ยอดรวม ${data.PriceTotal} บาท`, data.Year, data.Price, 'Order General')
                ChartOrderYear_(ogyQty, "bar", `จำนวนยอดสั่งซื้อต่อเดือน ประจำปี ${data.Year} | จำนวน ${data.QtyTotal} `, data.Year, data.Qty, 'Order General')
            }
        }
    })
}

$('#selectYearGeneral').on('change', function (e) {
    let _data = { action: "getOrder_Year", year: $(this).val() };
    $.ajax({
        url: "ajax/ajax.chart_order.php",
        type: "post",
        dataType: "json",
        data: _data,
        success: function (data) {

            if (data.Message == "OK") {
                console.log(data)
                ChartOrderYear_(ogyPrice, "bar", `ยอดขายของแต่ละเดือน ประจำปี ${data.Year} | ยอดรวม ${data.PriceTotal} บาท`, data.Year, data.Price, 'Order General')
                ChartOrderYear_(ogyQty, "bar", `จำนวนยอดสั่งซื้อต่อเดือน ประจำปี ${data.Year} | จำนวน ${data.QtyTotal} `, data.Year, data.Qty, 'Order General')
            }
        }
    })
})