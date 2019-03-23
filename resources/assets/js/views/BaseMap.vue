<template>
  <div>

    <!-- draw circle -->
    <draw-circle :circle-full-address="drawCircle.circleFullAddress" @get-circle-drawing="handleCircleData"/>

    <GmapMap
      ref="mapRef"
      style="width: auto; height: 600px;"
      :zoom="zoom_lvl"
      :center="sgcoord"
    >
      <!-- <gmap-info-window
        :options="infoOptions"
        :position="infoWindowPos"
        :opened="infoWinOpen"
        @closeclick="infoWinOpen=false"
      >
        <span v-html="infoContent"></span>
      </gmap-info-window>

      <GmapMarker
        v-for="(marker, index) in markers"
        :key="index"
        :position="marker.position"
        :clickable="true"
        @click="toggleInfoWindow(marker,index)"
      />

      <GmapMarker
        v-if="this.place"
        label="★"
        :position="{ lat: this.place.position.lat, lng: this.place.position.lng }"
        :clickable="true"
        @click="toggleInfoWindow(place,99)"
      /> -->

    </GmapMap>
 
  </div>
</template>

<script>
import bCollapse from "bootstrap-vue/es/components/collapse/collapse";
import { ButtonGroup } from "bootstrap-vue/es/components";
import { Modal } from "bootstrap-vue/es/components";
import * as turf from "@turf/turf";
import DrawCircle from './DrawCircle';

