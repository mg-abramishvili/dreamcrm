<template>
    <div v-if="client && client.id && client.id > 0" class="client-page">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">
                    <strong>
                        {{ client.name }}
                    </strong>
                </h1>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div v-if="errors && errors.length > 0" class="alert alert-danger">
                    <div class="alert-message">
                        <strong v-for="(error, index) in errors" :key="'error_' + index" class="d-block">
                            {{ error }}
                        </strong>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Имя</label>
                    <input v-model="name" type="text" class="form-control">
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="mb-3">
                            <label>Телефон</label>
                            <input v-model="tel" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="mb-3">
                            <label>E-mail</label>
                            <input v-model="email" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="mb-3">
                            <label>Контактное лицо</label>
                            <input v-model="kont_litso" type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="mb-3">
                            <label>ИНН</label>
                            <input v-model="inn" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="mb-3">
                            <label>КПП</label>
                            <input v-model="kpp" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="mb-3">
                            <label>ОГРН</label>
                            <input v-model="ogrn" type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="mb-3">
                            <label>Юридический адрес</label>
                            <input v-model="yur_address" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="mb-3">
                            <label>Почтовый адрес</label>
                            <input v-model="pocht_address" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="mb-3">
                            <label>Фактический адрес</label>
                            <input v-model="fakt_address" type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-3">
                        <div class="mb-3">
                            <label>Расчетный счет</label>
                            <input v-model="ras_schet" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="mb-3">
                            <label>Корр.счет</label>
                            <input v-model="korr_schet" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="mb-3">
                            <label>БИК</label>
                            <input v-model="bik" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="mb-3">
                            <label>Банк</label>
                            <input v-model="bank" type="text" class="form-control">
                        </div>
                    </div>
                </div>

                <button @click="updateClient(client.id)" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
    <div v-else>
        <div class="spinner-border text-primary me-2" role="status">
            <span class="sr-only">Загрузка...</span>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                client: {},

                name: '',
                tel: '',
                email: '',
                kont_litso: '',
                yur_address: '',
                pocht_address: '',
                fakt_address: '',
                inn: '',
                kpp: '',
                ogrn: '',
                ras_schet: '',
                korr_schet: '',
                bank: '',
                bik: '',

                errors: [],
            }
        },
        created() {
            this.loadClient()
        },
        methods: {
            loadClient() {
                axios.get(`/api/client/${this.$route.params.id}`)
                    .then((response => {
                        this.client = response.data

                        this.name = response.data.name
                        this.tel = response.data.tel
                        this.email = response.data.email
                        this.kont_litso = response.data.kont_litso
                        this.yur_address = response.data.yur_address
                        this.pocht_address = response.data.pocht_address
                        this.fakt_address = response.data.fakt_address
                        this.inn = response.data.inn
                        this.kpp = response.data.kpp
                        this.ogrn = response.data.ogrn
                        this.ras_schet = response.data.ras_schet
                        this.korr_schet = response.data.korr_schet
                        this.bank = response.data.bank
                        this.bik = response.data.bik
                    }))
            },
            updateClient(id) {
                this.errors = []

                if (!this.name) {
                    this.errors.push('Укажите имя');
                }

                if(this.errors && this.errors.length > 0) {
                    return
                }

                axios.put(`/api/client/${id}/update`,
                {
                    name: this.name,
                    tel: this.tel,
                    email: this.email,
                    kont_litso: this.kont_litso,
                    yur_address: this.yur_address,
                    pocht_address: this.pocht_address,
                    fakt_address: this.fakt_address,
                    inn: this.inn,
                    kpp: this.kpp,
                    ogrn: this.ogrn,
                    ras_schet: this.ras_schet,
                    korr_schet: this.korr_schet,
                    bank: this.bank,
                    bik: this.bik,
                })
                .then(response => (
                    this.$router.push({name: 'Clients'})
                ))
                .catch((error) => {
                    if(error.response) {
                        this.errors = []
                        for(var key in error.response.data.errors){
                            this.errors.push(key)
                        }
                    }
                })
            },
        },
    }
</script>