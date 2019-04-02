<template>
    <div>
        <flash :message = "message"></flash>
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Name</th> 
                    <th>NRIC</th>
                    <th>Email</th>
                    <th>Telephone</th>
                    <th>Roles</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for = "(account, index) in accounts" :key = "index + account">
                    <td>{{account.name}}</td>
                    <td>{{account.nric}}</td>
                    <td>{{account.email}}</td>
                    <td>{{account.telephone_number}}</td>
                    <td>{{account.roles}}</td>
                    <td> 
                        <span class = "tw-flex tw-justify-around tw-items-center" v-if = "account.deleted_at === null">
                            <popper trigger="hover" :options = "{placement: 'bottom'}">
                                <div class="popper tw-font-hairline tw-text-grey-dark">
                                    Disable this account
                                </div>
                                <button slot="reference">   
                                    <i class="fas fa-user-slash tw-cursor-pointer"  @click = "disable(account)"></i>
                                </button>
                            </popper>
                        </span>
                        <span class = "tw-flex tw-justify-around tw-items-center" v-else>
                            <popper trigger="hover" :options = "{placement: 'bottom'}">
                                <div class="popper tw-font-hairline tw-text-grey-dark">
                                    Enable this account
                                </div>
                                <button slot="reference">   
                                    <i class="fas fa-user-plus tw-cursor-pointer"  @click = "enable(account)"></i>
                                </button>
                            </popper>
                        </span>
                    </td>
                </tr>  
            </tbody>
        </table>

        <!-- Diable -->
        <b-modal title="Alert" v-model = "modalShow" hide-footer header-bg-variant="warning">
            <div class = "tw-w-full">
                Are you sure that you would like to disable the account?
            </div>
            <div class = "tw-border-t tw-pt-4 tw-mt-4 tw-flex tw-justify-end tw-w-full">
                <button class = "tw-mr-2 btn btn-secondary" id='dontDisableButton'>Cancel</button>
                <button class = "tw-ml-2 btn btn-primary" id='disableButton'>Disable</button>
            </div>
        </b-modal>

    </div>
</template>

<script>
    import 'vue-popperjs/dist/css/vue-popper.css';
    import Popper from 'vue-popperjs';
    import UpdateCrisis from './UpdateCrisis';
    
    export default {
            components: {
            'popper': Popper,
            'update-crisis' : UpdateCrisis
        },

        mounted() {
            this.getUser();
        },

        data() {
            return {
                accounts: [],

                modalShow: false,

                message:'',
                error: '',
            }
        },

        methods: {
            getUser() {
                axios.get('/api/register')
                .then((res) => {
                    this.accounts = res.data.users;
                })
                .catch((error) => {
                    this.error = error.response;
                })
            },

            disable(account) {
                this.modalShow = true;
                var scope = this;
                
                let promise = new Promise(function(resolve, reject) {
                    let disableButton = document.getElementById('disableButton');
                    disableButton.addEventListener("click",function(){
                        scope.modalShow = false;
                        resolve();
                    });
                    let dontDisableButton = document.getElementById('dontDisableButton');
                    dontDisableButton.addEventListener("click",function(){
                        scope.modalShow = false;
                        reject();
                    });

                });
                
                promise.then(function() { 
                    axios.delete('/api/register/'+account.id)
                    .then((res) => {
                        scope.message = res.data.message;
                        scope.accounts = [];
                        scope.getUser();
                    })
                    .catch((error) => {
                        scope.error = error.response;
                    })
                    scope.message = '';
                });
            },


            enable(account) {
                var scope = this;
                axios.patch('/api/register/'+account.id)
                .then((res) => {
                    scope.message = res.data.message;
                    scope.accounts = [];
                    scope.getUser();
                })
                .catch((error) => {
                    scope.error = error.response;
                })
                scope.message = '';
            },
        }
    }
</script>
                
       