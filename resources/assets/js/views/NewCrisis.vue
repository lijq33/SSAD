<template>
    <div class = "container-fluid" id="myForm">
        <div class = "row">
            <div class = "col-xl">
                <div class = "card">

                    <flash :message = "message"></flash>

                    <div class = "card-header tw-text-grey-darker">
                        Register a New Crisis
                    </div>

                    <!-- Personal Particular & Crisis Info -->
                    <div class = "card-body">
                        <div class = "form-group row">
                            <div class = "col-md-6">

                                <div class = "card">
                                    <div class = "card-body">
                                        <h5 class = "card-title">Personal Particulars</h5>

                                        <!-- Name -->
                                        <div class = "form-group row">
                                            <label for = "name" class = "col-md-4 col-form-label">
                                                Name:
                                                <popper trigger = "hover" :options = "{placement: 'bottom'}">
                                                    <div class = "popper tw-font-hairline tw-text-grey-dark">
                                                        Name of Crisis Reporter
                                                    </div>
                                                    <button slot="reference">   
                                                        <i class = "fas fa-question-circle tw-text-grey-dark tw-cursor-pointer"></i>
                                                    </button>
                                                </popper> 
                                            </label>

                                            <div class = "col-md-6">
                                                <input type = "text"
                                                    class = "tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey tw-italic"
                                                    id = "name" v-model = "form.name"
                                                    :class = "{ 'tw-border-red-light' : error['name'] != undefined}"
                                                    placeholder = "John Doe"
                                                    required autofocus
                                                >
                                                <div class = "tw-text-red" v-if = "error['name'] != undefined">
                                                    <span> {{this.error['name'].toString()}} </span>   
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Contact -->
                                        <div class = "form-group row">
                                            <label for = "Mobile Number" class = "col-md-4 col-form-label">
                                                Mobile Number:
                                                <popper trigger = "hover" :options = "{placement: 'bottom'}">
                                                    <div class = "popper tw-font-hairline tw-text-grey-dark">
                                                        Phone/Mobile No.
                                                    </div>
                                                    <button slot="reference">   
                                                        <i class = "fas fa-question-circle tw-text-grey-dark tw-cursor-pointer"></i>
                                                    </button>
                                                </popper> 
                                            </label>

                                            <div class = "col-md-6">
                                                <input type = "text"
                                                    class = "tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey ty-italic" 
                                                    id = "Mobile Number" v-model = "form.telephoneNumber"
                                                    :class = "{ 'tw-border-red-light' : error['telephoneNumber'] != undefined}"
                                                    placeholder = "98765654"
                                                    required autofocus
                                                >
                                                <div class = "tw-text-red" v-if = "error['telephoneNumber'] != undefined">
                                                    <span> {{this.error['telephoneNumber'].toString()}} </span>   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Assistance -->
                            <div class = "col-md-6">
                                <div class = "card" style="height:194px">
                                    <div class = "card-body">
                                        <h5 class = "card-title">Assistance Required:</h5>

                                        <div class = "form-check">
                                            <input class = "form-check-input" type = "checkbox" id = "Emergency Ambulance" v-model = "form.assistanceRequired" value = "1">
                                            <label class = "form-check-label" for = "Emergency Ambulance">Emergency Ambulance</label>
                                        </div>
                                        <div class = "form-check">
                                            <input class = "form-check-input" type = "checkbox" id = "Rescue" v-model = "form.assistanceRequired" value = "2">
                                            <label class = "form-check-label" for = "Rescue">Rescue &amp; Evac</label>
                                        </div>
                                        <div class = "form-check">
                                            <input class = "form-check-input" type = "checkbox" id = "Fire-Fighting" v-model = "form.assistanceRequired" value = "3">
                                            <label class = "form-check-label" for = "Fire-Fighting">Fire-Fighting</label>
                                        </div>
                                        <div class = "form-check">
                                            <input class = "form-check-input" type = "checkbox" id = "GasLeak" v-model = "form.assistanceRequired" value = "4">
                                            <label class = "form-check-label" for = "GasLeak">Gas Leak Control</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location and Crisis Type row -->
                        <div class = "form-group row">
                            <div class = "col-md-6">
                                <div class = "card" style = "height:304px">
                                    <div class = "card-body">
                                        <h5 class = "card-title">Location</h5>
                                        <div class = "form-group row">
                                            <label for = "" class = "col-md-4 col-form-label">
                                                Location:
                                            </label>
                                            <div class = "col-md-6">
                                                <div v-if= "form.crisisType == 'Dengue'" >
                                                    <!-- dengue -->
                                                    <button class = "btn btn-primary" style = "margin-right:5px;" @click = "displayModal">
                                                        Open Map
                                                    </button>
                                                </div>
                                                <div v-else>
                                                    <GmapAutocomplete
                                                        class="tw-border-grey tw-border-2 tw-rounded tw-p-2 tw-w-64"
                                                        @place_changed="setPlace"
                                                        ref="autocomplete"
                                                    ></GmapAutocomplete>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Date -->
                                        <div class = "form-group row">
                                            <label for = "date" class = "col-md-4 col-form-label">
                                                Date:
                                            </label>
                                            <div class = "tw-w-1/2">
                                                <date-picker v-model = "form.date" required = "required"
                                                    :class = "{ 'tw-border-red-light' : error['date'] != undefined}"                                                
                                                    :date = "date"
                                                > 
                                                </date-picker>
                                                <div class = "tw-text-red" v-if = "error['date'] != undefined">
                                                    <span> {{this.error['date'].toString()}} </span>   
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Time -->
                                        <div class = "form-group row">
                                            <label for = "time" class = "col-md-4 col-form-label">
                                                Time:
                                            </label>
                                            <div class = "tw-w-1/2">
                                                <time-picker id = "time" v-model = "form.time" required = "required" 
                                                    :class = "{ 'tw-border-red-light' : error['time'] != undefined}"
                                                    :useContainer = "true"
                                                > 
                                                </time-picker>
                                                <div class = "tw-text-red" v-if = "error['time'] != undefined">
                                                    <span> {{this.error['time'].toString()}} </span>   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end of location column -->

                            <!-- beginning of crisis type column -->
                            <div class = "col-md-6">
                                
                                <!-- Description -->
                                <div class = "card" style="height:304px">
                                    <div class = "card-body">
                                        <h5 class = "card-title"> Description:</h5>
                                        <textarea v-model = "form.description"
                                            class = "form-control" rows = "3" style = "max-width:100%; height:80%"
                                            :class = "{ 'tw-border-red-light' : error['description'] != undefined}"
                                        />
                                        <div class = "tw-text-red" v-if = "error['description'] != undefined">
                                            <span> {{this.error['description'].toString()}} </span>   
                                        </div>
                                    </div>
                                </div>
                        
                            </div>
                        </div>

                        <div class = "form-group row">
                            <div class = "col-md-6">
                                 <div class = "card">
                                    <div class = "card-body">
                                        <h5 class = "card-title"> Crisis Image </h5>
                                        <div class="form-group row">
                                        <label class="col-md-7 col-form-label">
                                            Please upload an image of the crisis:
                                            </label>
                                    <div v-if = "image === ''" >
                                        <input accept = "image/*" type = "file" class = "upload-image-input tw-hidden" @change = "onFileSelected" >

                                        <button class = "btn btn-primary" 
                                            @click = "uploadImage">
                                            Select An Image
                                        </button>
                                    </div>
                                    
                                    <div v-else class = "col-md-6">
                                        <div class = "tw-h-24 tw-w-24 tw-mb-6 tw-rounded-full tw-overflow-hidden" style="width:500px; height:300px">
                                            <img :src = "image" class = "tw-w-full tw-h-full tw-flex tw-items-center tw-justify-center" />
                                        </div>

                                        <button class = "btn btn-primary" 
                                            @click = "removeImage">
                                            Choose Another Image
                                        </button>
                                    </div>
                                        </div>
                                    </div>
                                 </div>
                            </div>
                             <!-- Crisis Type dropdown -->
                              <div class = "col-md-6">
                                <div class = "card tw-mb-6" style="height:130px">
                                    <div class = "card-body">
                                        <h5 class = "card-title"> Type of Crisis:</h5>
                                        <b-form-select v-model = "form.crisisType" :options = "options" 
                                            :class = "{ 'tw-border-red-light' : error['crisisType'] != undefined}"
                                        />
                                        <div class = "tw-text-red" v-if = "error['crisisType'] != undefined">
                                            <span> {{this.error['crisisType'].toString()}} </span>   
                                        </div> 
                                    </div>  
                                </div>
                                </div>
                        </div>


                        <!-- button group -->
                        <div class = "form-group row tw-my-6">
                            <div class = "tw-w-full">
                                <div class = "tw-flex tw-justify-center">
                                    <div v-if = "!isLoading">
                                        <button type = "submit" class = "btn btn-primary" style = "margin-right:5px;" @click = "submitCrisis">
                                            Submit
                                        </button>
                                        <button type = "submit" class = "btn btn-primary" @click = "resetFields()">
                                            Reset
                                        </button>
                                    </div>
                                    <div v-else>
                                        <img src = "/assets/img/loader.gif" alt = "Loading...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--just a postal code helper without map reference -->
        <div id="service-helper"></div>

        <!-- dengue map -->
        	<div>
			<map-modal
				:dengue = "dengue"
				:show-modal = "showModal"
				@hideModal = "hideModal"
			></map-modal>
		</div>

    </div>
