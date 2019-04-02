<template>
  <div>

    <!-- drawing tools extension -->
    <b-card no-body>

      <b-tabs card>

        <!--fire datatable-->
        <b-tab title="Fire">
          <v-client-table
            v-on:row-click="fireRowSelected"
            :columns="fireColumns"
            :data="fireData"
            :options="fireOptions"
          >

            <div
              slot="child_row"
              slot-scope="props"
            >
              The link to {{props}}

            </div>

          </v-client-table>
        </b-tab>

        <!--gas datatable-->
        <b-tab title="Gas Leakage">
          <v-client-table
            v-on:row-click="gasRowSelected"
            :columns="gasColumns"
            :data="gasData"
            :options="gasOptions"
          >

            <div
              slot="child_row"
              slot-scope="props"
            >
              The link to {{props}}

            </div>

          </v-client-table>
        </b-tab>

        <!--circle datatable-->
        <b-tab title="Circle">
          <v-client-table
            v-on:row-click="circleRowSelected"
            :columns="circleColumns"
            :data="circleData"
            :options="circleOptions"
          >

            <div
              slot="child_row"
              slot-scope="props"
            >
              The link to {{props}}

            </div>

          </v-client-table>
        </b-tab>

      </b-tabs>
    </b-card>

    <b-card no-body>

      <b-tabs card>

        <b-alert
          dismissible
          variant="success"
          @dismissed="dismissCountDown=0"
          @dismiss-count-down="countDownChanged"
        />


        <!-- create fire/gas markers -->
        
        <b-form @submit.prevent="submitCreateMarkers" v-if="enableCreateMarkerForm">
       
          <b-form-group id="createMarkerFormId" label="Create Crisis Type:" label-for="createMarkerFormId-3">
            <b-form-select
              id="createMarkerFormId-3"
              v-model="selectedMarkerOpion"
              :options="createCrisisTypeOptions"
              required
            ></b-form-select>
          </b-form-group> 
          <b-button type="submit" variant="primary" >Create</b-button>
          <b-button type="button" variant="secondary" @click="cancelCreateMarkers">Cancel</b-button>
        </b-form>


        
        <!-- create dengue form -->
        
        <b-form @submit.prevent="submitDengue" v-if="enableCreateDengueForm">
       
          <b-form-group id="createDengueFormId" label="Create Crisis Type:" label-for="createDengueFormId-3">
            <b-form-select
              id="createDengueFormId-3"
              v-model="selectedDengueOpion"
              :options="createDengueTypeOptions"
              required
            ></b-form-select>
          </b-form-group> 
          <b-button type="submit" variant="primary" >Create</b-button>
          <b-button type="button" variant="secondary" @click="cancelDengueShape">Cancel</b-button>
        </b-form>

        <!--fire edit-->
        <b-tab
          v-if="enableFireDrawing"
          title="Fire Edit"
        >
          <b-form>

            <b-form-group
              id="input-group-2"
              label="Event Name:"
              label-for="input-2"
            >
              <b-form-input
                id="input-2"
                v-model="fireProperty.name"
                required
                placeholder=""
                disabled
              ></b-form-input>
            </b-form-group>

            <!-- for updating crisis status -->
            <div class="form-group row">
              <label class="col-md-4 col-form-label text-md-right">
                Crisis Status :
              </label>
              <div class="col-md-6">
                <b-form-select
                  v-model="fireProperty.status"
                  :options="fireEditOptions"
                  class="form-control"
                   :disabled="isCrisisManager? false:true"
                />
              </div>
            </div>

            <!-- for updating crisis description -->
            <div class="form-group row">
              <label class="col-md-4 col-form-label text-md-right">
                Description :
              </label>
              <div class="col-md-6">
                <textarea
                  v-model="fireProperty.description"
                  class="form-control"
                  rows="3"
                  style="max-width:100%"
                  :disabled="isCrisisManager? false:true"
                >
                    </textarea>
              </div>
            </div>

            <div class="tw-flex tw-justify-end tw-m-4 tw-border-t tw-border-grey tw-pt-4">

              <button v-if="isCrisisManager"
                class="tw-ml-2 btn btn-primary"
                @click="updateCrisis()"
              >Update</button>
            </div>

          </b-form>

        </b-tab>

        <!--gas edit-->
        <b-tab
          v-if="enableGasDrawing"
          title="Gas Leakage Edit"
        >
          <b-form>

            <b-form-group
              id="input-group-3"
              label="Event Name:"
              label-for="input-3"
            >
              <b-form-input
                id="input-3"
                v-model="gasProperty.name"
                required
                placeholder=""
                disabled
              ></b-form-input>
            </b-form-group>

            <!-- for updating crisis status -->
            <div class="form-group row">
              <label class="col-md-4 col-form-label text-md-right">
                Crisis Status :
              </label>
              <div class="col-md-6">
                <b-form-select
                  v-model="gasProperty.status"
                  :options="gasEditOptions"
                  class="form-control"
                  :disabled="isCrisisManager? false:true"
                />
              </div>
            </div>

            <!-- for updating crisis description -->
            <div class="form-group row">
              <label class="col-md-4 col-form-label text-md-right">
                Description :
              </label>
              <div class="col-md-6">
                <textarea
                  v-model="gasProperty.description"
                  class="form-control"
                  rows="3"
                  style="max-width:100%"
                  :disabled="isCrisisManager? false:true"
                >
                    </textarea>
              </div>
            </div>

            <div class="tw-flex tw-justify-end tw-m-4 tw-border-t tw-border-grey tw-pt-4">

              <button v-if="isCrisisManager"
                class="tw-ml-2 btn btn-primary"
                @click="updateGasCrisis()"
              >Update</button>
            </div>

          </b-form>

        </b-tab>

        <!--circle edit-->
        <b-tab
          v-if="enableCircleDrawing"
          title="Circle"
        >
          <vue-slider
            v-model="circleProperty.radius"
            :min="minValue"
            :max="maxValue"
            :interval="1000"
            :clickable="false"
            @change="changeCircleRadius"
          />{{this.circleProperty.radius}} metres

          <div class="form__field">
            <div class="form__label">
              <strong>Please choose a color:</strong>
            </div>
            <div class="form__input">
              <swatches
                v-model="circleProperty.fillColor"
                popover-to="right"
                @input="changeCircleColor"
              ></swatches>
            </div>
          </div>
          <b-button
            variant="primary"
            @click="saveCircleData"
          >Save</b-button>
          <b-button
            @click="undoCircleData"
          >Undo</b-button>
        </b-tab>
      </b-tabs>
    </b-card>

        <!-- create markers modal -->
        <b-modal ref="my-modal" hide-footer no-close-on-backdrop no-close-on-esc title="Create New Crisis" size="xl">
       
          <new-crisis :geo-code-address="geoCodeAddress" :selected-crisis="selectedMarkerOpion" /> 
         </b-modal>

 

  </div>
