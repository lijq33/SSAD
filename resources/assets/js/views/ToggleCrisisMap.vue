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
                                (2H) Weather Forecast
                            </b-form-checkbox>
 
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

export default {
    data() {
        return {
            selectTwoHrWeather: "",
            selectDengue: "",
            selectFire: "",
            selectGasLeak: "",
            selectBombShelter:""
        };
    },
    methods: {
        removeCrisisDataFromFrontend(removeData) {
            this.$emit("clear-toggle-data", removeData);
        },
        getCrisisDataFromBackEnd(url, display_id, icon_url) {
            var scope = this;

            $.ajax({
                url: url,
                type: "GET",
                success: function(data, status, jqXHR) {
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
        selectBombShelter(){
            console.log("sfsaf")

            var data = {
                resource_id: '4ee17930-4780-403b-b6d4-b963c7bb1c09', // the resource id
                limit: 5, // get 5 results
                q: 'jones' // query for 'jones'
                };

              $.ajax({
               type:'GET',
               url:'https://data.gov.sg/api/action/datastore_search?resource_id=4ee17930-4780-403b-b6d4-b963c7bb1c09&limit=574',
               success:function(data) {
                  console.log(data)
               }
            });


            // var request = "https://data.gov.sg/api/action/datastore_search?resource_id=4ee17930-4780-403b-b6d4-b963c7bb1c09";
            // var markerIconUrl = "/assets/img/bomb-shelter.png";
            //     //https://www.scdf.gov.sg/images/default-source/scdf-images/cd-shelter-logo-01711c5eafc0a84b29afa7c5a52c2cfe7a.png?sfvrsn=d000c2ba_0

            // if (this.selectBombShelter.includes("show")) {
            //     this.getCrisisDataFromBackEnd(
            //         request,
            //         this.selectBombShelter,
            //         markerIconUrl
            //     );
            // } else {
            //     this.removeCrisisDataFromFrontend(this.selectBombShelter);
            // }

        },
        selectGasLeak() {
            var request = "/api/crisis/gasLeak";
            var markerIconUrl =
                "https://images.vexels.com/media/users/3/150012/isolated/preview/bf8475104937ca2ee44090829f4efa3a-small-gas-cylinder-icon-by-vexels.png";

            if (this.selectGasLeak.includes("show")) {
                this.getCrisisDataFromBackEnd(
                    request,
                    this.selectGasLeak,
                    markerIconUrl
                );
            } else {
                this.removeCrisisDataFromFrontend(this.selectGasLeak);
            }
        },
        selectFire() {
            var request = "/api/crisis/fire";
            var markerIconUrl =
                "https://cdn0.iconfinder.com/data/icons/fatcow/32/fire.png";

            if (this.selectFire.includes("show")) {
                this.getCrisisDataFromBackEnd(
                    request,
                    this.selectFire,
                    markerIconUrl
                );
            } else {
                this.removeCrisisDataFromFrontend(this.selectFire);
            }
        },
        selectDengue() {
            var request = "/api/crisis/dengue";
            var markerIconUrl =
                "https://www.sumitomo-chemical.co.uk/wp-content/uploads/icon-mosquito.png";

            if (this.selectDengue.includes("show")) {
                this.getCrisisDataFromBackEnd(
                    request,
                    this.selectDengue,
                    markerIconUrl
                );
            } else {
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
        }
    }
};
</script>