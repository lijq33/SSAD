<template>
    <div class="container-fluid">
         <div class="row">
            <div class="col-xl">
                <div class="card">
                    <div class="card-header tw-text-grey-darker">Register a New Crisis</div>
                    <div class="card-body">
                        <!-- Personal Particular & Crisis Info -->
                         <div class="form-group row">
                             <div class="col-md-6">
                                <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Personal Particulars</h5>
                                    <div class="form-group row">
                            <label for="login_id" class="col-md-4 col-form-label">
                                Name:
                                <popper trigger="hover" :options = "{placement: 'bottom'}">
                                    <div class="popper tw-font-hairline tw-text-grey-dark">
                                        Name of Crisis Reporter
                                    </div>
                                    <button slot="reference">   
                                        <i class="fas fa-question-circle tw-text-grey-dark tw-cursor-pointer"></i>
                                    </button>
                                </popper> 
                            </label>
                                <div class="col-md-6">
                                <input type = "text"
                                    class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey"
                                     style="font-style:italic;" 
                                    placeholder = "John Doe"
                                    required autofocus>
                            </div>
                        </div>

                       <!-- Contact -->
                        <div class="form-group row">
                            <label for="login_id" class="col-md-4 col-form-label">
                                Mobile Number:
                                <popper trigger="hover" :options = "{placement: 'bottom'}">
                                    <div class="popper tw-font-hairline tw-text-grey-dark">
                                        Phone/Mobile No.
                                    </div>
                                    <button slot="reference">   
                                        <i class="fas fa-question-circle tw-text-grey-dark tw-cursor-pointer"></i>
                                    </button>
                                </popper> 
                            </label>
                                <div class="col-md-6">
                                <input type = "text"
                                    class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey" 
                                    style="font-style:italic;"
                                    placeholder = "98765654"
                                    required autofocus>
                            </div>
                        </div>
                                </div>
                                </div>
                             </div>
                             <div class="col-md-6">
                                <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Assistance Required:</h5>
                                    <!-- Assistance -->
                                        <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="checkbox1" value="option1">
                                        <label class="form-check-label" for="inlineCheckbox1">Emergency Ambulance</label>
                                        </div>
                                        <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="checkbox2" value="option2">
                                        <label class="form-check-label" for="inlineCheckbox2">Fire-Fighting</label>
                                        </div>
                                        <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="checkbox3" value="option3">
                                        <label class="form-check-label" for="inlineCheckbox3">Rescue &amp; Evac</label>
                                        </div>
                                        <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="checkbox4" value="option4">
                                        <label class="form-check-label" for="inlineCheckbox4">Gas Leak Control</label>
                                        </div>

                                </div>
                                
                                </div>
                             </div>
                            </div>
                            <!-- Location and Crisis Type row -->
                            <div class="form-group row">
                                 <div class="col-md-6">
                                <div class="card">
                                <div class="card-body">
                                 <h5 class="card-title">Location</h5>
                            <!-- Postal Code -->
                             <div class="form-group row">
                             <label for="login_id" class="col-md-4 col-form-label">
                                Postal Code:
                                </label>
                                    <div class="col-md-6">
                                    <input type = "text"
                                        class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey" 
                                        style="font-style:italic;"
                                        placeholder = "520894"
                                        required autofocus>
                                </div>
                            </div>

                                <!-- Address -->
                                <div class="form-group row">
                                    <label for="login_id" class="col-md-4 col-form-label">
                                        Address:
                                    </label>
                                        <div class="col-md-6">
                                        <input type = "text"
                                            class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey"
                                            style="font-style:italic;" 
                                            placeholder = "Blk 35 Woodlands St 45"
                                            required autofocus>
                                    </div>
                                </div>

                                <!-- Date -->
                                <div class="form-group row">
                                    <label for="date" class="col-md-4 col-form-label">
                                        Date:
                                    </label>
                                    <div class="tw-w-1/2">
                                        <date-picker v-model = "form.date" required = "required"> </date-picker>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="time" class="col-md-4 col-form-label">
                                        Time:
                                    </label>
                                    <div class="tw-w-1/2">
                                        <time-picker id = "time" v-model = "form.time" required = "required" :useContainer = "true"> </time-picker>
                                    </div>
                                </div>
                            </div>
                                </div>
                                 </div>
                                  <!-- end of location column -->
                                  <!-- beginning of crisis type column -->

                                 <div class="col-md-6">
                                      <!-- Crisis Type dropdown -->
                                      <div class="card">
                                      <div class="card-body">
                                   <h5 class="card-title"> Type of Crisis:</h5>
                                        <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Please select
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#">Fire Outbreak</a>
                                            <a class="dropdown-item" href="#">Dengue</a>
                                            <a class="dropdown-item" href="#">Gas Leak</a>
                                        </div>
                                        </div>
                                      </div>
                                      </div>
                                    
                                     </div>
                            </div>


                        <!-- button group -->
                          <div class="form-group row tw-my-6">
                            <div class="col-md-6 offset-md-5">
                                    <button type="submit" class="btn btn-primary" style="margin-right:5px;" @click = "submitCrisis">
                                        Submit
                                    </button>
                                     <button type="submit" class="btn btn-primary" >
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
    
    export default {
        name: "NewCrisis",

        components: {
            'popper': Popper
        },
        
        data() {
            return{
                message: '',

                form: {
                    date:'',
                    time:'',
                }
            }
        },

        methods: {
            submitCrisis() {
                this.isLoading = true;
                axios.post('/crisis', this.form)
                .then(response => {
                    this.message = response.data.message
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
                
       