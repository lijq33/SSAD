<template>
  <div>
<!-- 
    <vue-bootstrap4-table id="markerIdTable"
    :classes="classes"
    :rows="rows" 
    :columns="columns" 
    :config="config">
   </vue-bootstrap4-table> -->

<template>
  <div>
    <b-table :items="items" :fields="fields" striped>
      <template slot="show_details" slot-scope="row">
        <b-button size="sm" @click="row.toggleDetails" class="mr-2">
          {{ row.detailsShowing ? 'Hide' : 'Show'}} Details
        </b-button>

        <!-- As `row.showDetails` is one-way, we call the toggleDetails function on @change -->
        <b-form-checkbox v-model="row.detailsShowing" @change="row.toggleDetails">
          Details via check
        </b-form-checkbox>
      </template>

      <template slot="row-details" slot-scope="row">
        <b-card>
          <!-- <b-row class="mb-2">
            <b-col sm="3" class="text-sm-right"><b>Age:</b></b-col>
            <b-col>{{ row.item.age }}</b-col>
          </b-row>

          <b-row class="mb-2">
            <b-col sm="3" class="text-sm-right"><b>Is Active:</b></b-col>
            <b-col>{{ row.item.isActive }}</b-col>
          </b-row> -->

          <b-button size="sm" @click="row.toggleDetails">Hide Details</b-button>
        </b-card>
      </template>
    </b-table>
  </div>
</template>

     <!-- drawing tools extension -->
    <b-card no-body>
   
      <b-tabs card>
        <!--marker-->
        <b-tab v-if="enableMarkerDrawing" title="Marker">
          <h6>{{this.markerGeoJson.geometry.coordinates}}</h6>
        </b-tab>

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
import Vue from 'vue';
import VueSlider from "vue-slider-component";
import "vue-slider-component/theme/antd.css";
import Swatches from "vue-swatches";
import "vue-swatches/dist/vue-swatches.min.css";
 import VueBootstrap4Table from 'vue-bootstrap4-table'

export default {
     props: ['enableDrawing','circleDrawingCenter','circleDrawingRadius','selectedShape','markerDrawing'],

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
    Swatches,
    VueBootstrap4Table
  },
  //drawCircle:{enableCircleDrawing:false,marker:null,circle:null,cirleDistanceValue: 0,circleFillColor:'#E84B3C'},
  data() {
    return {
     fields: ['id', 'event', 'show_details'],
        items: [
          {
            isActive: false,
            id: 1,
            event: '445',
            _showDetails: false
          }
        ],
       rows: [],
    columns: [{
            label: "id",
            name: "id",
            sort: true,
        },
        {
            label: "First Name",
            name: "name.first_name",
            sort: true,
        }],
    config: {
        checkbox_rows: true,
        rows_selectable: false,
          pagination: false, // default true
            pagination_info: false,
          global_search: {
            visibility: false,
            }  
      },
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
      tabCounter: 0,
      markerUID:null,
      enableMarkerDrawing: false,
      markerGeoJson: {
        type: "Feature",
        geometry: {
          type: "Point",
          coordinates: []  
        }
      }
    };
  },

  methods: {
    rowSelected(items) {
        this.selected = items
      },
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
    processMarkerDrawing(marker){
      console.log(marker)
        this.markerGeoJson.geometry.coordinates=[marker.position.lng(),marker.position.lat()]
        this.markerUID=marker.closure_uid_342722430;

        var found = false;

         this.items.forEach((element,index) => {  
           
        if(element.id === marker.closure_uid_342722430){
          console.log(element.id)

          found = true;
        }

         });

      if(found){
          console.log("1212")
      }else{
      console.log("add marker to item")
      
        //add to table
            this.items.push({
                isActive: false,
                id: marker.closure_uid_342722430,
                event: marker.closure_uid_342722430,
                _showDetails: true
            })

      }
          

       
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
    markerDrawing(newValue){
     this.markerGeoJson.geometry.coordinates=[newValue.lng(),newValue.lat()]
    },
    selectedShape(newValue){
      //receive shape
      //if is circle
      console.log("nevalue selected shape")

      if(newValue == null){
        console.log("not selected shape");
         this.drawCircle.enableCircleDrawing = false;
       this.enableMarkerDrawing = false;

        
      }else  if (newValue.type == "circle") {
       
       this.drawCircle.enableCircleDrawing = true;
       this.enableMarkerDrawing = false;
       this.processCircleDrawing(newValue);
      }else if (newValue.type == "marker"){

        this.enableMarkerDrawing = true;
         this.drawCircle.enableCircleDrawing = false;
        this.processMarkerDrawing(newValue);
         
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

