const ACCESS_TOKEN = 'pk.eyJ1IjoibWFyY2VsaXRvb29vIiwiYSI6ImNtNm1hNG5vdDBmaGUya3NoZnRldnhqd3YifQ.CMI4nKvoE7I8H9Dal7IHyw';
const LOCATION_COORDINATES = [8.770329057670912, 47.371124555517255];
const MAP_CONTAINER = 'contact-map';

const initMap = () => {
  mapboxgl.accessToken = ACCESS_TOKEN;
  let map = new mapboxgl.Map({
    container: MAP_CONTAINER,
    style: 'mapbox://styles/marcelitoooo/cm4vasj48001h01s9g6kubpu9',
    center: LOCATION_COORDINATES,
    zoom: 13
  });
  map.addControl(new mapboxgl.NavigationControl());
  map.scrollZoom.disable();

  const geojson = {
    type: 'FeatureCollection',
    features: [{
      type: 'Feature',
      geometry: {
        type: 'Point',
        coordinates: LOCATION_COORDINATES
      },
      properties: {
        title: 'Carrosserie Sandtner',
        description: ''
      }
    }]
  };

  // add markers to map
  geojson.features.forEach((marker) => {
  // create a HTML element for each feature
  let el = document.createElement('div');
  el.className = 'marker';

  // make a marker for each feature and add to the map
  new mapboxgl.Marker(el)
    .setLngLat(marker.geometry.coordinates)
    .addTo(map);
  });
};

const loadMapScript = () => {
  const script = document.createElement('script');
  script.src = 'https://api.mapbox.com/mapbox-gl-js/v3.7.0/mapbox-gl.js';
  script.async = true;
  document.head.appendChild(script);
  script.onload = () => {
    const mapEl = document.getElementById(MAP_CONTAINER);
    if (mapEl !== null) {
      initMap();
    }
  }
}
loadMapScript();