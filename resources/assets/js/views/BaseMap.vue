<template>
  <div>

    <GmapMap
      ref="mapRef"
      style="width: auto; height: 600px;"
      :zoom="zoom_lvl"
      :center="sgcoord"
    >
      <gmap-info-window
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
      />

    </GmapMap>

    
  </div>
</template>

<script>
import bCollapse from "bootstrap-vue/es/components/collapse/collapse";
import { ButtonGroup } from "bootstrap-vue/es/components";
import { Modal } from "bootstrap-vue/es/components";
import * as turf from "@turf/turf";

export default {
  props: ["_place", "clearSearch","toggleData"],

  components: {
    "b-collapse": bCollapse
  },

  data() {
    return {
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
    removeMarkers(markerType){
      var scope =this;
      var tempIndex = 0;

        this.markers.forEach((element,index) => {  
               if(element.markerType === markerType){
                  tempIndex++;
               }
        }); 

      while(tempIndex != 0){
       
         for( var i = 0; i < this.markers.length; i++){ 
          if ( this.markers[i].markerType == markerType) {
            this.markers.splice(i, 1);
            break; 
          }
        } 
        tempIndex--;
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
      $.ajax({
                url: "https://api.data.gov.sg/v1/environment/2-hour-weather-forecast",
                type: "GET",
                success: function (data, status, jqXHR) { 

                   data.area_metadata.forEach((element,index) => {  
                     scope.markers.push({
                        markerType:markerType,
                        position: {
                          lat: element.label_location.latitude,
                          lng: element.label_location.longitude
                        },
                        infoText: '<div id="content">'+
                                  '<div id="siteNotice">'+
                                  '</div>'+
                                  '<h6>'+element.name+'</h6>'+
                                  '<div id="bodyContent">'+data.items[0].forecasts[index].forecast+
                                  '</div>'+
                                  '</div>'
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