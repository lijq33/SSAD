<!-- Author: Li JinQuan -->
<template>
	<div class = " tw-cursor-pointer time-timepicker" ref = "timepicker">
		<div class = "tw-w-full tw-flex">
			<input type = "text" :value = "timeValue"
			       class = "tw-w-3/5  tw-bg-grey-lighter tw-appearance-none tw-cursor-pointer tw-bg-grey-lighter
                            tw-rounded-l tw-w-full tw-py-2 tw-px-4 tw-text-grey-darker tw-leading-tight tw-h-10 tw-border"
							disabled
			>
			
			<div class = "tw-rounded-r tw-border tw-w-2/5 tw-flex tw-flex-col tw-justify-center tw-h-10 tw-w-10">
	            <span class = "tw-flex tw-justify-center tw-text-grey-dark tw-text-lg">
	                <i class = "fas fa-clock"></i>
	            </span>
			</div>
		</div>

		<div class = "tw-w-full">
			<div :id = "containerName" class = "time-picker"></div>
		</div>
	</div>
</template>

<script>
	export default {
		model: {
			prop:  'time',
			event: 'update',
		},

		mounted() {
			if (this.timeFormat === undefined) {
				this.timeFormat = '';
			}

			this.containerName = 'timepicker-container' + Math.floor(Math.random() * 99999);

			setTimeout(() => {
				this.init();
			}, 3000);

		},

		// 12hours vs 24hours format
		props: [ 'format', 'time', 'useContainer' ],

		data() {
			return {
				timeFormat:    this.format,
				timeValue:     this.time,
				containerName: '',
			};
		},

		watch: {
			time() {
				this.timeValue = this.time;
			},

			timeValue() {
				this.$emit('update', this.timeValue);
			}
		},

		methods: {
			init() {
				let el = $(this.$refs.timepicker);

				let container = (this.useContainer) ? '#' + this.containerName : 'body';

				el.timepicker({
					appendWidgetTo: container,
				});

				// DONT use ECMA6
				el.timepicker().on('changeTime.timepicker', function (e) {
					this.timeValue = e.time.value;
				}.bind(this));
			}
		}
	}
</script>
<style>
.glyphicon-chevron-down{
    width:100px;
    display:block;
    height:20px;
    background:white;
    color:white;
    text-decoration:none;
    padding-left:20px;
}
.glyphicon-chevron-down:before{
    content: '';
    background:url('https://image.flaticon.com/icons/svg/3/3907.svg');
    background-size:cover;
        position:absolute;
    width:20px;
    height:20px;
    margin-left:-20px;
}
.glyphicon-chevron-up{
    width:100px;
    display:block;
    height:20px;
    background:white;
    color:white;
    text-decoration:none;
    padding-left:20px;
}
.glyphicon-chevron-up:before{
    content: '';
    background:url('https://image.flaticon.com/icons/svg/25/25223.svg');
    background-size:cover;
        position:absolute;
    width:20px;
    height:20px;
    margin-left:-20px;
}

</style>