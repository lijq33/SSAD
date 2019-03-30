<template>
    <div>
        <div class = "
            tw-text-3xl
            sm:tw-text-4xl
            tw-pin-t tw-bg-white tw-border-b tw-border-grey-light tw-sticky tw-p-8 tw-shadow tw-font-extrabold tw-flex tw-justify-between tw-items-center">
            CRISIS NEWS <i class="fas fa-newspaper"></i>
        </div>

        <div class="scrollbar scrollbar-primary">
            <div class="force-overflow">
                <div v-for = "(result, index) in results" :key = "index" 
                    class = "
                        tw-p-8
                        sm:tw-p-8
                        tw-mb-8"
                >
                    <div class = "
                        tw-text-2xl tw-text-center
                        sm:tw-text-3xl sm:tw-text-left
                        tw-underline tw-font-bold tw-cursor-pointer hover:tw-text-blue-dark tw-leading-tight" 
                        @click = "visit_site(index)"
                    >
                        {{result.title}}
                    </div>

                    <div class = "tw-flex tw--mx-6 tw-pt-8 ">
                        <div class = "
                            tw-text-base tw-mx-1
                            sm:tw-text-xl sm:tw-mx-6
                            tw-font-light tw-w-1/2">
                            {{result.description}}
                            <div class ="tw-text-xs
                                tw-font-hairline tw-italic tw-mt-8">
                                <div>
                                    Published at {{result.publishedAt | dateformat}} 
                                </div>
                                <div>
                                    Reported by {{result.author}}
                                </div>
                            </div>
                        </div>
                        
                        <div class ="
                            tw-mx-1
                            sm:tw-mx-6
                            tw-flex-1">
                            <img :src = "result.urlToImage" style = "height:200px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import SimpleBar from 'simplebar';

     export default {
        data() {
            return {
                results: [],
            }
        }, 
        
    }
</script>

<style>
    .scrollbar {
        float: left;
        height: 600px;
        background: #fff;
        overflow-y: scroll;
        margin-bottom: 25px;
    }
    .force-overflow {
        min-height: 450px;
    }

    .scrollbar-primary::-webkit-scrollbar {
        width: 12px;
        background-color: #F5F5F5; 
    }

    .scrollbar-primary::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
        background-color: #4285F4; 
    }

    .scrollbar-danger::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
        background-color: #F5F5F5;
        border-radius: 10px; 
    }

    .scrollbar-danger::-webkit-scrollbar {
        width: 12px;
        background-color: #F5F5F5; 
    }

    .scrollbar-danger::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
        background-color: #ff3547; 
    
}

    .scrollbar-warning::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
        background-color: #F5F5F5;
        border-radius: 10px; 
    }

    .scrollbar-warning::-webkit-scrollbar {
        width: 12px;
        background-color: #F5F5F5; 
    }

    .scrollbar-warning::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
        background-color: #FF8800; 
    }

    .scrollbar-success::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
        background-color: #F5F5F5;
        border-radius: 10px; 
    }

    .scrollbar-success::-webkit-scrollbar {
        width: 12px;
        background-color: #F5F5F5; 
    }

    .scrollbar-success::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
        background-color: #00C851; 
    }

    .scrollbar-info::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
        background-color: #F5F5F5;
        border-radius: 10px; 
    }

    .scrollbar-info::-webkit-scrollbar {
        width: 12px;
        background-color: #F5F5F5; 
    }

    .scrollbar-info::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
        background-color: #33b5e5; 
    }

    .scrollbar-default::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
        background-color: #F5F5F5;
        border-radius: 10px; 
    }

    .scrollbar-default::-webkit-scrollbar {
        width: 12px;
        background-color: #F5F5F5; 
    }

    .scrollbar-default::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
        background-color: #2BBBAD; 
    }

    .scrollbar-secondary::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
        background-color: #F5F5F5;
        border-radius: 10px; 
    }

    .scrollbar-secondary::-webkit-scrollbar {
        width: 12px;
        background-color: #F5F5F5; 
    }

    .scrollbar-secondary::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.1);
        background-color: #aa66cc; 
    }

</style>