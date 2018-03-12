<!doctype html>
<html lang="en">
  <head>
    <link rel="stylesheet" href="https://openlayers.org/en/v4.6.4/css/ol.css" type="text/css">
    <style>
      .map {
        height: 400px;
        width: 100%;
      }
    </style>
    <script src="https://openlayers.org/en/v4.6.4/build/ol.js" type="text/javascript"></script>
    <title>OpenLayers example</title>
  </head>
  <body>
    <h2>My Map</h2>
    <div id="map" class="map"></div>
    <script type="text/javascript">
    // a planet API key is required
var planet_api_key = '7c43e05581e94ad9b907802ec0e9c903'

// construct an Open Layers template string for a Planet item based
// on its item_type and item_id
function planet_template_url(item_type, item_id) {
  return 'https://tiles{1-3}.planet.com/data/v1/' + item_type +'/' + item_id + '/{z}/{x}/{y}.png?api_key=' + planet_api_key
}

// the geo json geometry object bounding Singapore
geo_json_geometry = {
  "type": "Polygon",
  "coordinates": [
    [
 [4.770812988281249,52.35180401565967],[4.803771972656249,52.335863527683586],[4.859046936035155,52.323274872052195],[4.915695190429687,52.32306503077291],
 [4.946594238281248,52.2854874074429],[4.993629455566405,52.29115758313225],[4.99774932861328,52.3008162110651],[4.986419677734374,52.32159611395241],
 [4.938354492187499,52.33859059756017],[4.965133666992187,52.358304406670584],[5.015258789062498,52.3440442328276],[5.024528503417968,52.35012634019213],
 [4.950370788574218,52.40503711170865],[4.879302978515625,52.42702417458642],[4.7962188720703125,52.42451192222822],[4.73785400390625,52.42639612491925],
 [4.782485961914063,52.383667752322566],[4.770812988281249,52.35180401565967]
    ]
  ]
}

// a search filter for items the overlap with our chosen geometry
geometry_filter = {
  "type": "GeometryFilter",
  "field_name": "geometry",
  "config": geo_json_geometry
}

// a search filter that filters out scenes with 50% or more cloud cover
cloud_cover_filter = {
  "type": "RangeFilter",
  "field_name": "cloud_cover",
  "config": {
    "lte": 0.02
  }
}

// a search filter for a specific date range
date_range_filter = {
  "type": "DateRangeFilter",
  "field_name": "acquired",
  "config": {
    "gte": "2018-01-01T00:00:00.000Z",
    "lte": "2018-03-12T00:00:00.000Z"
  }
}

// a search filter that combines our date range filter
// with our geometry filter
combined_filter = {
  "type": "AndFilter",
  "config": [geometry_filter, date_range_filter, cloud_cover_filter]
}

// make a search request against the Planet Data API with a specified filter
// and item_type
function get_items(item_type, filter, callback) {
  search_endpoint_request = {
    "item_types": [item_type],
    "filter": filter
  }
  var xhr = new XMLHttpRequest();
  xhr.open("POST", 'https://api.planet.com/data/v1/quick-search')
  xhr.setRequestHeader("Authorization", "Basic " + btoa(planet_api_key + ":"));
  xhr.setRequestHeader("Content-Type", "application/json");
  console.log(JSON.stringify(search_endpoint_request))
  xhr.onload = function (e) {
    if (xhr.readyState === 4) {
      console.log("made it")
      callback(JSON.parse(xhr.responseText))
    }
  }
  xhr.send(JSON.stringify(search_endpoint_request))
}

function build_open_layers_map(layers) {
  var map = new ol.Map({
    layers: layers,
    target: 'map',
    view: new ol.View({
      center: ol.proj.fromLonLat([4.907,52.366]),//Amsterdam coords
      zoom: 12
    })
  })
}


// run the script
get_items("PSScene4Band", combined_filter, function(response) {
  // add OSM as the first layer
  layers = [
    new ol.layer.Tile({
      source: new ol.source.OSM()
    })
  ]
  // loop over planet items from the seach api
  // and add them as layers
  response["features"].forEach(feature =>{
    layers.push(
      new ol.layer.Tile({
        source: new ol.source.XYZ({url: planet_template_url("PSScene3Band", feature["id"])})
      })
    )
  })

  build_open_layers_map(layers)
})

    </script>
  </body>
</html>
