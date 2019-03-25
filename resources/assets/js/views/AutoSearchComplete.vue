<template>
  <div>
    <!-- search -->

       <div class="form-group row">
        <label
          for="search"
          class="col-md-1 col-form-label"
        >
          Search :
        </label>
        <div class="col-md-6">
          <div> <GmapAutocomplete
        class="tw-border-grey tw-border-2 tw-rounded tw-p-2 tw-w-64"
        @place_changed="setPlace"
        ref="autocomplete"
      ></GmapAutocomplete>

        <b-button @click="clearSearch">Clear Search</b-button>
      </div>
        </div>
      </div>
 
	 <!-- Address -->
      <div class="form-group row">
        <label
          for="Address"
          class="col-md-1 col-form-label"
        >
          Address:
        </label>
        <div class="col-md-5">
          <input
            type="text"
            class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey tw-italic"
            id="Address"
            v-model="form.address"
            placeholder=""
            disabled
          >
        </div>
      </div>

  </div>
</template>

<script>

export default {
 	props: ["circleFullAddress"],
 
  data() {
    return {
	form: {
        address: ""
      },
    };
  },
  methods: {
    retrieveAddressFromBackEnd(postalCode) {

	axios.get('/api/address/postal_code/'+postalCode+'.json')
		.then((res) => {	
			console.log(res.data)

			 this.$emit("get-search-data",res.data);
			 this.form.address = res.data.full_address;
			
		}).catch((error) => {
			console.log(error)
		}).then(() => {
	});
	},
    setPlace(place) { 

		if(place.id){ 
	   	var matchPostalCode;

		place.address_components.forEach(address_component => {
			if(address_component.types[0] == 'postal_code'){
				matchPostalCode = address_component.long_name;
			}
		});

		if(matchPostalCode){
			this.retrieveAddressFromBackEnd(matchPostalCode);
		}else{
			console.log("No Postal Code Found");
		}

		}
    },
    clearSearch() {
		     //clear search text
		this.form.address = ""; 
      this.$refs.autocomplete.$el.value = ""; 
		this.$emit("clear-Search");
      
    }
  },
  watch:{
      circleFullAddress(newValue) {
      this.form.address = newValue;
    }
  }
};
</script>