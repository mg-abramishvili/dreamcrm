<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Детали остатка</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="list-group">
                <li v-for="reserve in reserves" class="list-group-item">
                    <strong class="text-muted text-sm">Производство:</strong><br> {{ reserve.production }}<br>
                    <strong class="text-muted text-sm">Кол-во:</strong><br>  {{ reserve.quantity }} шт.
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['balance'],
        data() {
            return {
                reserves: [],
            }
        },
        created() {
            this.loadReserves()
        },
        methods: {
            loadReserves() {
                axios.get(`/api/reserves-by-balance/${this.balance.id}`)
                .then(response => {
                    this.reserves = response.data.data
                })
            },
        },
    }
</script>