<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Добавить расчет</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <select v-model="selected.calculation" class="form-select mb-2">
                    <option v-for="calculation in calculations" :value="calculation.id">
                        [{{ calculation.created_at | date }}] [{{ calculation.id }}] {{ calculation.box }}
                    </option>
                </select>
                
                <button @click="save()" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['project'],
        data() {
            return {
                calculations: [],

                selected: {
                    calculation: '',
                },
            }
        },
        created() {
            this.loadCalculations()
        },
        methods: {
            loadCalculations() {
                axios.get('/api/calculations')
                .then(response => {
                    this.calculations = response.data.data.filter(c => c.project_id == null)
                })
            },
            save() {
                axios.put(`/api/calculation/${this.selected.calculation}/update`, {
                    project_id: this.project.id
                })
                .then(response => {
                    this.$parent.$parent.loadProject()
                    this.$parent.closeOffcanvas()
                })
            }
        }
    }
</script>