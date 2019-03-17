<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                        <button type="submit" class="btn btn-primary" @click ="test()" >
                            test
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    
    export default {
     
        methods: {
            test() {
                axios.get("/api/auth/subscriber")
                .then(response => {
                     console.log(response)
                })
                .catch((error) => {
                   
                });
            },
        
       
        },
        
    }
</script>