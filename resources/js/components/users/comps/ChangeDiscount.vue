<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Макс. скидка для юзера</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <input v-model="max_discount" type="number" min="0" class="form-control mb-2" />
                
                <button @click="save()" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['user'],
        data() {
            return {
                max_discount: '',
            }
        },
        created() {
            this.max_discount = this.user.max_discount
        },
        methods: {
            save() {
                axios.put(`/api/user/${this.user.uid}/update`, {
                    max_discount: this.max_discount
                })
                .then(response => {
                    this.$parent.loadUser()
                    this.$parent.closeOffcanvas()
                })
            }
        }
    }
</script>
