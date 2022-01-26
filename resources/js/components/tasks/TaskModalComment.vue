<template>
    <div class="task-modal-comments">
        <p v-for="comment in comments" :key="'comment_' + comment.id">
            <small class="d-block">{{ comment.user.name }}</small>
            {{ comment.text }}
        </p>

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
                this.comments = response.data
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
                this.getComments()
            ))
        }
    },
}
</script>
