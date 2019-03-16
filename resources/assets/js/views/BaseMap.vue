<template>
	<div>
		<div class = "tw-mb-4 tw-pb-4 tw-border-b tw-border-grey">
			<span class = "tw-font-grey tw-text-2xl tw-mr-2">
				Search:
			</span>

			<GmapAutocomplete class = "tw-border-grey tw-border-2 tw-rounded tw-p-2 tw-w-64" @place_changed = "setPlace"></GmapAutocomplete>
		</div>

		<br>

		<div>
			<b-card-group deck>
				<b-card img-src="" img-alt="Card image" img-top>
					<p class="card-text"></p>

					<GmapMap ref="mapRef" style="width: auto; height: 600px;" :zoom="zoom_lvl" :center="{lat: 0, lng: 0}">
						<gmap-info-window :options="infoOptions" :position="infoWindowPos" :opened="infoWinOpen" @closeclick="infoWinOpen=false">
							{{infoContent}}
						</gmap-info-window>

						<GmapMarker v-for="(marker, index) in markers" :key="index" :position="marker.position" :clickable="true" @click="toggleInfoWindow(marker,index)" />

						<GmapMarker v-if="this.place" label="★" :position="{ lat: this.place.position.lat, lng: this.place.position.lng }" :clickable="true" @click="toggleInfoWindow(place,99)" />

					</GmapMap>
				</b-card>

				<b-card img-src="" img-alt="Card image" img-bottom>
					<div class="tw-flex tw-justify-center tw-mb-6 tw-pb-6 tw-border-b tw-border-grey">
						 
						<div>
					 <b-button @click="showModal" id="showBtn">Open Modal</b-button>
          

          <b-modal ref="myModalRef" hide-footer title="Using Component Methods">
            <div class="d-block text-center">
              <h3>Hello From My Modal!</h3>
            </div>
            <b-button class="mt-3" variant="outline-danger" block @click="hideModal">Close Me</b-button>
          </b-modal>
 
						</div>
					</div>

					<div v-if='this.nearbyplaces.length == 0 && !isLoading' class="tw-text-center">
						No {{this.health_service_type}} is available within 2km!
					</div>

					<!--loading gif-->
					<div class="tw-text-center " v-if="isLoading">
						<img src="/assets/img/loader.gif" alt="Loading...">
					</div>
					<div class="scrollbar scrollbar-primary" style="width: 493px;">
						<div class="force-overflow">
							<div role="tablist">
								<b-card no-body class="mb-1 " v-for="(item,index) in nearbyplaces" :key="item.name">
									<b-card-header header-tag="header" class="p-1" role="tab" v-on:click="toggleMapInfo(item)">
										<b-btn class="tw-uppercase" block href="#" v-b-toggle="'accordionG'+index" variant="primary-outline">
											{{ item.name }}
										</b-btn>
									</b-card-header>

									<b-collapse v-bind:id="'accordionG'+index" accordion="my-accordion" role="tabpanel">
										<b-card-body>

											<p class="card-text">
												Location: {{ item.vicinity }}
											</p>

											<p class="card-text">
												<!-- Opening: {{ item.opening_hours }} -->
											</p>

										</b-card-body>
									</b-collapse>
								</b-card>

							</div>
						</div>
					</div>

				</b-card>
			</b-card-group>
		</div>

		<!--modal alert-->
		<div>
			<b-modal title="Alert" v-model="modalShow" ok-only header-bg-variant="warning">
				Please Enter A Valid Address.
			</b-modal>
		</div>
	</div>
</template>

<script>
import bCollapse from "bootstrap-vue/es/components/collapse/collapse";
import { ButtonGroup } from "bootstrap-vue/es/components";
import { Modal } from "bootstrap-vue/es/components";
import * as turf from "@turf/turf";

