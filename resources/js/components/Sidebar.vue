<template>
    <nav class="sidebar">
        <div class="sidebar-content">
            <div class="sidebar-user">
                <router-link :to="{name: 'User', params: {uid: $parent.user.uid}}">
                    <div class="sidebar-user-avatar">
                        <img v-if="$parent.user.avatar" :src="$parent.user.avatar" />
                        <img v-else src="/img/no-image.jpg" />
                    </div>
                    {{ $parent.user.name }}
                </router-link>
            </div>
            <ul class="sidebar-nav">
                <li>
                    <router-link :to="{name: 'Home'}" class="sidebar-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layout align-middle"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                        <span class="align-middle">Главная</span>
                    </router-link>
                </li>
                <li v-if="this.$parent.user.permissions && this.$parent.user.permissions.can_see_calculations" class="sidebar-item">
                    <router-link :to="{name: 'Calculations'}" class="sidebar-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sliders align-middle"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>
                        <span class="align-middle">Расчеты</span>
                    </router-link>
                </li>
                <li v-if="this.$parent.user.permissions && this.$parent.user.permissions.can_see_catalog" class="sidebar-item">
                    <router-link :to="{ name: 'Catalog' }" class="sidebar-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-monitor align-middle"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                        <span class="align-middle">Каталог</span>
                    </router-link>
                </li>
                <li v-if="this.$parent.user.permissions && this.$parent.user.permissions.can_see_stock" class="sidebar-item">
                    <router-link :to="{ name: 'Stock' }" class="sidebar-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-package align-middle"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        <span class="align-middle">Склад</span>
                    </router-link>
                </li>
                <li v-if="this.$parent.user.permissions && this.$parent.user.permissions.can_see_all_clients" class="sidebar-item">
                    <router-link :to="{name: 'Clients'}" class="sidebar-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase align-middle"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                        <span class="align-middle">Клиенты</span>
                    </router-link>
                </li>
                <li class="sidebar-item">
                    <router-link :to="{name: 'Tasks'}" class="sidebar-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square align-middle"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                        <span class="align-middle">Задачи</span>
                    </router-link>
                </li>
                <!-- <li v-if="this.$parent.user.permissions && this.$parent.user.permissions.can_see_users" class="sidebar-item">
                    <router-link :to="{name: 'Users'}" class="sidebar-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users align-middle"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span class="align-middle">Пользователи</span>
                    </router-link>
                </li> -->
            </ul>

        </div>
    </nav>
</template>

<script>
    export default {
        data() {
            return {
            }
        },
        created() {
        },
    }
</script>