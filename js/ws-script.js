function getAllUrlParams(url) {
  var queryString = url ? url.split('?')[1] : window.location.search.slice(1);
  var obj = {};
  if (queryString) {
    queryString = queryString.split('#')[0];
    var arr = queryString.split('&');
    for (var i=0; i<arr.length; i++) {
      var a = arr[i].split('=');
      var paramNum = undefined;
      var paramName = a[0].replace(/\[\d*\]/, function(v) {
        paramNum = v.slice(1,-1);
        return '';
      });
      var paramValue = typeof(a[1])==='undefined' ? true : a[1];
      paramName = paramName.toLowerCase();
      paramValue = paramValue.toLowerCase();
      if (obj[paramName]) {
        if (typeof obj[paramName] === 'string') {
          obj[paramName] = [obj[paramName]];
        }
        if (typeof paramNum === 'undefined') {
          obj[paramName].push(paramValue);
        }
        else {
          obj[paramName][paramNum] = paramValue;
        }
      }
      else {
        obj[paramName] = paramValue;
      }
    }
  }
  return obj;
}

function getQueryVariable(url, variable, para) {
  var query = url;
  var params = {};
  var u = 0;
  var vars = query.split('&');
  if (vars[0] != '') {
    for (var i = 0; i < vars.length; i++) {
      var pair = vars[i].split('=');
      if (pair[0] == variable) {
        // return pair[1]; 
        if (pair[1] == para) {
          return false;
        } else {
          pair[1] = para;
        }
        u = 1;
        if (para != "All") {
          params[pair[0]] = decodeURIComponent(pair[1]);
        }
      } else {
        params[pair[0]] = decodeURIComponent(pair[1]);
      }
    }
  }
  if (u == 0 && para != "All") {
    params[variable] = decodeURIComponent(para);
  }
  if (jQuery.isEmptyObject(params)) {
    new_url = url_ajax_request + '';
  } else {
    new_url = url_ajax_request + '?' + jQuery.param(params);
  }
  return new_url;
}

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function ChangeUrl(page, url) {
  // if (typeof (history.pushState) == "undefined") {
  //   var obj = { Page: page, Url: url };
  //   history.pushState(obj, obj.Page, obj.Url);
  // } else {
  //   window.location.href = site_url+url;
  // }
  window.location.href = url;
}