</template>

<script>
    import 'vue-popperjs/dist/css/vue-popper.css';
    import Popper from 'vue-popperjs';
    import moment from 'moment';
    import MapModal from '../components/MapModal.vue';
    
    export default {
        name: "NewCrisis",

        components: {
            'popper': Popper,
			'map-modal': MapModal,
        },
        
        data() {
            return{
                date: moment().format("DD/MM/YYYY"),

                options: [
                    { value: null, text: 'Please select an option' },
                    { value: 'Fire Outbreak', text: 'Fire Outbreak' },
                    { value: 'Dengue', text: 'Dengue' },
                    { value: 'Gas Leak', text: 'Gas Leak' }
                ],
                message: '',    
                isLoading: false,
                error: [],

                showModal: null,
                dengue: null,
                
                image: '',

                //data to be submitted
                selectedFile: null,
                form:{
                     name:'',
                    telephoneNumber:'',
                    date:'',
                    time:'',
                    address:'',
                    postalCode:'',
                    description: '',
                    assistanceRequired: [],
                    crisisType: null,
                }
            }
        },

        methods: {
            bestAddressMatch(geocoder, pos, serviceFormatedAddress,matchPostalCode){ 
                var scope = this;
                var foundBestMatch = false;
                
                geocoder.geocode({ location: pos }, function(results, status) {
                    if (status === "OK") {
                        results.forEach(element => {
                            if(element.formatted_address.includes(matchPostalCode)){
                                    foundBestMatch = true;
                                if(element.formatted_address.length > serviceFormatedAddress.length){
                                    scope.form.address = element.formatted_address; 
                                }else{
                                    scope.form.address = serviceFormatedAddress; 
                                }  
                            } 
                        });  

                        if(!foundBestMatch){
                            scope.form.address = serviceFormatedAddress; 
                        }

                    }
                });
            },

            setPlace(place) {  
                var scope = this;

                if (place.id) { 
                    var matchPostalCode;
                    place.address_components.forEach(address_component => {
                        if(address_component.types[0] == 'postal_code'){
                            matchPostalCode = address_component.long_name;
                            scope.form.postalCode = address_component.short_name;
                        }
                    });

                    var pos = {
                        lat: place.geometry.location.lat(),
                        lng: place.geometry.location.lng()
                    }; 

                    var service = new google.maps.places.PlacesService($('#service-helper').get(0)); 

                    service.getDetails({ placeId: place.place_id }, function(place, status) {
                        if (status === google.maps.places.PlacesServiceStatus.OK) {
                            scope.bestAddressMatch(new google.maps.Geocoder(), pos, place.formatted_address, matchPostalCode); 
                        }
                    });
                }
            },

            submitCrisis() {
                // this.isLoading = true;
                this.message = "";                
                this.error = [];

                const fd = new FormData();
                
                fd.append('image', this.selectedFile);
                fd.append('name', this.form.name);
                fd.append('telephoneNumber', this.form.telephoneNumber);
                fd.append('date', this.form.date);
                fd.append('time', this.form.time);
                fd.append('address', this.form.address);
                fd.append('postalCode', this.form.postalCode);
                fd.append('description', this.form.description);
                fd.append('assistanceRequired', this.form.assistanceRequired);
                fd.append('crisisType', this.form.crisisType);

                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                };

                axios.post('/api/crisis', fd, config)
                .then(response => {
                    this.message = response.data.message;
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                    this.isLoading = false;
                    this.resetFields();
                })
                .catch((error) => {
                    this.error = error.response.data.errors;
                    this.isLoading = false;
                });
            },

            resetFields() {
                var scope = this; 
                
                this.$refs.autocomplete.$el.value = '';

                Object.keys(this.form).forEach(function(key,index) {
                    scope.form[key] = '';
                });

                this.form.crisisType= null;
                this.form.assistanceRequired= [];
            },

            displayModal() {
				this.showModal = true;
			},

			hideModal() {
				this.showModal = false;
            },
            
            //Images related methods
            uploadImage(e) {
                document.querySelector('.upload-image-input').click();
            },

            removeImage(){
                this.image = '';
                this.selectedFile = null;
            },
            
            createImage(file) {
                let reader = new FileReader();

                reader.onload = (e) => {
                    this.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },


            onFileSelected (event) {
                let files = event.target.files || event.dataTransfer.files;

                if (!files.length)
                    return;

                this.selectedFile = files[0];
                
                this.createImage(this.selectedFile);
            }

        },
    }
</script>
                
       