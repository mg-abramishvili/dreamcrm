<template>
	<div>
		<div v-if="authenticated && authenticated === true" class="wrapper">
			<Sidebar :class="{ collapsed: sidebar === false }" />

			<div class="main">
				<main class="content">
					<div class="container-fluid p-0">
						<router-view :key="$route.path" />
					</div>
				</main>
			</div>
		</div>

		<div v-if="authenticated && authenticated === false" id="auth_form" class="main d-flex justify-content-center w-100">
			<main class="content d-flex p-0">
				<div class="container d-flex flex-column">
					<div class="row h-100">
						<div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
							<div class="d-table-cell align-middle">

								<div class="text-center mt-4 mb-4">
									<h1 class="h2">DreamApp CRM</h1>
								</div>

								<div class="card">
									<div class="card-body">
										<div class="m-sm-4">
											<form @submit.prevent="handleLogin">
												<div v-if="login_error_message.length" class="alert alert-danger alert-dismissible" role="alert">
													<div class="alert-message">
														{{ login_error_message }}
													</div>
												</div>
												<div class="mb-3">
													<label class="form-label">E-mail</label>
													<input type="email" name="email" class="form-control form-control-lg" v-model="formData.email" placeholder="">
												</div>
												<div class="mb-3">
													<label class="form-label">Пароль</label>
													<input type="password" name="password" class="form-control form-control-lg" v-model="formData.password" placeholder="">
													<!--<small>
														<a href="#">Восстановить пароль</a>
													</small>-->
												</div>
												<!--<div>
													<div class="form-check align-items-center">
														<input id="customControlInline" type="checkbox" class="form-check-input" value="remember-me" name="remember-me" checked="">
														<label class="form-check-label text-small" for="customControlInline">Запомнить меня</label>
													</div>
												</div>-->
												<div class="text-center mt-3">
													<button type="submit" class="btn btn-lg btn-primary">Войти</button>
												</div>
											</form>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
			</main>
		</div>

	</div>
</template>

<script>
    import Sidebar from './components/Sidebar'
    export default {
        data() {
            return {
				sidebar: true,
				formData: {
                    email: '',
                    password: ''
                },
				user: {},
				authenticated: true,
				login_error_message: '',
            }
        },
        created() {
			// this.checkMe()
        },
		methods: {
			handleLogin() {
                // send axios request to the login route
                axios.get('/sanctum/csrf-cookie').then(response => {
                    axios.post('/api/login', this.formData).then(response => {
						if(response.data === 'bad_login') {
							this.login_error_message = 'Неверный E-mail или пароль'
						} else {
							this.checkMe()
						}
                    })
                });
            },
			checkMe() {
				axios.post('/api/me').then(response => {
					this.user = response.data
					if(this.user.name && this.user.name.length) {
						this.authenticated = true
						this.counterUsers()
						this.counterProjects()
						this.counterClients()
						this.counterPartners()
						this.counterElementsBoxes()
					} else {
						this.authenticated = false
					}
				})
			},
			sidebar_toggle() {
				if (this.sidebar === false) {
					this.sidebar = true
				} else {
					this.sidebar = false
				}
			},
		},
        components: {
            Sidebar
        }
    }
</script>