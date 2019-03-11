<template>
    <div class = "container-fluid">
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
                                <div class = "card">
                                    <div class = "card-body">
                                        <h5 class = "card-title">Assistance Required:</h5>

                                        <div class = "form-check">
                                            <input class = "form-check-input" type = "checkbox" id = "Emergency Ambulance" v-model = "form.assistanceRequired" value = "Emergency Ambulance">
                                            <label class = "form-check-label" for = "Emergency Ambulance">Emergency Ambulance</label>
                                        </div>
                                        <div class = "form-check">
                                            <input class = "form-check-input" type = "checkbox" id = "Fire-Fighting" v-model = "form.assistanceRequired" value = "Fire-Fighting">
                                            <label class = "form-check-label" for = "Fire-Fighting">Fire-Fighting</label>
                                        </div>
                                        <div class = "form-check">
                                            <input class = "form-check-input" type = "checkbox" id = "Rescue" v-model = "form.assistanceRequired" value = "Rescue &amp; Evac">
                                            <label class = "form-check-label" for = "Rescue">Rescue &amp; Evac</label>
                                        </div>
                                        <div class = "form-check">
                                            <input class = "form-check-input" type = "checkbox" id = "GasLeak" v-model = "form.assistanceRequired" value = "Gas Leak Control">
                                            <label class = "form-check-label" for = "GasLeak">Gas Leak Control</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Location and Crisis Type row -->
                        <div class = "form-group row">
                            <div class = "col-md-6">
                                <div class = "card">
                                    <div class = "card-body">
                                        <h5 class = "card-title">Location</h5>

                                        <!-- Postal Code -->
                                        <div class = "form-group row">
                                            <label for = "Postal Code" class = "col-md-4 col-form-label">
                                                Postal Code:
                                            </label>
                                            <div class = "col-md-6">
                                                <input type = "text"
                                                    class = "tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey tw-italic" 
                                                    id = "Postal Code" v-model = "form.postalCode"
                                                    :class = "{ 'tw-border-red-light' : error['postalCode'] != undefined}"
                                                    placeholder = "520894"
                                                    required autofocus
                                                >
                                                <div class = "tw-text-red" v-if = "error['postalCode'] != undefined">
                                                    <span> {{this.error['postalCode'].toString()}} </span>   
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Address -->
                                        <div class = "form-group row">
                                            <label for = "Address" class = "col-md-4 col-form-label">
                                                Address:
                                            </label>
                                            <div class = "col-md-6">
                                                <input type = "text"
                                                    class = "tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey tw-italic"
                                                    id = "Address" v-model = "form.address"
                                                    :class = "{ 'tw-border-red-light' : error['address'] != undefined}"
                                                    placeholder = "Blk 35 Woodlands St 45"
                                                    required autofocus
                                                >
                                                <div class = "tw-text-red" v-if = "error['address'] != undefined">
                                                    <span> {{this.error['address'].toString()}} </span>   
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
                                <!-- Crisis Type dropdown -->
                                <div class = "card">
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
                            <div class = "col-md-6 offset-md-5">
                                <button type = "submit" class = "btn btn-primary" style = "margin-right:5px;" @click = "submitCrisis">
                                    Submit
                                </button>
                                <button type = "submit" class = "btn btn-primary" >
                                    Cancel
                                </button>
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
        name: "NewCrisis",

        components: {
            'popper': Popper
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

                error: [],

                form: {
                    name:'',
                    telephoneNumber:'',
                    date:'',
                    time:'',
                    address:'',
                    postalCode:'',
                    assistanceRequired: [],
                    crisisType: null,
                }
            }
        },

        methods: {
            submitCrisis() {
                this.message = "";
                this.isLoading = true;
                axios.post('/api/crisis', this.form)
                .then(response => {
                    this.message = response.data.message;
                    this.error = "";
                })
                .catch((error) => {
                    this.error = error.response.data.errors;
                    this.isLoading = false;
                });
            },
        },

        computed: {
            
        }
    }
</script>
                
       