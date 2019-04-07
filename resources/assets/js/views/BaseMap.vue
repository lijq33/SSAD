<template class="tw-w-full">
    <div class="tw-w-full">

        <div>
            <div>
                <b-row>
                    <b-col>
                        <!--autosearch -->
                        <auto-search
                            :query-full-address="searchMarkerFullAddress"
                            :clear-search-result-value="clearSearchVal"
                            :hide-confirm-button="hideToggleMap"
                            @get-search-data="handleSearchData"
                            @confirm-address="handleConfirmAddress"
                        />
                    </b-col>
                    
                    <b-col cols="5">
                        <!-- show and hide various crisis -->
                        <toggle-map
                            v-if="!hideToggleMap"
                            @get-toggle-data="handleToggleData"
                            @clear-toggle-data="handleClearToggleData"
                        />
                    </b-col>
                </b-row>
            </div>

            <div
                v-if="hideDrawingMap"
                class="tw-flex col-md-10 tw-pb-4"
            >
                <div class="tw-flex">
                    <label
                        for="Address"
                        class="col-md-4 col-form-label tw-mr-2 tw-pt-2"
                    >
                        Radius:
                    </label>
                    <div class="col-md-5">
                        <input
                            type="text"
                            class= "tw-border-grey-light tw-border-2 tw-rounded tw-p-2 tw-w-64"
                            v-model="newCircleRadius"
                        >

                    </div>
                </div>
                <b-col cols="5">
                </b-col> 
                <div class="col-md-5" >
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

        <div v-if="legendType == 'showTwoHrWeatherData'">
            <b-button
                v-b-toggle.collapse-1
                variant="outline-primary"
            >Legend+</b-button>
            <b-collapse
                id="collapse-1"
                class="mt-2"
            >
                <b-card>
                    <b-row
                        v-for="(item, index) in legendArray"
                        :key="index"
                    >
                        <b-col>
                            <img
                                class="image"
                                :src="item.iconUrl"
                                :alt="item.forevast"
                            >{{item.forecast}}
                        </b-col>

                        <!-- <b-col>
							<img
							class="image"
							src="https://www.nea.gov.sg/assets/images/icons/weather/pc.png"
							alt="Partly Cloudy Day icon"
							>Partly Cloudy (Day)
						</b-col>

						<b-col>
							<img
							class="image"
							src="https://www.nea.gov.sg/assets/images/icons/weather/tl.png"
							alt="Thunder Showering icon"
							>Thundery Showers
						</b-col> -->

                    </b-row>
                </b-card>
            </b-collapse>
        </div>

    </div>
</template>

<script>
import { Modal } from "bootstrap-vue/es/components";
import AutoSearchComplete from "./AutoSearchComplete";
import ToggleMap from "./ToggleCrisisMap";
import Collapse from "bootstrap-vue/es/components/collapse";

