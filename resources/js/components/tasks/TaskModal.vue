<template>
    <div class="modal fade show" tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ task.name }}</h5>
                    <button @click="closeModal()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-3">
                    <div class="row">
                        <div class="col-12 col-lg-9">
                            <p v-if="task.description" class="mb-0">{{ task.description }}</p>
                            <p v-else class="mb-0">Нет описания</p>

                            <TaskModalComment :task_id="task.id"></TaskModalComment>
                        </div>
                        <div class="col-12 col-lg-3">
                            <h6>Участники</h6>
                            <ul>
                                <li v-for="user in task.users" :key="'task_user_' + user.id">
                                    {{ user.name }}
                                </li>
                            </ul>
                            <h6>Действия</h6>
                            <button class="w-100 btn btn-outline-primary mb-2">Перенаправить</button>
                            <button class="w-100 btn btn-success">Задача выполнена</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import TaskModalComment from './TaskModalComment.vue'
    export default {
        props: ['task'],
        data() {
            return {
                //
            }
        },
        methods: {
            closeModal() {
                this.$parent.views.modal = false
            },
        },
        components: {
            TaskModalComment
        },
    }
</script>
