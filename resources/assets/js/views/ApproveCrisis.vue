<!-- Author: PRASHANTH -->
<template>
    <div style="margin:auto">
        <!-- for approving crisis status -->
       
        <div class="form-group row">
            <div class="col-md-4 tw-font-bold tw-text-lg">
                Assistance Required :
            </div>

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

        <!-- Description -->
        <div class="form-group">
            <label id="description" for="description" class="tw-font-bold tw-text-lg">Description:</label>
            <textarea v-model = "description" class = "form-control" rows = "3"
                :class = "{ 'tw-border-red-light' : error['description'] != undefined }"
            />
        </div>

        <!-- Image upload -->
        <div class="form-group">
            <label id="image" for="image" class="tw-font-bold tw-text-lg">Image:</label>

    
            <div v-if = "image === ''" class = "col-md-6">
                <input accept = "image/*" type = "file" class = "upload-image-input tw-hidden" @change = "onFileSelected">
                
                <button class = "btn btn-primary" 
                        @click = "uploadImage">
                    Select An Image
                </button>
            </div>
        
            <div v-else class = "col-md-6">
                <div class = "tw-h-24 tw-w-24 tw-mb-6 tw-overflow-hidden" style="width:400px; height:200px">
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

        
        <div v-if="!isLoading">
            <div class = "tw-flex tw-justify-end tw-m-4 tw-border-t tw-border-grey tw-pt-4">
                <button class = "tw-mr-2 btn btn-secondary" @click = "hideModal()">Cancel</button>
                <button class = "tw-ml-2 btn btn-primary" @click = "approveCrisis()">Approve</button>
            </div>
        </div>
        <div v-else>
            <div class = "tw-flex tw-justify-end tw-m-4 tw-border-t tw-border-grey tw-pt-4">
                <img
                    src="/assets/img/loader.gif"
                    alt="Loading..."
                />
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: ['crisis'],

        data() {
            return {
                assistanceRequired: [],
                description:'',
                error: '',
                isLoading: false,
                image: '',
                selectedFile: null,
            }
        },
   
        watch: {
            crisis() {
               this.description = this.crisis.description;
               this.image = this.crisis.image;
            },
        },

        methods: {
            approveCrisis(){
                this.isLoading = true;
                this.error = [];
                const fd = new FormData();
                
                if(this.selectedFile !== null)
                    fd.append('image', this.selectedFile);

                if(this.description !== null)
                    fd.append('description', this.description);

                fd.append('assistanceRequired', this.assistanceRequired);

                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                };

                axios.post('/api/report/crisis/'+this.crisis.id, fd, config)
                .then((res) => {
                    this.$emit('approveSuccess');
                    this.isLoading = false;
                })
                .catch((error) => {
                    this.error = error.response.data.errors;
                    this.isLoading = false;
                })
                
            },

            hideModal() {
                this.$emit('hideModal');
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

        }

    }
</script>

