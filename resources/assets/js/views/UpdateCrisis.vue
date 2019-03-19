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

<!-- <div>
    <div >
        <div class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label text-md-right">
                Appointment Type :
            </label>
            <input type="text" :value = "editAppointment.health_service_type" 
                class="col-md-6 tw-flex tw-items-center tw-border-grey tw-rounded tw-bg-grey-light"
                disabled
            >
        </div>

        <div class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label text-md-right">
                Location :
            </label>
            <textarea :value = "editAppointment.location"
                class = "col-md-6 tw-flex tw-items-center tw-border-grey tw-rounded tw-bg-grey-light tw-py-2"
                disabled
            >
            </textarea>
        </div>

        <div class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label text-md-right">
                {{this.editAppointment.health_service_type}} : 
            </label>
            <input type="text" :value = "clinic" 
                class="col-md-6 tw-flex tw-items-center tw-border-grey tw-rounded tw-bg-grey-light"
                disabled
            >
        </div>

        <!-- EDITABLE DATA -->
<!--         <div v-if = "!isChasclinic" class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label text-md-right">
                {{this.Role}} : 
            </label>
            <input type="text" v-model = "prefer_practitioner"
                class="col-md-6 tw-flex tw-items-center tw-border-grey tw-rounded tw-border"
            >
        </div>

        <div v-if = "isDental" class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label text-md-right">
                Services :
            </label>
            <div class="tw-w-1/2">
                <select v-model = "service " class="form-control" required>
                    <option v-for="(service, index) in allDentalServices" :key = "index">{{service}}</option>
                </select>
            </div>
        </div>

        <div v-else class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label text-md-right">
                Condition :
            </label>
            <input type="text" v-model = "condition"
                class="col-md-6 tw-flex tw-items-center tw-border-grey tw-rounded tw-border"
            >
        </div>

        <div v-if = "isHospital || isPolyclinic" class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label text-md-right">
                Department :
            </label>
            <div class="tw-w-1/2">
                <select v-model = "department" class="form-control" required>
                    <option v-for="(department, index) in allDepartments" :key = "index">{{department}}</option>
                </select>
            </div>
        </div>

        <div v-if = "isHospital" class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label text-md-right">
                Referral :
            </label>
            <input type="text" v-model = "referal"
                class="col-md-6 tw-flex tw-items-center tw-border-grey tw-rounded tw-border"
            >
        </div>

        <div class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label text-md-right">
                Appointment Date :
            </label>
            <div class="tw-w-1/2">
                <date-picker v-model = "appointment_date" required = "required"> </date-picker>
            </div>
        </div>

        <div class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label text-md-right">
                Appointment Time :
            </label>
            <div class="tw-w-1/2">
                <time-picker v-model = "appointment_time" required = "required" :useContainer = "true"> </time-picker>
            </div>
        </div>

    </div>
        <div class = "tw-flex tw-justify-end tw-m-4 tw-border-t tw-border-grey tw-pt-4">
            <button class = "tw-mr-2 btn btn-secondary" @click = "hideModal()">Cancel</button>
            <button class = "tw-ml-2 btn btn-primary" @click = "updateAppointment()">Update</button>
        </div>
    </div>  -->
    
</template>

<script>
 import ManageCrisis from './ManageCrisis';
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