export default {
  props: ["location"],

  components: {
    "b-collapse": bCollapse
  },

  data() {
    return {
      health_service_type: "ChasClinic",
      isLoading: false,
      sgcoord: { lat: 1.3521, lng: 103.8198 },
      zoom_lvl: 17,
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
      },

      chasClinicData: {
        type: "FeatureCollection",
        features: []
      }
    };
  },

  mounted() {
    //on page load, show entire sg map
    this.showSgMap();

    //get one map token
    this.retrieveAccessToken();

    //get current location
    // Try HTML5 geolocation.

    if (navigator.geolocation) {
      var scope = this;
      navigator.geolocation.getCurrentPosition(
        function(position) {
          let pos = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
          };

          //use promise first
          this.$refs.mapRef.$mapPromise.then(map => {
            scope.geocodeLatLng(
              new google.maps.Geocoder(),
              pos,
              new google.maps.InfoWindow()
            );
          });
        }.bind(this)
      );
    }
  },

  description: "",

  methods: {
    showModal() {
        this.$refs.myModalRef.show()
      },
      hideModal() {
        this.$refs.myModalRef.hide()
      },
	  polyClinic(){
	 this.health_service_type = "Polyclinic";
	 
	      this.nearbyplaces = [];
       this.markers = [];
      //  this.chasClinicData = null;
       this.chasClinicData.features = [];
      this.clearRightPanelInfo();
     
      this.queryOneMap(2000, "meters", "moh_medical_dental");

    },

     googleDataFilter(element){
 
       var format = {
          type: "Feature",

          properties: {
            Name: element.name,
            Address:element.vicinity
          },

          geometry: {
            type: "Point",
            coordinates: [parseFloat(element.geometry.location.lng()), parseFloat(element.geometry.location.lat())]
          }
        };

        this.chasClinicData.features.push(format);

     },
    
    addMarkersWithinRadius(input_radius,units){
      var scope = this;
      
      // Note order: longitude, latitude.
          var center = [
            parseFloat(scope.place.position.lng),
            parseFloat(scope.place.position.lat)
          ];

          //radius of 1km
          var radius = input_radius;
          var options = { steps: 10, units: units };
          var circle = turf.circle(center, radius, options);

          var result = turf.pointsWithinPolygon(scope.chasClinicData, circle);

          turf.featureEach(result, function(currentFeature, featureIndex) {
            
            scope.nearbyplaces.push({
              name: currentFeature.properties.Name,
              vicinity: currentFeature.properties.Address,
              latlng: {
                lat: currentFeature.geometry.coordinates[1],
                lng: currentFeature.geometry.coordinates[0]
              }
            });

            //add markers
            scope.markers.push({
              position: {
                lat: currentFeature.geometry.coordinates[1],
                lng: currentFeature.geometry.coordinates[0]
              },
              infoText: currentFeature.properties.Name
            });
          });
    },
    loadGoogleHospitalData() {
      this.health_service_type = "Hospital";

      this.clearRightPanelInfo();

      //clear chas clinic data
	  this.chasClinicData.features = [];
	  
      //loading gif
      this.isLoading = true;

      if (this.place != null) {
			var scope = this;
			this.$refs.mapRef.$mapPromise.then(map => {
				let request = {
					location: { lat: this.sgcoord.lat, lng: this.sgcoord.lng },
					radius: 42000,
					type: ["hospital"]
				};

			let service = new google.maps.places.PlacesService(map);

			service.nearbySearch(request, (results, status, pagination) => {
				if (status == google.maps.places.PlacesServiceStatus.OK) {
				 
				results.forEach(element => {
					if (element.plus_code !== undefined) {
					if (
						element.plus_code.compound_code.includes("Singapore") ||
						element.vicinity.includes("Singapore")
					) {

            scope.googleDataFilter(element);
              
					}
					} else {
             scope.googleDataFilter(element);
 
          
					}
				});
            }

            if (pagination.hasNextPage) {
              sleep: 2;
              pagination.nextPage();
            } else {
              scope.isLoading = false;

               scope.addMarkersWithinRadius(2000,"meters")
            }
          });
        });
      }
    },

    geocodeLatLng(geocoder, pos, infowindow) {
      var scope = this;
      geocoder.geocode({ location: pos }, function(results, status) {
        if (status === "OK") {
          //add to place
          scope.place = {
            position: {
              lat: pos.lat,
              lng: pos.lng
            },
            infoText: results[0].formatted_address
          };

          scope.panMap(pos.lat, pos.lng);

          //pass current location and get nearby hopistals
          scope.neabyHospital(pos);
        }
      });
    },

    handleDataFc: function() {
      //should use 1 way data flow instead
      // handle data and give it back to parent by interface
      this.$emit("interface", this.childData,this.health_service_type);
    },

    dentalClinic() {
      this.health_service_type = "Dental";

      this.clearRightPanelInfo();

      //moh_chas_clinics
      if (this.place != null) {
        this.queryOneMap(2000, "meters", "moh_medical_dental");
      }
    },

  

    retrieveAccessToken() {
      var form = new FormData();
      form.append("email", "JLI051@e.ntu.edu.sg");
      form.append("password", "1q3e5tab");

      var scope = this;

      var settings = {
        async: true,
        crossDomain: true,
        url: "https://developers.onemap.sg/privateapi/auth/post/getToken",
        method: "POST",
        processData: false,
        contentType: false,
        mimeType: "multipart/form-data",
        data: form
      };

      $.ajax(settings).done(function(response) {
        scope.access_token = JSON.parse(response).access_token.toString();
      });
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
    },

    bookappointment() {
      // if(this.selectedPlace){
      //   console.log("Book Appointment on : "+ this.selectedPlace.name);
      //   //pass data back to parent
      //   //should use 1 way data binding instead
      //   this.childData = this.selectedPlace;
      //   this.handleDataFc();
      // }
    },

    showBtnStatus(show) {
      // this.showAptBtnStatus = show;
    },

    bookBtnStatus(show) {
      this.bookAptBtnStatus = show;
    },

    clearMarkers() {
      this.markers = [];
    },

    clearRightPanelInfo() {
      this.nearbyplaces = [];
      this.clearMarkers();
    },

    setMapZoomLvl(zoomlvl) {
      this.zoom_lvl = zoomlvl;
    },

    panMap(lat, lng) {
      //pan to any location on the map by giving lat and lng coordinates
      this.$refs.mapRef.$mapPromise.then(map => {
        map.panTo({ lat: lat, lng: lng });
      });
    },

    toggleMapInfo(movemap) {
      //assign
      this.selectedPlace = movemap;
		
      //mapinfo panel toggle base map
      this.panMap(movemap.latlng.lat, movemap.latlng.lng);

      //set zoom lvl
      this.setMapZoomLvl(17);

      //enable book btn
      this.bookBtnStatus(false);

      //loop through markers
      //compare using name as for now,not guarantee!!!

		this.markers.map((marker, index) => {
        if (marker.infoText == movemap.name) {
          this.toggleInfoWindow(marker, index);
        }
      });

      if (this.selectedPlace) {
        console.log("Book Appointment on : " + this.selectedPlace.name);

        //pass data back to parent
        //should use 1 way data binding instead
        this.childData = this.selectedPlace;

        this.handleDataFc();
      }
    },

    setDescription(description) {
      this.description = description;
    },

    setPlace(place) {

      //   console.log("before")
      //    console.log(this.markers);
      //   console.log(this.nearbyplaces);
      //   console.log(this.chasClinicData)
      //  console.log("before end")
      //  this.clearRightPanelInfo()

      //  console.log(this.markers);
      //   console.log(this.nearbyplaces);
      //   console.log(this.chasClinicData)
        //clear chas clinic data
      

      //ensure there is a valid location
       this.nearbyplaces = [];
       this.markers = [];
      //  this.chasClinicData = null;
       this.chasClinicData.features = [];
      this.clearRightPanelInfo();
        
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

        //pass current location and get nearby hopistals
        this.neabyHospital(pos);

      
      } else {
        //clear neaby place
        this.nearbyplaces = [];

        //show alert modal
        this.modalShow = true;
      }
    },

    usePlace(place) {
      if (this.place) {
        this.markers.push({
          position: {
            lat: this.place.lat,
            lng: this.place.lng
          }
        });

        this.place = null;
        this.nearbyplaces = [];
      }
    },

    showSgMap() {
      //show sg map
      this.panMap(this.sgcoord.lat, this.sgcoord.lng);

      //set zoom lvl
      this.setMapZoomLvl(12);
    },

    neabyHospital(location) {
      //clear right map info & markers
      this.clearRightPanelInfo();

      //query oneMap
      //default load chas clinic

      this.chasClinic();

      //nearby hospital
      console.log("nearby hospital");
    },

    chasClinicFilter(entry) {
	  //filter out chas clinic data
      if (entry.DESCRIPTION != null || this.health_service_type == "Polyclinic") {
        var blknum = "";
        var streetname = "";
        var buildingName = "";
        var floornum = "";
        var unitnum = "";

        var splitchar = entry.LatLng.split(",");

        if (typeof entry.ADDRESSBLOCKHOUSENUMBER !== "undefined") {
          blknum = entry.ADDRESSBLOCKHOUSENUMBER;
        }

        if (typeof entry.ADDRESSSTREETNAME !== "undefined") {
		  streetname = entry.ADDRESSSTREETNAME;
        }

        if (typeof entry.ADDRESSBUILDINGNAME !== "undefined") {
		  buildingName = entry.ADDRESSBUILDINGNAME;
        }

        if (typeof entry.ADDRESSFLOORNUMBER !== "undefined") {
		  floornum = entry.ADDRESSFLOORNUMBER;
		  
        }

        if (typeof entry.ADDRESSUNITNUMBER !== "undefined") {
		  unitnum = entry.ADDRESSUNITNUMBER;
		  
        }

		var format = {
          type: "Feature",

          properties: {
            Name: entry.NAME,
            Address:
              " " +
              blknum +
              " " +
              streetname +
              " " +
              buildingName +
              " Singapore " +
              entry.ADDRESSPOSTALCODE +
              " Level/Floor: " +
              floornum +
              " - " +
              unitnum
          },

          geometry: {
            type: "Point",
            coordinates: [parseFloat(splitchar[1]), parseFloat(splitchar[0])]
          }
        };


 //calculate distance

        var from = turf.point([
          parseFloat( this.place.position.lng),
           parseFloat(  this.place.position.lat)
        ]);
        var to = turf.point([
            parseFloat(splitchar[1]),
            parseFloat(splitchar[0])
        ]);
        var options = { units: "kilometers" };

        var distance = turf.distance(from, to, options);
        console.log("distance: " + distance/100);


        
         
 
        this.chasClinicData.features.push(format);
      }
    },

    queryOneMap(input_radius, units, theme) {
      var scope = this;

      //clear chas clinic data
      scope.chasClinicData.features = [];

      //loading gif
      scope.isLoading = true;

      var settings = {
        async: true,
        crossDomain: true,
        url:
          `https://developers.onemap.sg/publicapi/themeapi/retrieveTheme?queryName=` +
          theme +
          `&token=` +
          scope.access_token,
        method: "GET",
        processData: false,
        contentType: false,
        mimeType: "multipart/json"
      };

      $.ajax(settings)
        .done(function(response) {
           
          response.SrchResults.filter((entry, index) => {
            //check for description that is chas clinic
             
            if(scope.health_service_type == "Polyclinic"){
				if (entry.NAME !== undefined) 
					if(entry.NAME.includes("POLYCLINICS")){
						scope.chasClinicFilter(entry);
					}
            }else{
				scope.chasClinicFilter(entry);
			}
            
          });
        })
        .then(function() {
          // always executed
          console.log("end");

          //end loading gif
          scope.isLoading = false;

          scope.addMarkersWithinRadius(input_radius,units)

          

        });
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