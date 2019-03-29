<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card tw-mb-6">
                    <!-- Image, Name, Phone, Type of Crisis, Location, Description-->
                    <div class="card-header tw-text-grey-darker">Submit a Crisis Now</div>

                    <div class="card-body">

                        <!-- NAME -->
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">
                                Full Name
                            </label>

                            <div class="col-md-6">
                                <input type = "text"
                                    id="name" 
                                    v-model = "form.name"
                                    class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey" 
                                    placeholder = "John Doe"
                                    required autofocus>
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
                                    v-model = "form.telephoneNumber"
                                    placeholder = "9512 2314"
                                    required autofocus>
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
                                                                            
                                :date = "date"
                            > 
                            </date-picker>
                            
                        </div>
                    </div>

                    <!-- Time -->
                    <div class = "form-group row">
                        <label for = "time" class = "col-md-4 col-form-label text-md-right">
                            Time
                        </label>
                        <div class = "col-md-6">
                            <time-picker id = "time" v-model = "form.time" required = "required" 
                              
                                :useContainer = "true"
                            > 
                            </time-picker>
                            
                        </div>
                    </div>

                        <!-- beginning of crisis type column -->
                            <div class = "form-group row">
                                <!-- Crisis Type dropdown -->
                                 <label for="crisis_type" class="col-md-4 col-form-label text-md-right">
                                Type of Crisis
                            </label>
                             <div class="col-md-6">
                                  <b-form-select v-model = "form.crisisType" :options = "options"  />
                                 </div>
                            </div>

                        <!-- Description -->
                            <div class = "form-group row">
                                <!-- Crisis Type dropdown -->
                                 <label for="description" class="col-md-4 col-form-label text-md-right">
                               Description of The Event
                            </label>
                            <div class="col-md-6">
                                 <textarea v-model = "form.description"
                                            class = "form-control" rows = "3" style = "max-width:100%"
                                        />

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
                    <div class = "tw-h-24 tw-w-24 tw-mb-6 tw-rounded-full tw-overflow-hidden" style="width:400px; height:200px">
                        <img :src = "form.image" class = "tw-w-full tw-h-full tw-flex tw-items-center tw-justify-center" />
                    </div>
                    <button class = "btn btn-primary" @click = "removeImage">
                        Upload Another Image Instead
                    </button>
                </div>

            </div>  

             <div class="form-group row tw-my-6">
            <div class="col-md-6 offset-md-4">
                <div v-if = "!isLoading">
                    <button type="submit" class="btn btn-primary" @click = "submitPubCrisis">
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

                 isLoading: false,

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

                    image: '',
                    selectedFile: null,
                    
                    lat: '',
                    lng: '',

                    geocode:'',
                }
               
            }
        }, 

        methods: {

            submitPubCrisis() {
                this.isLoading = true;
                const fd = new FormData();
                
                fd.append('image', this.form.selectedFile);
                fd.append('name', this.form.name);
                fd.append('telephoneNumber', this.form.telephoneNumber);
                fd.append('date', this.form.date);
                fd.append('time', this.form.time);
                fd.append('location', this.form.location);
                fd.append('description', this.form.description);
                fd.append('crisisType', this.form.crisisType);

                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                };

                // need to change to new address for public 
                axios.post('/api/pubcrisis', fd, config)
                .then(response => {
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                    this.isLoading = false;
                    this.resetFields();
                })
                .catch((error) => {
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

             bestAddressMatch(geocoder, pos, serviceFormatedAddress){ 
                var scope = this;
                var foundBestMatch = false;
                
                geocoder.geocode({ location: pos }, function(results, status) {
                    if (status === "OK") {
                        results.forEach(element => {
                            if(element.formatted_address.includes(serviceFormatedAddress)){
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
                    scope.form.lat = place.geometry.location.lat();
                    scope.form.lng = place.geometry.location.lng();

                    var pos = {
                        lat: place.geometry.location.lat(),
                        lng: place.geometry.location.lng()
                    }; 

                    var service = new google.maps.places.PlacesService($('#service-helper').get(0)); 

                    service.getDetails({ placeId: place.place_id }, function(place, status) {
                        if (status === google.maps.places.PlacesServiceStatus.OK) {
                            scope.bestAddressMatch(new google.maps.Geocoder(), pos, place.formatted_address); 
                        }
                    });
                }
            },
           
        },
        
    }
</script>