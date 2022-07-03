<template>
    <div class="offcanvas offcanvas-end show" tabindex="-1" id="offcanvasRight" role="dialog">
        <div class="offcanvas-header">
            <h4 id="offcanvasRightLabel" class="m-0">Примечания</h4>
            <button @click="$parent.closeOffcanvas()" type="button" class="btn-close text-reset"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                <textarea v-model="comment" class="form-control mb-2"></textarea>
                
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
                comment: '',
            }
        },
        created() {
            this.comment = this.project.comment
        },
        methods: {
            save() {
                axios.put(`/api/project/${this.project.id}/update`, {
                    comment: this.comment
                })
                .then(response => {
                    this.$parent.loadProject()
                    this.$parent.closeOffcanvas()
                })
            }
        }
    }
</script>