<template>
    <div v-if="item && item.id && item.id > 0" class="stock-item-page">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">
                    <strong>
                        {{ item.name }}
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

                <button @click="updateItem(item.id)" class="btn btn-primary">Сохранить</button>
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
                item: {},

                name: '',
                category_id: '',

                errors: [],
            }
        },
        created() {
            this.loadItem()
        },
        methods: {
            loadItem() {
                axios.get(`/api/stock/item/${this.$route.params.id}`)
                    .then((response => {
                        this.item = response.data

                        this.category_id = response.data.category_id
                    }))
            },
            updateItem(id) {
                this.errors = []

                if (!this.name) {
                    this.errors.push('Укажите имя');
                }

                if(this.errors && this.errors.length > 0) {
                    return
                }

                axios.put(`/api/stock/item/${id}/update`,
                {
                    name: this.name,
                    category_id: this.category_id,
                })
                .then(response => (
                    this.$router.push({name: 'Stock'})
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