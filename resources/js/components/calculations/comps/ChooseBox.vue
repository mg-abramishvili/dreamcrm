<template>
    <div class="mb-4">
        <div class="calculation-left-block-main-label">
            <strong>Корпус</strong>
        </div>

        <loader v-if="views.loading"></loader>
        
        <select v-else v-model="selected.box" class="form-select form-select-lg mt-2 mb-3">
            <template v-for="box in boxes">
                <option v-if="box.width > 0 && box.length > 0 && box.height > 0 && box.weight > 0" :key="'box_' + box.id" :value="box">
                    {{ box.name }} &mdash; {{ box.price | currency }} ₽
                </option>
            </template>
        </select>
        
        <div class="mt-4">
            <button @click="goBack()" class="btn btn-outline-primary">Назад</button>
            <button @click="goNext()" class="btn btn-outline-primary">Далее</button>
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

                    this.$parent.selected.box = {}
                }
            }
        },
        methods: {
            loadBoxes() {
                axios
                .get(`/api/catalog/boxes/type/${this.type_id}`)
                .then((response => {
                    this.boxes = response.data
                    this.views.loading = false
                }))
            },
            goBack() {
                this.$parent.goToStep('type')
            },
            goNext() {
                if(!this.selected.box.id) {
                    this.$swal({
                        text: 'Укажите корпус',
                        icon: 'error',
                    })
                    return
                }

                this.$parent.selected.box = this.selected.box

                this.$parent.goToStep('catalog')
            },
        },
    }
</script>