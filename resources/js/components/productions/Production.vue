<template>
    <div class="projects-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h1 class="h3 m-0">
                            <router-link :to="{name: 'Productions'}" class="back-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            </router-link>
                            <strong>
                                {{ production.name }}
                            </strong>
                        </h1>    
                    </div>
                    <!-- <div class="col-4 text-end">
                        <button @click="restart()" class="btn btn-outline-danger">restart</button>
                        <button @click="delConfirm()" class="btn btn-outline-danger">Удалить</button>
                    </div> -->
                </div>
            </div>
        </div>

        <Loader v-if="views.loading"></Loader>

        <div v-if="!views.loading" class="tab">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a @click="selectTab('general')" class="nav-link" :class="{'active': selected.tab == 'general'}" role="tab">Общая информация</a>
                </li>
                <li class="nav-item">
                    <a @click="selectTab('items')" class="nav-link" :class="{'active': selected.tab == 'items'}" role="tab">Сборка</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" :class="{'active': selected.tab == 'general'}" role="tabpanel">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <p class="d-flex align-items-center">
                                <strong class="me-2">Статус:</strong>

                                <span>
                                    <template v-if="production.status == 'new'">
                                        Новый
                                    </template>
                                    <template v-if="production.status == 'svarka'">
                                        Сварка
                                    </template>
                                    <template v-if="production.status == 'building'">
                                        Сборка
                                    </template>
                                    <template v-if="production.status == 'warehouse'">
                                        Склад
                                    </template>
                                    <template v-if="production.status == 'ready_for_supply'">
                                        Готово к отгрузке
                                    </template>
                                    <template v-if="production.status == 'waiting_for_feedback'">
                                        Ждем отзыв клиента
                                    </template>
                                </span>
                                
                                <small @click="changePanel('status')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeStatus v-if="views.changePanel == 'status'" :production="production" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Приоритет:</strong>

                                <span>
                                    <template v-if="production.priority == 'normal'">
                                        <span class="badge rounded-pill bg-info"></span>
                                        Нормальный
                                    </template>
                                    <template v-if="production.priority == 'high'">
                                        <span class="badge rounded-pill bg-warning"></span>
                                        Высокий
                                    </template>
                                    <template v-if="production.priority == 'urgent'">
                                        <span class="badge rounded-pill bg-danger"></span>
                                        Срочный
                                    </template>
                                </span>

                                <small @click="changePanel('priority')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangePriority v-if="views.changePanel == 'priority'" :production="production" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Пользователь:</strong>

                                <template v-if="production.user.name">
                                    {{ production.user.name }}
                                </template>
                                <template v-if="!production.user.name">
                                    &mdash;
                                </template>
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Цвет (RAL):</strong>

                                <span>
                                    <template v-if="production.ral">
                                        {{ production.ral }}
                                    </template>
                                    <template v-if="!production.ral">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('ral')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeRal v-if="views.changePanel == 'ral'" :production="production" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Оплата:</strong>

                                <span>
                                    <template v-if="production.payment_type">
                                        {{ production.payment_type }}
                                    </template>
                                    <template v-if="!production.payment_type">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('paymentType')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangePaymentType v-if="views.changePanel == 'paymentType'" :production="production" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Данные для доставки:</strong>

                                <span>
                                    <template v-if="production.supply_info">
                                        {{ production.supply_info }}
                                    </template>
                                    <template v-if="!production.supply_info">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('supplyInfo')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeSupplyInfo v-if="views.changePanel == 'supplyInfo'" :production="production" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Номер счета:</strong>

                                <span>
                                    <template v-if="production.invoice_number">
                                        {{ production.invoice_number }}
                                    </template>
                                    <template v-if="!production.invoice_number">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('invoiceNumber')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeInvoiceNumber v-if="views.changePanel == 'invoiceNumber'" :production="production" />
                            </p>

                            <p v-if="production.additional_hardware" class="d-flex align-items-center">
                                <strong class="me-2">Доп. оборудование:</strong>

                                <span>{{ production.additional_hardware }}</span>
                            </p>

                            <p v-if="production.additional_for_tribune" class="d-flex align-items-center">
                                <strong class="me-2">Доп. для трибун:</strong>

                                <span>{{ production.additional_for_tribune }}</span>
                            </p>
                        </div>
                        <div class="col-12 col-lg-6">
                            <p class="d-flex align-items-center">
                                <strong class="me-2">Дата начала:</strong>

                                <span>
                                    <template v-if="production.start_date">
                                        {{ production.start_date | date }}
                                    </template>
                                    <template v-if="!production.start_date">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('startDate')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeStartDate v-if="views.changePanel == 'startDate'" :production="production" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Срок завершения:</strong>

                                <span>
                                    <template v-if="production.end_date">
                                        {{ production.end_date | date }}
                                    </template>
                                    <template v-if="!production.end_date">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('endDate')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeEndDate v-if="views.changePanel == 'endDate'" :production="production" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Контакты:</strong>

                                <span>
                                    <template v-if="production.contacts">
                                        {{ production.contacts }}
                                    </template>
                                    <template v-if="!production.contacts">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('contacts')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeContacts v-if="views.changePanel == 'contacts'" :production="production" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">E-mail:</strong>

                                <span>
                                    <template v-if="production.email">
                                        {{ production.email }}
                                    </template>
                                    <template v-if="!production.email">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('email')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeEmail v-if="views.changePanel == 'email'" :production="production" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Серийный номер:</strong>

                                <span>
                                    <template v-if="production.serial_number">
                                        {{ production.serial_number }}
                                    </template>
                                    <template v-if="!production.serial_number">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('serialNumber')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeSerialNumber v-if="views.changePanel == 'serialNumber'" :production="production" />
                            </p>

                            <p class="d-flex align-items-center">
                                <strong class="me-2">Ключ активации:</strong>

                                <span>
                                    <template v-if="production.activation_key">
                                        {{ production.activation_key }}
                                    </template>
                                    <template v-if="!production.activation_key">
                                        &mdash;
                                    </template>
                                </span>

                                <small @click="changePanel('activationKey')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </small>

                                <ChangeActivationKey v-if="views.changePanel == 'activationKey'" :production="production" />
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <hr>
                            <strong class="me-2">Описание:</strong>
                            <div v-if="production.description" v-html="production.description"></div>
                            <template v-if="!production.description">&mdash;</template>

                            <small @click="changePanel('description')" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </small>

                            <ChangeDescription v-if="views.changePanel == 'description'" :production="production" />
                        </div>
                    </div>
                </div>
                <div class="tab-pane" :class="{'active': selected.tab == 'items'}" role="tabpanel">
                    <table class="table dataTable">
                        <tbody>
                            <tr v-for="item in production.items">
                                <td style="width: 42.5%;">
                                    {{ item.stock_item.name }}
                                </td>
                                <td style="width: 42.5%;">
                                    <ul class="m-0">
                                        <li v-for="reserve in item.reserves" class="my-2" style="line-height: 1;">
                                            {{ reserve.quantity }} шт из склада <span class="text-muted">{{ reserve.price_total | currency }}₽</span><br>
                                            <small class="text-muted">Поступление от {{ reserve.stock_balance.date | date }} / {{ reserve.price | currency }}₽ ({{ reserve.pre_rub | currency }}₽ + ${{ reserve.pre_usd | currency }})</small>
                                        </li>
                                        <li v-for="need in item.stock_needs" class="text-danger my-2" style="line-height: 1;">
                                            не хватает {{ need.quantity }} шт
                                        </li>
                                    </ul>
                                </td>
                                <td style="width: 15%; text-align: right;">
                                    <!-- <button @click="changeProductionItem(item)" class="btn btn-sm btn-outline-secondary">заменить</button> -->
                                    <!-- <button @click="delItem(item.id)">удалить</button> -->
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <ChangeProductionItem v-if="views.changeProductionItem" :productionItem="selected.productionItem" />
                </div>
            </div>
        </div>

        <div v-if="views.backdrop" @click="closeOffcanvas()" class="offcanvas-backdrop fade show"></div>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'
    import ChangeStatus from './comps/ChangeStatus'
    import ChangePriority from './comps/ChangePriority'
    import ChangeRal from './comps/ChangeRal'
    import ChangeProductionItem from './comps/ChangeProductionItem'
    import ChangePaymentType from './comps/ChangePaymentType'
    import ChangeSupplyInfo from './comps/ChangeSupplyInfo'
    import ChangeInvoiceNumber from './comps/ChangeInvoiceNumber'
    import ChangeStartDate from './comps/ChangeStartDate'
    import ChangeEndDate from './comps/ChangeEndDate'
    import ChangeContacts from './comps/ChangeContacts'
    import ChangeEmail from './comps/ChangeEmail'
    import ChangeSerialNumber from './comps/ChangeSerialNumber'
    import ChangeActivationKey from './comps/ChangeActivationKey'
    import ChangeDescription from './comps/ChangeDescription'

    export default {
        data() {
            return {
                production: {},

                selected: {
                    tab: 'general',
                    productionItem: '',
                },

                views: {
                    loading: true,
                    backdrop: false,
                    changePanel: '',
                    changeProductionItem: false,
                }
            }
        },
        created() {
            this.loadProduction()
        },
        methods: {
            loadProduction() {
                axios
                .get(`/api/production/${this.$route.params.id}`)
                .then(response => {
                    this.production = response.data

                    this.views.loading = false
                })
            },
            selectTab(tab) {
                this.selected.tab = tab
            },
            delItem(id) {
                axios.delete(`/api/production-item/${id}`)
                .then(response => {
                    this.loadProduction()
                })
            },
            changePanel(panel) {
                this.views.backdrop = true
                this.views.changePanel = panel
            },
            changeProductionItem(item) {
                this.selected.productionItem = item
                this.views.backdrop = true
                this.views.changeProductionItem = true
            },
            closeOffcanvas() {
                this.views.backdrop = false
                this.views.changePanel = ''
                this.views.changeProductionItem = false
            },
            delConfirm() {
                this.$swal({
                    html: '<strong class="d-block mb-3">Точно удалить производство?</strong> Будут также удалены все резервы на складе и запросы в Закупки.',
                    showCancelButton: true,
                    confirmButtonText: 'Да',
                    cancelButtonText: 'Отмена',
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.del()
                    } else if (result.isDenied) {
                        return
                    }
                })
            },
            del() {
                axios.delete(`/api/production/${this.$route.params.id}/delete`)
                .then(response => {
                    this.$router.push({name: 'Productions'})
                })
                .catch(error => {
                    this.$swal({
                        text: 'Ошибка',
                        icon: 'error',
                    })
                })
            },
            restart() {
                axios.get(`/api/production/${this.$route.params.id}/restart`)
                .then(response => {
                    this.loadProduction()
                })
                .catch(error => {
                    this.$swal({
                        text: 'Ошибка',
                        icon: 'error',
                    })
                })
            },
        },
        components: {
            Loader,
            ChangeStatus,
            ChangePriority,
            ChangeRal,
            ChangeProductionItem,
            ChangePaymentType,
            ChangeSupplyInfo,
            ChangeInvoiceNumber,
            ChangeStartDate,
            ChangeEndDate,
            ChangeContacts,
            ChangeEmail,
            ChangeSerialNumber,
            ChangeActivationKey,
            ChangeDescription,
        }
    }
</script>