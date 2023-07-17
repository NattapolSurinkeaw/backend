
var ohyPrice = document.getElementById('order-hospital-year-price').getContext('2d');
var ohyQty = document.getElementById('order-hospital-year-qty').getContext('2d');

window.onload = (e) => {

    $.ajax({
        url: "ajax/ajax.chart_order.php",
        type: "post",
        dataType: "json",
        data: { action: "getOrder_Year",type:"hospital" },
        success: function (data) {

            if (data.Message == "OK") {
                ChartOrderYear_(ohyPrice, "bar", `ยอดขายของแต่ละเดือน ประจำปี ${data.Year} | ยอดรวม ${data.PriceTotal} บาท`, data.Year, data.Price, 'Order Hospital')
                ChartOrderYear_(ohyQty, "bar", `จำนวนยอดสั่งซื้อต่อเดือน ประจำปี ${data.Year} | จำนวน ${data.QtyTotal} `, data.Year, data.Qty, 'Order Hospital')
            }
        }
    })

}

$('#selectYearHospital').on('change', function (e) {
    let _data = { action: "getOrder_Year",type:"hospital", year: $(this).val() };
    $.ajax({
        url: "ajax/ajax.chart_order.php",
        type: "post",
        dataType: "json",
        data: _data,
        success: function (data) {

            if (data.Message == "OK") {
                ChartOrderYear_(ohyPrice, "bar", `ยอดขายของแต่ละเดือน ประจำปี ${data.Year} | ยอดรวม ${data.PriceTotal} บาท`, data.Year, data.Price, 'Order Hospital')
                ChartOrderYear_(ohyQty, "bar", `จำนวนยอดสั่งซื้อต่อเดือน ประจำปี ${data.Year} | จำนวน ${data.QtyTotal} `, data.Year, data.Qty, 'Order Hospital')
            }
        }
    })
})