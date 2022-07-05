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
                    <th>Скидка</th>
                    <th>Итого</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="calculation in project.calculations" :key="calculation.id">
                    <td class="align-middle">
                        {{ calculation.created_at | date }}
                    </td>
                    <td class="align-middle">
                        <div @click="goTo(calculation.id)">Расчет №{{ calculation.id }}</div>
                    </td>
                    <td class="align-middle">
                        {{ calculation.box }}
                    </td>
                    <td class="align-middle">
                        {{ calculation.price | currency }} ₽
                    </td>
                    <td class="align-middle">
                        <p class="d-flex align-items-center">
                            <span v-if="calculation.discount > 0">{{ calculation.discount }}%</span>
                            <span v-else>&mdash;</span>

                            <small @click="changeDiscount(calculation)" class="cursor-pointer text-muted fw-normal ms-2" style="opacity: 50%;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="d-block feather feather-edit align-middle me-2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </small>
                        </p>
                    </td>
                    <td class="align-middle">
                        {{ calculation.price_with_discount | currency }} ₽
                    </td>
                </tr>
            </tbody>
        </table>

        <AddCalculation v-if="views.addCalculation" :project="project" />

        <ChangeDiscount v-if="views.changeDiscount" :calculation="selected.calculation" />

        <div v-if="views.backdrop" @click="closeOffcanvas()" class="offcanvas-backdrop fade show"></div>
    </div>
</template>

<script>
    import Loader from './../Loader.vue'
    import AddCalculation from './comps/AddCalculation.vue'
    import ChangeDiscount from './comps/ChangeCalculationDiscount.vue'

    export default {
        props: ['project'],
        data() {
            return {
                selected: {
                    calculation: '',
                },

                views: {
                    backdrop: false,
                    addCalculation: false,
                    changeDiscount: false,
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
            changeDiscount(calculation) {
                this.selected.calculation = calculation

                this.views.backdrop = true
                this.views.changeDiscount = true
            },
            closeOffcanvas() {
                this.views.backdrop = false
                this.views.addCalculation = false
                this.views.changeDiscount = false
            },
        },
        components: {
            Loader,
            AddCalculation,
            ChangeDiscount
        }
    }
</script>