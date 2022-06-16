<template>
    <div class="form-check form-switch">
        <input v-model="dark_mode" @change="save()" class="form-check-input" type="checkbox" id="darkModeCheckbox">
        <label class="form-check-label" for="darkModeCheckbox">Темная тема</label>
    </div>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            dark_mode: ''
        }
    },
    created() {
        this.dark_mode = this.user.dark_mode
    },
    methods: {
        save() {
            axios.put(`/api/user/${this.user.uid}/update`, {
                dark_mode: this.dark_mode
            })
            .then(response => {
                // this.$parent.loadUser()
                window.location.href = '/user/' + this.user.uid
            })
        }
    }
}
</script>