export default {
  props: ["_place", "clearSearch","toggleData"],

  components: {
    "b-collapse": bCollapse,
    drawCircle:DrawCircle
  },

  data() {
    return {
      drawCircle:{marker:null,circle:null,draggableMarkerListener:null,clickMarkerListener:null,circleFullAddress:''},
      clearSearchVal: false,
      isLoading: false,
      zoom_lvl: 12,
      sgcoord: { lat: 1.3521, lng: 103.8198 },
      markers: [],
      place: null,
      nearbyplaces: [],
      bookAptBtnStatus: true,
      showAptBtnStatus: false,
      selectedPlace: null,
      infoContent: "",
      infoWindowPos: null,
      infoWinOpen: false,
      currentMidx: null,
      access_token: "",
      childData: null,
      modalShow: false,
      infoOptions: {
        pixelOffset: {
          width: 0,
          height: -35
        }
      }
    };
  },
  description: "",

  methods: {
    enableCircleDrawing(circleData){
      
      var scope = this;

      if(circleData.enableCircleDrawing){

           this.$refs.mapRef.$mapPromise.then(map => {


         scope.drawCircle.clickMarkerListener= google.maps.event.addListener(map, "click", function(
          event
        ) {
          console.log("click");
          scope.drawCircle.marker = new google.maps.Marker({
            icon:'https://cdn4.iconfinder.com/data/icons/cologne/32x32/flag.png',
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
                  scope.drawCircle.circleFullAddress = results[0].formatted_address; 

                }
              }
            );
         
            scope.drawCircle.draggableMarkerListener = google.maps.event.addListener(scope.drawCircle.marker, "dragend", function(marker) {
            
            var latLng = marker.latLng;
            var currentLatitude = latLng.lat();
            var currentLongitude = latLng.lng();

             scope.drawCircle.circle.setOptions({center:{lat:currentLatitude,lng:currentLongitude}});       

            new google.maps.Geocoder().geocode(
              {
                location: {
                  lat: currentLatitude,
                  lng: currentLongitude
                }
              },
              function(results, status) {
                if (status === "OK") {
                  scope.drawCircle.circleFullAddress = results[0].formatted_address;  

                }
              }
            );
          });

          //remove click on map listenser

          if(scope.drawCircle.clickMarkerListener != null ){
            google.maps.event.removeListener(scope.drawCircle.clickMarkerListener);
             
          } 
        });

      });

       if(scope.drawCircle.clickMarkerListener != null ){
          google.maps.event.removeListener( scope.drawCircle.clickMarkerListener);
            
        } 

      }else{
        //remove circle listener
        //remove marker & circle

        if(scope.drawCircle.marker != null || scope.drawCircle.circle != null){
          scope.drawCircle.marker.setMap(null);
          scope.drawCircle.circle.setMap(null);
        google.maps.event.removeListener(scope.drawCircle.draggableMarkerListener);
        }

        this.drawCircle.circleFullAddress = ''; 
         
      }

    },
    circleFillColor(circleData){

      if(this.drawCircle.circle != null){
       this.drawCircle.circle.setOptions({fillColor: circleData.circleFillColor,strokeColor: circleData.circleFillColor});   
      }

    },
     circleRadiusValue(circleData){
        if(this.drawCircle.circle != null){
        this.drawCircle.circle.setOptions({radius:circleData.circleRadiusValue});   
        } 
       
    },
    handleCircleData(circleData){
        
        if(circleData.circleDataChangedType =="enableCircleDrawing" || !circleData.circleDataChangedType =="enableCircleDrawing"){
          this.enableCircleDrawing(circleData);
        }else if(circleData.circleDataChangedType == "circleFillColor"){
          this.circleFillColor(circleData);
        }else if(circleData.circleDataChangedType == "circleRadiusValue"){
          this.circleRadiusValue(circleData);
        }
      
    },
    removeMarkers(markerType){
    
      for(var i=0; i<this.markers.length; i++){
        this.markers[i].setMap(null);
    }

    },
    notContainedIn(arr) {
    return function arrNotContains(element) {
        return arr.indexOf(element) === -1;
    };
    },
    showDegueData(){
      console.log("show fire data on the map!")

      	// axios.get('/api/crisis')
				// .then((res) => {	
				// 	 console.log(res) 
					
				// }).catch((error) => {
				// 	console.log(error)
				// }).then(() => {
					 
        // });
        
        
    },
    showFireData(){

    },
    showGasLeakData(){

    },
    showHazeData(){

    },
    showRainData(){

    },
    showTwoHrWeatherData(markerType){
      var scope = this;
       var infowindow = new google.maps.InfoWindow({
             content:''
       });

      $.ajax({
                url: "https://api.data.gov.sg/v1/environment/2-hour-weather-forecast",
                type: "GET",
                success: function (data, status, jqXHR) { 

                   data.area_metadata.forEach((element,index) => {  

                     scope.$refs.mapRef.$mapPromise.then(map => {
 
                      var marker = new google.maps.Marker({
                        markerType:markerType,
                        animation: google.maps.Animation.DROP,
                      position:  {lat: element.label_location.latitude, lng: element.label_location.longitude},
                      map: map,
                      title: ''
                    });

                    scope.markers.push(marker);

                     google.maps.event.addListener(marker, 'click', (function(marker, index) {
                      return function() {
                         
                          infowindow.setContent('<div id="content">'+
                                  '<div id="siteNotice">'+
                                  '</div>'+
                                  '<h6>'+element.name+'</h6>'+
                                  '<div id="bodyContent">'+data.items[0].forecasts[index].forecast+
                                  '</div>'+
                                  '</div>'); 
                          infowindow.open(map, marker);


                          //one animation
                           for(var i=0; i<scope.markers.length; i++){
                              scope.markers[i].setAnimation(null);
                          }

                           marker.setAnimation(google.maps.Animation.BOUNCE);
                         
                      }
                    })(marker, index));
 
                    });
                    });  
                },
                error: function (jqXHR, status, err) {
                    console.log(err);
                },
                complete: function (jqXHR, status) {
                 
                }
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
    },
    toggleInfoWindow: function(marker, idx) {
      this.infoWindowPos = marker.position;
      this.infoContent = marker.infoText;

      //check if its the same marker that was selected if yes toggle
      if (this.currentMidx == idx) {
        this.infoWinOpen = !this.infoWinOpen;
      }
      //if different marker set infowindow to open and reset current marker index
      else {
        this.infoWinOpen = true;
        this.currentMidx = idx;
      }

      //zoom to clicked markers or from right info panel
      //this.setMapZoomLvl(18);
    }
  },
  watch: {
    _place: function(place) {
      // watch it

      if (place.id) {
        this.place = null;

        var pos = {
          lat: place.geometry.location.lat(),
          lng: place.geometry.location.lng()
        };

        //add to place
        this.place = {
          position: {
            lat: place.geometry.location.lat(),
            lng: place.geometry.location.lng()
          },
          infoText: place.formatted_address
        };

        this.panMap(pos.lat, pos.lng);

        //set zoom lvl
        this.setMapZoomLvl(17);
      }
    },
    clearSearch(newValue, oldValue) {
      this.clearSearchVal = newValue;
    },
    clearSearchVal() {
      //clear search place marker
      this.place = null;
    },
    toggleData(newValue, oldValue) {
       var scope = this;

       if(oldValue == null){
         console.log("add the first time");
         newValue.forEach(element => {
                  if(element === "showDegueData"){
                    scope.showDegueData(element);
                  }else if (element === "showFireData"){
                      scope.showFireData(element);
                  }else if (element === "showGasLeakData"){
                    scope.showGasLeakData(element);
                  }else if (element === "showHazeData"){
                    scope.showHazeData(element);
                  }else if(element === "showRainData"){
                      scope.showRainData(element);
                  }else if(element === "showTwoHrWeatherData"){
                      scope.showTwoHrWeatherData(element);
                  } 
           });
       }else{

         if(newValue.length > oldValue.length){
           console.log("add")
           console.log(newValue.filter(scope.notContainedIn(oldValue)).concat(oldValue.filter(scope.notContainedIn(newValue))));
               newValue.forEach(element => {
                  if(element === "showDegueData"){
                    scope.showDegueData(element);
                  }else if (element === "showFireData"){
                      scope.showFireData(element);
                  }else if (element === "showGasLeakData"){
                    scope.showGasLeakData(element);
                  }else if (element === "showHazeData"){
                    scope.showHazeData(element);
                  }else if(element === "showRainData"){
                      scope.showRainData(element);
                  }else if(element === "showTwoHrWeatherData"){
                      scope.showTwoHrWeatherData(element);
                  } 
                });
          }else{     
              scope.removeMarkers(newValue.filter(scope.notContainedIn(oldValue)).concat(oldValue.filter(scope.notContainedIn(newValue)))[0])
          }  
       }
    }
  } 
};
</script>

<style>
.scrollbar {
  float: left;
  height: 550px;
  background: #fff;
  overflow-y: scroll;
  margin-bottom: 25px;
}
.force-overflow {
  min-height: 450px;
}

.scrollbar-primary::-webkit-scrollbar {
  width: 12px;
  background-color: #f5f5f5;
}

.scrollbar-primary::-webkit-scrollbar-thumb {
  border-radius: 10px;
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
  background-color: #4285f4;
}

.scrollbar-danger::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
  background-color: #f5f5f5;
  border-radius: 10px;
}

