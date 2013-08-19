<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" 
xmlns:v="urn:schemas-microsoft-com:vml">
  <head>
    <script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=ABQIAAAA5Ekx1hr1F5dJzNLzAkeDFxQVPxdEPnWfeUxsKiAVw_khTr3ibxSangsHd2ioXZDZkNsG582UKTP8kw"
      type="text/javascript"></script>
    <script type="text/javascript"> 
var map;
var userAdded = 1;

var $basedir = "http://sauloal.mine.nu/programs/map/";
var $latbase = 42.06560675405716;
var $longbase = 13.359375;

var layers = {
 "Maps":
 {"url": $basedir,
  "name": "Maps",
  "zoom": 2}<?php
$directoria = ".";

$dir = @opendir($directoria) or die("Ocorreu Um Erro Ao Tentar Abrir a Directoria: $directoria");

$i=0;

$dir = @opendir($directoria) or die("Ocorreu Um Erro Ao Tentar Abrir a Directoria: $directoria");
while ($file = readdir($dir)) {
	if( is_file("$file") ) {
		if(strncmp($file,".",1)) {
			$extencao = strtolower(substr(strrchr($file, "."), 1 ));
			$extencao = "." . $extencao;

			if ($extencao == ".kml")
			{			
				$name = strtolower(substr($file,0,(strlen($file)-strlen($extencao))));
				echo ",\n";
				echo "\"$name\":\n"; 
				echo "{\"url\": \$basedir + \"" . $file . "\",\n";
				echo "\"name\": \"$name\",\n";
				echo "\"zoom\": 5,\n";
				echo "\"lat\": \$latbase,\n";
				echo "\"lng\": \$longbase}";
			}
			$i++;
		}
	}
}
echo " };\n";


closedir($dir);

$i=0;

?>

function onLoad() {

if (parseInt(navigator.appVersion)>3) {
 if (navigator.appName=="Netscape") {
  winW = window.innerWidth;
  winH = window.innerHeight;
 }
 if (navigator.appName.indexOf("Microsoft")!=-1) {
  winW = document.body.offsetWidth;
  winH = document.body.offsetHeight;
 }
}

  document.getElementById("map").style.height = winH;
  document.getElementById("map").style.width = winW -160;

  map = new GMap2(document.getElementById("map")); 

  map.setCenter(new GLatLng($latbase, $longbase), 5);

  var mapTypeControl = new GMapTypeControl();
  var topRight = new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(10,10));
  var bottomRight = new GControlPosition(G_ANCHOR_BOTTOM_RIGHT, new GSize(10,10));
  map.addControl(mapTypeControl, topRight);
  GEvent.addListener(map, "dblclick", function() {
  	map.removeControl(mapTypeControl);
 	map.addControl(new GMapTypeControl(), bottomRight);
  });
  map.addControl(new GLargeMapControl());
  map.enableDoubleClickZoom();
  map.enableScrollWheelZoom();

  document.getElementById("url").value = "http://";

  for(var layer in layers) {
    addTR(layer, layers[layer].name);
  }
  
  //document.getElementById(layer).checked = true;
  //toggleGeoXML(layer, true);

} 



function addGeoXML() {
  var theUrl = document.getElementById("url").value;
  theUrl = theUrl.replace(/^\s+/, "");
  theUrl = theUrl.replace(/\s+$/, "");
  theUrl = URLEncode(theUrl);
  alert('error');
  if (theUrl.indexOf(' ') != -1) {
    alert('Error - that address has a space in it');
  } else {
    var id = "userAdded" + userAdded;
    layers[id] = {};
    layers[id].url = theUrl;
    layers[id].name = "User Layer " + userAdded;

    addTR(id);
    document.getElementById(id).checked = true;
    toggleGeoXML(id, true);
    userAdded++;
  }
}


function URLEncode(plaintext)
{
	// The Javascript escape and unescape functions do not correspond
	// with what browsers actually do...
	var SAFECHARS = "0123456789" +					// Numeric
					"ABCDEFGHIJKLMNOPQRSTUVWXYZ" +	// Alphabetic
					"abcdefghijklmnopqrstuvwxyz" +
					"-_.!~*'()";					// RFC2396 Mark characters
	var HEX = "0123456789ABCDEF";

	//var plaintext = document.URLForm.F1.value;
	var encoded = "";
	for (var i = 0; i < plaintext.length; i++ ) {
		var ch = plaintext.charAt(i);
	    if (ch == " ") {
		    //encoded += "+";				// x-www-urlencoded, rather than %20
		    encoded += "%20";				// x-www-urlencoded, rather than %20
		} else if (SAFECHARS.indexOf(ch) != -1) {
		    encoded += ch;
		} else {
		    var charCode = ch.charCodeAt(0);
			if (charCode > 255) {
			    alert( "Unicode Character '" 
                        + ch 
                        + "' cannot be encoded using standard URL encoding.\n" +
				          "(URL encoding only supports 8-bit characters.)\n" +
						  "A space (+) will be substituted." );
				encoded += "+";
			} else {
				encoded += ch;
			}
		}
	} // for

	//document.URLForm.F2.value = encoded;
	return encoded;
};


function addTR(id) {
  var layerTR = document.createElement("tr");

  var inputTD = document.createElement("td");

  var input = document.createElement("input");
  input.type = "checkbox";
  input.id = id;
  input.onclick = function () { toggleGeoXML(this.id, this.checked) };
  inputTD.appendChild(input);

  var nameTD = document.createElement("td");
  var nameA = document.createElement("a");
  nameA.href = layers[id].url;
  var name = document.createTextNode(layers[id].name);
  nameA.appendChild(name);
  nameTD.appendChild(nameA);

  layerTR.appendChild(inputTD);
  layerTR.appendChild(nameTD);
  document.getElementById("sidebarTBODY").appendChild(layerTR);
}

function toggleGeoXML(id, checked) {
  if (checked) {
    encodedurl = URLEncode(layers[id].url);
    alert(encodedurl);
    var geoXml = new GGeoXml(encodedurl);
    layers[id].geoXml = geoXml;

    if (layers[id].zoom) {
      map.setZoom(layers[id].zoom);
    } else {
      map.setZoom(1);
    }
    if (layers[id].lat && layers[id].lng) {
      map.setCenter(new GLatLng(layers[id].lat, layers[id].lng));
    } else {
      map.setCenter(new GLatLng($latbase, $longbase));
    }

    dml=document.forms["geo"];
    len = dml.elements.length;
    var i=0;
    for( i=0 ; i<len ; i++) {
	if (dml.elements[i].id != id) {
		dml.elements[i].checked = false;
    		map.clearOverlays();
	}
    }
    map.addOverlay(geoXml);
  } else if (layers[id].geoXml) {
    map.removeOverlay(layers[id].geoXml);
  }
}



    </script>

  </head>

  <body onload="onLoad()">
    <br/>
    <input id="url" value="" size="60"/>
    <input type="button" value="Add" onClick="addGeoXML();"/>
    <br/>
    <br/>
    <div id="map" style="width: 640px; height: 480px; float:left; border: 1px solid black;"></div>
    <div id="sidebar" style="float:left; overflow-vertical:scroll; height: 400px; width:150px; border:1px solid black">
    <form name="geo">
    <table id="sidebarTABLE">
    <tbody id="sidebarTBODY">
    </tbody>
    </table>
    </form>
    </div>

  </body>
</html>
