<template>
    <div>
        <div class="tw-justify-center">
            <b-card no-body>
                <b-tabs card>
                <b-tab title="Crisis" active>

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

                    <div v-if="selectTwoHrWeather == 'showTwoHrWeatherData'">
                      <b-button size="sm" variant="outline-primary" v-b-toggle.collapse1>Legend +</b-button>
                      <b-collapse id="collapse1" class="mt-2">
                        <b-card>
                      <div class="row legend-list2"><div class="col-xs-6 col-md-3"><div class="force-col-xs-3 col-xs-3"><img class="image" src="https://www.nea.gov.sg/assets/images/icons/weather/pn.png" alt="{forecast} icon"></div><div class="force-col-xs-9 col-xs-9 legend-desciption">Partly Cloudy</div></div><div class="col-xs-6 col-md-3"><div class="force-col-xs-3 col-xs-3"><img class="image" src="https://www.nea.gov.sg/assets/images/icons/weather/pc.png" alt="Partly Cloudy icon"></div><div class="force-col-xs-9 col-xs-9 legend-desciption">Partly Cloudy</div></div><div class="col-xs-6 col-md-3"><div class="force-col-xs-3 col-xs-3"><img class="image" src="https://www.nea.gov.sg/assets/images/icons/weather/tl.png" alt="Thundery icon"></div><div class="force-col-xs-9 col-xs-9 legend-desciption">Thundery Showers</div></div></div>

                        </b-card>
                      </b-collapse>
                    </div> 
                  </b-form-group> 
                </b-tab>


                </b-tabs>
            </b-card>
        </div>
    </div>
</template>

<script>
    import Tabs  from 'bootstrap-vue/es/components';
    import  FormCheckbox  from 'bootstrap-vue/es/components/form-checkbox'
  
    export default {
      
    data() {
      return {
        selectTwoHrWeather:'',
        selectDengue:'',
        selectFire:'',
        selectGasLeak:''
      }
    },
    methods:{
      removeCrisisDataFromFrontend(removeData){
        this.$emit("clear-toggle-data", removeData);
      },
      getCrisisDataFromBackEnd(url,display_id,icon_url){
        
        var scope = this;

        $.ajax({
                url: url,
                type: "GET",
                success: function (data, status, jqXHR) {
                  data["displayId"] = display_id; 
                  data["iconUrl"] = icon_url;
                  console.log(data)
                  scope.$emit("get-toggle-data", data);
                },
                error: function (jqXHR, status, err) {
                    console.log(err);
                },
                complete: function (jqXHR, status) {
                 
                }
            });  
      }
    },
    watch:{
      selectGasLeak(){ 
        var request = "/api/crisis/gasLeak";
         var markerIconUrl = 'https://images.vexels.com/media/users/3/150012/isolated/preview/bf8475104937ca2ee44090829f4efa3a-small-gas-cylinder-icon-by-vexels.png';

          if(this.selectGasLeak.includes("show")){
           this.getCrisisDataFromBackEnd(request,this.selectGasLeak,markerIconUrl); 
        }else{
          this.removeCrisisDataFromFrontend(this.selectGasLeak);
        }  
      },
      selectFire(){ 
        var request = "/api/crisis/fire";
         var markerIconUrl = 'https://cdn0.iconfinder.com/data/icons/fatcow/32/fire.png';

          if(this.selectFire.includes("show")){
           this.getCrisisDataFromBackEnd(request,this.selectFire,markerIconUrl); 
        }else{
          this.removeCrisisDataFromFrontend(this.selectFire);
        }  
      },
      selectDengue(){
        var request = "/api/crisis/dengue";
        var markerIconUrl = 'https://www.sumitomo-chemical.co.uk/wp-content/uploads/icon-mosquito.png';

         if(this.selectDengue.includes("show")){
           this.getCrisisDataFromBackEnd(request,this.selectDengue,markerIconUrl);
        }else{
          this.removeCrisisDataFromFrontend(this.selectDengue);
        } 

      },
      selectTwoHrWeather(){ 
        var request = "https://api.data.gov.sg/v1/environment/2-hour-weather-forecast";
        var markerIconUrl = 'https://www.nea.gov.sg/assets/images/icons/weather-bg/PC.png';

        if(this.selectTwoHrWeather.includes("show")){
           this.getCrisisDataFromBackEnd(request,this.selectTwoHrWeather,markerIconUrl);
        }else{
          this.removeCrisisDataFromFrontend(this.selectTwoHrWeather);
        } 
      }
       
    }
  }
</script>