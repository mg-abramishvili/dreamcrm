<template>
    <loader v-if="views.loading"></loader>
    
    <div v-else class="mb-4">
        <div class="calculation-left-block-main-label">
            <strong>Тип</strong>
        </div>
        <select v-model="selected.type" @change="changeType()" class="form-select form-select-lg mt-2 mb-3">
            <option v-for="type in types" :key="'type_' + type.id" :value="type">{{ type.name }}</option>
        </select>
        <div class="mt-4">
            <button class="btn btn-outline-primary" disabled>Назад</button>
            <button @click="$parent.viewBoxes()" class="btn btn-outline-primary">Далее</button>
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
            changeType() {
                this.$parent.selected.type = this.selected.type
                this.$parent.selected.box = {}
                this.$parent.resetCatalogItems()
            }
        },
    }
</script>