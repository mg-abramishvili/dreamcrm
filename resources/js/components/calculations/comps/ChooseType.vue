<template>    
    <div class="mb-4">
        <div class="calculation-left-block-main-label">
            <strong>Тип</strong>
        </div>

        <loader v-if="views.loading"></loader>

        <select v-else v-model="selected.type" class="form-select form-select-lg mt-2 mb-3">
            <option v-for="type in types" :key="'type_' + type.id" :value="type">{{ type.name }}</option>
        </select>
        
        <div class="mt-4">
            <button class="btn btn-outline-primary" disabled>Назад</button>
            <button @click="goNext()" class="btn btn-outline-primary">Далее</button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                types: [],

                selected: {
                    type: {},
                },

                views: {
                    loading: true,
                }
            }
        },
        created() {
            this.loadTypes()
        },
        methods: {
            loadTypes() {
                axios
                .get('/api/catalog/types')
                .then((response => {
                    this.types = response.data
                    this.views.loading = false
                }))
            },
            goNext() {
                if(!this.selected.type.id) {
                    this.$swal({
                        text: 'Укажите тип',
                        icon: 'error',
                    })
                    return
                }

                this.$parent.selected.type = this.selected.type

                this.$parent.goToStep('box')
            }
        },
    }
</script>