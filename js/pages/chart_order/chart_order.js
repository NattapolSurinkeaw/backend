

window.onload = (e) => {

    // setTimeout(()=>{
    //     $('body').addClass('sidebar-collapse');
    // },1000 )
}

$('#add-date-display-start').datepicker({
    format: 'yyyy-mm',
    viewMode: "months",
    minViewMode: "months",
    autoclose: true,
    language: 'th',
    todayHighlight: true
}).on('changeDate', function (e) {
    // $('#add-date-display-hidden').val(e.format('yyyy-mm-dd'));
});

$('#add-date-display-end').datepicker({
    format: 'yyyy-mm',
    viewMode: "months",
    minViewMode: "months",
    autoclose: true,
    language: 'th',
    todayHighlight: true
}).on('changeDate', function (e) {
    // $('#add-date-display-hidden').val(e.format('yyyy-mm-dd'));
});

$('#add-date-display-start-h').datepicker({
    format: 'yyyy-mm',
    viewMode: "months",
    minViewMode: "months",
    autoclose: true,
    language: 'th',
    todayHighlight: true
}).on('changeDate', function (e) {
    // $('#add-date-display-hidden').val(e.format('yyyy-mm-dd'));
});

$('#add-date-display-end-h').datepicker({
    format: 'yyyy-mm',
    viewMode: "months",
    minViewMode: "months",
    autoclose: true,
    language: 'th',
    todayHighlight: true
}).on('changeDate', function (e) {
    // $('#add-date-display-hidden').val(e.format('yyyy-mm-dd'));
});

function ChartOrderYear_(_NameTag, _type, _title, _year, _data, _text) {
    var chart = new Chart(_NameTag, {
        type: _type, //bar , line , radar ,horizontalBar , pie , doughnut , polarArea
        data: {
            labels: ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'],
            datasets: [{
                label: _title,
                backgroundColor: ['#4bc0c0','#ffb3b3', '#36a2eb', '#ffcd56', '#ff6384','#3273dc','#23d160','#ff9800','#e91e63','#c74bdd','#009688','#cddc39'],
                borderColor: 'white',
                borderWidth: 1,
                data: _data,
                borderJoinStyle: 'miter'
            }]
        },
        options: {
            title: {
                display: true,
                text: _text,
                position: 'top'
            },
            cutoutPercentage: 50,//ความกว้างของรู
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                    }
                }]
            }
        }
    })

}

function ChartOrderByMemberId_(_NameTag, _type, _label, _title, _data, _text) {

    var _options = {
        title: {
            display: true,
            text: _text,
            position: 'top'
        },
        cutoutPercentage: 20,//ความกว้างของรู
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                }
            }]
        }
    }

    if(_type == "doughnut"){
        _options = {
            title: {
                display: true,
                text: _text,
                position: 'top'
            },
            cutoutPercentage: 20,//ความกว้างของรู
        }
    }
    var chart = new Chart(_NameTag, {
        type: _type, //bar , line , radar ,horizontalBar , pie , doughnut , polarArea
        data: {
            labels: _label,
            datasets: [{
                label: _title,
                backgroundColor: ['#4bc0c0','#ffb3b3', '#36a2eb', '#ffcd56', '#ff6384','#3273dc','#23d160','#ff9800','#e91e63','#9c27b0','#009688','#cddc39','#4bc0c0','#ffb3b3', '#36a2eb', '#ffcd56', '#ff6384','#3273dc','#23d160','#ff9800','#e91e63','#9c27b0','#009688','#cddc39','#4bc0c0','#ffb3b3', '#36a2eb', '#ffcd56', '#ff6384','#3273dc','#23d160','#ff9800','#e91e63','#9c27b0','#009688','#cddc39'],
                borderColor: 'white',
                borderWidth: 1,
                data: _data,
                borderJoinStyle: 'miter',
                datalabels: {
                    color: '#000000'
                }
            }]
        },
        options: _options
    })

}
