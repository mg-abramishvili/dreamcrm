<template>
    <div>
        <!-- <h1>DreamApp</h1> -->
        <div class="row">
            <div class="col-12 col-sm-6 col-xxl-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body py-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h1 class="illustration-text lh-1 mb-2">{{ time | formatDateTimeOnly }}</h1>
                                <p class="mb-0 fw-normal lh-1">{{ date | formatDateLong }}</p>
                                <!-- <p class="mb-0">AppStack Dashboard</p> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xxl-3 d-flex">
                <div class="card flex-fill">
                    <div class="card-body py-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="mb-3 lh-1">Курс доллара ({{ usd.date | formatDateShort }})</p>
                                <h3 class="mb-0 lh-1">{{ usd.kurs }} ₽</h3>
                            </div>
                            <div class="d-inline-block ms-3">
                                <div class="stat">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign align-middle text-success"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
    </div>
</template>
<script>    
    export default {
        data() {
            return {
                date: '',
                time: '',

                usd: {
                    kurs: '',
                    date: '',
                },

                moment: moment,
            }
        },
        mounted() {
            setInterval(this.timeCurrent, 1000)
        },
        created() {
            this.loadDate()
            this.loadUsd()
        },
        methods: {
            loadDate() {
                axios
                .get(`/api/date`)
                .then(response => (
                    this.date = response.data,
                    this.time = response.data
                ))
            },
            loadUsd() {
                axios
                .get('/api/usd')
                .then((response => {
                    this.usd.kurs = response.data.kurs,
                    this.usd.date = response.data.updated_at
                }))
            },
            timeCurrent:function() {
                this.time = moment(this.time).add(1, 'seconds')
            },
        },
    }
</script>