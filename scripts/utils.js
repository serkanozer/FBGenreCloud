function makeGet(endpoint,params){
    var xmlHttp = new XMLHttpRequest();
   	theUrl = endpoint + "?" + serialize(params)
    xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
    xmlHttp.send( null );
    return xmlHttp.responseText;
}
function serialize(obj) {
  var str = [];
  
  for(var p in obj){
    if(obj[p].constructor===Array){
      arr = obj[p];
      for (i=0; i<arr.length; i++){
        el = arr[i]
        str.push(encodeURIComponent(p) + "=" + encodeURIComponent(el));

      }
    }
    else{
      str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
    }
  }
  return str.join("&");
}