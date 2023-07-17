
import data from "./thaiprovince.js";
var code = data.map(function(item) {
  return item.zipCode
})

export function subDistrictName(zipCode) {
  var zip = String(zipCode)
  if (zip && zip.length == 5 && code.includes(zip)) {
    var result = data.find(function(item) {
       return item.zipCode === zip
    }).subDistrictList.map(function(item) {
      return item.subDistrictName
    })
    return result
  } else {
    return []
  }
}

export function districtName(zipCode) {
  var zip = String(zipCode)
  if (zip && zip.length == 5 && code.includes(zip)) {
    var result = data.find(function(item) {
       return item.zipCode === zip
    }).districtList.map(function(item) {
        return item.districtName
    })
    return result
  } else {
    return []
  }
}

export function provinceName(zipCode) {
  var zip = String(zipCode)

  if (zip && zip.length == 5 && code.includes(zip)) {
    var result = data.find(function(item) {
       return item.zipCode === zip
    }).provinceList[0].provinceName
    return result
  } else {
    return null
  }
}

export function allField(zipCode) {
  var zip = String(zipCode)
  if (zip && zip.length == 5 && code.includes(zip)) {
    var result = data.find(function(item) {
       return item.zipCode === zip
    })
    return result
  } else {
    return []
  }
}

export function autoSuggestion(zipCode, subDistrict) {
  var zip = String(zipCode)
  if (zip && zip.length == 5 && !subDistrict && code.includes(zip)) {
    var allData = allField(zipCode).subDistrictList.map(function(item) {
      return item.subDistrictName
    })
    return {
      subDistrict: allData,
      districtName: null,
      provinceName: provinceName(zipCode),
      zipCode: zipCode 
    }
  } else if (zip && zip.length == 5 && subDistrict) {
    var allData = allField(zipCode)
    var districtId = (allData.subDistrictList.find(function(item) {
      return item.subDistrictName === subDistrict
    }) || []).districtId

    var districtName = (allData.districtList.find(function(item) {
      return item.districtId === districtId
    }) || []).districtName

    return {
      subDistrict: subDistrict,
      districtName: districtName,
      provinceName: provinceName(allData.zipCode),
      zipCode: zipCode 
    }
  } else {
    return {
      subDistrict: null,
      districtName: null,
      provinceName: null,
      zipCode: null 
    }
  }
}

export function allData() {
  return data
}
 
$(".page_purchaseOrderData").on("keyup",".txt_zipcode",function(){
    let post =  $(this).val();
    if(post.length == 5){ 
       let src = allField(post);
       let subdis = "";
        let _old = $(".page_purchaseOrderData #slc_subdistrict option:selected").val();
       $.each(src.subDistrictList,function(key , val){
           let set = (val.subDistrictName == _old)?"selected":"";
           subdis += "<option "+set+" data-district='"+val.districtId+"' data-province='"+val.provinceId+"' value="+val.subDistrictName+">"+val.subDistrictName+"</option>";
       });
       $(".page_purchaseOrderData #slc_subdistrict").html(subdis);
       completed_select_province();  
    }
});

function completed_select_province(){
    let prov_id = $(".page_purchaseOrderData #slc_subdistrict option:selected").data('province');
    let dist_id = $(".page_purchaseOrderData #slc_subdistrict option:selected").data('district'); 
    let src = allField($(".page_purchaseOrderData .txt_zipcode").val());
    console.log(src);

    $.each(src.districtList,function(key , val){ 
       if(val.districtId == dist_id){
            $(".page_purchaseOrderData .txt_district").val(val.districtName)
       }
    });
    $.each(src.provinceList,function(key , val){
        if(val.provinceId == prov_id){
            $(".page_purchaseOrderData .txt_province").val(val.provinceName)
        }
     });
}

$(".page_purchaseOrderData").on("change","#slc_subdistrict",function(){
    completed_select_province();  
});

 