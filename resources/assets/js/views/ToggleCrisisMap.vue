<!-- Author: Chen XingYu -->
<template>
    <div>
        <div class="tw-justify-center">
            <b-card no-body>
                <b-tabs card>
                    <b-tab
                        title="Crisis"
                        active
                    >

                        <!--dengue-->
                        <b-form-group>

                            <b-form-checkbox
                                id="showDengueDataId"
                                name="showDengueDataId"
                                v-model="selectDengue"
                                value="showDegueData"
                                unchecked-value="hideDengueData"
                                :disabled="disableDengueData"
                            >
                                Dengue
                            </b-form-checkbox>
                            <!--Fire-->

                            <b-form-checkbox
                                id="showFireDataId"
                                name="showFireDataId"
                                v-model="selectFire"
                                value="showFireData"
                                unchecked-value="hideFireData"
                                :disabled="disableFireData"
                            >
                                Fire
                            </b-form-checkbox>

                            <!--gas leak control-->
                            <b-form-checkbox
                                id="showGasLeakDataId"
                                name="showGasLeakDataId"
                                v-model="selectGasLeak"
                                value="showGasLeakData"
                                unchecked-value="hideGasLeakData"
                                :disabled="disableGasLeakData"
                            >
                                Gas Leakage
                            </b-form-checkbox>

                        </b-form-group>

                    </b-tab>

                    <!--weather-->
                    <b-tab title="Weather">

                        <b-form-group>
                            <b-form-checkbox
                                id="showTwoHrWeatherDataId"
                                name="showTwoHrWeatherDataId"
                                v-model="selectTwoHrWeather"
                                value="showTwoHrWeatherData"
                                unchecked-value="hideTwoHrWeatherData"
                            >
                                Weather Forecast
                            </b-form-checkbox>

                             <!-- <b-form-checkbox
                                id="showTemperatureDataId"
                                name="showTemperatureDataId"
                                v-model="selectTemperature"
                                value="showTemperatureData"
                                unchecked-value="hideTemperatureData"
                            >
                                Temperature
                            </b-form-checkbox> -->


                        </b-form-group> 

                    </b-tab>

                    <!--bomb sheleter-->
                    <b-tab title="Bomb Shelter">

                        <b-form-group>
                            <b-form-checkbox
                                id="showBombShelterDataId"
                                name="showBombShelterDataId"
                                v-model="selectBombShelter"
                                value="showBombShelterData"
                                unchecked-value="hideBombShelterData"
                            >
                                Bomb Shelter
                            </b-form-checkbox>

                        </b-form-group>
                    </b-tab>

                </b-tabs>
            </b-card>
        </div>
    </div>
</template>

<script>
import Tabs from "bootstrap-vue/es/components";
import FormCheckbox from "bootstrap-vue/es/components/form-checkbox";
import BombShelterJson from '../../bombShelter.json';

export default {
    mounted() {
        this.getAllCrisis();
        this.disableFireData = true;
        this.disableDengueData = true;
        this.disableGasLeakData = true;
    },
    data() {
        return {
            selectTwoHrWeather: "",
            selectDengue: "",
            selectFire: "",
            selectGasLeak: "",
            selectBombShelter: "",
            selectTemperature:"",
            dengueData: [],
            fireData: [],
            gasData: [],
            bombShelterData:BombShelterJson,
            disableFireData: false,
            disableDengueData: false,
            disableGasLeakData: false,
        };
    },
    methods: {

        getAllCrisis() {
            axios
                .get("/api/crisis/all")
                .then(res => {
                    this.dengueData = res.data.dengue;
                    this.fireData = res.data.fire;
                    this.gasData = res.data.gas;

                    if (this.disableFireData) {
                        this.disableFireData = false;
                    }
                    if (this.disableDengueData) {
                        this.disableDengueData = false;
                    }
                    if (this.disableGasLeakData) {
                        this.disableGasLeakData = false;
                    }
                })
                .catch(error => {
                    console.log("error loading all crisis from backend.");
                });
        },
        removeCrisisDataFromFrontend(removeData) {
            this.$emit("clear-toggle-data", removeData);
        },
        appendCrisisDataFromBackEnd(data, display_id, icon_url) {
            data["displayId"] = display_id;
            data["iconUrl"] = icon_url;
            this.$emit("get-toggle-data", data);
        },
         getCrisisDataFromBackEnd(url, display_id, icon_url) {
            var scope = this;

            $.ajax({
                url: url,
                type: "GET",
                success: function(data, status, jqXHR) {
                    console.log(data)
                    data["displayId"] = display_id;
                    data["iconUrl"] = icon_url;
                    scope.$emit("get-toggle-data", data);
                },
                error: function(jqXHR, status, err) {
                    console.log(err);
                },
                complete: function(jqXHR, status) {}
            });

         }
    },
    watch: {
 
        selectBombShelter() { 
             var request = this.bombShelterData;
            var markerIconUrl = "/assets/img/bomb-shelter.png";
            
            if (this.selectBombShelter.includes("show")) {
                this.appendCrisisDataFromBackEnd(
                    request,
                    this.selectBombShelter,
                    markerIconUrl
                );
            } else {
                this.removeCrisisDataFromFrontend(this.selectBombShelter);
            }
        },
        selectGasLeak() {
            var request = this.gasData;
            var markerIconUrl = "/assets/img/gasleak.png";

            if (this.selectGasLeak.includes("show")) {
                this.appendCrisisDataFromBackEnd(
                    request,
                    this.selectGasLeak,
                    markerIconUrl
                );
            } else {
                this.disableGasLeakData = true;
                this.getAllCrisis();
                this.removeCrisisDataFromFrontend(this.selectGasLeak);
            }
        },
        selectFire() {
            var request = this.fireData;
            var markerIconUrl = "/assets/img/fire.png";

            if (this.selectFire.includes("show")) {
                this.appendCrisisDataFromBackEnd(
                    request,
                    this.selectFire,
                    markerIconUrl
                );
            } else {
                this.disableFireData = true;
                this.getAllCrisis();
                this.removeCrisisDataFromFrontend(this.selectFire);
            }
        },
        selectDengue() {
            var request = this.dengueData;
            var markerIconUrl = "/assets/img/icon-mosquito.png";

            if (this.selectDengue.includes("show")) {
                this.appendCrisisDataFromBackEnd(
                    request,
                    this.selectDengue,
                    markerIconUrl
                );
            } else {
                this.disableDengueData = true;
                this.getAllCrisis();
                this.removeCrisisDataFromFrontend(this.selectDengue);
            }
        },
        selectTwoHrWeather() {
            var request =
                "https://api.data.gov.sg/v1/environment/2-hour-weather-forecast";
            var markerIconUrl =
                "https://www.nea.gov.sg/assets/images/icons/weather-bg/PC.png";

            if (this.selectTwoHrWeather.includes("show")) {
                this.getCrisisDataFromBackEnd(
                    request,
                    this.selectTwoHrWeather,
                    markerIconUrl
                );
            } else {
                this.removeCrisisDataFromFrontend(this.selectTwoHrWeather);
            }
        },
        selectTemperature(){
            var request =
                "https://api.data.gov.sg/v1/environment/air-temperature";
            var markerIconUrl =
                "https://www.nea.gov.sg/assets/images/icons/weather-bg/PC.png";

            if (this.selectTemperature.includes("show")) {
                this.getCrisisDataFromBackEnd(
                    request,
                    this.selectTemperature,
                    markerIconUrl
                );
            } else {
                this.removeCrisisDataFromFrontend(this.selectTemperature);
            }

        },
    }
};
</script>