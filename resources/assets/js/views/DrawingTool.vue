<template>
  <div>


     <!-- drawing tools extension -->
    <b-card no-body>
     
      <b-tabs card>
        <!--circle-->
        <b-tab v-if="drawCircle.enableCircleDrawing" title="Circle">
          <vue-slider
        v-model="drawCircle.circleRadiusValue"
        :min="minValue"
        :max="maxValue"
        :interval="1000"
        :clickable="false"
        @change="changeCircleRadius"
      />{{this.drawCircle.circleRadiusValue}} metres

      <div class="form__field">
        <div class="form__label">
          <strong>Please choose a color:</strong>
        </div>
        <div class="form__input">
          <swatches
            v-model="drawCircle.circleFillColor"
            popover-to="right"
            @input="changeCircleColor"
          ></swatches>
        </div>
      </div> 
      <b-button variant="primary" @click="saveCircleData">Save</b-button>
      </b-tab>

        <!-- show and hide extension -->
        <template slot="tabs"> 
          <!-- <b-nav-item v-if ="!localEnableDrawing" @click.prevent="newTab" href="#"><b>Enable Drawing Extension</b></b-nav-item> -->
          <!-- <b-nav-item v-if ="localEnableDrawing" @click.prevent="newTab" href="#"><b>Hide</b></b-nav-item> -->
        </template>

      </b-tabs>
    </b-card>


     
 
  </div>
</template>

<script>
import VueSlider from "vue-slider-component";
import "vue-slider-component/theme/antd.css";
import Swatches from "vue-swatches";
import "vue-swatches/dist/vue-swatches.min.css";



export default {
     props: ['enableDrawing','circleDrawingCenter','circleDrawingRadius','selectedShape'],

  mounted(){

      
    //tell base map which componet will appear first
    //console.log("mounted draw circle");
    //enable drawing

      // if (this.drawCircle.enableCircleDrawing) {
      //   this.drawCircle.enableCircleDrawing = false;
      // } else {
      //   this.drawCircle.enableCircleDrawing = true;
      // }
      // this.minValue = 0;
      // this.drawCircle.circleRadiusValue = 0;
      // this.drawCircle.circleDataChangedType = "enableCircleDrawing";

      // //emit to base map
      // this.passDataToBaseMap();

   // this.$emit("get-backend-data", sampleData);
  },
  destroyed(){
    console.log("destory circle compoent")
  },
  components: {
    VueSlider,
    Swatches
  },
  //drawCircle:{enableCircleDrawing:false,marker:null,circle:null,cirleDistanceValue: 0,circleFillColor:'#E84B3C'},
  data() {
    return {
      
      localDrawing:this.drawingEvent,
      localEnableDrawing:false,
      minValue: 0,
      maxValue: 21000,
      drawCircle: {
        type:'circle',
        enableCircleDrawing: false,
        circleRadiusValue: 0,
        circleFillColor: "#E84B3C",
        center:{lat:'',lng:''},
        postData:false
      },
        tabs: [],
        tabCounter: 0
    };
  },

  methods: {
    newTab(){
      if(this.localEnableDrawing){
        this.this.localEnableDrawing = false;
      }else{
        this.localEnableDrawing = true
      }

    },
    saveCircleData(){
      
      this.drawCircle.postData = true;

      this.passDataToBaseMap();
      console.log(this.drawCircle)

    },
    processCircleDrawing(circle){
     
     console.log(circle)
         
      this.drawCircle.circleRadiusValue = circle.radius.toFixed(0);
      this.drawCircle.center.lat = circle.center.lat();
      this.drawCircle.center.lng = circle.center.lng();
      this.drawCircle.circleFillColor=circle.fillColor;

    }, 
    closeTab(x) {
    for (let i = 0; i < this.tabs.length; i++) {
      if (this.tabs[i] === x) {
        this.tabs.splice(i, 1)
      }
    }
      },
    passDataToBaseMap() {
      this.$emit("get-updated-drawing", this.drawCircle);
    },
    changeCircleColor(color) {
      this.drawCircle.circleFillColor = color;
      this.passDataToBaseMap();
    },
    changeCircleRadius(radius) {
      this.drawCircle.circleRadiusValue = radius;
      this.passDataToBaseMap();
    }
  },
  watch: { 
    selectedShape(newValue){
      //receive shape

      //if is circle

      if(newValue == null){
        console.log("not selected shape") 

        //reset
        if(this.drawCircle.enableCircleDrawing){
          this.drawCircle.enableCircleDrawing = false
        }
      }else  if (newValue.type == "circle") {
       
       this.drawCircle.enableCircleDrawing = true;
       this.processCircleDrawing(newValue);
      }
    },
     circleDrawingRadius(newValue,oldValue){
        console.log("radius changed");
      this.drawCircle.circleRadiusValue = newValue.toFixed(0);
    },
    circleDrawingCenter(newValue,oldValue){
       
       this.drawCircle.center.lat = newValue.lat();
       this.drawCircle.center.lng = newValue.lng();
    },
  enableDrawing(newValue){
    console.log(newValue)
  }
  },
  computed:{
    
  }
};
</script>

