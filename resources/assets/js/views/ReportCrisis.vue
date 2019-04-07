<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <flash :message = "message"></flash>

                <div class="card tw-mb-6">
                    <!-- Image, Name, Phone, Type of Crisis, Location, Description-->
                    <div class="card-header tw-text-grey-darker">
                        Submit a Crisis Now
                    </div>

                    <!-- NAME -->
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">
                                Full Name
                            </label>

                            <div class="col-md-6">
                                <input type = "text"
                                    id="name" 
                                    v-model = "form.name"
                                    class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey" 
                                    :class = "{ 'tw-border-red-light' : error['name'] != undefined}"
                                    placeholder = "John Doe"
                                    required autofocus
                                >

                                <div class = "tw-text-red" v-if = "error['name'] != undefined">
                                    <span> {{this.error['name'].toString()}} </span>   
                                </div>
                            </div>
                        </div>

                        <!-- Telephone Number -->
                        <div class="form-group row">
                            <label for="telephone_number" class="col-md-4 col-form-label text-md-right">
                                Telephone Number
                            </label>

                            <div class="col-md-6">
                                <input type = "text"
                                    id="telephone_number" 
                                    class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey" 
                                    :class = "{ 'tw-border-red-light' : error['telephoneNumber'] != undefined}"
                                    v-model = "form.telephoneNumber"
                                    placeholder = "9512 2314"
                                    required autofocus
                                >

                                <div class = "tw-text-red" v-if = "error['telephoneNumber'] != undefined">
                                    <span> {{this.error['telephoneNumber'].toString()}} </span>   
                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="form-group row">
                            <label for="location" class="col-md-4 col-form-label text-md-right">Location</label>

                            <div class="col-md-6">
                                <GmapAutocomplete
                                    class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey"
                                    @place_changed="setPlace"
                                    ref="autocomplete"
                                ></GmapAutocomplete>
                            </div>
                        </div>

                        <!--just a postal code helper without map reference -->
                        <div id="service-helper"></div>

                        <!-- Date -->
                        <div class = "form-group row">
                            <label for = "date" class = "col-md-4 col-form-label text-md-right">
                                Date
                            </label>
                            <div class = "col-md-6">
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
                            <label for = "time" class = "col-md-4 col-form-label text-md-right">
                                Time
                            </label>
                            <div class = "col-md-6">
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

                        <!-- Crisis Type -->
                        <div class = "form-group row">
                            <label for="crisis_type" class="col-md-4 col-form-label text-md-right">
                                Type of Crisis
                            </label>

                            <div class="col-md-6">
                                <b-form-select v-model = "form.crisisType" :options = "options"  
                                    :class = "{ 'tw-border-red-light' : error['crisisType'] != undefined}"
                                />

                                <div class = "tw-text-red" v-if = "error['crisisType'] != undefined">
                                    <span> {{this.error['crisisType'].toString()}} </span>   
                                </div> 
                            </div>
                        </div>

                        <!-- Radius -->
                        <div class = "form-group row" v-show = "isDengue">
                            <label for="radius" class="col-md-4 col-form-label text-md-right">
                                Area Affected (in meters)
                            </label>

                            <div class="col-md-6">
                                <input type = "text"
                                    id="radius" 
                                    class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey" 
                                    :class = "{ 'tw-border-red-light' : error['radius'] != undefined}"
                                    v-model = "form.radius"
                                    placeholder = "500"
                                    required autofocus
                                >
                                <div class = "tw-text-red" v-if = "error['radius'] != undefined">
                                    <span> {{this.error['radius'].toString()}} </span>   
                                </div> 
                            </div>
                        </div>

                        <!-- Description -->
                        <div class = "form-group row">
                            <label for="description" class="col-md-4 col-form-label text-md-right">
                                Description of The Crisis
                            </label>
                            
                            <div class="col-md-6">
                                <textarea v-model = "form.description"
                                    class = "form-control" rows = "3" style = "max-width:100%"
                                    :class = "{ 'tw-border-red-light' : error['description'] != undefined}"
                                />

                                <div class = "tw-text-red" v-if = "error['description'] != undefined">
                                    <span> {{this.error['description'].toString()}} </span>   
                                </div>
                            </div>
                        </div>

                        <!-- Image upload -->
                        <div class="form-group row">
                            <label for="crisis image" class="col-md-4 col-form-label text-md-right">
                                Upload an Image 
                            </label>
                    
                            <div v-if = "form.image === ''" class = "col-md-6">
                                <input accept = "image/*" type = "file" class = "upload-image-input tw-hidden" @change = "onFileSelected">
                                
                                <button class = "btn btn-primary" 
                                        @click = "uploadImage">
                                    Select An Image
                                </button>
                            </div>
                        
                            <div v-else class = "col-md-6">
                                <div class = "tw-h-24 tw-w-24 tw-mb-6 tw-overflow-hidden" style="width:400px; height:200px">
                                    <img :src = "form.image" class = "tw-w-full tw-h-full tw-flex tw-items-center tw-justify-center" />

                                    <div class = "tw-text-red" v-if = "error['image'] != undefined">
                                        <span> {{this.error['image'].toString()}} </span>   
                                    </div>
                                </div>
                                
                                <button class = "btn btn-primary" @click = "removeImage">
                                    Remove Image
                                </button>
                            </div>
                        </div>  

                        <div class="form-group row tw-my-6">
                            <div class="col-md-6 offset-md-4">
                                <div v-if = "!isLoading">
                                    <button type="submit" class="btn btn-primary" @click = "reportCrisis">
                                        Submit
                                    </button>

                                    <button type="submit" class="btn btn-primary" @click = "resetFields()">
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
</template>

