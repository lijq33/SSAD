<template>
  <div>

    <!--autosearch -->
    <auto-search :circle-full-address="drawCircle.circleFullAddress"  @get-search-data="handleSearchData" @clear-Search="clearSearch"/>
   
    <!-- drawing tools extension -->
    <b-card no-body>
     
      <b-tabs card>
        <!--circle-->
        <b-tab v-if ="enableDrawingToolsExtension" title="Circle" @click="getTabInfo('circleDrawingTools')" @is-mounted="getMountedComponent" ><draw-circle @get-circle-drawing="handleCircleData"/></b-tab>

        <b-tab v-if ="enableDrawingToolsExtension" title="Square">square</b-tab>
        <!-- show and hide extension -->
        <template slot="tabs"> 
          <b-nav-item v-if ="!enableDrawingToolsExtension" @click.prevent="newTab" href="#"><b>Enable Drawing Extension</b></b-nav-item>
          <b-nav-item v-if ="enableDrawingToolsExtension" @click.prevent="newTab" href="#"><b>Hide</b></b-nav-item>
        </template>

      </b-tabs>
    </b-card>
  
    <!-- crisis -->
    <toggle-map @get-toggle-data="handleToggleData"  @clear-toggle-data="handleClearToggleData"/>
     
    <!-- google base map -->
    <GmapMap
      ref="mapRef"
      style="width: auto; height: 600px;"
      :zoom="zoom_lvl"
      :center="sgcoord"
    >
    </GmapMap>
 
  </div>
</template>

<script>
import { Modal } from "bootstrap-vue/es/components";
import * as turf from "@turf/turf";
import DrawCircle from './DrawCircle';
import AutoSearchComplete from './AutoSearchComplete'
import ToggleMap from './ToggleCrisisMap';

