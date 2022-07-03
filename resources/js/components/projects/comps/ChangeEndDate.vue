<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Срок реализации</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <input v-model="end_date" type="text" class="form-control mb-2">
                
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
                end_date: '',
            }
        },
        created() {
            this.end_date = this.project.end_date
        },
        methods: {
            save() {
                axios.put(`/api/project/${this.project.id}/update`, {
                    end_date: this.end_date
                })
                .then(response => {
                    this.$parent.loadProject()
                    this.$parent.closeOffcanvas()
                })
            }
        }
    }
</script>