<template>
    <div class="container">
         <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header tw-text-grey-darker">Register a New Crisis</div>
                    <div class="card-body">
                        <!-- Personal Particular -->
                        <div class="card-header tw-text-grey-darker"> Personal Particulars
                        <!-- Name -->
                        <div class="form-group row">
                            <label for="login_id" class="col-md-4 col-form-label text-md-right">
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
                            <label for="login_id" class="col-md-4 col-form-label text-md-right">
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
                        <!-- Location -->
                         <div class="card-header tw-text-grey-darker" style="margin-top:10px;"> Location 
                              <!-- Postal Code -->
                        <div class="form-group row">
                            <label for="login_id" class="col-md-4 col-form-label text-md-right">
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
                            <label for="login_id" class="col-md-4 col-form-label text-md-right">
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
                            <label for="login_id" class="col-md-4 col-form-label text-md-right">
                                Date &amp; Time:
                            </label>
                                <div class="col-md-6">
                                <input type = "text"
                                    class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey"
                                    style="font-style:italic;" 
                                    placeholder = "2/06/19 1300hrs"
                                    required autofocus>
                            </div>
                        </div>

                         </div>

                         <!-- Assistance -->
                         <div class="card-header tw-text-grey-darker" style="margin-top:10px;"> 
                             Type of Assistance Required: <br/>
                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                            <label class="form-check-label" for="inlineCheckbox1">Emergency Ambulance</label>
                            </div>
                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                            <label class="form-check-label" for="inlineCheckbox2">Fire-Fighting</label>
                            </div>
                             <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
                            <label class="form-check-label" for="inlineCheckbox3">Rescue &amp; Evac</label>
                            </div>
                            <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="option4">
                            <label class="form-check-label" for="inlineCheckbox4">Gas Leak Control</label>
                            </div>
                         </div>

                         <!-- Crisis -->
                         <div class="card-header tw-text-grey-darker" style="margin-top:10px;"> 
                             Type of Crisis:
                             <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Dropdown button
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                            </div>
                         </div>

                        <!-- button group -->
                          <div class="form-group row tw-my-6">
                            <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary" >
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
            
        },

        methods: {
            
        },

        computed: {
            
        }
    }
</script>
                
       