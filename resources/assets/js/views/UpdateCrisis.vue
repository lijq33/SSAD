<template>
    <div>
        <!-- for updating crisis status -->
        <div class="form-group row">
            <label class="col-md-4 col-form-label text-md-right">
                Crisis Status :
            </label>
            <div class="col-md-6">
                <b-form-select v-model = "updatedStatus" :options = "options"  class="form-control"
                />
            </div>
        </div>
            
        <!-- for updating crisis description -->
        <div class="form-group row">
            <label class="col-md-4 col-form-label text-md-right">
                Description :
            </label>
            <div class="col-md-6">
            <textarea  v-model="updatedDescription"
               class = "form-control" rows = "3" style = "max-width:100%"
            >
            </textarea>
            </div>
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

