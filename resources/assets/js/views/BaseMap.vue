<template class="tw-w-full">
  <div class="tw-w-full">

    <div>
      <b-row>
        <b-col cols="8">
          <!--autosearch -->
          <auto-search
            :circle-full-address="drawCircle.circleFullAddress"
            @get-search-data="handleSearchData"
            @clear-Search="clearSearch"
          />
        </b-col>

        <b-col cols="4">
          <!-- crisis -->
          <toggle-map
            @get-toggle-data="handleToggleData"
            @clear-toggle-data="handleClearToggleData"
          />
        </b-col>
      </b-row>

      <b-row>
        <b-col
          cols="12"
          md="8"
        >
          <!-- google base map -->
          <GmapMap
            ref="mapRef"
            style="width: auto; height: 600px;"
            :zoom="zoom_lvl"
            :center="sgcoord"
          >
          </GmapMap>
        </b-col>

        <b-col
          cols="6"
          md="4"
        >

          <draw-tool
            :gas-leak-markers="markers.gasLeakMarkers"
            :fire-markers="markers.fireMarkers"
            :base-map-all-shape="baseMapAllShape"
            :selected-shape="selectedShape"
            :enable-drawing="enableDrawingToolsExtension"
            :circle-drawing-center="localDrawCircle.center"
            :circle-drawing-radius="localDrawCircle.radius"
            :marker-drawing="localDrawMarker.position"
            @get-updated-circle-drawing="handleCircleData"
            @get-updated-fire-drawing="handleFireData"
            @get-updated-gas-drawing="handleGasLeakData"
            @get-backend-data="handleBackendData"
          >
          </draw-tool>

        </b-col>

      </b-row>

    </div>

    <button
      id="delete-button"
      @click="deleteSelectedShape"
    >Delete Selected Shape</button>

  </div>
</template>

<script>
import { Modal } from "bootstrap-vue/es/components";
import * as turf from "@turf/turf";
import DrawTool from "./DrawingTool";
import AutoSearchComplete from "./AutoSearchComplete";
import ToggleMap from "./ToggleCrisisMap";

