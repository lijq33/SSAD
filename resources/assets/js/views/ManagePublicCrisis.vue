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
                    <th>Location</th>
                    <th>Status</th>
                    <th>Reported By</th>
                    <th>Submitted By</th>
                    <th>Approval</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for = "(crisis, index) in crises" :key = "index + crisis">
                    <td>{{crisis.date}}</td>
                    <td>{{crisis.time}}</td>
                    <td>{{crisis.crisis_type}}</td>
                    <td>{{crisis.description}}</td>
                    <td>{{crisis.address}} {{crisis.postal_code}}</td>
                    <td class = "tw-capitalize">{{crisis.status}}</td>
                    <td>{{crisis.name}}</td>
                    <td>{{crisis.user.name}}</td>
                    <td> 
                        <span class = "tw-flex tw-justify-around tw-items-center">
                            <popper trigger="hover" :options = "{placement: 'bottom'}">
                                <div class="popper tw-font-hairline tw-text-grey-dark">
                                   Approve Crisis
                                </div>
                                <button slot="reference">   
                                    <i class="fas fa-check" @click = "approve(crisis)"></i>
                                </button>
                            </popper>
                        </span>
                    </td>
                </tr>  
            </tbody>
        </table>
        <!-- Approve -->
        <b-modal ref="approveModalRef" size="lg" hide-footer title = "Please add on to the Crisis Details:" >
            <approve-crisis 
                :crisis = "approveCrisis"
                @hideModal = "hideModal"
                @approveSuccess = "approveSuccess"></approve-crisis>

        </b-modal>
    </div>
</template>

<script>
    import 'vue-popperjs/dist/css/vue-popper.css';
    import Popper from 'vue-popperjs';
     import ApproveCrisis from './ApproveCrisis';
    
    export default {
            components: {
            'popper': Popper,
            'approve-crisis' : ApproveCrisis,
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
                approveCrisis: '',
                crisisStatus: '',
                crisisDesc: '',
            }
        },

        methods: {
            approve(crisis)
            {
                 this.$refs.approveModalRef.show()
                this.approveCrisis = crisis;
            },
            getCrisis() {
                axios.get('/api/crisis')
                .then((res) => {
                    this.crises = res.data.crises;
                })
                .catch((error) => {
                    this.error = error.response.data.errors;
                })
            },

            /* archive(crisis) {
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
            }, */
            approveSuccess(){
                this.message = "The Crisis has been approved!";
                this.hideModal();
                this.getCrisis();
                this.crisis = [];
            },
         
            hideModal() {
                this.$refs.approveModalRef.hide()
            },

        }
    }
</script>
                
       