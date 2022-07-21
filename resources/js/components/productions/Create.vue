<template>
    <div class="projects-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">Отправка проекта в производство</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-bordered">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-8">
                        <div class="mb-3">
                            <label class="form-label">Название проекта</label>
                            <input v-model="name" type="text" class="form-control">
                        </div>
                    </div>

                    <div class="col-12 col-lg-4">
                        <div class="mb-3">
                            <label class="form-label">Приоритет</label>
                            <select v-model="priority" class="form-select">
                                <option value="normal">Нормальный</option>
                                <option value="high">Высокий</option>
                                <option value="urgent">Срочный</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-lg-3">
                        <div class="mb-3">
                            <label class="form-label">Оплата</label>
                            <select v-model="payment_type" class="form-select">
                                <option value="Предоплата 100%">Предоплата 100%</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-lg-3">
                        <div class="mb-3">
                            <label class="form-label">Номер счета</label>
                            <input v-model="invoice_number" type="text" class="form-control">
                        </div>
                    </div>

                    <div class="col-12 col-lg-3">
                        <div class="mb-3">
                            <label class="form-label">Дата начала</label>
                            <input v-model="start_date" type="date" class="form-control">
                        </div>
                    </div>

                    <div class="col-12 col-lg-3">
                        <div class="mb-3">
                            <label class="form-label">Срок завершения</label>
                            <input v-model="end_date" type="date" class="form-control">
                        </div>
                    </div>

                    <div class="col-12 col-lg-3">
                        <div class="mb-3">
                            <label class="form-label">Цвет RAL</label>
                            <input v-model="ral" type="text" class="form-control">
                        </div>
                    </div>

                    <div class="col-12 col-lg-9">
                        <div class="mb-3">
                            <label class="form-label">Данные для доставки</label>
                            <input v-model="supply_info" type="text" class="form-control">
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Контакты</label>
                            <input v-model="contacts" type="text" class="form-control">
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">E-mail</label>
                            <input v-model="email" type="text" class="form-control">
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Серийный номер</label>
                            <input v-model="serial_number" type="text" class="form-control">
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">Ключ активации</label>
                            <input v-model="activation_key" type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Описание</label>
                    <textarea v-model="description" class="form-control"></textarea>
                </div>

                <button @click="save()" v-if="views.saveButton" class="btn btn-primary">Cохранить</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                project: {},

                name: '',
                priority: 'normal',
                payment_type: '',
                supply_info: '',
                invoice_number: '',
                serial_number: '',
                activation_key: '',
                ral: '',
                contacts: '',
                email: '',
                description: '',
                start_date: moment().format('YYYY-MM-DD'),
                end_date: '',

                views: {
                    saveButton: true,
                }
            }
        },
        created() {
            this.loadProject()
        },
        methods: {
            loadProject() {
                axios.get(`/api/project/${this.$route.params.project_id}`)
                .then(response => {
                    this.project = response.data.data

                    let name = []

                    response.data.data.calculations.forEach(calculation => {
                        name.push(calculation.box + ' ' + calculation.quantity + 'шт')
                    })

                    this.name = name.join(' - ') + ' - ' + response.data.data.client.name
                })
            },
            save() {
                if(!this.name) {
                    return this.$swal({
                        text: 'Укажите название',
                        icon: 'error',
                    })
                }

                this.views.saveButton = false

                axios.post(`/api/productions`, {
                    project: this.$route.params.project_id,
                    name: this.name,
                    priority: this.priority,
                    payment_type: this.payment_type,
                    supply_info: this.supply_info,
                    invoice_number: this.invoice_number,
                    serial_number: this.serial_number,
                    activation_key: this.activation_key,
                    ral: this.ral,
                    contacts: this.contacts,
                    email: this.email,
                    description: this.description,
                    start_date: this.start_date,
                    end_date: this.end_date,
                })
                .then(response => {
                    if(response.data) {
                        this.$router.push({name: 'Production', params: {id: response.data}})
                    }
                })
                .catch(errors => {
                    this.views.saveButton = true
                })
            }
        },
    }
</script>