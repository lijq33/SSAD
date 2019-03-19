<template>
  <div class="container">
    <!-- search -->
    <div>Search : <GmapAutocomplete
        class="tw-border-grey tw-border-2 tw-rounded tw-p-2 tw-w-64"
        @place_changed="setPlace"
        ref="autocomplete"
      ></GmapAutocomplete>

      <b-button @click="clearSearch">Clear Search</b-button>
    </div>

    <!-- map components -->
    <basemap
      :_place="place"
      :clear-search="clearSearchData"
      :incident-data="incidentData"
    ></basemap>

</div>
</template>

<script>
import BaseMap from './BaseMap';

export default {
  name: "",
  components: {
    basemap: BaseMap,
  },

  data() {
    return {
      place: null,
      clearSearchData: false,
      incidentData:null
    };
  },
  methods: {
    handleReceiveIncidentData(value){

     this.incidentData = value;
      console.log(value)
      
    },
    setPlace(place) {
      this.place = place;
    },
    clearSearch() {

      //clear search text
      this.$refs.autocomplete.$el.value = '';

      if (this.clearSearchData) {
        this.clearSearchData = false;
         
      } else {
        this.clearSearchData = true;
      }  
    }
  }
};
</script>