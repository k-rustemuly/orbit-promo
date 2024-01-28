<div class="section-modal modal-form" x-data="{
    phone_number: '77782284032',
    password: '973956',
    messages: {},
    loading: false,
    forgotPassword: false,
    email: '',
    confirmPasswordForm: false,
    async signIn() {
        this.clearMessages();
        this.loading = true;
        try {
            this.loading = true;
            const sendData = {
                'phone_number': this.phone_number,
                'password': this.password
            }
            $store.service.signIn(sendData).catch(error => {
                this.messages = error;
            });
        } catch (error) {
            console.error('Error:', error);
        } finally {
            this.loading = false;
        }
    },
    async forgotPasswordRequest() {
        this.clearMessages();
        this.loading = true;
        try {
            const sendData = {
                'phone_number': this.phone_number
            }
            const response = await fetch('/api/{{ app()->getLocale() }}/forgotPassword', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(sendData)
            });

            const result = await response.json();

            if (!result.success) {
                if(result.errors) {
                    this.messages = result.errors;
                } else {
                    this.messages.message = [result.message];
                }
                
                throw result.errors || { message: [result.message] };
            }
           
            this.email = result.data.email;
            this.confirmPasswordForm = true;
            this.startCountdown();
        } catch (error) {
            console.error('Error:', error);
        } finally {
            this.loading = false;
        }
    },
    showForgotPasswordForm() {
        this.password = '';
        this.clearMessages();
        this.forgotPassword = true;
    },
    showLoginForm() {
        this.clearMessages();
        this.forgotPassword = false;
    },
    showRegistrationModal() {
        this.closeModal();
        $store.modal.registration = true;
    },
    closeModal() {
        $store.modal.signIn = false;
        this.clearMessages();
        this.phone_number = '';
        this.password = '';
        this.email = '';
        this.forgotPassword = false;
        this.confirmPasswordForm = false;
    },
    clearMessages() {
        this.messages = {};
    },
    countdown: null,
    interval: null,
    startCountdown() {
        this.countdown = 60;
        this.interval = setInterval(() => {
            this.countdown--;
            if (this.countdown === 0) {
                this.clearInterval();
            }
        }, 1000);
    },
    clearInterval() {
        clearInterval(this.interval);
    }
}" x-cloak x-show="$store.modal.signIn" id="login-modal">
    <div class="wrapper-modal">
        <div class="container-form" x-show="!confirmPasswordForm">
            <div class="head">
                <img src="{{ asset('assets/media/icons/stars_blue.svg') }}" alt="" class="decoration">
                <h3 x-text="forgotPassword == true ? 'Забыли пароль?' : 'ВХОД'"></h3>
                <img src="{{ asset('assets/media/icons/close-icon_01.svg') }}" alt="" class="close-icon" @click="closeModal()">
            </div>
            <div class="body">
                <form class="form">
                    <div class="input-row">
                        <input x-model="phone_number" type="text" class="input" placeholder="Номер телефона или E-mail" autocomplete="username">
                        <span x-cloak x-show="messages?.phone_number?.[0]" x-text="messages?.phone_number?.[0]"></span>
                    </div>
                    
                    <div class="input-row" x-show="forgotPassword == false">
                        <input x-model="password" type="password" class="input" placeholder="Пароль" autocomplete="current-password">
                        <span x-cloak x-show="messages?.password?.[0]" x-text="messages?.password?.[0]"></span>
                    </div>

                    <div class="input-row">
                        <span x-cloak x-show="messages?.message?.[0]" x-text="messages?.message?.[0]"></span>
                    </div>
                    <a href="#" @click.prevent="showForgotPasswordForm()" x-show="forgotPassword == false">
                        <p class="caption">Забыли пароль?</p>
                    </a>
                    <button type="button" class="button" @click="signIn()" :disabled="loading" x-show="forgotPassword == false">Войти</button>
                            

                    <a href="#" @click.prevent="showLoginForm()" x-show="forgotPassword == true">
                        <p class="caption">Вход</p>
                    </a>
                    <button type="button" class="button" @click="forgotPasswordRequest()" :disabled="loading" x-show="forgotPassword == true">Отправить</button>
                </form>
            </div>
            <div class="footer">
                <p>НЕ ЗАРЕГИСТРИРОВАН?</p>
                <a href="#" @click.prevent="showRegistrationModal()">Зарегистрироваться</a>
            </div>
        </div>
        
		<div class="container-form" x-show="confirmPasswordForm">
			<div class="head">
				<p>Сообщение с кодом-паролем <br>отправлено на E-mail</p>
				<h3 x-text="email"></h3>
				<img src="{{ asset('assets/media/icons/close-icon_01.svg') }}" alt="" class="close-icon" @click="closeModal()">
			</div>
			<div class="body">
				<form class="form">
					<div class="input-row">
						<input x-model="password" type="text" name="code" class="input" placeholder="Код-пароль ">
						<span x-cloak x-show="messages?.password?.[0]" x-text="messages?.password?.[0]"></span>
					</div>
					<button type="button" class="button" @click="signIn()" :disabled="loading">ПОДТВЕРДИТЬ</button>
				</form>
			</div>
			<div class="footer reverse">
                <template x-if="countdown > 0">
                    <div>
                        <p><b>НЕ ПРИШЕЛ КОД?</b></p>
                        <p>Повторно код-пароль на почту можно запросить через <span x-text="countdown"></span> секунд</p>
                    </div>
                </template>
                <template x-if="countdown == 0">
				    <a href="#" @click.prevent="forgotPasswordRequest()">ОТПРАВИТЬ СНОВА</a>
                </template>
			</div>
		</div>
    </div>
</div>