export default {
  mounted() {
    var scope = this;
    //  this.$refs.mapRef.$mapPromise.then(map => {
    //   map.data.setControls(['Point', 'LineString', 'Polygon']);
    //   map.data.setStyle({
    //     editable: true,
    //     draggable: true
    //   });

    //   scope.bindDataLayerListeners(map.data);
    //  });

    this.$refs.mapRef.$mapPromise.then(map => {
      scope.markerInfoWindow = new google.maps.InfoWindow({
        content: ""
      });

      var polyOptions = {
        strokeColor: "#E84B3C",
        fillColor: "#E84B3C",
        strokeWeight: 1,
        fillOpacity: 0.35,
        editable: true,
        draggable: true
      };

      scope.drawingManager = new google.maps.drawing.DrawingManager({
        drawingMode: google.maps.drawing.OverlayType.null,
        drawingControlOptions: {
          position: google.maps.ControlPosition.TOP_CENTER
        },
        markerOptions: {
          draggable: true
        },
        polylineOptions: {
          editable: true,
          draggable: true
        },
        rectangleOptions: polyOptions,
        circleOptions: polyOptions,
        polygonOptions: polyOptions,
        map: map
      });

      google.maps.event.addListener(
        scope.drawingManager,
        "overlaycomplete",
        function(e) {
          var newShape = e.overlay;

          newShape.type = e.type;

          //push any shape to baseMapAllshape
          e.overlay["id"] = scope.baseMapAllShape.length;
          scope.baseMapAllShape.push(e);

          console.log(e);

          // Switch back to non-drawing mode after drawing a shape, inclcude marker
          scope.drawingManager.setDrawingMode(null);

          if (e.type !== google.maps.drawing.OverlayType.MARKER) {
            // Add an event listener that selects the newly-drawn shape when the user
            // mouses down on it.
            google.maps.event.addListener(newShape, "click", function(e) {
              if (e.vertex !== undefined) {
                if (newShape.type === google.maps.drawing.OverlayType.POLYGON) {
                  var path = newShape.getPaths().getAt(e.path);
                  path.removeAt(e.vertex);
                  if (path.length < 3) {
                    newShape.setMap(null);
                  }
                }
                if (
                  newShape.type === google.maps.drawing.OverlayType.POLYLINE
                ) {
                  var path = newShape.getPath();
                  path.removeAt(e.vertex);
                  if (path.length < 2) {
                    newShape.setMap(null);
                  }
                }
              }
              scope.setSelection(newShape);
            });

            // //add radius listener
            // google.maps.event.addListener(newShape , 'radius_changed', function(event) {
            //  scope.localDrawCircle.radius = newShape.radius;
            // });

            // //add re-center listener
            // google.maps.event.addListener(newShape, 'center_changed', function(event) {

            // scope.localDrawCircle.center = newShape.center;

            // });

            scope.setSelection(newShape);
          } else {
            //marker selection99

            //attach infowindow

            //  var contentString = '<div id="iw-container">' +
            //     '<div class="iw-title">'+"sdfsdf"+'</div>' +
            //     '<div class="iw-content">' +
            //       '<div class="iw-subTitle">'+"8899"+'</div>' +
            //     '</div>' +
            //     '<div class="iw-bottom-gradient"></div>' +
            //   '</div>';

            //   google.maps.event.addListener(newShape, 'click', function() {
            //   scope.markerInfoWindow.setContent(contentString);
            //   scope.markerInfoWindow.open(map, this);
            // });

            //   google.maps.event.addListener(newShape, 'click', function (e) {
            //       scope.setSelection(newShape);
            //   });

            //    google.maps.event.addListener(newShape, "dragend", function(e) {

            //       scope.localDrawMarker.position=e.latLng;
            //    });

            scope.setSelection(newShape);
          }

          // Clear the current selection when the drawing mode is changed, or when the
          // map is clicked.
          google.maps.event.addListener(
            scope.drawingManager,
            "drawingmode_changed",
            scope.clearSelection
          );
          google.maps.event.addListener(map, "click", scope.clearSelection);
          //google.maps.event.addDomListener(document.getElementById('delete-button'), 'click', deleteSelectedShape);
        }
      );
    });
  },

  components: {
    drawTool: DrawTool,
    autoSearch: AutoSearchComplete,
    toggleMap: ToggleMap
  },

  data() {
    return {
      localFireData: null,
      baseMapAllShape: [],
      geoJson: [],
      markerInfoWindow: null,
      searchMarker: null,
      drawCircle: {
        marker: null,
        circle: null,
        draggableMarkerListener: null,
        clickMarkerListener: null,
        circleFullAddress: ""
      },
      isLoading: false,
      zoom_lvl: 12,
      sgcoord: { lat: 1.3521, lng: 103.8198 },
      markers: { twoHrWeatherMarkers: [], fireMarkers: [], gasLeakMarkers: [] },
      polygon: { dengueData: [] },
      enableDrawingToolsExtension: false,
      drawingTool: null,
      localDrawCircle: {
        center: null,
        radius: null,
        localCircleRadiusListener: null,
        localCircleCenterListener: null
      },
      drawingManager: null,
      selectedShape: null,
      localDrawMarker: { position: null }
    };
  },
  methods: {
    bindDataLayerListeners(dataLayer) {
      dataLayer.addListener("addfeature", this.refreshGeoJsonFromData);
      //dataLayer.addListener('removefeature', this.refreshGeoJsonFromData);
      dataLayer.addListener("setgeometry", this.refreshGeoJsonFromData);
    },
    refreshGeoJsonFromData() {
      var scope = this;
      this.$refs.mapRef.$mapPromise.then(map => {
        map.data.toGeoJson(function(geoJson) {
          scope.geoJson.value = JSON.stringify(scope.geoJson, null, 2);
          console.log("2");
        });
      });
    },
    handleBackendData(backendData) {},
    removePolygon(polygonVar, polygonData) {
      for (var i = 0; i < polygonData.length; i++) {
        if (polygonVar == "hideDengueData") {
          polygonData[i].setMap(null);
        }
      }
    },

    handleClearToggleData(clearToggleData) {
      //empty markers
      if (clearToggleData === "hideDengueData") {
        this.removePolygon(clearToggleData, this.polygon.dengueData);
        this.polygon.dengueData = [];
      } else if (clearToggleData === "hideFireData") {
        this.removeMarkers(this.markers.fireMarkers);
        this.markers.fireMarkers = [];
      } else if (clearToggleData === "hideGasLeakData") {
        this.removeMarkers(this.markers.gasLeakMarkers);
        this.markers.gasLeakMarkers = [];
      } else if (clearToggleData === "hideHazeData") {
        scope.showHazeData(element);
      } else if (clearToggleData === "hideRainData") {
        scope.showRainData(element);
      } else if (clearToggleData === "hideTwoHrWeatherData") {
        console.log("clear 2h weather");

        this.removeMarkers(this.markers.twoHrWeatherMarkers);
        this.markers.twoHrWeatherMarkers = [];
      }
    },
    handleToggleData(toggleData) {
      if (toggleData.displayId === "showDegueData") {
        this.showDengueData(toggleData);
      } else if (toggleData.displayId === "showFireData") {
        this.showFireData(toggleData);
      } else if (toggleData.displayId === "showGasLeakData") {
        this.showGasLeakData(toggleData);
      } else if (toggleData.displayId === "showHazeData") {
        //scope.showHazeData(element);
      } else if (toggleData.displayId === "showRainData") {
        //scope.showRainData(element);
      } else if (toggleData.displayId === "showTwoHrWeatherData") {
        this.showTwoHrWeatherData(toggleData);
      }
    },
    getMountedComponent(component) {
      //get the first active component
      console.log(component);
    },
    deleteSelectedShape() {
      if (this.selectedShape) {
        this.selectedShape.setMap(null);
      }
    },
    clearSelection() {
      if (this.selectedShape) {
        if (this.selectedShape.type !== "marker") {
          console.log("not marker");
          this.selectedShape.setEditable(false);
        }

        this.selectedShape = null;
      }
    },
    setSelection(shape) {
      if (shape.type !== "marker") {
        console.log("998");
        this.clearSelection();
        shape.setEditable(true);

        //selectColor(shape.get('fillColor') || shape.get('strokeColor'));
      }

      this.selectedShape = shape;
    },
    enableDrawingTool() {
      var scope = this;

      if (!this.enableDrawingToolsExtension) {
        this.enableDrawingToolsExtension = true;
      } else {
        this.enableDrawingToolsExtension = false;
      }

      //this.tabs.push(this.tabCounter++)
    },
    addDraggendListener(draggendVar) {
      var scope = this;
      //attach draggend listener
      google.maps.event.addListener(draggendVar, "dragend", function(e) {
        var latLng = e.latLng;
        var currentLatitude = latLng.lat();
        var currentLongitude = latLng.lng();

        //var temp = draggendVar;

        draggendVar.setPosition({
          lat: currentLatitude,
          lng: currentLongitude
        });
        var temp = draggendVar;
        // draggendVar = null;
        //draggendVar.setMap(null);
        scope.clearSelection();
        scope.setSelection(temp);
        // scope.drawCircle.circle.setOptions({center:{lat:currentLatitude,lng:currentLongitude}});

        //update full address
        //pass by props
        // new google.maps.Geocoder().geocode(
        //   {
        //     location: {
        //       lat: currentLatitude,
        //       lng: currentLongitude
        //     }
        //   },
        //   function(results, status) {
        //     if (status === "OK") {
        //       scope.drawCircle.circleFullAddress = results[0].formatted_address;

        //     }
        //   }
        // );
      });
    },
    haveExistingSearchMarker(circleData) {
      var scope = this;

      this.$refs.mapRef.$mapPromise.then(map => {
        console.log("have search marker");

        if (scope.searchMarker) {
          scope.drawCircle.marker = new google.maps.Marker({
            draggable: true,
            position: scope.searchMarker.position,
            map: map
          });

          scope.drawCircle.circle = new google.maps.Circle({
            path: google.maps.SymbolPath.CIRCLE,
            strokeColor: circleData.circleFillColor,
            strokeOpacity: 1,
            strokeWeight: 1,
            fillColor: circleData.circleFillColor,
            fillOpacity: 0.35,
            map: map,
            center: scope.searchMarker.position,
            radius: circleData.circleRadiusValue
          });

          //remove search marker from map
          scope.searchMarker.setMap(null);
          scope.searchMarker = null;

          //attach draggend listener
          scope.drawCircle.draggableMarkerListener = google.maps.event.addListener(
            scope.drawCircle.marker,
            "dragend",
            function(marker) {
              var latLng = marker.latLng;
              var currentLatitude = latLng.lat();
              var currentLongitude = latLng.lng();

              scope.drawCircle.circle.setOptions({
                center: { lat: currentLatitude, lng: currentLongitude }
              });

              //update full address
              //pass by props
              new google.maps.Geocoder().geocode(
                {
                  location: {
                    lat: currentLatitude,
                    lng: currentLongitude
                  }
                },
                function(results, status) {
                  if (status === "OK") {
                    scope.drawCircle.circleFullAddress =
                      results[0].formatted_address;
                  }
                }
              );
            }
          );
        }
      });
    },
    noExistingSearchMarker(circleData) {
      var scope = this;

      this.$refs.mapRef.$mapPromise.then(map => {
        console.log("no search marker");

        scope.drawCircle.clickMarkerListener = google.maps.event.addListener(
          map,
          "click",
          function(event) {
            scope.drawCircle.marker = new google.maps.Marker({
              draggable: true,
              position: event.latLng,
              map: map
            });

            scope.drawCircle.circle = new google.maps.Circle({
              path: google.maps.SymbolPath.CIRCLE,
              strokeColor: circleData.circleFillColor,
              strokeOpacity: 1,
              strokeWeight: 1,
              fillColor: circleData.circleFillColor,
              fillOpacity: 0.35,
              map: map,
              center: event.latLng,
              radius: circleData.circleRadiusValue
            });

            //code can be further optimized
            //or retrieve from backend
            new google.maps.Geocoder().geocode(
              {
                location: event.latLng
              },
              function(results, status) {
                if (status === "OK") {
                  scope.drawCircle.circleFullAddress =
                    results[0].formatted_address;
                }
              }
            );

            scope.drawCircle.draggableMarkerListener = google.maps.event.addListener(
              scope.drawCircle.marker,
              "dragend",
              function(marker) {
                var latLng = marker.latLng;
                var currentLatitude = latLng.lat();
                var currentLongitude = latLng.lng();

                scope.drawCircle.circle.setOptions({
                  center: { lat: currentLatitude, lng: currentLongitude }
                });

                new google.maps.Geocoder().geocode(
                  {
                    location: {
                      lat: currentLatitude,
                      lng: currentLongitude
                    }
                  },
                  function(results, status) {
                    if (status === "OK") {
                      scope.drawCircle.circleFullAddress =
                        results[0].formatted_address;
                    }
                  }
                );
              }
            );

            //remove click on map listenser

            if (scope.drawCircle.clickMarkerListener != null) {
              google.maps.event.removeListener(
                scope.drawCircle.clickMarkerListener
              );
            }
          }
        );
      });
    },
    getTabInfo: function(event) {
      console.log(event);
    },
    clearSearch() {
      //reset components
      this.enableDrawingToolsExtension = false;

      //remove search marker
      if (this.searchMarker) {
        this.searchMarker.setMap(null);
        this.searchMarker = null;
      }

      //clear drawcircle
      if (this.drawCircle) {
        this.drawCircle.marker.setMap(null);
        this.drawCircle.circle.setMap(null);
        this.drawCircle.circleFullAddress = "";
        google.maps.event.removeListener(
          this.drawCircle.draggableMarkerListener
        );
        google.maps.event.removeListener(
          this.drawCircle.draggableMarkerListener
        );

        if (this.drawCircle.clickMarkerListener) {
          google.maps.event.removeListener(this.drawCircle.clickMarkerListener);
        }
      }
    },
    handleSearchData(searchData) {
      var pos = {
        lat: searchData.lat,
        lng: searchData.lng
      };

      this.$refs.mapRef.$mapPromise.then(map => {
        if (!this.searchMarker) {
          //create search marker
          this.searchMarker = new google.maps.Marker({
            draggable: true,
            position: pos,
            map: map
          });
        } else {
          //just change latlng
          this.searchMarker.setPosition(pos);
        }
      });
    },
    retrieveAddressFromBackEnd(postalCode) {
      axios
        .get("/api/crisis/gasLeak")
        .then(res => {
          console.log(res);
        })
        .catch(error => {
          console.log(error);
        })
        .then(() => {});

      axios
        .get("/api/crisis/dengue")
        .then(res => {
          console.log(res);
        })
        .catch(error => {
          console.log(error);
        })
        .then(() => {});

      axios
        .get("/api/crisis/fire")
        .then(res => {
          console.log(res);
        })
        .catch(error => {
          console.log(error);
        })
        .then(() => {});
    },
    clearLocalCircleDrawing() {
      this.localDrawCircle.localCircleCenterListener = null;
      this.localDrawCircle.localCircleRadiusListener = null;
    },

    handleGasLeakData(gasLeakData) {
      var scope = this;
      console.log(gasLeakData);
      this.$refs.mapRef.$mapPromise.then(map => {
        this.markers.gasLeakMarkers.forEach((element, index) => {
          if (element.db_data.id === gasLeakData.id) {
            element.setAnimation(google.maps.Animation.BOUNCE);
            setTimeout(function() {
              element.setAnimation(null);
            }, 10000);
          } else {
            element.setAnimation(null);
          }
        });
      });
    },
    handleFireData(fireData) {
      var scope = this;

      this.$refs.mapRef.$mapPromise.then(map => {
        this.markers.fireMarkers.forEach((element, index) => {
          if (element.db_data.id === fireData.id) {
            element.setAnimation(google.maps.Animation.BOUNCE);
            setTimeout(function() {
              element.setAnimation(null);
            }, 10000);
          } else {
            element.setAnimation(null);
          }
        });
      });
    },
    handleCircleData(circleData) {
      //  this.selectedShape.setOptions({
      //    center:circleData.center,
      //    radius:parseFloat(circleData.circleRadiusValue),
      //    fillColor: circleData.circleFillColor,
      //    strokeColor: circleData.circleFillColor
      //    });

      this.baseMapAllShape.forEach((element, index) => {
        //set rest shape editable false

        if (element.overlay.id === circleData.id) {
          element.overlay.setOptions(circleData);
        } else {
          element.overlay.setOptions({ editable: false });
        }
      });
    },
    removeMarkers(removeMarkersType) {
      console.log("remove ");

      for (var i = 0; i < removeMarkersType.length; i++) {
        removeMarkersType[i].setMap(null);
      }
    },
    showDengueData(dengue) {
      var scope = this;
      this.$refs.mapRef.$mapPromise.then(map => {
        dengue.forEach((element, index) => {
          //     strokeColor: '#E84B3C',
          //     fillColor: '#E84B3C',
          //     strokeWeight: 1,
          //     fillOpacity: 0.35,
          //     clickable: false,
          //     editable: true,
          //     zIndex: 1

          if (element.type == "circle") {
            var temp = new google.maps.Circle({
              path: google.maps.SymbolPath.CIRCLE,
              strokeColor: element.fillColor,
              strokeOpacity: 1,
              strokeWeight: 1,
              fillColor: element.fillColor,
              fillOpacity: 0.35,
              map: map,
              center: element.center,
              radius: element.radius
            });

            scope.polygon.dengueData.push(temp);
          }
        });
      });
    },
    showFireData(fireData) {
      var scope = this;

      fireData.crises.forEach((element, index) => {
        //add marker to twoHrWeatherMarkers variable
        //param(variable,icon,inforwindow content)

        scope.addMarker(
          "Array",
          scope.markers.fireMarkers,
          {
            icon: fireData.iconUrl,
            markerDisplayId: element.id,
            position: { lat: element.lat, lng: element.lng }
          },
          {
            infoWindowTitle: element.name,
            infoWindowBody: element,
            imageAvailable: false
          },
          element
        );
      });

      //this.addMarker("Array",this.markers.fireMarkers,)
      //addMarker(markerVarType,markerVar,element,infowindow)
    },
    showGasLeakData(gasLeakData) {
      var scope = this;
      console.log(gasLeakData);
      gasLeakData.crises.forEach((element, index) => {
        //add marker to twoHrWeatherMarkers variable
        //param(variable,icon,inforwindow content)

        scope.addMarker(
          "Array",
          scope.markers.gasLeakMarkers,
          {
            icon: gasLeakData.iconUrl,
            markerDisplayId: element.id,
            position: { lat: element.lat, lng: element.lng }
          },
          { infoWindowTitle: element.name, infoWindowBody: element },
          element
        );
      });
    },
    showHazeData() {},
    showRainData() {},
    addMarker(markerVarType, markerVar, element, infowindow, db_data) {
      var scope = this;

      this.$refs.mapRef.$mapPromise.then(map => {
        //new marker
        var temp = new google.maps.Marker({
          icon: {
            url: element.icon ? element.icon : "",
            scaledSize: new google.maps.Size(32, 32)
          },
          draggable: element.draggable ? element.draggable : false,
          markerDisplayId: element.displayId ? element.displayId : "",
          animation: element.animation
            ? element.animation
            : google.maps.Animation.DROP,
          position: element.position,
          map: map,
          label: element.label ? element.label : "",
          visible: element.visible ? element.visible : true,
          zIndex: element.zIndex ? element.zIndex : 1,
          clickable: element.clickable ? element.clickable : true,
          opacity: element.opacity ? element.opacity : 1
        });

        if (typeof infowindow.infoWindowBody.image !== "undefined") {
          //attach infowindow
          var contentString =
            '<div id="iw-container">' +
            '<div class="iw-title">' +
            infowindow.infoWindowTitle +
            "</div>" +
            '<div class="iw-content">' +
            '<div class="iw-subTitle">' +
            infowindow.infoWindowBody.description +
            "</div>" +
            '<img src = "/crisis/' +
            infowindow.infoWindowBody.image +
            '" alt="fire image"/>' +
            "</div>";
          '<div class="iw-bottom-gradient"></div>' + "</div>";
        } else {
          var contentString =
            '<div id="iw-container">' +
            '<div class="iw-title">' +
            infowindow.infoWindowTitle +
            "</div>" +
            '<div class="iw-content">' +
            '<div class="iw-subTitle">' +
            infowindow.infoWindowBody +
            "</div>" +
            "</div>";
          '<div class="iw-bottom-gradient"></div>' + "</div>";
        }

        google.maps.event.addListener(temp, "mouseover", function() {
          scope.markerInfoWindow.setContent(contentString);
          scope.markerInfoWindow.open(map, this);
        });

        // if want to assign to a array
        if (markerVarType === "Array") {
          temp["db_data"] = db_data;
          markerVar.push(temp);
        }
      });
    },
    showTwoHrWeatherData(data) {
      var scope = this;

      data.area_metadata.forEach((element, index) => {
        //add marker to twoHrWeatherMarkers variable
        //param(variable,icon,inforwindow content)
        console.log(element);
        scope.addMarker(
          "Array",
          scope.markers.twoHrWeatherMarkers,
          {
            icon: data.iconUrl,
            markerDisplayId: element.displayId,
            position: {
              lat: element.label_location.latitude,
              lng: element.label_location.longitude
            }
          },
          {
            infoWindowTitle: element.name,
            infoWindowBody: data.items[0].forecasts[index].forecast,
            imageAvailable: true
          },
          null
        );
      });
    },
    panMap(lat, lng) {
      //pan to any location on the map by giving lat and lng coordinates
      this.$refs.mapRef.$mapPromise.then(map => {
        map.panTo({ lat: lat, lng: lng });
      });
    },
    setMapZoomLvl(zoomlvl) {
      this.zoom_lvl = zoomlvl;
    }
  },
  watch: {},
  computed: {
    computedGeoJson: function() {
      console.log("computed");
      return this.geoJson;
    }
  }
};
</script>

