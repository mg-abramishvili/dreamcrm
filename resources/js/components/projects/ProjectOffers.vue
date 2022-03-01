<template>
    <div>
        <table v-if="offers.length" class="table table-hover dataTable">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>№</th>
                    <th>Корпус</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="offer in offers" :key="offer.id">
                    <td class="align-middle">
                        {{ offer.created_at | date }}
                    </td>
                    <td class="align-middle">
                        Коммерческое предложение №{{ offer.id }}
                    </td>
                    <td class="align-middle">
                        <template v-for="calculation in offer.calculations">
                            <template v-for="box in calculation.boxes">
                                {{ box.name }}
                            </template>
                        </template>
                    </td>
                    <td class="align-middle">
                        <a :href="offer.pdf" target="_blank" class="btn btn-sm btn-primary">PDF</a>
                        <button @click="recreatePDF(offer.id)" class="btn btn-sm btn-outline-primary" :disabled="views.recreatePdfButton == false">Обновить</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div v-else>
            <p>Нет коммерческих предложений.</p>
            <button @click="createOffer()" class="btn btn-primary" :disabled="views.createOfferButton == false">Создать КП</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['project_id', 'offers'],
        data() {
            return {
                views: {
                    createOfferButton: true,
                    recreatePdfButton: true
                }
            }
        },
        created() {
            //
        },
        methods: {
            createOffer() {
                this.views.createOfferButton = false

                axios.post(`/api/offers`, {
                    project_id: this.project_id
                })
                .then((response => {
                    this.views.createOfferButton = true
                }))
            },
            recreatePDF(id) {
                this.views.recreatePdfButton = false

                axios.get(`/api/offer/${id}/pdf`)
                .then((response => {
                    this.views.recreatePdfButton = true
                    console.log(response)
                }))
                .catch((error) => {
                    if(error.response) {
                        for(var key in error.response.data.errors){
                            console.log(key)
                        }
                    }
                    console.log(error.response)
                })
            }
        },
    }
</script>