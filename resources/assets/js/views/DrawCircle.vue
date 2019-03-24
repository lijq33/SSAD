<template>
  <div>

    <b-button @click="drawCirle">Draw circle</b-button>

    <div v-if="drawCircle.enableCircleDrawing">
 
      <vue-slider
        v-model="drawCircle.circleRadiusValue"
        :min="minValue"
        :max="maxValue"
        :interval="1"
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

      <b-button variant="primary" >Save</b-button>
    </div>

    

  </div>
</template>

<script>
import VueSlider from "vue-slider-component";
import "vue-slider-component/theme/antd.css";
import Swatches from "vue-swatches";
import "vue-swatches/dist/vue-swatches.min.css";

export default {

  components: {
    VueSlider,
    Swatches
  },
  //drawCircle:{enableCircleDrawing:false,marker:null,circle:null,cirleDistanceValue: 0,circleFillColor:'#E84B3C'},
  data() {
    return {
      minValue: 0,
      maxValue: 21,
      drawCircle: {
        enableCircleDrawing: false,
        circleDataChangedType: null,
        circleRadiusValue: 0,
        circleFillColor: "#E84B3C"
      }
    };
  },

  methods: {
    passDataToBaseMap() {
      this.$emit("get-circle-drawing", this.drawCircle);
    },
    changeCircleColor(color) {
      this.drawCircle.circleFillColor = color;
      this.drawCircle.circleDataChangedType = "circleFillColor";
      this.passDataToBaseMap();
    },
    changeCircleRadius(radius) {
      this.drawCircle.circleRadiusValue = radius * 1000;
      this.drawCircle.circleDataChangedType = "circleRadiusValue";
      this.passDataToBaseMap();
    },
    drawCirle() {
      //enable drawing

      if (this.drawCircle.enableCircleDrawing) {
        this.drawCircle.enableCircleDrawing = false;
      } else {
        this.drawCircle.enableCircleDrawing = true;
      }
      this.minValue = 0;
      this.drawCircle.circleRadiusValue = 0;
      this.drawCircle.circleDataChangedType = "enableCircleDrawing";

      //emit to base map
      this.passDataToBaseMap();
    }
  },
  watch: {
    circleFullAddress(newValue) {
      this.form.address = newValue;
    }
  }
};
</script>

