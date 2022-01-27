<template>
    <div v-if="views.loading == false" class="task-modal-comments">
        <div v-for="comment in comments" :key="'comment_' + comment.id" class="chat-message-left pb-4">
            <div>
                <img src="img/no-image.jpg" class="rounded-circle me-1" alt="Bertha Martin" width="40" height="40">
                <div class="text-muted text-comment-date small text-nowrap mt-2" >
                    {{ comment.created_at | formatDateTimeOnly }}<br>
                    {{ comment.created_at | formatDate }}
                </div>
            </div>
            <div class="w-100 flex-shrink-1 bg-light rounded py-2 px-3 ms-3">
                <div class="fw-bold mb-1">{{ comment.user.name }}</div>
                {{ comment.text }}
            </div>
        </div>

        <textarea v-model="text" class="form-control mb-2" placeholder="Напишите комментарий" style="resize: vertical;"></textarea>
        <button @click="saveComment()" class="btn btn-primary">Сохранить</button>
    </div>
</template>

<script>
export default {
    props: ['task_id'],
    data() {
        return {
            comments: [],

            text: '',

            views: {
                loading: true,
            },
        }
    },
    created() {
        this.getComments()
    },
    methods: {
        getComments() {
            axios
            .get(`/api/task/${this.task_id}/comments`)
            .then(response => (
                this.comments = response.data,
                this.views.loading = false
            ))
        },
        saveComment() {
            axios
            .post(`/api/task/${this.task_id}/comments`, {
                task_id: this.task_id,
                user_id: this.$parent.$parent.$parent.user.id,
                text: this.text
            })
            .then(response => (
                this.text = '',
                this.getComments(),
                this.$parent.$parent.getTasks()
            ))
        }
    },
}
</script>
