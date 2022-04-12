<template>
    <div class="client-page">
        <div v-if="!innData" class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">
                    <strong>
                        Новый клиент
                    </strong>
                </h1>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 v-if="innData" class="text-primary">Добавление клиента</h4>

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
                            <input v-model="inn" type="text" class="form-control" :disabled="innData">
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

                <button @click="saveClient()" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['innData'],
        data() {
            return {
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
            if(this.innData) {
                this.inn = this.innData
            }
        },
        methods: {
            saveClient() {
                this.errors = []

                if (!this.name) {
                    this.errors.push('Укажите имя');
                }

                if(this.errors && this.errors.length > 0) {
                    return
                }

                axios.post(`/api/clients`,
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
                .then(response => {
                    if(this.innData && response) {
                        this.$parent.client.id = response.data.id
                        this.$parent.client.name = response.data.name
                        this.$parent.goToRegistration('normal')
                        return
                    }

                    this.$router.push({name: 'Clients'})
                })
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