.scrollbar-danger::-webkit-scrollbar {
  width: 12px;
  background-color: #f5f5f5;
}

.scrollbar-danger::-webkit-scrollbar-thumb {
  border-radius: 10px;
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
  background-color: #ff3547;
}

.scrollbar-warning::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
  background-color: #f5f5f5;
  border-radius: 10px;
}

.scrollbar-warning::-webkit-scrollbar {
  width: 12px;
  background-color: #f5f5f5;
}

.scrollbar-warning::-webkit-scrollbar-thumb {
  border-radius: 10px;
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
  background-color: #ff8800;
}

.scrollbar-success::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
  background-color: #f5f5f5;
  border-radius: 10px;
}

.scrollbar-success::-webkit-scrollbar {
  width: 12px;
  background-color: #f5f5f5;
}

.scrollbar-success::-webkit-scrollbar-thumb {
  border-radius: 10px;
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
  background-color: #00c851;
}

.scrollbar-info::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
  background-color: #f5f5f5;
  border-radius: 10px;
}

.scrollbar-info::-webkit-scrollbar {
  width: 12px;
  background-color: #f5f5f5;
}

.scrollbar-info::-webkit-scrollbar-thumb {
  border-radius: 10px;
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
  background-color: #33b5e5;
}

.scrollbar-default::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
  background-color: #f5f5f5;
  border-radius: 10px;
}

.scrollbar-default::-webkit-scrollbar {
  width: 12px;
  background-color: #f5f5f5;
}

.scrollbar-default::-webkit-scrollbar-thumb {
  border-radius: 10px;
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
  background-color: #2bbbad;
}

.scrollbar-secondary::-webkit-scrollbar-track {
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
  background-color: #f5f5f5;
  border-radius: 10px;
}

.scrollbar-secondary::-webkit-scrollbar {
  width: 12px;
  background-color: #f5f5f5;
}

.scrollbar-secondary::-webkit-scrollbar-thumb {
  border-radius: 10px;
  -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
  background-color: #aa66cc;
}
</style>