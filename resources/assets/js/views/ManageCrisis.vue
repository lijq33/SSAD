<template>
    <div class="container">
         <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header tw-text-grey-darker">View Crisis</div>
                    <div class="card-body">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Date &amp; Time</th>
                                    <th>Event Type</th>
                                    <th>Description</th>
                                    <th>Assistance Requested</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr v-for = "(crisis, index) in chasCliniccrisiss" :key = "index + crisis.health_service_type">
                                    <td>{{crisis.health_service_type}}</td>
                                    <td>{{crisis.crisis_date}}</td>
                                    <td>{{crisis.crisis_time}}</td>
                                    <td class = "tw-capitalize">{{crisis.status}}</td>
                                    <td> 
                                        <span class = "tw-flex tw-justify-around tw-items-center" v-if = "crisis.status === 'booked'">
                                            <i class="fas fa-trash-alt tw-cursor-pointer"  @click = "deletes(crisis)"></i>
                                            <i class="fas fa-pencil-alt tw-cursor-pointer" @click = "update(crisis)"></i>
                                        </span>
                                    </td>
                                </tr>  
                            </tbody>
                        </table>

                        <b-modal title="Alert" v-model = "modalShow" hide-footer header-bg-variant="warning">
                            <div class = "tw-w-full">
                                This action cannot be <span class ="tw-font-bold">reverse</span>. 
                            </div>
                            <div class = "tw-w-full">
                                Are you sure that you would like to cancel the crisis?
                            </div>
                            <div class = "tw-border-t tw-pt-4 tw-mt-4 tw-flex tw-justify-end tw-w-full">
                                <button class = "tw-mr-2 btn btn-secondary" id='dontDeleteButton'>Cancel</button>
                                <button class = "tw-ml-2 btn btn-primary" id='deleteButton'>Delete</button>
                            </div>
                        </b-modal>

                        <b-modal ref="myModalRef" size="lg" hide-footer title = "Current crisis Details:">
                            <edit-crisis :crisis = "editcrisis"
                                @hideModal = "hideModal"
                                @updateSuccess = "updateSuccess"
                            >
                            </edit-crisis>
                        
                        </b-modal>
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
            'popper': Popper,
            'edit-crisis' : Edit
        },

        mounted() {
            this.getCrisis();

            setTimeout(function() { 
                $("#crisis").DataTable(); 
            }, 2000);
        
        },

        data() {
            return {
                error: '',
                message:'',

                confirmDelete:false,
                modalShow: false,

                crisis: [],
                editCrisis: '',
            }
        },

        methods: {
            archive(crisis) {
                this.modalShow = true;
                var scope = this;
                
                let promise = new Promise(function(resolve, reject) {
                    let deleteButton = document.getElementById('deleteButton');
                    deleteButton.addEventListener("click",function(){
                        scope.modalShow = false;
                        resolve();
                    });
                    let dontDeleteButton = document.getElementById('dontDeleteButton');
                    dontDeleteButton.addEventListener("click",function(){
                        scope.modalShow = false;
                        reject();
                    });

                });
                
                promise.then(function() { 
                    axios.post('/api/crisis/delete', {
                        crisis_id: crisis.id,
                        health_service_type: crisis.health_service_type.split(" ").join("")
                    })
                    .then((res) => {
                        scope.message = "We've successfully cancel your crisis!";
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
                this.editcrisis = crisis;
            },

            hideModal() {
                this.$refs.myModalRef.hide()
            },

            updateSuccess(){
                this.message = "We've successfully update your crisis details!";
                this.hideModal();
                this.getCrisis();
                this.crisis = [];
            },
            
            getCrisis() {
                axios.get('/api/crisis/get')
                .then((res) => {
                    this.crisis = res.data.crisis;
                })
                .catch((error) => {
                    this.error = error.response;
                })
            },

        }
    }
</script>
                
       