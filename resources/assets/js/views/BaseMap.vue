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
        {{infoContent}}
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
    showDegueData(){
      console.log("show fire data on the map!")

      	axios.get('/api/crisis')
				.then((res) => {	
					 console.log(res) 
					
				}).catch((error) => {
					console.log(error)
				}).then(() => {
					 
        });
        
        // $.ajax({
        //         url: "/api/crisis",
        //         type: "GET",
        //         success: function (data, status, jqXHR) { 
        //             console.log(data)
        //         },
        //         error: function (jqXHR, status, err) {
        //             console.log("Local error callback.");
        //         },
        //         complete: function (jqXHR, status) {
                
        //         }
        //     }) 
    },
    showFireData(){

    },
    showGasLeakData(){

    },
    showHazeData(){

    },
    showRainData(){

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
      this.setMapZoomLvl(18);
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
      //console.log("new old value of new marker");
       //console.log(newValue);

       var scope = this;
 
       newValue.forEach(element => {
         if(element === "showDegueData"){
           scope.showDegueData();
         }else if (element === "showFireData"){
            scope.showFireData();
         }else if (element === "showGasLeakData"){
           scope.showGasLeakData();
         }else if (element === "showHazeData"){
           scope.showHazeData();
         }else if(element === "showRainData"){
            scope.showRainData();
         }
         
      });
 
    //   newValue.observe(obj, function(changes) {
    //   console.log(changes);
    // });
          
      // this.markers.push({
      //     position: {
      //       lat: newValue.place.position.lat,
      //       lng: newValue.place.position.lng
      //     },
      //     infoText: newValue.incidentName
      //   })
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