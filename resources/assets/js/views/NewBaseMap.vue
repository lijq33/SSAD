<template class="tw-w-full">
  <div class="tw-w-full">

    <div>
      <b-row>
        <b-col cols="8">
          <!--autosearch -->
          <auto-search
            :query-full-address="searchMarkerFullAddress"
            :clear-search-result-value="clearSearchVal"
            @get-search-data="handleSearchData"
            @confirm-address="handleConfirmAddress"
           
          />
        </b-col>

        <b-col cols="4">
          <!-- crisis -->
          <toggle-map v-if="!hideToggleMap"
            @get-toggle-data="handleToggleData"
            @clear-toggle-data="handleClearToggleData"
          />
        </b-col>
      </b-row>

        <div v-if="hideDrawingMap" class="form-group row">
        <label
          for="Address"
          class="col-md-1 col-form-label"
        >
          Radius:
        </label>
        <div class="col-md-5">
          <input type = "text"
              class = "tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey tw-italic"
              v-model = "newCircleRadius"
          >
           
        </div>
      </div>

      <b-row>
        <b-col
          cols="12"
          md="12"
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

         

      </b-row>

    </div>
 

  </div>
</template>

<script>
import { Modal } from "bootstrap-vue/es/components";
import * as turf from "@turf/turf";
import DrawTool from "./DrawingTool";
import AutoSearchComplete from "./AutoSearchComplete";
import ToggleMap from "./ToggleCrisisMap";

