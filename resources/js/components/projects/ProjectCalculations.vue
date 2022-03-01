<template>
    <div>
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
                <tr @click="goTo(calculation.id)" v-for="calculation in calculations" :key="calculation.id">
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
                        {{ calculation.price | currency }} ₽
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: ['calculations'],
        data() {
            return {
                //
            }
        },
        created() {
            //
        },
        methods: {
            goTo(id) {
                this.$router.push({ name: 'Calculation', params: { id: id } });
            }
        },
    }
</script>