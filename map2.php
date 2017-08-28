<!DOCTYPE html>
<html>
  <head>
    <title>Custom Controls</title>
    <link rel="stylesheet" href="https://openlayers.org/en/v4.3.1/css/ol.css" type="text/css">
    <!-- The line below is only needed for old environments like Internet Explorer and Android 4.x -->
    <script src="https://cdn.polyfill.io/v2/polyfill.min.js?features=requestAnimationFrame,Element.prototype.classList,URL"></script>
    <script src="https://openlayers.org/en/v4.3.1/build/ol.js"></script>
    <style>
    .rotate-north {
        top: 65px;
        left: .5em;
    }
    .ol-touch .rotate-north {
        top: 80px;
    }
    .ol-mycontrol {
        background-color: rgba(255, 255, 255, 0.4);
        border-radius: 4px;
        padding: 2px;
        position: absolute;
        width:300px;
        top: 5px;
        left:40px;
    }
    button {
        background-color: Transparent;
        background-repeat:no-repeat;
        border: none;
        cursor:pointer;
        overflow: hidden;
        outline:none;
    }
    </style>
  </head>
  <body>
    <div class="row-fluid">
      <div class="span12">
      <div id="map" class="map"></div>
    </div>
    </div>
    <script>
      /**
       * Define a namespace for the application.
       */
      window.app = {};
      var app = window.app;

      var source = new ol.source.Vector({wrapX: false});

      var vector = new ol.layer.Vector({
        source: source
      });

      var pointIcon = '<img class="icon icons8-Marker" width="25" height="25" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAADXklEQVRIS2NkIBJ4tB9X+Mv8Xx6mnPkv48MdlZYPiNHOSEhRyIQLPkxszLX8PByKkqIComwsLAy//vxheP76w+uPX37c//frb/OaAoMt+MzBa4lv39lNKnJi1uoKUkK4DLn54Nm7O49eHd1cZOyHSw1OS4InX1hjqqMcLMDLRcizDB8+f2M4feXu2rW5BiHYFGO1xLfvTJaWsky3orQoYRugpt5/+vrbtbtPSjcXmUxDtwirJTGzrt6xMdJQRlb86u07hn8/vjC8/vCNQVSAi4GJg4dBTBg1FI+cu3F3SZq2CkFL3DqPBRhqKC5TlBbjhCl+/OwFg4E4I0OImSRc/5pTzxkuvPzPICslARcD+eb8jXvRu8qtNiBbhOGTsCmXFljoq8RzcbLD1T1/dJ+hIRDFY2C5hvV3GSTlFOHqvn3/yXDi4p2Fq3L0Egha4mShHQ9T9Ob9JwYJ1i8MyfYyGHE69+AThjf/BRiQE8e+E1eJs8TGRCOejYUZbCjIdRy/3jJkOmFaMn3fE4Y/3OIMMLW//vxlOHLmBmFLfHpPVVroqbaJCPLBXX7txm2G1hAVBh52iMUg8OXnX4bqNXcYtDRU4WIgX5+8fDcbPYVhxIlr1ykDHUWJfeqK0oIw3SDfPHr0iCHMVIyBm52Z4evPvwyrTr9ikJOTY0COu5v3n76/cv+F0+4yswt44wQkGT/n2nlLA3UD9EgAufTjp88M/Hy8DMg+hak7fPb69aXpOloEkzBIASi3WxuoBSO7EiPW0QRAvj107sayDflG0URZAipxFWXFD+hryMNLXUKWnLly9+mT529tsJXMOMuuiOmXdlkZarjCUg4+S0Cp6ui561tWZun7YlOH0xKQb2QkhY+Y6ChLE/LF8Qu3Xr58/cECV/2Ct6gnpiQGlcAnLt3GGhcwx+G1xKH/vIA4G9NZR3MdJVy+2X/yyr2Xv/4ZHyg0/IBLDcGa0a//bLSyjOhk5HwDM+zy7ccfH794m72p0HgpviAlaAlIc8DEc0st9FSjkMsoYoKJqOCCKQIFmygr4wlbEy11UGoDpaaDp69eefuHwRZfMJFkCUgxqLiRFOTZZGWoLnvg9NXHHz58taNaawU5rEEtlx9/f63gYGaLINRCIVh24YtEUP4h1gcwcwDlAFYphLjWhgAAAABJRU5ErkJggg==">';
      var polyIcon = '<img class="icon icons8-Polygon" width="25" height="25" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAACI0lEQVRIS2NkoANgRLfDteuUgQQv63pCdv/79//G01+MkQcKDT8QUothSdSUczuqw3TdudiY8ep9+/knw5w9dx59/vp3wdIcg3p8ilEsceg/L2AmwXEj01NdnJDrYPJn7739uu3ss8fP3nzL3FFpeQCbPhRLwiefnZDtpZ4vK8xFrB1wdetOPv5w6cH7vQ+//U9BD0IUS/IXX71T4KupTLINUA2gIFx26MGjp++/rVuZY1wIMwduSdCEM1lRdvJdxkrC3ORaAtN348nHL0sOP3jDzMCYuyjTYAvckvgZFy43ROjpUGoBsv5JW66f7I/RtgBb4tF+3MHPQmadp6G0IDUtmbXr1sH2MA0HsCVJsy4eKQ7QsiaUbEl1AIolMdPOPWiOMpAn1RBC6kctIRRCKPKjwUV0cD1++41hxvZby5blGEWD8wktknD72sv3bn36ZwwqLMGWRE45d6cmTFeZWplx+/mn7w9ee5W7Mtt4Kch8WLGiICrAsc5aXUze3UhSiOgwwaIQVBJP2npz/6IMAyeMUhhWhkmLcM7wNJKWNVYmo1JhYGCYvPXG43Ovfuoh1ykY1S/IMlCxL8bHVhFlryRLSgV29u7bb0sO3W/ZUGjWjuxJrJaAFICqYnGWfw3qUrwhfmay0sK87HhD8duvvwwTNt84PTtZ1wxdIU5LYAo92o8riAtx9kgJcojgs+Xz97+MN19+zd9dZnaBZEsoSQQwvQDSTvsapRF3SwAAAABJRU5ErkJggg==">';

      //
      // Define rotate to north control.
      //


      /**
       * @constructor
       * @extends {ol.control.Control}
       * @param {Object=} opt_options Control options.
       */
