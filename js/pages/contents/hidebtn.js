$(document).ready(function () {
    //ซ่อนปุ่มลบ
    $('#modalHide').click(function () {
        $(".deleteHideBtnhide").hide();
        $("#modalHide").hide();
        $("#modalShow").show();
        $(".hideSecBtn").show();
        $(".hideSecModalhide").hide();
        $(".showSecModalhide").show();
        
    });

    //เสร็จสิ้น
    $('#modalShow').click(function () {
        $("#modalHide").show();
        $("#modalShow").hide();
        $(".showSecBtn").hide();
        $(".hideSecBtn").hide();
        $(".deleteHideBtnhide").show();
       location.reload();
    });

    //แสดง
    $(".hideSecBtn").on("click", function(){ 
        var data = {
            id: $(this).data("id")
        };   
        $("#hideSecBtn" + data.id).hide();
        $("#showSecBtn" + data.id).show();
        $("#deleteHideBtn" + data.id).hide();

        $.ajax({
            type: "POST",
            dataType: 'json',
           url: 'ajax/ajax.hidebtn.php',
            data: {
               action: 'updateModalHide',
                id: data.id
            },
            success: function(){
            }
        });
    }); 
    //ซ่อน
    $(".showSecBtn").on("click", function(){
        var data = {
            id: $(this).data("id")
        }; 
        $("#hideSecBtn"+data.id).show();
        $("#showSecBtn"+data.id).hide();
        $("#deleteHideBtn"+data.id).show();
      
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: 'ajax/ajax.hidebtn.php',
            data: {
               action: 'updateModalShow',
                id: data.id
            },
            success: function(){    
            }
        });
    });
});


  
   
  
