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

  components: {
    drawCircle:DrawCircle,
    autoSearch:AutoSearchComplete,
    toggleMap:ToggleMap
  },

  data() {
    return {
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
    showTwoHrWeatherData(data){
     
      var scope = this;
       var infowindow = new google.maps.InfoWindow({
             content:''
       });

       console.log(data)

        data.area_metadata.forEach((element,index) => {  

        scope.$refs.mapRef.$mapPromise.then(map => {

        var marker = new google.maps.Marker({
            icon:element.iconUrl,
            markerDisplayId:element.displayId,
            animation: google.maps.Animation.DROP,
            position:  {lat: element.label_location.latitude, lng: element.label_location.longitude},
            map: map,
        });

      scope.markers.twoHrWeatherMarkers.push(marker);

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
              for(var i=0; i<scope.markers.twoHrWeatherMarkers.length; i++){
                scope.markers.twoHrWeatherMarkers[i].setAnimation(null);
            }

              marker.setAnimation(google.maps.Animation.BOUNCE);
            
        }
      })(marker, index));

      });
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

