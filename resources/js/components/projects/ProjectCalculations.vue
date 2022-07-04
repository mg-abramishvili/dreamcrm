<template>
    <div>
        <button @click="addCalculation()" class="btn btn-sm btn-outline-primary">Добавить расчет</button>
        
        <table class="table table-hover dataTable">
            <thead>
                <tr>
                    <th>Дата расчета</th>
                    <th>№</th>
                    <th>Корпус</th>
                    <th>Стоимость</th>
                </tr>
            </thead>
            <tbody>
                <tr @click="goTo(calculation.id)" v-for="calculation in project.calculations" :key="calculation.id">
                    <td class="align-middle">
                        {{ calculation.created_at | date }}
                    </td>
                    <td class="align-middle">
                        Расчет №{{ calculation.id }}
                    </td>
                    <td class="align-middle">
                        {{ calculation.box }}
                    </td>
                    <td class="align-middle">
                        {{ calculation.price | currency }} ₽
                    </td>
                </tr>
            </tbody>
        </table>

        <AddCalculation v-if="views.addCalculation" :project="project" />

        <div v-if="views.backdrop" @click="closeOffcanvas()" class="offcanvas-backdrop fade show"></div>
    </div>
</template>

<script>
    import Loader from './../Loader.vue'
    import AddCalculation from './comps/AddCalculation.vue'

    export default {
        props: ['project'],
        data() {
            return {
                views: {
                    backdrop: false,
                    addCalculation: false,
                }
            }
        },
        created() {
            //
        },
        methods: {
            goTo(id) {
                this.$router.push({ name: 'Calculation', params: { id: id } });
            },
            addCalculation() {
                this.views.backdrop = true
                this.views.addCalculation = true
            },
            closeOffcanvas() {
                this.views.backdrop = false
                this.views.addCalculation = false
            },
        },
        components: {
            Loader,
            AddCalculation
        }
    }
</script>