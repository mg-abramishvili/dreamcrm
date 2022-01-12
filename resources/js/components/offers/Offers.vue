<template>
    <div>
        {{ offers }}
    </div>
</template>

<script>
    export default {
        data() {
            return {
                offers: [],
            }
        },
        created() {
            this.loadOffers()
        },
        methods: {
            loadOffers() {
                axios
                .get('/api/offers', {responseType: 'blob'})
                .then((response => {
                    const url = window.URL.createObjectURL(new Blob([response.data]))
                    const link = document.createElement('a')
                    link.href = url
                    link.setAttribute('download', 'file.pdf')
                    document.body.appendChild(link)
                    link.click()
                }))
            }
        }
    }
</script>