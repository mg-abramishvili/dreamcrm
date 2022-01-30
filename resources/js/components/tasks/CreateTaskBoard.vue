<template>
    <div class="d-inline-flex w-100">
        <input type="text" class="form-control w-50" placeholder="Название новой доски">
        <button class="btn btn-primary mx-1">OK</button>
        <button class="btn btn-outline-danger">&times;</button>
    </div>
</template>

<script>
export default {
        data() {
            return {
                name: '',
            }
        },
        mounted() {
            document.getElementsByClassName('form-control')[0].focus()
        },
        methods: {
        },
    }
</script>