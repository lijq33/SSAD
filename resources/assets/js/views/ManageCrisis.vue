<template>
    <div>
        <flash :message = "message"></flash>
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Date</th> 
                    <th>Time</th>
                    <th>Crisis Type</th>
                    <th>Description</th>
                    <th>Assistance Requested</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Reported By</th>
                    <th>Submitted By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for = "(crisis, index) in crises" :key = "index + crisis">
                    <td>{{crisis.date}}</td>
                    <td>{{crisis.time}}</td>
                    <td>{{crisis.crisis_type}}</td>
                    <td>{{crisis.description}}</td>
                    <td>{{crisis.assistance_required}}</td>
                    <td>{{crisis.address}} {{crisis.postal_code}}</td>
                    <td class = "tw-capitalize">{{crisis.status}}</td>
                    <td>{{crisis.name}}</td>
                    <td>{{crisis.user.name}}</td>
                    <td> 
                        <span class = "tw-flex tw-justify-around tw-items-center">
                            <popper  v-if = "crisis.status === 'resolved'" trigger="hover" :options = "{placement: 'bottom'}">
                                <div class="popper tw-font-hairline tw-text-grey-dark">
                                    archive the crisis
                                </div>
                                <button slot="reference">   
                                    <i class="fas fa-archive tw-cursor-pointer"  @click = "archive(crisis)"></i>
                                </button>
                            </popper>

                            <popper trigger="hover" :options = "{placement: 'bottom'}">
                                <div class="popper tw-font-hairline tw-text-grey-dark">
                                    Update the crisis
                                </div>
                                <button slot="reference">   
                                    <i class="fas fa-pencil-alt tw-cursor-pointer" @click = "update(crisis)"></i>
                                </button>
                            </popper>
                        </span>
                    </td>
                </tr>  
            </tbody>
        </table>

        <!-- Archive -->
        <b-modal title="Alert" v-model = "modalShow" hide-footer header-bg-variant="warning">
            <div class = "tw-w-full">
                Are you sure that you would like to archive the crisis?
            </div>
            <div class = "tw-border-t tw-pt-4 tw-mt-4 tw-flex tw-justify-end tw-w-full">
                <button class = "tw-mr-2 btn btn-secondary" id='dontArchiveButton'>Cancel</button>
                <button class = "tw-ml-2 btn btn-primary" id='archiveButton'>Archive</button>
            </div>
        </b-modal>

        <!-- Update -->
        <b-modal ref="myModalRef" size="lg" hide-footer title = "Current Crisis Details:">
            <update-crisis 
                :crisis = "updateCrisis"
                @hideModal = "hideModal"
                @updateSuccess = "updateSuccess"></update-crisis>
           
            <!-- UI -->

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
            this.getCrisis();

            setTimeout(function() { 
                $("#crisis").DataTable(); 
            }, 2000);
        },

        data() {
            return {
                crises: [],

                modalShow: false,

                message:'',
                error: '',
                updateCrisis: '',
                crisisStatus: '',
                crisisDesc: '',
            }
        },

        methods: {
            getCrisis() {
                axios.get('/api/crisis')
                .then((res) => {
                    this.crises = res.data.crises;
                })
                .catch((error) => {
                    this.error = error.response.data.errors;
                })
            },

            archive(crisis) {
                this.modalShow = true;
                var scope = this;
                
                let promise = new Promise(function(resolve, reject) {
                    let archiveButton = document.getElementById('archiveButton');
                    archiveButton.addEventListener("click",function(){
                        scope.modalShow = false;
                        resolve();
                    });
                    let dontArchiveButton = document.getElementById('dontArchiveButton');
                    dontArchiveButton.addEventListener("click",function(){
                        scope.modalShow = false;
                        reject();
                    });

                });
                
                promise.then(function() { 
                    axios.delete('/api/crisis/'+crisis.id)
                    .then((res) => {
                        scope.message = "We've successfully archive the crisis!";
                        scope.crisis = [];
                        scope.getcrisis();
                    })
                    .catch((error) => {
                        scope.error = error.response;
                    })
                });
            },

            update(crisis) {
                this.$refs.myModalRef.show()
                this.updateCrisis = crisis;
                this.crisisStatus = crisis.status;
                this.crisisDesc = crisis.description;
            },


            updateSuccess(){
                this.message = "We've successfully update the crisis details!";
                this.hideModal();
                this.getCrisis();
                this.crisis = [];
            },
         
            hideModal() {
                this.$refs.myModalRef.hide()
            },

        }
    }
</script>
                
       