app.CustomToolbarControl = function(opt_options) {

  var options = opt_options || {};

  var button = document.createElement('button');
  button.innerHTML = pointIcon;
    
  var button1 = document.createElement('button');
  button1.innerHTML = polyIcon;
    
  var selectList = document.createElement("select");
  selectList.id = "mySelect";
  selectList.onchange = function(e){
      console.log(e);
      alert(this.value);
  }
  var array = ["layer1","layer2","layer3","layer4"];
  for (var i = 0; i < array.length; i++) {
    var option = document.createElement("option");
    option.value = array[i];
    option.text = array[i];
    selectList.appendChild(option);
	}


  //global so we can remove it later
  var draw;

  var this_ = this;

  
  var drawPoint = function(e) {
    draw = new ol.interaction.Draw({
        source: source,
        type: "Point"
    });
    map.addInteraction(draw);

    draw.on('drawend', function(evt) {
        map.removeInteraction(draw);
    }, this);
  };



  button.addEventListener('click', drawPoint, false);
  //button.addEventListener('touchstart', handleRotateNorth, false);

  var element = document.createElement('div');
  element.className = 'ol-unselectable ol-mycontrol';
  element.appendChild(button);
  element.appendChild(button1);
  element.appendChild(selectList);

  ol.control.Control.call(this, {
    element: element,
    target: options.target
  });

};
ol.inherits(app.CustomToolbarControl, ol.control.Control);




      //
      // Create map, giving it a rotate to north control.
      //


      var map = new ol.Map({
        controls: ol.control.defaults({
          attributionOptions: /** @type {olx.control.AttributionOptions} */ ({
            collapsible: false
          })
        }).extend([
          new app.CustomToolbarControl()
        ]),
        layers: [
          new ol.layer.Tile({
            source: new ol.source.OSM()
          }), vector 
        ],
        target: 'map',
        view: new ol.View({
          center: [0, 0],
          zoom: 3,
          rotation: 1
        })
      });
    </script>
  </body>
</html>