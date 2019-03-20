<template>
    <div>
        <div class="tw-justify-center">
            <b-card no-body>
                <b-tabs card>
                <b-tab title="Crisis" active>
                       <b-form-group>
                         <b-form-checkbox-group switches v-model="selected" name="crisisSwitch" :options="crisisOptions" />
                        </b-form-group> 
                </b-tab>
                <b-tab title="Weather">

                    <b-form-group>
                         <b-form-checkbox-group switches v-model="selected" name="weatherSwitch" :options="weatherOptions" />
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
        selected: [], // Must be an array reference!
        crisisOptions: [
          { text: 'Degue', value: 'showDegueData' },
          { text: 'Fire', value: 'showFireData' },
          { text: 'Gas Leak', value: 'showGasLeakData' }
        ],
        weatherOptions: [
          { text: 'Haze', value: 'showHazeData' },
          { text: 'Rain', value: 'showRainData' },
        ]
      }
    },
    watch:{
        selected(){
            this.$emit('get-toggle-data', this.selected);
        }
    }
  }
</script>