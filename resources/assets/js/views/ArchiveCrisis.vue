<template>
    <div>
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
                </tr>  
            </tbody>
        </table>
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
            }
        },

        methods: {
            getCrisis() {
                axios.get('/api/crisis/archive')
                .then((res) => {
                    console.log(res);
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
        }
    }
</script>
                
       