export default {
  props: ["searchData","toggleData"],

  mounted(){ 
    var scope = this;
       this.$refs.mapRef.$mapPromise.then(map => {
          scope.markerInfoWindow = new google.maps.InfoWindow({
          content: ''
        });
       }); 
  },

  components: {
    drawCircle:DrawCircle,
    autoSearch:AutoSearchComplete,
    toggleMap:ToggleMap
  },

  data() {
    return {
      markerInfoWindow:"99",
      searchMarker:null,
      drawCircle:{marker:null,circle:null,draggableMarkerListener:null,clickMarkerListener:null,circleFullAddress:''},
      isLoading: false,
      zoom_lvl: 12,
      sgcoord: { lat: 1.3521, lng: 103.8198 },
      markers: {twoHrWeatherMarkers:[]},
      enableDrawingToolsExtension:false,
      tabs: [],
        tabCounter: 0
    };
  },
  methods: {
   
    handleClearToggleData(clearToggleData){
        
      //empty markers
     if(clearToggleData === "hideDegueData"){
        scope.showDegueData(element);
      }else if (clearToggleData === "hideFireData"){
          scope.showFireData(element);
      }else if (clearToggleData === "hideGasLeakData"){
        scope.showGasLeakData(element);
      }else if (clearToggleData === "hideHazeData"){
        scope.showHazeData(element);
      }else if(clearToggleData === "hideRainData"){
          scope.showRainData(element);
      }else if(clearToggleData === "hideTwoHrWeatherData"){
        console.log("clear 2h weather")

           this.removeMarkers(this.markers.twoHrWeatherMarkers);
           this.markers.twoHrWeatherMarkers=[];
      } 
       
    },
    handleToggleData(toggleData){
        
       if(toggleData.displayId === "showDegueData"){
        scope.showDegueData(element);
      }else if (toggleData.displayId === "showFireData"){
          scope.showFireData(element);
      }else if (toggleData.displayId === "showGasLeakData"){
        scope.showGasLeakData(element);
      }else if (toggleData.displayId === "showHazeData"){
        scope.showHazeData(element);
      }else if(toggleData.displayId === "showRainData"){
          scope.showRainData(element);
      }else if(toggleData.displayId === "showTwoHrWeatherData"){
       
          this.showTwoHrWeatherData(toggleData);
      } 

    },
    getMountedComponent(component){

      //get the first active component
      console.log(component)
    },
     closeTab(x) {
        for (let i = 0; i < this.tabs.length; i++) {
          if (this.tabs[i] === x) {
            this.tabs.splice(i, 1)
          }
        }
      },
      newTab() {

        if(!this.enableDrawingToolsExtension){
          this.enableDrawingToolsExtension = true;
        }else{
          this.enableDrawingToolsExtension = false;
        }

        //this.tabs.push(this.tabCounter++)
      },
    haveExistingSearchMarker(circleData){

      var scope =this;

       this.$refs.mapRef.$mapPromise.then(map => {
         console.log("have search marker")

          if(scope.searchMarker){

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
              scope.searchMarker=null;

             //attach draggend listener
            scope.drawCircle.draggableMarkerListener = google.maps.event.addListener(scope.drawCircle.marker, "dragend", function(marker) {
            
            var latLng = marker.latLng;
            var currentLatitude = latLng.lat();
            var currentLongitude = latLng.lng();

             scope.drawCircle.circle.setOptions({center:{lat:currentLatitude,lng:currentLongitude}});       

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
                  scope.drawCircle.circleFullAddress = results[0].formatted_address;  

                }
              }
            );
          });

          }

       });

    },
    noExistingSearchMarker(circleData){

      var scope= this;

       this.$refs.mapRef.$mapPromise.then(map => {

          console.log("no search marker");

		scope.drawCircle.clickMarkerListener= google.maps.event.addListener(map, "click", function(
          event
        ) {
          
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

       })

    },
    getTabInfo: function(event) {
      console.log(event)
    },
    enableDrawingBtn(){
      console.log("click")
      this.enableDrawingToolsExtension = true;
    },
    clearSearch() {

      //reset components
      this.enableDrawingToolsExtension = false

      //remove search marker
      if(this.searchMarker){
        this.searchMarker.setMap(null);
        this.searchMarker = null;
      } 

      //clear drawcircle
      if(this.drawCircle){
        this.drawCircle.marker.setMap(null);
        this.drawCircle.circle.setMap(null);
        this.drawCircle.circleFullAddress='';
        google.maps.event.removeListener(this.drawCircle.draggableMarkerListener);
        google.maps.event.removeListener(this.drawCircle.draggableMarkerListener);

        if(this.drawCircle.clickMarkerListener){
           google.maps.event.removeListener(this.drawCircle.clickMarkerListener);
        } 
      }
    },
    handleSearchData(searchData){
      
      var scope = this;
      var pos = {
        lat:searchData.lat,
        lng:searchData.lng
      }

      this.$refs.mapRef.$mapPromise.then(map => {
      
        if(!scope.searchMarker){

          //check if cirlc is available
          if(scope.drawCircle.marker){
            
            scope.drawCircle.marker.setPosition(pos);
             scope.drawCircle.circle.setOptions({center:pos});       
          }else{
               //create search marker
            scope.searchMarker = new google.maps.Marker({
            animation: google.maps.Animation.DROP,
            position:  pos,
            map: map
          });
          } 
        }else{ 
          //just change latlng
          scope.searchMarker.setPosition(pos); 
        }  

      }); 

    },
    retrieveAddressFromBackEnd(postalCode){

       axios.get('/api/crisis/gasLeak')
				.then((res) => {	
					 console.log(res) 
					
				}).catch((error) => {
					console.log(error)
				}).then(() => {
					 
        });

         axios.get('/api/crisis/dengue')
				.then((res) => {	
					 console.log(res) 
					
				}).catch((error) => {
					console.log(error)
				}).then(() => {
					 
        });

        
         axios.get('/api/crisis/fire')
				.then((res) => {	
					 console.log(res) 
					
				}).catch((error) => {
					console.log(error)
				}).then(() => {
					 
        });


    },

    enableCircleDrawing(circleData){
      
      var scope = this;

      if(circleData.enableCircleDrawing){

        //if have existing search marker
        if(scope.searchMarker){

        scope.haveExistingSearchMarker(circleData);

        }else{

          //start of no search marker
          scope.noExistingSearchMarker(circleData);
        }
  

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
    removeMarkers(removeMarkersType){
    
      for(var i=0; i<removeMarkersType.length; i++){  
          removeMarkersType[i].setMap(null);  
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
     addMarker(markerVar,element,infowindow){
       var scope =this;

       //if want to assign to a variable 
        if(markerVar){
         
           this.$refs.mapRef.$mapPromise.then(map => {
           
                      
          //new marker
         var temp = new google.maps.Marker({
                icon:element.iconUrl ? element.iconUrl:'',
                draggable:element.draggable? element.draggable:false,
                markerDisplayId:element.displayId? element.displayId:'' ,
                animation: element.animation? element.animation:google.maps.Animation.DROP,
                position:  element.position,
                map: map,
            });  


            //attach infowindow
          var contentString = '<div id="iw-container">' +
              '<div class="iw-title">'+infowindow.infoWindowTitle+'</div>' +
              '<div class="iw-content">' +
                '<div class="iw-subTitle">'+infowindow.infoWindowBody+'</div>' +
              '</div>' +
              '<div class="iw-bottom-gradient"></div>' +
            '</div>';

            google.maps.event.addListener(temp, 'click', function() {
            scope.markerInfoWindow.setContent(contentString);
            scope.markerInfoWindow.open(map, this);
          });

             markerVar.push(temp);
              
        });   
        } 
         
    },
    showTwoHrWeatherData(data){
       
      var scope = this;
 
        data.area_metadata.forEach((element,index) => {  
 
          //add marker to twoHrWeatherMarkers variable
          //param(variable,icon,inforwindow content)
        scope.addMarker(
        scope.markers.twoHrWeatherMarkers,
        {icon:element.iconUrl,
        markerDisplayId:element.displayId,position:  {lat: element.label_location.latitude, lng: element.label_location.longitude},},
        {infoWindowTitle:element.name,infoWindowBody:data.items[0].forecasts[index].forecast}
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
    searchData: function(search) {
        
        this.searchMarker = null;
 
        //create search marker
        this.searchMarker = new google.maps.Marker({
            draggable: true,
            position: event.latLng,
            map: map
          });
 
        //add to place
        this.searchMarker = {
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
  } 
};
</script>

<style>
#iw-container .iw-title {
	font-family: 'Open Sans Condensed', sans-serif;
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
	background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
	background: -webkit-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
	background: -moz-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
	background: -ms-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
}
</style>