<template>
<div>
    <!-- for updating crisis status -->
  <div class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label">
                Crisis Status :
            </label>
            <input type="text"  v-model="crisisStatus"
                class="col-md-6 tw-flex tw-items-center tw-border-grey tw-rounded tw-bg-grey-light"
                
            >
        </div>
        <!-- for updating crisis description -->
        <div class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label">
                Description :
            </label>
            <textarea  v-model="crisisDescription"
                class = "col-md-6 tw-flex tw-items-center tw-border-grey tw-rounded tw-bg-grey-light tw-py-2"
                
            >
            </textarea>
        </div>

        <div class = "tw-flex tw-justify-end tw-m-4 tw-border-t tw-border-grey tw-pt-4">
            <button class = "tw-mr-2 btn btn-secondary" @click = "hideModal()">Cancel</button>
            <button class = "tw-ml-2 btn btn-primary" @click = "updateCrisis()">Update</button>
        </div>

    </div>


    
</template>

<script>

    export default {
        props: ['crisis'],
        
        data() {
            return {
                crisisStatus: 'Test',
                crisisDescription: 'Test',

            }
        },

        watch: {
            crisis() {

            },
        },



        computed: {
 
        },

        methods: {
            /* updateAppointment(){
                axios.post('/api/appointment/update', {
                    id: this.appointment.id,
                    
                    health_service_type: this.editAppointment.health_service_type.split(" ").join(""),
                    department: this.department,
                    service: this.service,
                    
                    condition: this.condition,
                    prefer_doctor: this.prefer_practitioner,
                    prefer_dentist: this.prefer_practitioner,

                    referal: this.referal,
                    appointment_date: this.appointment_date,
                    appointment_time: this.appointment_time,
                })
                .then((res) => {
                    this.$emit('updateSuccess');
                })
                .catch((error) => {
                    this.error = error.response;
                })
            } */

            updateCrisis(){
                    alert("Test");
            },

            hideModal() {
                this.$emit('hideModal');
            },
        }

    }
</script>

