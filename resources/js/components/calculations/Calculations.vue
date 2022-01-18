<template>
    <div>
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">Расчеты</h1>
            </div>
        </div>

        <div class="card">
            <div class="card-body pt-1">
                <table class="table table-striped table-hover dataTable no-footer dtr-inline">
                    <thead>
                        <tr>
                            <th>Дата расчета</th>
                            <th>№</th>
                            <th>Корпус</th>
                            <th>Автор расчета</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="calculation in calculations" :key="calculation.id" @click="goTo(calculation.id)">
                            <td class="align-middle text-end">
                                {{moment(calculation.created_at).utcOffset(180).format('DD.MM.YYYY HH:mm')}}
                            </td>
                            <td class="align-middle">
                                Расчет №{{ calculation.id }}
                            </td>
                            <td class="align-middle">
                                <template v-for="box in calculation.boxes">
                                    {{ box.name }}
                                </template>
                            </td>
                            <td class="align-middle">
                                <!-- <img src="/img/profile.png" width="48" height="48" class="rounded-circle me-2" alt="Avatar"> -->
                                <template v-if="calculation.user">
                                    {{ calculation.user.name }}
                                </template>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                calculations: [],
                moment: moment,
            }
        },
        created() {
            axios
                .get('/api/calculations')
                .then(response => (
                    this.calculations = response.data
                ));
        },
        methods: {
            goTo(id) {
                this.$router.push({ name: 'Calculation', params: { id: id } });
            }
        },
        components: {
        }
    }
</script>

<style scoped>
    .table tr:hover {
        cursor: pointer;
    }
</style>