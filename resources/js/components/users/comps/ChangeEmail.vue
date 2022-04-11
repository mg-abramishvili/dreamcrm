<template>
    <div class="d-flex align-items-center">
        <input v-model="email" type="text" class="form-control w-50">
        <button @click="save()" class="btn btn-lg btn-primary ms-2">Сохранить</button>
    </div>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            email: ''
        }
    },
    created() {
        this.email = this.user.email
    },
    mounted() {
        document.getElementsByTagName('input')[0].focus()
    },
    methods: {
        save() {
            axios.put(`/api/user/${this.user.uid}/update`, {
                email: this.email
            })
            .then(response => {
                this.$parent.loadUser()
                this.$parent.views.changeEmail = false
            })
        }
    }
}
</script>
