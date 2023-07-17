// upload images
$("#edit-images-content").uploadImage({
  preview: true
});

$("#edit-images-content").on("change", function(){ 
  if(formdata.getAll("images[]").length !== 0){
    $(".image-label").hide();
    // var img = formdata.getAll("images[]")["0"].name;
    // $('#edit-images-content-hidden').val(img);
    // $(".form-edit-images").removeClass("has-error");
    // $(".edit-images-error").css("display","none");
  }
});

/*------------------- Map ------------------ */
var geocoder; 
var map; 
var my_Marker; 
var GGM; 
var Lat = document.getElementById('lat_value').value;
var Lng = document.getElementById('lon_value').value;
var zoom_value = document.getElementById('zoom_value').value;
function initialize() { 
    GGM=new Object(google.maps); 
    geocoder = new GGM.Geocoder(); 
    var my_Latlng  = new GGM.LatLng(Lat,Lng);
    var my_mapTypeId=GGM.MapTypeId.ROADMAP; 
    var my_DivObj=$("#map_canvas")[0];
    var myOptions = {
        zoom: parseInt(zoom_value), 
        center: my_Latlng , 
        mapTypeId:my_mapTypeId 
    };
    map = new GGM.Map(my_DivObj,myOptions); 
     
    my_Marker = new GGM.Marker({ 
        position: my_Latlng,  
        map: map, 
        draggable:true, 
        title:"คลิกลากเพื่อหาตำแหน่งจุดที่ต้องการ!" 
    });
        
    GGM.event.addListener(my_Marker, 'dragend', function() {
        var my_Point = my_Marker.getPosition();  
        map.panTo(my_Point);         
        $("#lat_value").val(my_Point.lat());  
        $("#lon_value").val(my_Point.lng());   
        $("#zoom_value").val(map.getZoom());    

        $("#text-lat").html(my_Point.lat());  
        $("#text-lng").html(my_Point.lng());   
        $("#text-zoom").html(map.getZoom());          
    });     
 
    GGM.event.addListener(map, 'zoom_changed', function() {
        $("#zoom_value").val(map.getZoom()); 
        $("#text-zoom").html(map.getZoom());       
    });
 
}
$(function(){
    var searchPlace=function(){ 
        var AddressSearch=$("#namePlace").val();
        if(geocoder){  
            geocoder.geocode({
                 address: AddressSearch 
            },function(results, status){ 
                if(status == GGM.GeocoderStatus.OK) { 
                    var my_Point=results[0].geometry.location; 
                    map.setCenter(my_Point); 
                    my_Marker.setMap(map);                    
                    my_Marker.setPosition(my_Point); 
                    $("#lat_value").val(my_Point.lat());  
                    $("#lon_value").val(my_Point.lng());   
                    $("#zoom_value").val(map.getZoom());                                
                }else{
                    alert("Geocode was not successful for the following reason: " + status);
                    $("#namePlace").val("");
                 }
            });
        }       
    }
    $("#SearchPlace").click(function(){ 
        searchPlace();  
    });
    $("#namePlace").keyup(function(event){ 
        if(event.keyCode==13){  
            searchPlace();      
        }       
    });
 
});
$(function(){
    $("<script/>", {
      "type": "text/javascript",
      src: "https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyCNxdEaeJMMEFaash6JCScE3Nf7SdeA6Qw&callback=initialize"
      //"http://maps.google.com/maps/api/js?v=3.2&key=AIzaSyCNxdEaeJMMEFaash6JCScE3Nf7SdeA6Qw&language=th&callback=initialize"
    }).appendTo("body");    
});


/*------------------- Save Map ------------------ */
$("#save-map").on("click", function(){ 
  var data = {
      action: "updatemap",
      id: $('#map_id').val(),
      lat: $('#lat_value').val(),
      lon: $('#lon_value').val(),
      zoom: $('#zoom_value').val(),
      city: $('#city_id').val()
  };

  update_map(data);
});

function update_map(data) {
  var url = "ajax/ajax.map.php",
      dataSet = data;
  $.ajax({
    type: "POST",
    url: url,
    data: dataSet,
    success: function(data){
      var obj = jQuery.parseJSON(data);
      var mapId = dataSet.id;
      if (dataSet.id == '') {
        // console.log(obj);
        mapId = obj.insert_id;
      }
      if (obj.message == 'OK') {
        if(formdata.getAll("images[]").length !== 0){
          uploadimages(mapId, "uploadmarker");
          // console.log(formdata.getAll("images[]"));
        }else{
          $.confirm({
            theme: 'modern',
            type: 'green',
            icon: 'fa fa-check',
            title: 'บันทึกข้อมูลเรียบร้อยแล้ว',
            content: '',
            buttons: {
                somethingElse: {
                    text: 'ตกลง',
                    keys: ['enter'],
                    action: function(){
                      location.reload();
                    }
                }
            }
          });
        }
        
      }
      
    }
  });
}

function uploadimages(mapId,action) {
  formdata.append("action", action);
  formdata.append("id", mapId);
  $.ajax({
    url: url_ajax_request + "ajax/ajax.map.php",
    type: 'POST',
    data: formdata,
    processData: false,
    contentType: false,
    success: function(msg){
      var obj = jQuery.parseJSON(msg);
      if(obj['message'] === "OK"){
        $.confirm({
          theme: 'modern',
          type: 'green',
          icon: 'fa fa-check',
          title: 'บันทึกข้อมูลเรียบร้อยแล้ว',
          content: '',
          buttons: {
              somethingElse: {
                  text: 'ตกลง',
                  keys: ['enter'],
                  action: function(){
                    location.reload();
                  }
              }
          }
        });
      }
    }
  });
}