<template>
     <div>
    <b-carousel id="carousel1"
                style="text-shadow: 1px 1px 2px #333;"
                controls
                indicators
                background="#ababab"
                :interval="4000"
                img-width="1024"
                img-height="480"
                v-model="slide"
                @sliding-start="onSlideStart"
                @sliding-end="onSlideEnd"
    >


		<b-carousel-slide>
			<img slot="img" class="d-block img-fluid w-100" width="1024" height="480"
				src="/assets/img/drinkdrive.jpg" alt="image slot">
			Between 2012 and 2016, {{this.no_of_accidents}} accidents occured due to influence of alcohol 
		</b-carousel-slide>

		<!-- Slides with image only -->
		<b-carousel-slide img-src="/assets/img/blood.jpg">
		</b-carousel-slide>

		<b-carousel-slide img-src="/assets/img/dental.jpg">
		</b-carousel-slide>

		<b-carousel-slide img-src="/assets/img/healtheir choice.jpg">
		</b-carousel-slide>

		<b-carousel-slide img-src="/assets/img/veg.jpg">
		</b-carousel-slide>

		<b-carousel-slide img-src="/assets/img/Heat-stroke.jpg">
			<h1 class = "">
						UV Status : {{this.uv_result}}
			</h1>
		</b-carousel-slide>

		<b-carousel-slide caption="QuitLine 1800 438 2000"
							img-src="/assets/img/smoke.jpg" 
		>
		</b-carousel-slide>

		<!-- <b-carousel-slide img-src="/assets/img/mosquito.jpg">
		</b-carousel-slide> -->

		<!-- Slides with custom text -->
		<!-- <b-carousel-slide img-src="https://picsum.photos/1024/480/?image=54">
			<h1>
						PSI Status : {{this.psi_status}}
						PSI Value : {{this.psi_result}}
			</h1>
		</b-carousel-slide> -->
    </b-carousel>

	
  </div>
</template>

<script>
import { Carousel } from 'bootstrap-vue/es/components';
   
   export default {
	    mounted() { 
            axios.get(`https://api.data.gov.sg/v1/environment/uv-index?date=`+ this.date)
            .then(res => {                    
                this.uv_result = res.data.api_info.status;
            });

            axios.get(`https://api.data.gov.sg/v1/environment/psi`)
            .then(res => {                    
                this.psi_status = res.data.api_info.status;
                this.psi_result = res.data.items[0].readings.psi_twenty_four_hourly.national;
            });

             axios.get(`https://data.gov.sg/api/action/datastore_search?resource_id=d68321b6-c438-425d-b9f4-d5777eee9e77&q=alcohol`)
            .then(res => {                 
                this.accident = res.data.result.records;
            });
        },

		data () {
			return {
			slide: 0,
			sliding: null,
			access_token: '',
			uv_result: '',
			psi_status: '',
			psi_result: '',
			accident:[],
			no_of_accidents:0,
			}
		},

		computed: {
            date() {
                var myDate = new Date();
                var month = ('0' + (myDate.getMonth() + 1)).slice(-2);
                var date = ('0' + myDate.getDate()).slice(-2);
                var year = myDate.getFullYear();
                return year + '-' + month + '-' + date;
            },
        },

		watch: {
			accident(){
				this.accident.forEach((item, index, array) => {
					this.no_of_accidents += parseInt(item.number_of_accidents);
				});
			},
        },

		methods: {
			onSlideStart (slide) {
				this.sliding = true
			},

			onSlideEnd (slide) {
				this.sliding = false
			},
		}
	}
     
</script>
