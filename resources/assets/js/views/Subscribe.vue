<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card tw-mb-6">

                    <flash :message = "message"></flash>

                    <div class="card-header tw-text-grey-darker">Subscription to Crisis Alert</div>

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
                                    :class = "{ 'tw-border-red-light' : error['name'] != undefined}"
                                    placeholder = "John Doe"
                                    required autofocus
                                >
                            
                                <div class = "tw-text-red" v-if = "error['name'] != undefined">
                                    <span> {{this.error['name'].toString()}} </span>   
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                            <div class="col-md-6">
                                <input type = "text"
                                    id="email" 
                                    v-model = "form.email"
                                    class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey" 
                                    :class = "{ 'tw-border-red-light' : error['email'] != undefined}"
                                    placeholder = "JohnDoe@gmail.com"
                                    required autofocus
                                >

                                <div class = "tw-text-red" v-if = "error['email'] != undefined">
                                    <span> {{this.error['email'].toString()}} </span>   
                                </div>
                            </div>
                        </div>

                        <!-- Telephone Number -->
                        <div class="form-group row">
                            <label for="telephone_number" class="col-md-4 col-form-label text-md-right">
                                Telephone Number
                                 <popper trigger="hover" :options = "{placement: 'bottom'}">
                                    <div class="popper tw-font-hairline tw-text-grey-dark">
                                        You will be alerted through our SMS services.
                                    </div>
                                    <button slot="reference">   
                                        <i class="fas fa-question-circle tw-text-grey-dark tw-cursor-pointer"></i>
                                    </button>
                                </popper>    
                            </label>

                            <div class="col-md-6">
                                <input type = "text"
                                    id="telephone_number" 
                                    v-model = "form.telephone_number"
                                    class="tw-border tw-rounded tw-p-2 tw-w-full tw-border-grey" 
                                    :class = "{ 'tw-border-red-light' : error['telephone_number'] != undefined}"
                                    placeholder = "9512 2314"
                                    required autofocus
                                >

                                <div class = "tw-text-red" v-if = "error['telephone_number'] != undefined">
                                    <span> {{this.error['telephone_number'].toString()}} </span>   
                                </div>
                            </div>
                        </div>

                        <div class="form-group row tw-my-6">
                            <div class="col-md-6 offset-md-4">
                                <div v-if = "!isLoading">
                                    <button type="submit" class="btn btn-primary" @click ="subscribe()" >
                                        Subscribe
                                    </button>
                                </div>
                                <div v-else>
                                    <img src = "/assets/img/loader.gif" alt = "Loading...">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div>
                    <a href="https://twitter.com/SSAD18789166?ref_src=twsrc%5Etfw" data-size="large" class="twitter-follow-button" data-show-count="false">
                        Follow @SSAD18789166
                    </a>
                    <div class="fb-like" data-href="https://www.facebook.com/SsadTTCA/" 
                        data-layout="standard" data-action="like" data-size="large" data-show-faces="true" data-share="true">
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
        components: {
            'popper': Popper
        },

        data() {
            return {
                form:{
                    name: '',
                    email: '',
                    telephone_number: '',
                },
                error: [],
                message: '',
                isLoading: false,
            }
        }, 

        methods: {
            subscribe() {
                this.isLoading = true;
                this.message = "";
                this.error = "";

                axios.post("/api/auth/subscriber", this.form)
                .then(response => {
                    this.message = response.data.message;

                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                    this.isLoading = false;
                    this.resetFields();

                    setTimeout(() => {
                        this.$router.replace( "/");
                    }, 3000);         
  
                })
                .catch((error) => {
                    console.log(error);
                    this.error = error.response.data.errors;
                    this.isLoading = false;
                });
            },
        
            resetFields() {
                var scope = this; 

                Object.keys(this.form).forEach(function(key,index) {
                    scope.form[key] = '';
                });
            }
        },
        
    }
</script>