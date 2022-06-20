<template>
    <div>
        <table v-if="offers.length" class="table table-hover dataTable">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>№</th>
                    <th>Скидка</th>
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
                        <p class="d-flex align-items-center">
                            <span v-if="offer.discount > 0">{{ offer.discount }}%</span>
                            <span v-else>&mdash;</span>

                            <small @click="changePanel('discount')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </small>

                            <ChangeDiscount v-if="views.changePanel == 'discount'" :offer="offer" />
                        </p>
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

        <div v-if="views.backdrop" @click="closeOffcanvas()" class="offcanvas-backdrop fade show"></div>
    </div>
</template>

<script>
    import ChangeDiscount from './ChangeOfferDiscount'

    export default {
        props: ['project_id', 'offers'],
        data() {
            return {
                views: {
                    createOfferButton: true,
                    recreatePdfButton: true,
                    backdrop: false,
                    changePanel: '',
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
                    this.$parent.loadProject()
                }))
            },
            recreatePDF(id) {
                this.views.recreatePdfButton = false

                axios.get(`/api/offer/${id}/pdf`)
                .then((response => {
                    this.views.recreatePdfButton = true
                }))
                .catch((error) => {
                    if(error.response) {
                        for(var key in error.response.data.errors){
                            console.log(key)
                        }
                    }
                    console.log(error.response)
                })
            },
            changePanel(panel) {
                this.views.backdrop = true
                this.views.changePanel = panel
            },
            closeOffcanvas() {
                this.views.backdrop = false
                this.views.changePanel = ''
            },
        },
        components: {
            ChangeDiscount
        }
    }
</script>