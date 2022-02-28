<template>
    <div class="projects-page">
        <div class="card card-bordered">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-6">
                        <h1 class="h3 m-0">
                            <router-link :to="{name: 'Projects'}" class="back-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left align-middle me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                            </router-link>
                            <strong>
                                {{ project.name }}
                            </strong>
                        </h1>    
                    </div>
                </div>
            </div>
        </div>

        <div v-if="project && project.id > 0" class="tab">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><a class="nav-link" href="#tab-1" data-bs-toggle="tab" role="tab" aria-selected="false">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#tab-2" data-bs-toggle="tab" role="tab" aria-selected="false">Profile</a></li>
                <li class="nav-item"><a class="nav-link active" href="#tab-3" data-bs-toggle="tab" role="tab" aria-selected="true">Messages</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" id="tab-1" role="tabpanel">
                    <h4 class="tab-title">Default tabs</h4>
                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor tellus eget condimentum rhoncus. Aenean massa. Cum sociis natoque
                        penatibus et magnis neque dis parturient montes, nascetur ridiculus mus.</p>
                    <p>Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate
                        eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.</p>
                </div>
                <div class="tab-pane" id="tab-2" role="tabpanel">
                    <h4 class="tab-title">Another one</h4>
                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor tellus eget condimentum rhoncus. Aenean massa. Cum sociis natoque
                        penatibus et magnis neque dis parturient montes, nascetur ridiculus mus.</p>
                    <p>Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate
                        eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.</p>
                </div>
                <div class="tab-pane active" id="tab-3" role="tabpanel">
                    <h4 class="tab-title">One more</h4>
                    <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor tellus eget condimentum rhoncus. Aenean massa. Cum sociis natoque
                        penatibus et magnis neque dis parturient montes, nascetur ridiculus mus.</p>
                    <p>Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate
                        eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Loader from '../Loader.vue'

    export default {
        data() {
            return {
                project: {},
            }
        },
        created() {
            axios
                .get(`/api/project/${this.$route.params.id}`)
                .then(response => (
                    this.project = response.data
                ));
        },
        components: {
            Loader
        }
    }
</script>