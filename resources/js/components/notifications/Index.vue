<template>
    <div>
        <div class="row align-items-center mb-4">
            <div class="col-12 col-lg-6">
                <h1 class="h3 m-0">Центр уведомлений</h1>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-striped table-hover dataTable no-footer dtr-inline">
                    <tbody>
                        <tr v-for="notification in notifications" :key="notification.id">
                            <td class="align-middle">
                                {{ notification.name }}
                                <template v-if="notification.task && notification.task.name">
                                    <strong>{{ notification.task.name }}</strong>
                                </template>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                notifications: [],
            }
        },
        created() {
            this.loadNotifications()
        },
        methods: {
            loadNotifications() {
                axios
                .get(`/api/notifications/${this.$parent.user.id}`)
                .then(response => (
                    this.notifications = response.data
                ))
            },
        },
    }
</script>