<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        Alpine.data('signIn', () => ({
            phone_number: '',
            password: '',
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
                    Alpine.store('service').signIn(sendData).catch(error => {
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
                        'phone_number': this.phone_number.replace(/\D/g, '')
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
                        if (result.errors) {
                            this.messages = result.errors;
                        } else {
                            this.messages.message = [result.message];
                        }

                        throw result.errors || {
                            message: [result.message]
                        };
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
                Alpine.store('modal').registration = true;
            },
            closeModal() {
                Alpine.store('modal').signIn = false;
                
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
        }))
    });
</script>

<div class="section-modal modal-form" x-data="signIn" x-cloak x-show="$store.modal.signIn" id="login-modal">
    <div class="wrapper-modal">
        <div class="container-form" x-show="!confirmPasswordForm">
            <div class="head">
                <img src="{{ asset('assets/media/icons/stars_blue.svg') }}" alt="" class="decoration">
                <h3 x-text="forgotPassword == true ? '{!! trans('front.sign_in.forgot') !!}' : '{!! trans('front.sign_in.title') !!}'"></h3>
                <img src="{{ asset('assets/media/icons/close-icon_01.svg') }}" alt="" class="close-icon" @click="closeModal()">
            </div>
            <div class="body">
                <form class="form">
                    <div class="input-row">
                        <input x-model="phone_number" type="text" class="input mask_phone" placeholder="{!! trans('front.sign_in.login') !!}" autocomplete="username">
                        <span x-cloak x-show="messages?.phone_number?.[0]" x-text="messages?.phone_number?.[0]"></span>
                    </div>

                    <div class="input-row" x-show="forgotPassword == false">
                        <input x-model="password" type="password" class="input" placeholder="{!! trans('front.sign_in.password') !!}" autocomplete="current-password">
                        <span x-cloak x-show="messages?.password?.[0]" x-text="messages?.password?.[0]"></span>
                    </div>

                    <div class="input-row">
                        <span x-cloak x-show="messages?.message?.[0]" x-text="messages?.message?.[0]"></span>
                    </div>
                    <a href="#" @click.prevent="showForgotPasswordForm()" x-show="forgotPassword == false">
                        <p class="caption">{!! trans('front.sign_in.forgot') !!}</p>
                    </a>
                    <button type="button" class="button" @click="signIn()" :disabled="loading" x-show="forgotPassword == false">{!! trans('front.sign_in.enter') !!}</button>


                    <a href="#" @click.prevent="showLoginForm()" x-show="forgotPassword == true">
                        <p class="caption">{!! trans('front.sign_in.enter') !!}</p>
                    </a>
                    <button type="button" class="button" @click="forgotPasswordRequest()" :disabled="loading" x-show="forgotPassword == true">{!! trans('front.sign_in.send') !!}</button>
                </form>
            </div>
            <div class="footer">
                <p>{!! trans('front.sign_in.not_reg') !!}</p>
                <a href="#" @click.prevent="showRegistrationModal()">{!! trans('front.sign_in.reg') !!}</a>
            </div>
        </div>

        <div class="container-form" x-show="confirmPasswordForm">
            <div class="head">
                <p>{!! trans('front.sign_in.email_sent') !!}</p>
                <h3 x-text="email"></h3>
                <img src="{{ asset('assets/media/icons/close-icon_01.svg') }}" alt="" class="close-icon" @click="closeModal()">
            </div>
            <div class="body">
                <form class="form">
                    <div class="input-row">
                        <input x-model="password" type="text" name="code" class="input" placeholder="{!! trans('front.sign_in.code') !!}">
                        <span x-cloak x-show="messages?.password?.[0]" x-text="messages?.password?.[0]"></span>
                    </div>
                    <button type="button" class="button" @click="signIn()" :disabled="loading">{!! trans('front.sign_in.approve') !!}</button>
                </form>
            </div>
            <div class="footer reverse">
                <template x-if="countdown > 0">
                    <div>
                        <p><b>{!! trans('front.sign_in.no_code') !!}</b></p>
                        <p>{!! trans('front.sign_in.resend_coundown') !!}</p>
                    </div>
                </template>
                <template x-if="countdown == 0">
                    <a href="#" @click.prevent="forgotPasswordRequest()">{!! trans('front.sign_in.resend') !!}</a>
                </template>
            </div>
        </div>
    </div>
</div>