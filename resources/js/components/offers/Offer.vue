<template>
    <div>
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">
                    КП №{{ offer.id }} от {{moment(offer.created_at).utcOffset(180).format('DD.MM.YYYY HH:mm')}}
                </h1>    
            </div>
        </div>

        <div v-if="offer && offer.id > 0" class="card">
            <div class="card-body">
                <button @click="getPDF(offer.id)" class="btn btn-primary">Скачать PDF</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                offer: {},
                moment: moment,
            }
        },
        created() {
            axios
                .get(`/api/offer/${this.$route.params.id}`)
                .then(response => (
                    this.offer = response.data
                ));
        },
        methods: {
            getPDF(id) {
                axios
                .get(`/api/offer/${id}/pdf`, {responseType: 'blob'})
                .then((response => {
                    const url = window.URL.createObjectURL(new Blob([response.data]))
                    const link = document.createElement('a')
                    link.href = url
                    link.setAttribute('download', 'КП_' + this.offer.id + '.pdf')
                    document.body.appendChild(link)
                    link.click()
                }))
            }
        },
    }
</script>