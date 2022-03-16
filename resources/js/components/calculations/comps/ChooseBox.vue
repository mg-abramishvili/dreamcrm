<template>
    <loader v-if="views.loading"></loader>
    
    <div v-else class="mb-4">
        <div class="calculation-left-block-main-label">
            <strong>Корпус</strong>
        </div>
        <select v-model="selected.box" @change="changeBox()" class="form-select form-select-lg mt-2 mb-3">
            <template v-for="box in boxes">
                <option v-if="box.width > 0 && box.length > 0 && box.height > 0 && box.weight > 0" :key="'box_' + box.id" :value="box">{{ box.name }} &mdash; {{ box.price | currency }} ₽</option>
            </template>
        </select>
        <div class="mt-4">
            <button @click="$parent.viewTypes()" class="btn btn-outline-primary">Назад</button>
            <button @click="$parent.viewCategories()" class="btn btn-outline-primary">Далее</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['type_id'],
        data() {
            return {
                boxes: [],

                selected: {
                    box: {},
                },

                views: {
                    loading: true,
                }
            }
        },
        watch: {
            type_id: {
                handler() {
                    this.loadBoxes()
                }
            }
        },
        methods: {
            loadBoxes() {
                if(!this.type_id) {
                    alert('Не указан тип')
                    return
                }

                axios
                .get(`/api/catalog/boxes/type/${this.type_id}`)
                .then((response => {
                    this.boxes = response.data
                    this.views.loading = false
                }))
            },
            changeBox() {
                this.$parent.selected.box = this.selected.box
                this.$parent.loadCatalogItems()
                this.$parent.resetCatalogItems()
            }
        },
    }
</script>