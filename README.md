GoogleMaps KML viewer
=====================

PHP system to automatically list and plot KML files in a Google Map.

![Screenshot](images/screenshot.png)

### Features
This webpage allows you to automatically display on a Google Map the KML files you have inside the same folder on your website.
You can also temporarily add other external KML by inserting their URL (e.g. http://www.website.com/kmltest.kml).
These files will be shown on your webpage, but will be lost once you refresh it.

### License
Original [GeoPHP project] (https://code.google.com/p/geophp/) licensed under [Apache Licence 2.0](http://www.apache.org/licenses/LICENSE-2.0.html).

### Changelog
- First commit: Forked [GeoPHP project] (https://code.google.com/p/geophp/) and updated it from [Google Maps JavaScript API v2 (Deprecated)](https://developers.google.com/maps/documentation/javascript/v2/reference) to [Google Maps JavaScript API v3](https://developers.google.com/maps/documentation/javascript/)
- 28/08/2013: KML files displayed in alphabetical order by default 

### USAGE
 - Simply copy index.php file along with all your KML files into the same web folder
 - Open your browser and go to the index.php file on your web server (e.g. www.mywebsite.com/mymaps/index.php)

__TODO__
- [ ] add options to order KML layers by name, size, etc. (maybe via a sortable JQuery table)