export default {
    props: ["hideToggleWindow", "hideDrawingWindow", "clearSearchResult"],
    mounted() {
        var scope = this;

        //initialize a single infowindow for all markers
        this.$refs.mapRef.$mapPromise.then(map => {
            scope.markerInfoWindow = new google.maps.InfoWindow({
                content: ""
            });
        });
    },

    components: {
        toggleMap: ToggleMap,
        "auto-search": AutoSearchComplete
    },

    data() {
        return {
            weatherForecastCenterControl: null,
            legendArray: [],
            legendType: null,
            newCircle: null,
            clearSearchVal: false,
            newCircleRadius: "",
            hideDrawingMap: false,
            hideToggleMap: false,
            markerInfoWindow: null,
            searchMarker: null,
            searchMarkerFullAddress: null,
            isLoading: false,
            zoom_lvl: 12,
            sgcoord: { lat: 1.3521, lng: 103.8198 },
            markers: {
                twoHrWeatherMarkers: [],
                fireMarkers: [],
                gasLeakMarkers: [],
                bombShelterMarkers: []
            },
            polygon: { dengueData: [], dengueMarkerData: [] },
            heatMap:{temperatureData:null}
        };
    },
    methods: {
        removePolygon(polygonVar, polygonData) {
            for (var i = 0; i < polygonData.length; i++) {
                if (polygonVar == "hideDengueData") {
                    polygonData[i].setMap(null);
                }
            }
        },
        handleConfirmAddress(confirmAddress) {
            //add additonal property if is a circle
            if (this.newCircleRadius) {
                confirmAddress["radius"] = this.newCircleRadius;
            }
            this.$emit("get-new-crisis-location", confirmAddress);
        },
        handleClearToggleData(clearToggleData) {
            //empty markers
            if (clearToggleData === "hideDengueData") {
                //clear circle
                this.removePolygon(clearToggleData, this.polygon.dengueData);
                this.polygon.dengueData = [];

                //clear marker
                this.removePolygon(
                    clearToggleData,
                    this.polygon.dengueMarkerData
                );
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
                this.removeMarkers(this.markers.twoHrWeatherMarkers);
                this.markers.twoHrWeatherMarkers = [];
                //hide legend
                this.legendType = "";
                //hide control

                this.$refs.mapRef.$mapPromise.then(map => {
                    map.controls[
                        google.maps.ControlPosition.TOP_CENTER
                    ].clear();
                    this.weatherForecastCenterControl = null;
                });
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
                this.legendType = toggleData.displayId;
                this.showTwoHrWeatherData(toggleData);

                //filter out duplicates
                var tempArray = this.legendArray;
                this.legendArray = Object.values(
                    tempArray.reduce(
                        (acc, cur) =>
                            Object.assign(acc, { [cur.forecast]: cur }),
                        {}
                    )
                );

                // console.log(toggleData.items[0].valid_period.start.slice(11,16))
                var validTime =
                    "Valid Period: " +
                    toggleData.items[0].valid_period.start.slice(11, 16) +
                    " - " +
                    toggleData.items[0].valid_period.end.slice(11, 16);

                this.$refs.mapRef.$mapPromise.then(map => {
                    //set up custom control
                    var centerControlDiv = document.createElement("div");
                    this.weatherForecastCenterControl = new this.weatherCenterControl(
                        centerControlDiv,
                        map,
                        validTime
                    );

                    centerControlDiv.index = 1;
                    map.controls[google.maps.ControlPosition.TOP_CENTER].push(
                        centerControlDiv
                    );
                });
            } else if (toggleData.displayId === "showBombShelterData") {
                this.showBombShelterData(toggleData);
            }else if(toggleData.displayId === "showTemperatureData"){
                this.showTemperatureData(toggleData);
            }
        },
        weatherCenterControl(controlDiv, map, updatedTime) {
            // Set CSS for the control border.
            var controlUI = document.createElement("div");
            controlUI.style.backgroundColor = "#fff";
            controlUI.style.border = "2px solid #fff";
            controlUI.style.borderRadius = "3px";
            controlUI.style.boxShadow = "0 2px 6px rgba(0,0,0,.3)";
            controlUI.style.cursor = "pointer";
            controlUI.style.marginBottom = "22px";
            controlUI.style.textAlign = "center";
            controlUI.title = "Click to recenter the map";
            controlDiv.appendChild(controlUI);

            // Set CSS for the control interior.
            var controlText = document.createElement("div");
            controlText.style.color = "rgb(25,25,25)";
            controlText.style.fontFamily = "Roboto,Arial,sans-serif";
            controlText.style.fontSize = "16px";
            controlText.style.lineHeight = "38px";
            controlText.style.paddingLeft = "5px";
            controlText.style.paddingRight = "5px";
            controlText.innerHTML = updatedTime;
            controlUI.appendChild(controlText);

            // Setup the click event listeners: simply set the map to Chicago.
            controlUI.addEventListener("click", function() {
                map.setCenter(chicago);
            });
        },
        handleSearchData(searchData) {
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
                    if (scope.hideDrawingMap) {
                        console.log("create dengue dengue");

                        scope.newCircle = new google.maps.Circle({
                            path: google.maps.SymbolPath.CIRCLE,
                            strokeColor: "#E84B3C",
                            strokeOpacity: 1,
                            strokeWeight: 1,
                            fillColor: "#E84B3C",
                            fillOpacity: 0.35,
                            map: map,
                            center: pos,
                            radius: 150,
                            editable: true
                        });

                        scope.newCircle.bindTo(
                            "center",
                            this.searchMarker,
                            "position"
                        );

                        scope.newCircleRadius = 150;

                        google.maps.event.addListener(
                            scope.newCircle,
                            "radius_changed",
                            function(e) {
                                scope.newCircleRadius = parseFloat(
                                    scope.newCircle.radius
                                );
                            }
                        );
                    } else {
                    }

                    //add drag listener

                    google.maps.event.addListener(
                        this.searchMarker,
                        "dragend",
                        function(e) {
                            var latLng = e.latLng;

                            var fullAddressPos = {
                                lat: latLng.lat(),
                                lng: latLng.lng()
                            };

                            scope.searchMarkerFullAddress = fullAddressPos;
                        }
                    );
                } else {
                    //just change latlng

                    this.searchMarker.setPosition(pos);
                }
            });

            this.panMap(searchData.lat, searchData.lng);
            this.setMapZoomLvl(17);
        },
        removeMarkers(removeMarkersType) {
            for (var i = 0; i < removeMarkersType.length; i++) {
                removeMarkersType[i].setMap(null);
            }
        },
        showTemperatureData(temperatureData){
            console.log(temperatureData)
            var scope = this;

            this.$refs.mapRef.$mapPromise.then(map => {

                var heatmapData = [
                {location: new google.maps.LatLng(37.782, -122.447), weight: 0.5},
                new google.maps.LatLng(37.782, -122.445),
                {location: new google.maps.LatLng(37.782, -122.443), weight: 2},
                {location: new google.maps.LatLng(37.782, -122.441), weight: 3},
                {location: new google.maps.LatLng(37.782, -122.439), weight: 2},
                new google.maps.LatLng(37.782, -122.437),
                {location: new google.maps.LatLng(37.782, -122.435), weight: 0.5},

                {location: new google.maps.LatLng(37.785, -122.447), weight: 3},
                {location: new google.maps.LatLng(37.785, -122.445), weight: 2},
                new google.maps.LatLng(37.785, -122.443),
                {location: new google.maps.LatLng(37.785, -122.441), weight: 0.5},
                new google.maps.LatLng(37.785, -122.439),
                {location: new google.maps.LatLng(37.785, -122.437), weight: 2},
                {location: new google.maps.LatLng(37.785, -122.435), weight: 3}
                ];

                 scope.heatMap.temperatureData = new google.maps.visualization.HeatmapLayer({
                data: heatmapData
                });
                scope.heatMap.temperatureData.setMap(map);


            });


        },
        showBombShelterData(bombShelterData) {
            var scope = this;

            bombShelterData.forEach((element, index) => {
                scope.addMarker(
                    "Array",
                    scope.markers.bombShelterMarkers,
                    {
                        icon: bombShelterData.iconUrl,
                        markerDisplayId: element.id,
                        position: { lat: parseFloat(element.lat), lng: parseFloat(element.lng) }
                    },
                    { infoWindowTitle: element.name, infoWindowBody: element },
                    element
                );
            });
        },
        showDengueData(dengue) {
            var scope = this;
            this.$refs.mapRef.$mapPromise.then(map => {
                dengue.forEach((element, index) => {
                    var pos = {
                        lat: parseFloat(element.lat),
                        lng: parseFloat(element.lng)
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
                        strokeColor: "#E84B3C",
                        strokeOpacity: 1,
                        strokeWeight: 1,
                        fillColor: "#E84B3C",
                        fillOpacity: 0.35,
                        map: map,
                        center: pos,
                        radius: element.radius
                    });

                    temp.bindTo("center", tempDengueMarker, "position");

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

                    google.maps.event.addListener(
                        temp,
                        "mouseover",
                        function() {
                            scope.markerInfoWindow.setContent(contentString);
                            scope.markerInfoWindow.setPosition(pos);
                            scope.markerInfoWindow.open(map, this);
                        }
                    );

                    scope.polygon.dengueData.push(temp);
                    scope.polygon.dengueMarkerData.push(tempDengueMarker);
                });
            });
        },
        showFireData(fireData) {
            var scope = this;

            console.log(fireData);

            fireData.forEach((element, index) => {
                scope.addMarker(
                    "Array",
                    scope.markers.fireMarkers,
                    {
                        icon: fireData.iconUrl,
                        markerDisplayId: element.id,
                        position: { lat: parseFloat(element.lat), lng: parseFloat(element.lng) }
                    },
                    {
                        infoWindowTitle: element.name,
                        infoWindowBody: element,
                        imageAvailable: false
                    },
                    element
                );
            });
        },
        showGasLeakData(gasLeakData) {
            var scope = this;
            gasLeakData.forEach((element, index) => {
                scope.addMarker(
                    "Array",
                    scope.markers.gasLeakMarkers,
                    {
                        icon: gasLeakData.iconUrl,
                        markerDisplayId: element.id,
                        position: { lat: parseFloat(element.lat), lng: parseFloat(element.lng) }
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

                  google.maps.event.addListener(temp, "click", function() {
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

            var iconUrl = "";

            data.area_metadata.forEach((element, index) => {
                var forecast = data.items[0].forecasts[index].forecast;

                if (forecast == "Fair (Day)") { 
                    //done
                    iconUrl =
                        "http://www.weather.gov.sg/wp-content/themes/wiptheme/assets/img/icon-fair-day-sm.png";
                } else if (forecast == "Fair (Night)") {
                    //done
                    iconUrl =
                        "http://www.weather.gov.sg/wp-content/themes/wiptheme/assets/img/icon-fair-night-sm.png";
                } else if (forecast == "Fair & Warm") {
                     
                    iconUrl =
                        "http://www.weather.gov.sg/wp-content/themes/wiptheme/assets/img/icon-fair-warm-sm.png";
                } else if (forecast == "Partly Cloudy (Day)") {
                    //done
                    iconUrl =
                        "http://www.weather.gov.sg/wp-content/themes/wiptheme/assets/img/icon-partly-cloudy-day-sm.png";
                } else if (forecast == "Partly Cloudy (Night)") {
                    //done
                    iconUrl =
                        "http://www.weather.gov.sg/wp-content/themes/wiptheme/assets/img/icon-partly-cloudy-night-sm.png";
                } else if (forecast == "Cloudy") {
                     
                    iconUrl =
                        "http://www.weather.gov.sg/wp-content/themes/wiptheme/assets/img/icon-cloudy-sm.png";
                } else if (forecast == "Hazy") {
                    iconUrl =
                        "http://www.weather.gov.sg/wp-content/themes/wiptheme/assets/img/icon-hazy-sm.png";
                } else if (forecast == "Slightly Hazy") {
                    iconUrl =
                        "http://www.weather.gov.sg/wp-content/themes/wiptheme/assets/img/icon-slightly-hazy-sm.png";
                } else if (forecast == "Windy") {
                    iconUrl =
                        "http://www.weather.gov.sg/wp-content/themes/wiptheme/assets/img/icon-windy-sm.png";
                } else if (forecast == "Mist") {
                    iconUrl =
                        "http://www.weather.gov.sg/wp-content/themes/wiptheme/assets/img/icon-mist-sm.png";
                } else if (forecast == "Light Rain") {
                    iconUrl =
                        "http://www.weather.gov.sg/wp-content/themes/wiptheme/assets/img/icon-light-rain-sm.png";
                } else if (forecast == "Moderate Rain") {
                    iconUrl =
                        "http://www.weather.gov.sg/wp-content/themes/wiptheme/assets/img/icon-moderate-rain-sm.png";
                } else if (forecast == "Heavy Rain") {
                    iconUrl =
                        "http://www.weather.gov.sg/wp-content/themes/wiptheme/assets/img/icon-heavy-rain-sm.png";
                } else if (forecast == "Passing Showers") {
                    iconUrl =
                        "http://www.weather.gov.sg/wp-content/themes/wiptheme/assets/img/icon-passing-shower-sm.png";
                } else if (forecast == "Light Showers") {
                    iconUrl =
                        "http://www.weather.gov.sg/wp-content/themes/wiptheme/assets/img/icon-light-shower-sm.png";
                } else if (forecast == "Showers") {
                    //done
                    iconUrl =
                        "http://www.weather.gov.sg/wp-content/themes/wiptheme/assets/img/icon-shower-sm.png";
                } else if (forecast == "Heavy Showers") {
                    iconUrl =
                        "http://www.weather.gov.sg/wp-content/themes/wiptheme/assets/img/icon-heavy-showers-sm.png";
                } else if (forecast == "Thundary Showers") {
                    iconUrl =
                        "http://www.weather.gov.sg/wp-content/themes/wiptheme/assets/img/icon-thundery-showers-sm.png";
                } else if (forecast == "Heavy Thundary Showers") {
                    iconUrl =
                        "http://www.weather.gov.sg/wp-content/themes/wiptheme/assets/img/icon-heavy-thundery-showers-sm.png";
                } else if (
                    forecast == "Heavy Thundary Showers with Gusty Winds"
                ) {
                    iconUrl =
                        "http://www.weather.gov.sg/wp-content/themes/wiptheme/assets/img/icon-heavy-thundery-showers-with-gusty-winds-sm.png";
                }

                //add to legend array
                this.legendArray.push({
                    iconUrl: iconUrl,
                    forecast: data.items[0].forecasts[index].forecast
                });

                scope.addMarker(
                    "Array",
                    scope.markers.twoHrWeatherMarkers,
                    {
                        icon: iconUrl,
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
        newCircleRadius() {
            if (this.newCircle) {
                if (isNaN(this.newCircleRadius)) {
                    this.newCircleRadius = 0;
                }
                this.newCircle.setOptions({
                    radius: parseFloat(this.newCircleRadius)
                });
            }
        },
        clearSearchResult(value) {
            console.log("clear1");
            if (this.searchMarker) {
                this.searchMarker.setMap(null);
                this.searchMarker = null;
                this.searchMarkerFullAddress = null;
                this.clearSearchVal = value;

                if (this.hideDrawingMap) {
                    this.newCircle.setMap(null);
                }
            }
        },
        hideToggleWindow(value) {
            this.hideToggleMap = value;
        },
        hideDrawingWindow(value) {
            this.hideDrawingMap = value;
        }
    },
    computed: {
        currentUser() {
            return this.$store.getters.currentUser;
        },

        isCallCenterOperator() {
            if (!this.currentUser) return false;
            return this.currentUser.roles == "CallCenterOperator";
        },

        isCrisisManager() {
            if (!this.currentUser) return false;
            return this.currentUser.roles == "CrisisManager";
        },

        isAccountManager() {
            if (!this.currentUser) return false;
            return this.currentUser.roles == "AccountManager";
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