<style>
.mapClass {
  width: 20%;
}

#iw-container .iw-title {
  font-family: "Open Sans Condensed", sans-serif;
  font-size: 22px;
  font-weight: 400;
  padding: 10px;
  background-color: #48b5e9;
  color: white;
  margin: 0;
  border-radius: 2px 2px 0 0;
}
#iw-container .iw-content {
  font-size: 13px;
  line-height: 18px;
  font-weight: 400;
  margin-right: 1px;
  padding: 15px 5px 20px 15px;
  max-height: 140px;
  overflow-y: auto;
  overflow-x: hidden;
}
.iw-content img {
  float: right;
  margin: 0 5px 5px 10px;
}
.iw-subTitle {
  font-size: 16px;
  font-weight: 700;
  padding: 5px 0;
}
.iw-bottom-gradient {
  position: absolute;
  width: 326px;
  height: 25px;
  bottom: 10px;
  right: 18px;
  background: linear-gradient(
    to bottom,
    rgba(255, 255, 255, 0) 0%,
    rgba(255, 255, 255, 1) 100%
  );
  background: -webkit-linear-gradient(
    top,
    rgba(255, 255, 255, 0) 0%,
    rgba(255, 255, 255, 1) 100%
  );
  background: -moz-linear-gradient(
    top,
    rgba(255, 255, 255, 0) 0%,
    rgba(255, 255, 255, 1) 100%
  );
  background: -ms-linear-gradient(
    top,
    rgba(255, 255, 255, 0) 0%,
    rgba(255, 255, 255, 1) 100%
  );
}
</style>