export default {
  props:["hideToggleWindow","hideDrawingWindow","clearSearchResult"],
   mounted(){
     var scope = this;
     this.$refs.mapRef.$mapPromise.then(map => {

     
      scope.markerInfoWindow = new google.maps.InfoWindow({
        content: ""
      });

      
       });

  },

  components: {
    drawTool: DrawTool,
    autoSearch: AutoSearchComplete,
    toggleMap: ToggleMap
  },

  data() {
    return {
      newCircle:null,
      clearSearchVal:false,
      newCircleRadius:'',
      hideDrawingMap:false,
      hideToggleMap:false,
      localFireData: null,
      baseMapAllShape: [],
      geoJson: [],
      markerInfoWindow: null,
      searchMarker: null,
      searchMarkerFullAddress:null,
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
      polygon: { dengueData: [],dengueMarkerData:[] },
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

    handleConfirmAddress(confirmAddress){

      if(this.newCircleRadius){
        confirmAddress["radius"]=this.newCircleRadius;
      }
     this.$emit("get-new-crisis-location",confirmAddress);
    },

    handleChangeMarkerIcon(iconUrl){

      var scope = this;

         if (this.selectedShape) {
        if (this.selectedShape.type !== "marker") {
       
          
        }else{ 
          this.selectedShape.setIcon({url:iconUrl,scaledSize: new google.maps.Size(32, 32)});
            this.selectedShape.setAnimation(google.maps.Animation.BOUNCE);
            setTimeout(function() {
              if(scope.selectedShape){
                 scope.selectedShape.setAnimation(null);
              }
             
            }, 5000);

        } 
       
      }

    },
    handleCancelDrawingCreation(cancelDrawingType){
      console.log("clear393837")

       if (this.selectedShape) {
        if (this.selectedShape.type !== "marker") {
         this.selectedShape.setMap(null);
          
        }else{

             console.log("clear selected marker");
          this.selectedShape.setMap(null);

        }

        this.selectedShape = null;
        // To show:
        this.drawingManager.setOptions({
          drawingControl: true
        });
      }
      
      
    },
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

        //clear circle
        this.removePolygon(clearToggleData, this.polygon.dengueData);
        this.polygon.dengueData = [];

        //clear marker
        this.removePolygon(clearToggleData, this.polygon.dengueMarkerData);
        this.polygon.dengueMarkerData = [];

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

      // var scope = this;

      // var pos = {
      //   lat: searchData.lat,
      //   lng: searchData.lng
      // };

      // this.$refs.mapRef.$mapPromise.then(map => {
      //   if (!this.searchMarker) {
      //     //create search marker
      //     this.searchMarker = new google.maps.Marker({
      //       draggable: true,
      //       position: pos,
      //       map: map
      //     });
 
      //     //add drag listener

      //   google.maps.event.addListener(this.searchMarker, "dragend", function(e) {
      //   var latLng = e.latLng;

      //   var fullAddressPos = {
      //     lat:latLng.lat(),
      //     lng:latLng.lng()
      //   }
      //   scope.searchMarkerFullAddress = fullAddressPos;

      //   });

      //   this.panMap(searchData.lat,searchData.lng);


      //   } else {
      //     console.log(searchData)
      //     //just change latlng

      //     if(searchData.googleLatLng == null){
      //       this.searchMarker.setPosition(pos);
      //     this.panMap(searchData.lat,searchData.lng);
      //     }else{
      //     this.searchMarker.setPosition(searchData.googleLatLng);
      //     this.panMap(searchData.googleLatLng.lat,searchData.googleLatLng.lng);
      //     }
    
      //   }
      // });

      
      // this.setMapZoomLvl(17)

      var scope = this;

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
        

          //if is circle
          if(scope.hideDrawingMap){
            console.log("create dengue dengue")

          scope.newCircle = new google.maps.Circle({
              path: google.maps.SymbolPath.CIRCLE,
              strokeColor: '#E84B3C',
              strokeOpacity: 1,
              strokeWeight: 1,
              fillColor: '#E84B3C',
              fillOpacity: 0.35,
              map: map,
              center: pos,
              radius: 150,
              editable: true
            });

             scope.newCircle.bindTo('center', this.searchMarker, 'position');

             scope.newCircleRadius = 150;

              google.maps.event.addListener(scope.newCircle, "radius_changed", function(e) {
               
                scope.newCircleRadius =  parseFloat(scope.newCircle.radius);
              });


             
            
          }else{

          

          }

          //add drag listener

        google.maps.event.addListener(this.searchMarker, "dragend", function(e) {
        var latLng = e.latLng;

        var fullAddressPos = {
          lat:latLng.lat(),
          lng:latLng.lng()
        }

        scope.searchMarkerFullAddress = fullAddressPos;

        });

        } else {
          //just change latlng
        
          this.searchMarker.setPosition(pos);
        }
      });

      this.panMap(searchData.lat,searchData.lng);
      this.setMapZoomLvl(17)


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
          var scope = this;
           this.$refs.mapRef.$mapPromise.then(map => {
      this.polygon.dengueData.forEach((element, index) => {
        //set rest shape editable false

        if (element.db_data.id === circleData.id) {
            element.setOptions({
              editable:true,
              strokeColor: circleData.strokeColor,
              strokeOpacity: 1,
              strokeWeight: 1,
              fillColor: circleData.fillColor,
              fillOpacity: 0.35,
              radius: circleData.radius,
              center:circleData.center,
            });


        } else {
          element.setOptions({ editable: false });
        }
      });

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
        dengue.crises.forEach((element, index) => {
          

          var pos = {
                      lat: element.lat,
                      lng: element.lng
                    };


                  //create dengue search marker
         var tempDengueMarker = new google.maps.Marker({
            icon: {
              url: dengue.iconUrl,
              scaledSize: new google.maps.Size(64, 64)
            },
            draggable: false,
            position: pos,
            map: map
          });
        
  
 
            var temp = new google.maps.Circle({
              path: google.maps.SymbolPath.CIRCLE,
              strokeColor: '#E84B3C',
              strokeOpacity: 1,
              strokeWeight: 1,
              fillColor: '#E84B3C',
              fillOpacity: 0.35,
              map: map,
              center: pos,
              radius: element.radius,
            });
            
           temp.bindTo('center', tempDengueMarker, 'position');

            //attach infowindow
          var contentString =
            '<div id="iw-container">' +
            '<div class="iw-title">' +
            element.name +
            "</div>" +
            '<div class="iw-content">' +
            '<div class="iw-subTitle">' +
            element.description +
            "</div>" +
            '<img src = "/crisis/' +
            element.image +
            '" alt="dengue image"/>' +
            "</div>";
          '<div class="iw-bottom-gradient"></div>' + "</div>";
             
           google.maps.event.addListener(temp, "mouseover", function() {
          
          scope.markerInfoWindow.setContent(contentString);
           scope.markerInfoWindow.setPosition(pos);
          scope.markerInfoWindow.open(map, this);
        });

         
        scope.polygon.dengueData.push(temp);
         scope.polygon.dengueMarkerData.push(tempDengueMarker);

           
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
  watch: {

    newCircleRadius(){
        if(this.newCircle){
           
          if(isNaN(this.newCircleRadius)){
       
            this.newCircleRadius = 0;
          }     
          this.newCircle.setOptions({radius:parseFloat(this.newCircleRadius)});

        }
         
    },
    clearSearchResult(value){

       console.log("clear1")
      if(this.searchMarker){
         this.searchMarker.setMap(null);
        this.searchMarker = null;
        this.searchMarkerFullAddress=null;
        this.clearSearchVal = value;

        if(this.hideDrawingMap){
          this.newCircle.setMap(null);
        }
       
      }
    },
       hideToggleWindow(value){
      this.hideToggleMap = value;
    },
    hideDrawingWindow(value){
      this.hideDrawingMap = value;
    },
  },
  computed: {
 
    computedGeoJson: function() {
      console.log("computed");
      return this.geoJson;
    },
    currentUser() {
                return this.$store.getters.currentUser
            },
        
            isCallCenterOperator(){
                if (!this.currentUser)
                    return false
                return this.currentUser.roles == 'CallCenterOperator';
            },
                        
            isCrisisManager(){
                if (!this.currentUser)
                    return false
                return this.currentUser.roles == 'CrisisManager';
            },

            isAccountManager(){
                if (!this.currentUser)
                    return false
                return this.currentUser.roles == 'AccountManager';
            },
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