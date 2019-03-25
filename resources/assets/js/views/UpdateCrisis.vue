<template>
    <div>
        <!-- for updating crisis status -->
        <div class="form-group row justify-content-center">
            <label class="col-md-4 col-form-label">
                Crisis Status :
            </label>
            <div class="col-md-6">
                <b-form-select v-model = "updatedStatus" :options = "options"  class="form-control"
                />
            </div>
        </div>
            
        <!-- for updating crisis description -->
        <div class="form-group row tw-flex tw-justify-center">
            <label class="col-md-4 col-form-label">
                Description :
            </label>
            <textarea  v-model="updatedDescription"
                class = "col-md-6 tw-flex tw-items-center tw-border tw-border-grey tw-rounded tw-bg-white"
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
                updatedStatus: '',
                updatedDescription: '',
                error: '',

                options: [
                    { value: 'registered', text: 'registered' },
                    { value: 'attending', text: 'attending' },
                    { value: 'resolved', text: 'resolved' }
                ],

            }
        },
   
        watch: {
            crisis() {
                this.updatedStatus = this.crisis.status;
                this.updatedDescription = this.crisis.description;
              
            },
        },

        methods: {
            updateCrisis(){
                axios.post('/api/crisis/'+this.crisis.id, {
                    status : this.updatedStatus,
                    description : this.updatedDescription,
                })
                .then((res) => {
                    this.$emit('updateSuccess');
                })
                .catch((error) => {
                    this.error = error.response;
                })
            
            },

            hideModal() {
                this.$emit('hideModal');
            },
        }

    }
</script>

