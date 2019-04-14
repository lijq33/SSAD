<!--Author: JinQuan-->
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

        <!-- Crisis Image -->
        <div class="form-group row">
             <label class="col-md-4 col-form-label text-md-right">
                Image :
            </label>

            <div class="col-md-6">
                <div class = "card">
                    <div class = "card-body">
                        <!-- Image upload -->
                        <div class="form-group">
                    
                            <div v-if = "image === ''" >
                                <input accept = "image/*" type = "file" class = "upload-image-input tw-hidden" @change = "onFileSelected">
                                
                                <button class = "btn btn-primary" 
                                        @click = "uploadImage">
                                    Select An Image
                                </button>
                            </div>
                        
                            <div v-else class = "col-md-6">
                                <div class = "tw-h-24 tw-w-24 tw-mb-6 tw-overflow-hidden" style="width:300px; height:200px">
                                    <img :src = "image" class = "tw-w-full tw-h-full tw-flex tw-items-center tw-justify-center" />

                                    <div class = "tw-text-red" v-if = "error['image'] != undefined">
                                        <span> {{this.error['image'].toString()}} </span>   
                                    </div>
                                </div>
                                
                                <button class = "btn btn-primary" @click = "removeImage">
                                    Remove Image
                                </button>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>


        <div class = "tw-flex tw-justify-end tw-m-4 tw-border-t tw-border-grey tw-pt-4">
            <div v-if ="!isLoading">
                <button class = "tw-mr-2 btn btn-secondary" @click = "hideModal()">Cancel</button>
                <button class = "tw-ml-2 btn btn-primary" @click = "updateCrisis()">Update</button>
            </div>
            <div v-else>
                <img
                    src="/assets/img/loader.gif"
                    alt="Loading..."
                >
            </div>
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

                selectedFile: null,
                image: '',
                isLoading: false,
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
                this.isLoading = true;

                const fd = new FormData();

                var scope = this;

                if(this.selectedFile !== null)
                    fd.append('image', this.selectedFile);

                fd.append('status', this.updatedStatus);
                fd.append('description', this.updatedDescription);
                
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                };

                axios.post('/api/crisis/'+this.crisis.id, fd, config)
                .then((response) => {
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                    this.$emit('updateSuccess');
                    this.isLoading = false;
                })
                .catch((error) => {
                    // this.error = error.response.data.errors;
                    this.isLoading = false;
                });
            },

            uploadImage(e) {
                document.querySelector('.upload-image-input').click();
            },

            removeImage(){
                this.image = '';
                this.selectedFile = null;
            },
            
            createImage(file) {
                let reader = new FileReader();

                reader.onload = (e) => {
                    this.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },

            onFileSelected (event) {
                let files = event.target.files || event.dataTransfer.files;

                if (!files.length)
                    return;

                this.selectedFile = files[0];
                
                this.createImage(this.selectedFile);
            },

            hideModal() {
                this.$emit('hideModal');
            },
        }

    }
</script>

