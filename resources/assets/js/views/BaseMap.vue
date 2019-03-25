<template>
  <div>

    <!--autosearch -->
    <auto-search :circle-full-address="drawCircle.circleFullAddress"  @get-search-data="handleSearchData" @clear-Search="clearSearch"/>
   

    <!-- drawing tools extension -->
    <b-card no-body>
     
      <b-tabs card>
        <!-- Render Tabs, supply a unique `key` to each tab -->
        <!-- <b-tab v-for="i in tabs" :key="`dyntab-${i}`" :title="`Tab ${i}`">
          Tab Contents {{ i }}
          <b-button size="sm" variant="danger" class="float-right" @click="() => closeTab(i)">
            Close tab
          </b-button>
        </b-tab> -->

        <b-tab v-if ="enableDrawingToolsExtension" title="Circle" @click="getTabInfo('circleDrawingTools')" @is-mounted="getMountedComponent" ><draw-circle @get-circle-drawing="handleCircleData"/></b-tab>

        <!-- New Tab Button (Using tabs slot) -->
        <template slot="tabs">
          <b-nav-item @click.prevent="newTab" href="#"><b>Enable Drawing Extension</b></b-nav-item>
        </template>

        <!-- Render this if no tabs -->
        <!-- <div v-if ="enableDrawingToolsExtension" slot="empty" class="text-center text-muted">
          There are no open tabs <br />
          Open a new tab using the <b>Enable Drawing Extension</b> button above.
        </div> -->
      </b-tabs>
    </b-card>
  
  
    <!--toggle map @get-toggle-data="handleToggleData" 
    <toggle-map />-->

    
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
      markers: [],
      enableDrawingToolsExtension:false,
      tabs: [],
        tabCounter: 0
    };
  },
  methods: {
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


      // axios.get('/api/address/postal_code/'+'419786'+'.json')
			// 	.then((res) => {	
			// 		 console.log(res.data) 
					
			// 	}).catch((error) => {
			// 		console.log(error)
			// 	}).then(() => {
					 
      //   });

      //  $.ajax({
      //           url: "/api/address/postal_code/419786.json",
      //           type: "GET",
      //           dataType:"json",
      //           success: function (data, status, jqXHR) { 
      //              console.log(jqXHR)
      //             console.log(data)
                    
      //           },
      //           error: function (jqXHR, status, err) {
      //               console.log(err);
      //           },
      //           complete: function (jqXHR, status) {
                 
      //           }
      //       });  


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

