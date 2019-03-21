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

    <!--toggle map -->
    <toggle-map  @get-toggle-data="handleToggleData"/>

    <!-- map components -->
    <basemap
      :_place="place"
      :clear-search="clearSearchData"
      :toggle-data="toggleData"
    ></basemap>

</div>
</template>

<script>
import BaseMap from './BaseMap';
import ToggleMap from './ToggleCrisisMap';

export default {
  name: "",
  components: {
    basemap: BaseMap,
    toggleMap:ToggleMap
  },

  data() {
    return {
      place: null,
      clearSearchData: false,
      toggleData:null
    };
  },
  methods: {
    handleToggleData(toggleData){
      this.toggleData = toggleData;
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