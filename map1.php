<!DOCTYPE html>
<html>
  <head>
    <title>Draw Features</title>
    <link rel="stylesheet" href="https://openlayers.org/en/v4.3.1/css/ol.css" type="text/css">
    <!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
    <script src="https://openlayers.org/en/v4.3.1/build/ol.js"></script>
  </head>
  <body>
    <div id="map" class="map"></div>
    <form class="form-inline">
      <label>Geometry type &nbsp;</label>
      <select id="type">
        <option value="Point">Point</option>
        <option value="None">None</option>
      </select>
    </form>
    <script>
      var raster = new ol.layer.Tile({
        source: new ol.source.OSM()
      });

      var source = new ol.source.Vector({wrapX: false});

      var vector = new ol.layer.Vector({
        source: source
      });

      var map = new ol.Map({
        layers: [raster, vector],
        target: 'map',
        view: new ol.View({
          center: [-11000000, 4600000],
          zoom: 4
        })
      });

      var typeSelect = document.getElementById('type');

      var draw; // global so we can remove it later
      function addInteraction() {
        var value = typeSelect.value;
        if (value !== 'None') {
          draw = new ol.interaction.Draw({
            source: source,
            type: /** @type {ol.geom.GeometryType} */ (typeSelect.value)
          });
          map.addInteraction(draw);
        }
        //single feature
        draw.on('drawend', function(evt) {
        //... unset sketch
        map.removeInteraction(draw);
    }, this);
      }


      /**
       * Handle change event.
       */
      typeSelect.onchange = function() {
        map.removeInteraction(draw);
        addInteraction();
      };

      addInteraction();

      
    </script>
  </body>
</html>