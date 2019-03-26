<template>
    <div>
        <div class="tw-justify-center">
            <b-card no-body>
                <b-tabs card>
                <b-tab title="Crisis" active>
                     
                </b-tab>
                
                
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
      
      selectTwoHrWeather(){ 
        var request = "https://api.data.gov.sg/v1/environment/2-hour-weather-forecast";
        var markerIconUrl = 'https://cdn0.iconfinder.com/data/icons/fatcow/32x32/weather_cloudy.png';

        if(this.selectTwoHrWeather.includes("show")){
           this.getCrisisDataFromBackEnd(request,this.selectTwoHrWeather,markerIconUrl);
        }else{
          this.removeCrisisDataFromFrontend(this.selectTwoHrWeather);
        } 
      }
       
    }
  }
</script>