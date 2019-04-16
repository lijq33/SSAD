<!-- Author: Chen XingYu -->
<template>
    <!-- Custom Edit Modal Component -->
    <div>
        <b-modal
            ref="myModalRef"
            hide-footer
            title="Dengue Hotspot"
            no-close-on-backdrop
        >
            <div>
                <div class="form-group row tw-my-6 tw-flex tw-justify-center">
                    <div class="">
                        <button type="submit" class="btn btn-secondary tw-ml-4" @click = "closeModal" >
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </b-modal>
    
    </div>
</template>

<script>
	import { Modal } from "bootstrap-vue/es/components";

    export default {

        props:['showModal', 'dengue'],
        
        data(){
            return{
                message: '',
                error: [],
                isLoading: false,
            }
        },

        watch:{
            showModal(){
                if (this.showModal == true)
				    this.$refs.myModalRef.show();
                else 
				    this.$refs.myModalRef.hide();
            },

            message() {
				this.$emit('message', this.message);
            }
        },
        methods:{

            closeModal() {
				this.$emit('hideModal');
            },
        }
    }
</script>

<style>
    .close{
        visibility: hidden;
    }
</style>

      


       

        
     