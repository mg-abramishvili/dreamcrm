<template>
    <div class="d-flex align-items-center">
        <input v-model="name" type="text" class="form-control w-50">
        <button @click="save()" class="btn btn-lg btn-primary ms-2">Сохранить</button>
    </div>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            name: ''
        }
    },
    created() {
        this.name = this.user.name
    },
    mounted() {
        document.getElementsByTagName('input')[0].focus()
    },
    methods: {
        save() {
            axios.put(`/api/user/${this.user.uid}/update`, {
                name: this.name
            })
            .then(response => {
                this.$parent.loadUser()
                this.$parent.views.changeName = false
            })
        }
    }
}
</script>
