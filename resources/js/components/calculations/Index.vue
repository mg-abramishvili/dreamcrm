<template>
    <div class="calculations-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">Расчеты</h1>
                    </div>
                    <div class="col-12 col-lg-6 text-end">
                        <router-link :to="{name: 'CalculationCreate'}" class="btn btn-primary">Новый расчет</router-link>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="calculations && calculations.length" class="card card-bordered">
            <div class="card-body p-0">
                <table class="table table-hover dataTable">
                    <thead>
                        <tr>
                            <th>Дата расчета</th>
                            <th>№</th>
                            <th>Корпус</th>
                            <th>Автор расчета</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="calculation in calculations" :key="calculation.id" @click="goTo(calculation.id)">
                            <td class="align-middle">
                                {{ calculation.created_at | date }}
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
                                {{ calculation.user.name }}
                            </td>
                            <td class="align-middle">
                                <template v-if="calculation.project && calculation.project.id">
                                    <router-link :to="{name: 'Project', params: {id: calculation.project.id}}" class="btn btn-sm btn-outline-primary">{{ calculation.project.name }}</router-link>
                                </template>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <Loader v-else></Loader>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'

    export default {
        data() {
            return {
                calculations: [],
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
            Loader
        }
    }
</script>

<style scoped>
    .table tr:hover {
        cursor: pointer;
    }
</style>