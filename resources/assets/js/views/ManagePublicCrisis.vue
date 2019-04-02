<template>
    <div>
        <flash :message = "message"></flash>
        
        <table id="report_crises" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th> 
                    <th>Time</th>
                    <th>Crisis Type</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Radius</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for = "(crisis, index) in crises" :key = "index + crisis">
                    <td>{{crisis.name}}</td>
                    <td>{{crisis.date}}</td>
                    <td>{{crisis.time}}</td>
                    <td>{{crisis.crisis_type}}</td>
                    <td>{{crisis.description}}</td>
                    <td>{{crisis.address}}</td>
                    <td>{{crisis.radius}}m</td>
                    <td> 
                        <div class = "tw-flex tw-justify-around">
                            <span>
                                <popper trigger="hover" :options = "{placement: 'bottom'}">
                                    <div class="popper tw-font-hairline tw-text-grey-dark">
                                    Approve Crisis
                                    </div>
                                    <button slot="reference">   
                                        <i class="fas fa-check" @click = "approve(crisis)"></i>
                                    </button>
                                </popper>
                            </span>

                            <span>
                                <popper trigger="hover" :options = "{placement: 'bottom'}">
                                    <div class="popper tw-font-hairline tw-text-grey-dark">
                                    Reject Crisis
                                    </div>
                                    <button slot="reference">   
                                        <i class="fas fa-times" @click = "reject(crisis)"></i>
                                    </button>
                                </popper>
                            </span>
                        </div>
                    </td>
                </tr>  
            </tbody>
        </table>

        <!-- Approve -->
        <b-modal ref="approveModalRef" size="lg" hide-footer title = "Please add on to the Crisis Details:" >
            <approve-crisis 
                :crisis = "approveCrisis"
                @hideModal = "hideModal"
                @approveSuccess = "approveSuccess">
            </approve-crisis>
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
                $("#report_crises").DataTable(); 
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
            approve(crisis) {
                this.$refs.approveModalRef.show()
                this.approveCrisis = crisis;
            },

            reject(crisis) {
                axios.delete('/api/report/crisis/'+crisis.id)
                .then((res) => {
                    this.message = res.data.message;
                })
                .catch((error) => {
                    this.error = error.response.data.errors;
                })

                this.crises = [];
                this.getCrisis();
            },

            getCrisis() {
                axios.get('/api/report/crisis')
                .then((res) => {
                    this.crises = res.data.report_crises;
                })
                .catch((error) => {
                    this.error = error.response.data.errors;
                })
            },

            approveSuccess(){
                this.message = "The Crisis has been approved!";
                this.hideModal();
                this.crises = [];
                this.getCrisis();
            },
         
            hideModal() {
                this.$refs.approveModalRef.hide()
            },

        }
    }
</script>
                
       