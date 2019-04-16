<!-- Author: Li JinQuan -->
<template>
    <div class = "tw-flex tw-flex-row tw-cursor-pointer">
        <div class = "tw-flex tw-w-full">
            <input type = "text" ref = "datepicker" v-model = "dateValue" 
                class = "tw-w-3/5 tw-bg-grey-lighter  tw-appearance-none tw-cursor-pointer
                    tw-rounded-l tw-w-full tw-py-2 tw-px-4 tw-text-grey-darker tw-leading-tight tw-h-10 tw-border">

            <div class ="tw-rounded-r tw-border tw-w-2/5 tw-flex tw-flex-col tw-justify-center tw-h-10">
                <span class = "tw-flex tw-justify-center tw-text-grey-dark">
                    <i class = "fa fa-calendar"></i>
                </span>
            </div>
        </div>

        <div :id = "containerName" class = "date-picker tw-relative"></div>
    </div>
</template>

<script>
export default {
    model: {
        prop: 'date',
        event: 'update',    
    },

    created() {

        if (this.dateFormat === undefined) {
            this.dateFormat = 'dd/mm/yyyy';
        }

        this.containerName = 'datepicker-container' +  Math.floor(Math.random() * 99999);

        setTimeout(() => {
            this.init();
        }, 3000);
    },

    props: ['format', 'date'],

    data() {
        return {
            dateFormat: this.format,
            dateValue: this.date,
            containerName: '',
        };
    },

    watch: {
        date() {
            this.dateValue = this.date;
        },

        dateValue() {
            this.$emit('update', this.dateValue);
        }
    },

    methods: {
        init() {
            let el = $(this.$refs.datepicker);
            // Configuration options is available here
            // https://uxsolutions.github.io/bootstrap-datepicker
            el.datepicker({
                format: this.dateFormat,
                todayHighlight: true,
                autoclose: true,
                container: '#'+this.containerName,
                startDate: "-50d",
                endDate: "0",
                todayBtn: "linked"
            });

            el.datepicker().on('changeDate.datepicker', function(e) {
                this.dateValue = e.format(this.dateFormat);
            }.bind(this));
        }
    }

}
</script>
