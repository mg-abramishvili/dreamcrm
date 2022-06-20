<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Скидка</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <input v-model="discount" type="number" min="0" :max="$parent.$parent.$parent.user.max_discount" class="form-control mb-2" />
                
                <button @click="save()" :disabled="!views.saveButton" class="btn btn-primary mb-4">Сохранить</button>

                <Loader v-if="views.loading"></Loader>
            </div>
        </div>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'

    export default {
        props: ['offer'],
        data() {
            return {
                discount: '',

                views: {
                    loading: false,
                    saveButton: true,
                }
            }
        },
        created() {
            this.discount = this.offer.discount
        },
        methods: {
            save() {
                this.views.loading = true
                this.views.saveButton = false

                axios.put(`/api/offer/${this.offer.id}/update`, {
                    discount: this.discount
                })
                .then(response => {
                    this.views.loading = true
                    this.views.saveButton = false

                    this.$parent.$parent.loadProject()
                    this.$parent.closeOffcanvas()
                })
                .catch(errors => {
                    this.views.loading = true
                    this.views.saveButton = false
                })
            }
        },
        components: {
            Loader
        }
    }
</script>
