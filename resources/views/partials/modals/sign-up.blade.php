<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        Alpine.data('signUp', () => ({
            loading: false,
            showConfirmPage: false,

            phone_number: '',
            name: '',
            email: '',
            password: '',

            messages: {},

            async signUp() {
                this.clearMessages();
                this.loading = true;
                try {

                    const response = await fetch('/api/{{ app()->getLocale() }}/signUp', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            'phone_number': this.phone_number,
                            'name': this.name,
                            'email': this.email
                        })
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

                    this.showConfirmPage = true;
                    this.startCountdown();
                } catch (error) {
                    console.error('Error:', error);
                } finally {
                    this.loading = false;
                }
            },

            async reSendSms() {
                try {
                    this.loading = true;

                    const response = await fetch('/api/{{ app()->getLocale() }}/reSendSms', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            'phone_number': this.phone_number
                        })
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

                    this.showConfirmPage = true;
                    this.startCountdown();
                } catch (error) {
                    console.error('Error:', error);
                } finally {
                    this.loading = false;
                }
            },

            async signIn() {
                try {
                    this.loading = true;
                    const sendData = {
                        'phone_number': this.phone_number.replace(/\D/g, ''),
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
            clearMessages() {
                this.messages = {};
            },
            showSignInModal() {
                this.closeModal();
                Alpine.store('modal').signIn = true;
            },
            closeModal() {
                Alpine.store('modal').registration = false;
                this.clearMessages();
                this.clearInterval();
                this.phone_number = '';
                this.password = '';
                this.email = '';
                this.name = '';
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

<div class="section-modal modal-form" x-data="signUp" x-cloak x-show="$store.modal.registration" id="register-modal">
    <div class="wrapper-modal">
        <div class="container-form" x-show="!showConfirmPage">
            <div class="head">
                <img src="{{ asset('assets/media/icons/stars_blue.svg') }}" alt="" class="decoration">
                <h3>{!! trans('front.sign_up.title') !!}</h3>
                <img src="{{ asset('assets/media/icons/close-icon_01.svg') }}" alt="" class="close-icon" @click="closeModal()">
            </div>
            <div class="body">
                <form class="form">
                    <div class="input-row">
                        <input x-model="name" type="text" name="name" class="input" placeholder="{!! trans('front.sign_up.login') !!}">
                        <span x-cloak x-show="messages?.name?.[0]" x-text="messages?.name?.[0]"></span>
                    </div>
                    <div class="input-row">
                        <input x-model="phone_number" type="number" name="phone" class="input mask_phone" placeholder="{!! trans('front.sign_up.phone_number') !!}">
                        <span x-cloak x-show="messages?.phone_number?.[0]" x-text="messages?.phone_number?.[0]"></span>
                    </div>
                    <div class="input-row">
                        <input x-model="email" type="email" name="email" class="input" placeholder="E-Mail">
                        <span x-cloak x-show="messages?.email?.[0]" x-text="messages?.email?.[0]"></span>
                    </div>
                    <div class="input-row">
                        <span x-cloak x-show="messages?.message?.[0]" x-text="messages?.message?.[0]"></span>
                    </div>
                    <button type="button" class="button" @click="signUp()" :disabled="loading">{!! trans('front.sign_up.reg') !!}</button>

                    <p class="caption">
                        {!! trans('front.sign_up.bottom_text') !!}
                    </p>
                </form>
            </div>
            <div class="footer">
                <p>{!! trans('front.sign_up.alredy_reg') !!}</p>
                <a href="#" @click.prevent="showSignInModal()">{!! trans('front.sign_up.sing_in') !!}</a>
            </div>
        </div>

        <div class="container-form" x-show="showConfirmPage">
            <div class="head">
                <p>{!! trans('front.sign_up.sms_sent') !!}</p>
                <h3 x-text="phone_number"></h3>
                <img src="{{ asset('assets/media/icons/close-icon_01.svg') }}" alt="" class="close-icon" @click="closeModal()">
            </div>
            <div class="body">
                <form class="form">
                    <div class="input-row">
                        <input x-model="password" type="number" name="code" class="input" placeholder="{!! trans('front.sign_up.code') !!}">
                        <span x-cloak x-show="messages?.phone_number?.[0]" x-text="messages?.phone_number?.[0]"></span>
                    </div>
                    <div class="input-row">
                        <span x-cloak x-show="messages?.message?.[0]" x-text="messages?.message?.[0]"></span>
                    </div>
                    <button type="button" class="button" @click="signIn()" :disabled="loading">{!! trans('front.sign_up.approve') !!}</button>
                </form>
            </div>
            <div class="footer reverse">
                <template x-if="countdown > 0">
                    <div>
                        <p><b>{!! trans('front.sign_up.no_code') !!}</b></p>
                        <p>{!! trans('front.sign_up.resend_countdown') !!}</p>
                    </div>
                </template>
                <template x-if="countdown == 0">
                    <a href="#" @click.prevent="reSendSms()">{!! trans('front.sign_up.resend') !!}</a>
                </template>
            </div>
        </div>
    </div>
</div>