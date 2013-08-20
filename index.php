  <!-- 
  original GeoPHP https://code.google.com/p/geophp/ released under Apache Licence 2.0,
  modified by Michele Dalla Torre
  -->
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    <style type="text/css">
      
	  html { 
		height: 100%
		}
      
	  body { 
		height: 100%; 
		margin: 5; 
		padding: 0;
		background-color: light-grey
		}
		
		h1 {
			font-family:Georgia, "Arial", Serif
		}
      
	  #map-canvas { 
		height: 100% 
		}
	  
	  #status { 
		float:bottom; 
		clear:left; 
		overflow-vertical:scroll; 
		height: 200px; 
		width:1200px; 
		border:0px dotted black;

		}
	
	#sidebar {
		float:left; 
		overflow-vertical:scroll; 
		height: 600px; 
		width:300px; 
		border:1px dashed black; 
		background-color:#D3D3D3
	}
	
	#map_canvas {
		width: 900px; 
		height: 600px; 
		float:left; 
		border: 0px solid black
	}
		
    </style>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
    <script type="text/javascript">
		var userAdded = 1;
		var layers=[];
		
		<?php
		$dir = ".";
		
		//////////////// CONFIG VARIABLES /////////////
		// e.g. http://www.mywebsite.com				  
		$mywebsite = "http://" . $_SERVER['HTTP_HOST'];	  
		// e.g. /mymaps													  
		$currentfolder = dirname($_SERVER['PHP_SELF']); 
		// e.g. http://www.mywebsite.com/mymaps/	
		$basedir = $mywebsite . $currentfolder . "/";     
		///////////////////////////////////////////////
		
		$i=0;
		$dir = @opendir($dir) or die("Ocorreu Um Erro Ao Tentar Abrir a dir: $dir");
		while ($file = readdir($dir)) {
			if( is_file("$file") ) {
				if(strncmp($file,".",1)) {
					$extension = strtolower(substr(strrchr($file, "."), 1 ));
					$extension = "." . $extension;

					if ($extension == ".kml")
					{	
						$name = strtolower(substr($file,0,(strlen($file)-strlen($extension))));
						echo "layers[" . $i . "] = ";
						echo "new google.maps.KmlLayer('";
						echo $basedir . $file . "',\n {preserveViewport: true});\n";
						$i++;
					}
				}
			}
		}
		closedir($dir);
		$i=0;
		?>
		
		var numlayers = layers.length;

		var map;
	
		function initialize() {
				  var latlng = new google.maps.LatLng(46.064151,11.122153);
				  
				  var myOptions = {
					zoom: 5,
					center: latlng,
					mapTypeIds: google.maps.MapTypeId.ROADMAP
				  }
				  
				  map = new google.maps.Map(document.getElementById('map_canvas'),myOptions);
				  			  
				  document.getElementById("url").value = "http://";
				  
				  // create HTML table with all KML files
				  for(var layer in layers) {
					addTR(layer);
				  }
				  
				}
		
		// show/hide layers
		function toggleLayers(i) {
			if(layers[i].getMap()==null) {
				layers[i].setMap(map);
			}
			 else {
				layers[i].setMap(null);
			}
			document.getElementById('status').innerHTML += "toggleLayers("+i+") [setMap("+layers[i].getMap()+"] returns status: "+layers[i].getStatus()+"<br>";
		}

		// allow users to add other KML files via external URL
		function addGeoXML() {
		  var theUrl = document.getElementById("url").value;
		  theUrl = theUrl.replace(/^\s+/, "");
		  theUrl = theUrl.replace(/\s+$/, "");
		  theUrl = URLEncode(theUrl);
		  
		  if (theUrl.indexOf(' ') != -1 ) {
			alert('Error - that address has a space in it');
		  } else {
			var id = "userAdded" + userAdded;
			layers[id] = new google.maps.KmlLayer(theUrl, {preserveViewport: true});
			layers[id].url = theUrl;
			addTR(id);
			document.getElementById(id).checked = true;
			toggleLayers(id);			
			userAdded++;
		  }
		}

		function URLEncode(plaintext) {
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

		// create a new HTML TR element for each KML file (i.e. map layer)
		function addTR(id) {
		  var layerTR = document.createElement("tr");
		  var inputTD = document.createElement("td");

		  var input = document.createElement("input");
		  input.type = "checkbox";
		  input.id = id;
		  input.onclick = function ()  { toggleLayers(id) };
		  inputTD.appendChild(input);

		  var nameTD = document.createElement("td");
		  var nameA = document.createElement("a");
		  nameA.href = layers[id].url;
		  
		  // the full URL with basedir + filename + extension (.kml)
		  var myfullurl = layers[id].url;
		  // get intex of last / chart
		  var myindex = myfullurl.lastIndexOf("/") + 1;
		  var name = document.createTextNode(myfullurl.substring(myindex));
		  nameA.appendChild(name);
		  nameTD.appendChild(nameA);

		  layerTR.appendChild(inputTD);
		  layerTR.appendChild(nameTD);
		  document.getElementById("sidebarTBODY").appendChild(layerTR);
		}
		
		
		
	</script>
  </head>
  <body onload="initialize()">
	
	<h1> Google Maps KML Viewer </h1>

    <input id="url" value="" size="60"/>
    <input type="button" value="Add new KML file!" onClick="addGeoXML();"/>
    <br/>
    <br/>
		
	<div id="map_canvas">
	</div>
	
	<div id="sidebar">
		<table id="sidebarTABLE">
			<b>KML layers</b>
			<tbody id="sidebarTBODY">
			</tbody>
		</table>
    </div>
	
	<div id="status">
	</div>
	
  </body>
</html>
