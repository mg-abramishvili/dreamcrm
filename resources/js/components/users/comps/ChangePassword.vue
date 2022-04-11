<template>
    <div class="d-flex align-items-center">
        <input v-model="password" type="text" placeholder="Новый пароль" class="form-control w-50">
        <button @click="save()" class="btn btn-lg btn-primary ms-2">Сохранить</button>
    </div>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            password: ''
        }
    },
    mounted() {
        document.getElementsByTagName('input')[0].focus()
    },
    methods: {
        save() {
            axios.put(`/api/user/${this.user.uid}/update`, {
                password: this.password
            })
            .then(response => {
                this.$parent.loadUser()
                this.$parent.views.changePassword = false
            })
        }
    }
}
</script>
