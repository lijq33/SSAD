<template>
    <div style="margin:auto">
        <!-- for approving crisis status -->
       
        <div class="form-group row">
            <h5 class="col-md-4 card-title">
                Assistance Required :
            </h5>
            <div class="form-group">
             <div class = "form-check">
                <input class = "form-check-input" type = "checkbox" id = "Emergency Ambulance" v-model = "assistanceRequired" value = "1">
                <label class = "form-check-label" for = "Emergency Ambulance">Emergency Ambulance</label>
            </div>
            <div class = "form-check">
                <input class = "form-check-input" type = "checkbox" id = "Rescue" v-model = "assistanceRequired" value = "2">
                <label class = "form-check-label" for = "Rescue">Rescue &amp; Evac</label>
            </div>
            <div class = "form-check">
                <input class = "form-check-input" type = "checkbox" id = "Fire-Fighting" v-model = "assistanceRequired" value = "3">
                <label class = "form-check-label" for = "Fire-Fighting">Fire-Fighting</label>
            </div>
            <div class = "form-check">
                <input class = "form-check-input" type = "checkbox" id = "GasLeak" v-model = "assistanceRequired" value = "4">
                <label class = "form-check-label" for = "GasLeak">Gas Leak Control</label>
            </div>
            </div>
             
        </div>
            

        <div class = "tw-flex tw-justify-end tw-m-4 tw-border-t tw-border-grey tw-pt-4">
            <button class = "tw-mr-2 btn btn-secondary" @click = "hideModal()">Cancel</button>
            <button class = "tw-ml-2 btn btn-primary" @click = "approveCrisis()">Approve</button>
        </div>

    </div>
</template>

<script>
    export default {
        props: ['crisis'],

        data() {
            return {
                assistanceRequired: [],
                error: '',

            }
        },
   
        watch: {
            crisis() {
               
            },
        },

        methods: {
            approveCrisis(){
              /*   axios.post('/api/crisis/'+this.crisis.id, {
                    status : this.updatedStatus,
                    description : this.updatedDescription,
                })
                .then((res) => {
                    this.$emit('approveSuccess');
                })
                .catch((error) => {
                    this.error = error.response;
                }) */

                this.$emit('approveSuccess');
            
            },

            hideModal() {
                this.$emit('hideModal');
            },
        }

    }
</script>

