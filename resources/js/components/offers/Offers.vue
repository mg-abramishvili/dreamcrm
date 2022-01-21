<template>
    <div>
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">Коммерческие предложения</h1>
            </div>
        </div>

        <div class="card">
            <div class="card-body pt-1">
                <table class="table table-striped table-hover dataTable no-footer dtr-inline">
                    <thead>
                        <tr>
                            <th>Дата</th>
                            <th>№</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="offer in offers" :key="offer.id" @click="goTo(offer.id)">
                            <td class="align-middle">
                                {{moment(offer.created_at).utcOffset(180).format('DD.MM.YYYY HH:mm')}}
                            </td>
                            <td class="align-middle">
                                КП №{{ offer.id }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                offers: [],
                moment: moment,
            }
        },
        created() {
            axios
                .get('/api/offers')
                .then(response => (
                    this.offers = response.data
                ));
        },
        methods: {
            goTo(id) {
                this.$router.push({ name: 'Offer', params: { id: id } });
            }
        },
        components: {
        }
    }
</script>

<style scoped>
    .table tr:hover {
        cursor: pointer;
    }
</style>