<script>
    import 'vue-popperjs/dist/css/vue-popper.css';
    import Popper from 'vue-popperjs';
     import moment from 'moment';
    
    export default {
        name: "PublicCrisis",
        components: {
            'popper': Popper
        },

        data() {
            return {
                date: moment().format("DD/MM/YYYY"),
                error:'',

                 isLoading: false,
                  message: '',    

                options: [
                    { value: null, text: 'Please select an option' },
                    { value: 'Fire Outbreak', text: 'Fire Outbreak' },
                    { value: 'Dengue', text: 'Dengue' },
                    { value: 'Gas Leak', text: 'Gas Leak' }
                ],

                form: {
                    name:'',
                    telephoneNumber:'',
                    location:'',
                    description: '',
                    crisisType: null,
                    date: '',
                    time: '',
                    postalCode:'',      
                    image: '',
                    radius: '',
                    selectedFile: null,
                }
               
            }
        }, 

        computed:{
            isDengue(){
                return this.form.crisisType === 'Dengue';
            }
        },

        methods: {

            reportCrisis() {
                this.isLoading = true;
                this.message = ""; 
                this.error = [];
                const fd = new FormData();
                
                if(this.form.selectedFile !== null)
                    fd.append('image', this.form.selectedFile);
                fd.append('name', this.form.name);
                fd.append('telephoneNumber', this.form.telephoneNumber);
                fd.append('date', this.form.date);
                fd.append('time', this.form.time);
                fd.append('postalCode', this.form.postalCode);
                fd.append('location', this.form.location);
                fd.append('description', this.form.description);
                fd.append('crisisType', this.form.crisisType);
                if(this.form.crisisType == 'Dengue')
                    fd.append('radius', this.form.radius);
                else
                    fd.append('radius', 0);

                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                };

                // need to change to new address for public 
                axios.post('/api/report/crisis', fd, config)
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

                Object.keys(this.form).forEach(function(key,index) {
                    scope.form[key] = '';
                });

                this.$refs.autocomplete.$el.value = '';
                this.form.crisisType= null;
            },

             uploadImage(e) {
                document.querySelector('.upload-image-input').click();
            },

            removeImage(){
                this.form.image = '';
                this.form.selectedFile = null;
            },
            
            createImage(file) {
                let reader = new FileReader();

                reader.onload = (e) => {
                    this.form.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },

            onFileSelected (event) {
                let files = event.target.files || event.dataTransfer.files;

                if (!files.length)
                    return;

                this.form.selectedFile = files[0];
                
                this.createImage(this.form.selectedFile);
            },

            bestAddressMatch(geocoder, pos, serviceFormatedAddress, matchPostalCode){ 
                var scope = this;
                var foundBestMatch = false;
                
                geocoder.geocode({ location: pos }, function(results, status) {
                    if (status === "OK") {
                        results.forEach(element => {
                            if(element.formatted_address.includes(matchPostalCode)){
                                    foundBestMatch = true;
                                if(element.formatted_address.length > serviceFormatedAddress.length){
                                    scope.form.location = element.formatted_address; 
                                }else{
                                    scope.form.location = serviceFormatedAddress; 
                                }  
                            } 
                        });  

                        if(!foundBestMatch){
                            scope.form.location = serviceFormatedAddress; 
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
           
        },
        
    }
</script>