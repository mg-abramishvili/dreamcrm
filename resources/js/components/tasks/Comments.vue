<template>
    <div v-if="views.loading == false" class="task-modal-comments">
        <div v-for="comment in comments" :key="'comment_' + comment.id" class="chat-message-left pb-4">
            <div>
                <img v-if="comment.user.avatar" :src="comment.user.avatar" class="rounded-circle me-1" alt="" width="40" height="40">
                <img v-else src="/img/no-image.jpg" class="rounded-circle me-1" alt="" width="40" height="40">
                <div class="text-muted text-comment-date small text-nowrap mt-2" >
                    {{ comment.created_at | formatDateTimeOnly }}<br>
                    {{ comment.created_at | formatDate }}
                </div>
            </div>
            <div class="w-100 flex-shrink-1 bg-light rounded py-2 px-3 ms-3">
                <div class="fw-bold mb-1">{{ comment.user.name }}</div>
                <div v-html="$options.filters.findLinks(comment.text)"></div>
            </div>
        </div>

        <textarea v-model="text" class="form-control mb-2" placeholder="Напишите комментарий" style="resize: vertical;"></textarea>
        <button v-if="text.length > 0" @click="saveComment()" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right align-middle"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
        </button>
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
            if(this.text && this.text.length > 0) {
                axios
                .post(`/api/task/${this.task_id}/comments`, {
                    task_id: this.task_id,
                    user_id: this.$parent.$parent.$parent.user.id,
                    text: this.text
                })
                .then(response => (
                    this.text = '',
                    this.getComments(),
                    this.$parent.$parent.getColumns()
                ))
            }
        }
    },
    filters: {
        findLinks(value) {
            return value.replace(
                /((http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?)/g,
                '<a href="$1" target="_blank">$1</a>'
            );
        }
    },
}
</script>
