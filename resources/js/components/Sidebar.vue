<template>
    <nav class="sidebar">
        <div class="sidebar-content">
            <!-- <div class="sidebar-brand">
                <router-link :to="{name: 'Projects'}">
                    <img src="/img/logo.svg" alt="DreamApp">
                    <span>DreamApp CRM</span>
                </router-link>
            </div> -->
            <div class="sidebar-user">
                <router-link :to="{name: 'CalculationCreate'}">
                    <div class="sidebar-user-avatar">
                        <img src="https://eu.ui-avatars.com/api/?background=0D8ABC&color=fff" />
                    </div>
                    {{ $parent.user.name }}
                </router-link>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-header">
					Проекты и КП
				</li>
                <li class="sidebar-item">
                    <router-link :to="{name: 'CalculationCreate'}" class="sidebar-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle align-middle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                        <span class="align-middle">Новый расчёт</span>
                    </router-link>
                </li>
                <li class="sidebar-item">
                    <router-link :to="{name: 'Calculations'}" class="sidebar-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sliders align-middle"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>
                        <span class="align-middle">Расчеты</span>
                    </router-link>
                </li>
                <li class="sidebar-item">
                    <router-link :to="{name: 'Offers'}" class="sidebar-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text align-middle"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <span class="align-middle">КП</span>
                    </router-link>
                </li>
                <li class="sidebar-item">
                    <router-link :to="{name: 'Projects'}" class="sidebar-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-briefcase align-middle"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path></svg>
                        <span class="align-middle">Проекты</span>
                        <span class="badge badge-sidebar-primary">{{ $parent.counter_projects }}</span>
                    </router-link>
                </li>
                <li class="sidebar-item">
                    <router-link :to="{name: 'Clients'}" class="sidebar-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users align-middle"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span class="align-middle">Клиенты</span>
                        <span class="badge badge-sidebar-primary">{{ $parent.counter_clients }}</span>
                    </router-link>
                </li>
                <li class="sidebar-item">
                    <router-link :to="{ name: 'Catalog' }" class="sidebar-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list align-middle"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                        <span class="align-middle">Каталог</span>
                        <span class="badge badge-sidebar-primary">{{ $parent.counter_elements_boxes }}</span>
                    </router-link>
                </li>
                <li class="sidebar-header">
					Пользователи
				</li>
                <!--<li class="sidebar-item">
                    <router-link to="/tasks" class="sidebar-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square align-middle"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                        <span class="align-middle">Задачи</span>
                        <span class="badge badge-sidebar-primary">1</span>
                    </router-link>
                </li>-->
                <li class="sidebar-item">
                    <router-link to="/users" class="sidebar-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users align-middle"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span class="align-middle">Пользователи</span>
                        <span class="badge badge-sidebar-primary">{{ $parent.counter_users }}</span>
                    </router-link>
                </li>
                <li class="sidebar-header">
					Новости
				</li>
                <li class="sidebar-item">
                    <router-link to="/announcements" class="sidebar-link">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square align-middle"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
                        <span class="align-middle">Новости</span>
                        <!--<span class="badge badge-sidebar-primary">1</span>-->
                    </router-link>
                </li>
            </ul>

        </div>
    </nav>
</template>

<script>
    export default {
        data() {
            return {
                moment: moment,
            }
        },
        created() {
        },
        components: {
        }
    }
</script>