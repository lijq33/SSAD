<template>
    <div class="container">
         <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header tw-text-grey-darker">View Crisis</div>
                    <div class="card-body">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Date &amp; Time</th>
                                <th>Event Type</th>
                                <th>Description</th>
                                <th>Assistance Requested</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>20/12/18; 10:00AM</td>
                                <td>Fire Outbreak</td>
                                <td>Woodlands St 91</td>
                                <td>Fire-fighting, Ambulance</td>
                                <td>Unattended</td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
         </div>
    </div>
</template>

<script>
    import 'vue-popperjs/dist/css/vue-popper.css';
    import Popper from 'vue-popperjs';
    
    export default {
        name: "ViewCrisis",

        components: {
            'popper': Popper
        },
        
        data() {
            
            
        },

        methods: {
            
        },

        computed: {
            
        }
    }
</script>
                
       