</template>

<script>
import Vue from "vue";
import VueSlider from "vue-slider-component";
import "vue-slider-component/theme/antd.css";
import Swatches from "vue-swatches";
import NewCrisis from './NewCrisis';
import "vue-swatches/dist/vue-swatches.min.css";
import { ServerTable, ClientTable, Event } from "vue-tables-2";
// let tableOptions =[[theme = 'bootstrap3']]
// Vue.use(ClientTable, [useVuex = false], [theme = 'bootstrap3']);
Vue.use(ClientTable, {}, false, "bootstrap4");
export default {
  props: [
    "enableDrawing",
    "circleDrawingCenter",
    "circleDrawingRadius",
    "selectedShape",
    "markerDrawing",
    "baseMapAllShape",
    "fireMarkers",
    "gasLeakMarkers",
    "denguePolygon"
  ],

  mounted() {
    this.getCrisis();

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
  destroyed() {
    console.log("destory circle compoent");
  },
  components: {
    VueSlider,
    Swatches,
    'new-crisis':NewCrisis
  },
  //drawCircle:{enableCircleDrawing:false,marker:null,circle:null,cirleDistanceValue: 0,circleFillColor:'#E84B3C'},
  data() {
    return {
      geoCodeAddress:null,
       modalShow: false,
      enableCreateMarkerForm:false,
      selectedMarkerOpion:null,
      createCrisisTypeOptions: [{ text: 'Select One', value: null }, 'Fire Outbreak', 'Gas Leak'],
      myClass: "backColor",

      //circle form
       enableCreateDengueForm:false,
      selectedDengueOpion:null,
      createDengueTypeOptions: [{ text: 'Select One', value: null }, 'Dengue'],

      //circle
      circleColumns: ["id", "radius", "fillColor"],
      circleData: [],
      tempCircle: null,
      enableCircleDrawing: false,
      circleOptions: {
        headings: {}
      },
      minValue: 0,
      maxValue: 21000,
      circleProperty: {
        id: "",
        type: "",
        visible: "",
        zIndex: "",
        draggable: "",
        editable: "",
        fillColor: "",
        fillOpacity: "",
        radius: "",
        strokeColor: "",
        strokeWeight: "",
        center: "",
        postData: false
      },
      //fire
      fireColumns: ["id", "postal_code", "name"],
      fireData: [],
      tempFireGas: null,
      enableFireDrawing: false,
      fireOptions: {},
      fireEditOptions: [
        { value: "registered", text: "registered" },
        { value: "attending", text: "attending" },
        { value: "resolved", text: "resolved" }
      ],
      fireProperty: {
        id: "",
        name: "",
        status: "",
        description: ""
      },

      //gas leakage
      gasColumns: ["id", "postal_code", "name"],
      gasData: [],
      enableGasDrawing: false,
      gasOptions: {
        headings: {}
      },
      gasEditOptions: [
        { value: "registered", text: "registered" },
        { value: "attending", text: "attending" },
        { value: "resolved", text: "resolved" }
      ],
      gasProperty: {
        id: "",
        name: "",
        status: "",
        description: ""
      },
      tabs: [],
      tabCounter: 0,
      dismissSecs: 5,
      dismissCountDown: 0,
      localSelectedShape:null
    };
  },

  methods: {
    cancelDengueShape(){

        this.enableCreateDengueForm = false;
     this.localSelectedShape=null;
     this.selectedMarkerOpion=null;

      this.$emit('cancel-drawing-creation',this.localSelectedShape);

    },
    submitDengue(){
      console.log("submt dengue")
    },
    undoCircleData(){
      console.log(this.tempCircle)

      var scope = this;
      //get id from tempcircle and retrieve original value from circleData
      var circleId = this.tempCircle.id;

        this.circleData.forEach((element, index) => {
         

          if(element.id == circleId){
             Object.assign(scope.tempCircle, element);
               Object.assign(scope.circleProperty, element);
             
             scope.tempCircle["db_data"] ={id:circleId};
             
           this.$emit("get-updated-circle-drawing", scope.tempCircle);
          }

        });
 
      
      
      
    },

    showModal() {
        this.$refs['my-modal'].show()
      },
      hideModal() {
        this.$refs['my-modal'].hide()
      },

    submitCreateMarkers(){
       this.showModal();

    },
    cancelCreateMarkers(){

      this.enableCreateMarkerForm = false;
     this.localSelectedShape=null;
     this.selectedMarkerOpion=null;

      this.$emit('cancel-drawing-creation',this.localSelectedShape);
    },
    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown;
    },

    updateGasCrisis() {
      axios
        .post("/api/crisis/" + this.gasProperty.id, {
          status: this.gasProperty.status,
          description: this.gasProperty.description
        })
        .then(res => {
          this.dismissCountDown = this.dismissSecs;
          console.log("success update fire!");
        })
        .catch(error => {
          this.error = error.response;
        });
    },

    updateCrisis() {
      axios
        .post("/api/crisis/" + this.fireProperty.id, {
          status: this.fireProperty.status,
          description: this.fireProperty.description
        })
        .then(res => {
          this.dismissCountDown = this.dismissSecs;
          console.log("success update fire!");
        })
        .catch(error => {
          this.error = error.response;
        });
    },

    getCrisis() {
      var scope = this;
      axios
        .get("/api/crisis")
        .then(res => {
          // console.log(res.data)
          //scope.fireData = res.data.crises;
        })
        .catch(error => {
          console.log(error);
          //this.error = error.response.data.errors;
        });
    },

    gasRowSelected(rowObject) {
      //this.tempCircle = this.circleProperty;

      Object.assign(this.gasProperty, rowObject.row);

      //disable other editing
      this.enableCircleDrawing = false;
      this.enableFireDrawing = false;

      //enable editing
      this.enableGasDrawing = true;

      this.$emit("get-updated-gas-drawing", rowObject.row);
    },
    fireRowSelected(rowObject) {
      //this.tempCircle = this.circleProperty;

      Object.assign(this.fireProperty, rowObject.row);

      //disable other editing
      this.enableCircleDrawing = false;
      this.enableGasDrawing = false;

      //enable editing
      this.enableFireDrawing = true;

      this.$emit("get-updated-fire-drawing", rowObject.row);
      //return rowObject.active?'activeRow':'';
    },
    circleRowSelected(rowObject) {
      //update data circle var
      //Object.assign(target, source);
      console.log(rowObject)

    //temp circle obj
      this.tempCircle = rowObject;
      Object.assign(this.circleProperty, rowObject.row);
      //tempCircle["db_data"] = rowObject.row.db_data;
      //enable editing
      this.enableCircleDrawing = true;
      this.enableGasDrawing = false;
       this.enableFireDrawing = false;

      //select map circle shape

      //console.log(emitObjrowObjectct);
      this.$emit("get-updated-circle-drawing", rowObject.row);
    },
    newTab() {
      if (this.localEnableDrawing) {
        this.this.localEnableDrawing = false;
      } else {
        this.localEnableDrawing = true;
      }
    },
    saveCircleData() {
      this.drawCircle.postData = true;

      this.passDataToBaseMap();
      console.log(this.drawCircle);
    },
    processMarkerDrawing(marker) {
      console.log(marker);
      this.markerGeoJson.geometry.coordinates = [
        marker.position.lng(),
        marker.position.lat()
      ];
      this.markerUID = marker.closure_uid_342722430;

      var found = false;

      this.items.forEach((element, index) => {
        if (element.id === marker.closure_uid_342722430) {
          console.log(element.id);

          found = true;
        }
      });

      if (found) {
        console.log("1212");
      } else {
        console.log("add marker to item");

        //add to table
        this.items.push({
          isActive: false,
          id: marker.closure_uid_342722430,
          event: marker.closure_uid_342722430,
          _showDetails: true
        });
      }
    },
    processCircleDrawing(circle) {
      console.log(circle);

      this.drawCircle.circleRadiusValue = circle.radius.toFixed(0);
      this.drawCircle.center.lat = circle.center.lat();
      this.drawCircle.center.lng = circle.center.lng();
      this.drawCircle.circleFillColor = circle.fillColor;
    },
    closeTab(x) {
      for (let i = 0; i < this.tabs.length; i++) {
        if (this.tabs[i] === x) {
          this.tabs.splice(i, 1);
        }
      }
    },
    passDataToBaseMap() {
      //this.$emit("get-updated-drawing", this.drawCircle);
    },
    changeCircleColor(color) {
     this.circleProperty.fillColor = color;
      this.circleProperty.strokeColor = color;
     //take temp circle obj
       Object.assign(this.tempCircle, this.circleProperty);
        this.$emit("get-updated-circle-drawing", this.tempCircle);
    },
    changeCircleRadius(radius) {
      
      this.circleProperty.radius = radius;
       Object.assign(this.tempCircle, this.circleProperty);
      this.$emit("get-updated-circle-drawing", this.tempCircle);
    }
  },
  watch: {
    selectedMarkerOpion(){
      if(this.selectedMarkerOpion == "Fire Outbreak"){
        this.$emit('change-marker-icon','https://cdn0.iconfinder.com/data/icons/fatcow/32/fire.png')
      }else{
        this.$emit('change-marker-icon','https://images.vexels.com/media/users/3/150012/isolated/preview/bf8475104937ca2ee44090829f4efa3a-small-gas-cylinder-icon-by-vexels.png')
      }
    },
    denguePolygon(dengueDrawing){
       var scope = this;
      console.log(dengueDrawing);
      if (dengueDrawing.length == 0) {
        scope.circleData = [];
        scope.enableCircleDrawing = false;
      } else {
        dengueDrawing.forEach((element, index) => {
          scope.circleData.push(element.db_data);
        });
      }
    },
    gasLeakMarkers(marker) {
      var scope = this;
      console.log(marker);
      if (marker.length == 0) {
        scope.gasData = [];
        scope.enableGasDrawing = false;
      } else {
        marker.forEach((element, index) => {
          scope.gasData.push(element.db_data);
        });
      }
    },
    fireMarkers(marker) {
      var scope = this;

      if (marker.length == 0) {
        scope.fireData = [];
        scope.enableFireDrawing = false;
      } else {
        marker.forEach((element, index) => {
          scope.fireData.push(element.db_data);
        });
      }

      //this.fireData = marker.crises;
    },
    baseMapAllShape(allShape) {
      //console.log(allShape);

      //take the last shape all add to local
      var lastShape = allShape[allShape.length - 1];

      //circle shape
      if (lastShape.type === "circle") {
        var temp = {
          id: lastShape.overlay.id,
          type: lastShape.overlay.type,
          visible: lastShape.overlay.visible,
          zIndex: lastShape.overlay.zIndex,
          draggable: lastShape.overlay.draggable,
          editable: lastShape.overlay.editable,
          fillColor: lastShape.overlay.fillColor,
          fillOpacity: lastShape.overlay.fillOpacity,
          radius: lastShape.overlay.radius,
          strokeColor: lastShape.overlay.strokeColor,
          strokeWeight: lastShape.overlay.strokeColor,
          center: lastShape.overlay.center
        };

        this.circleData.push(temp);

        console.log(lastShape.overlay);
      }
    },
    markerDrawing(newValue) {
      this.markerGeoJson.geometry.coordinates = [
        newValue.lng(),
        newValue.lat()
      ];
    },
    selectedShape(newValue) {

        this.localSelectedShape = newValue;

      //receive shape
      //if is circle
      console.log("nevalue selected shape")
      if(newValue == null){
        console.log("not selected shape");
        // this.drawCircle.enableCircleDrawing = false;
      // this.enableMarkerDrawing = false;
      }else  if (newValue.type == "circle") {
        this.enableCreateDengueForm = true;


      Object.assign(this.circleProperty, newValue.overlay);

      //  var temp = {
      //     id: lastShape.overlay.id,
      //     type: lastShape.overlay.type,
      //     visible: lastShape.overlay.visible,
      //     zIndex: lastShape.overlay.zIndex,
      //     draggable: lastShape.overlay.draggable,
      //     editable: lastShape.overlay.editable,
      //     fillColor: lastShape.overlay.fillColor,
      //     fillOpacity: lastShape.overlay.fillOpacity,
      //     radius: lastShape.overlay.radius,
      //     strokeColor: lastShape.overlay.strokeColor,
      //     strokeWeight: lastShape.overlay.strokeColor,
      //     center: lastShape.overlay.center
      //   };

      // this.enableMarkerDrawing = false;
       //this.processCircleDrawing(newValue);
      }else if (newValue.type == "marker"){
        this.enableCreateMarkerForm = true;
        this.geoCodeAddress = newValue
        console.log(newValue);
 
      }
    },
    circleDrawingRadius(newValue, oldValue) {
      console.log("radius changed");
      this.drawCircle.circleRadiusValue = newValue.toFixed(0);
    },
    circleDrawingCenter(newValue, oldValue) {
      this.drawCircle.center.lat = newValue.lat();
      this.drawCircle.center.lng = newValue.lng();
    },
    enableDrawing(newValue) {
      console.log(newValue);
    }
  },
 computed: {
            currentUser() {
                return this.$store.getters.currentUser
            },
        
            isCallCenterOperator(){
                if (!this.currentUser)
                    return false
                return this.currentUser.roles == 'CallCenterOperator';
            },
                        
            isCrisisManager(){
                if (!this.currentUser)
                    return false
                return this.currentUser.roles == 'CrisisManager';
            },

            isAccountManager(){
                if (!this.currentUser)
                    return false
                return this.currentUser.roles == 'AccountManager';
            },
        }
};
</script>

<style>
.backColorMark {
  background-color: dodgerblue;
}

.backColor {
  background-color: white;
}
/* .VuePagination {
  text-align: center;
}

.vue-title {
  text-align: center;
  margin-bottom: 10px;
}

.vue-pagination-ad {
  text-align: center;
}

.glyphicon.glyphicon-eye-open {
  width: 16px;
  display: block;
  margin: 0 auto;
}

th:nth-child(3) {
  text-align: center;
}

.VueTables__child-row-toggler {
  width: 16px;
  height: 16px;
  line-height: 16px;
  display: block;
  margin: auto;
  text-align: center;
}

.VueTables__child-row-toggler--closed::before {
  content: "+";
}

.VueTables__child-row-toggler--open::before {
  content: "-";
} */
</style>