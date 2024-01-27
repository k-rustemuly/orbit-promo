@extends('layouts.app')

@section('title', 'Orbit - Главная')
@section('bodyClass', 'landing')

@section('headerContent')
<div class="wrapper-fix wrapper-small wrapper-header">
    <div class="container-header">
        <div class="container-header_column-01">
            <h2 class="title">
                ОТКРОЙ ВТОРОЕ ДЫХАНИЕ
            </h2>
            <img src="{{ asset('assets/media/image_01.png') }}" alt="" class="image mobile">
            <h1 class="title">
                ПОКУПАЙ ORBIT®, <br>
                ИГРАЙ И ВЫИГРЫВАЙ
            </h1>
            <img src="{{ asset('assets/media/image_01.png') }}" alt="" class="image desktop">
            <div class="decorations">
                <img src="{{ asset('assets/media/header_decoration_01.svg') }}" alt="" class="decoration_01">
                <img src="{{ asset('assets/media/header_decoration_02.svg') }}" alt="" class="decoration_02">
            </div>
        </div>
        <div class="container-header_column-02">
            <div class="image">
                <img src="{{ asset('assets/media/image_02.png') }}" alt="" class="image">
            </div>
        </div>
    </div>

    <div class="container-notes">
        <div class="container-notes_column-01">
            <a href="#" class="block-notes block-01">
                <img src="{{ asset('assets/media/note_01.svg') }}" alt="Играть">
            </a>
        </div>
        <div class="container-notes_column-02">
            <a href="#" class="block-notes block-02">
                <img src="{{ asset('assets/media/note_02.svg') }}" alt="Загрузить чек">
            </a>
            <a href="#" class="block-notes block-03">
                <img src="{{ asset('assets/media/note_03.svg') }}" alt="Призы">
            </a>
        </div>
    </div>

    <div class="container-notes-mobile">
        <a href="#" class="block-notes block-01">
            <img src="{{ asset('assets/media/note_01.svg') }}" alt="Играть">
        </a>
        <a href="#" class="block-notes block-02">
            <img src="{{ asset('assets/media/note_02.svg') }}" alt="Загрузить чек">
        </a>
        <a href="#" class="block-notes block-03">
            <img src="{{ asset('assets/media/note_03.svg') }}" alt="Призы">
        </a>
    </div>

</div>
<div class="decorations">
    <div class="decoration_01"></div>
    <img src="{{ asset('assets/media/header_decoration_03.svg') }}" alt="" class="decoration_02">
</div>
@endsection

@section('content')
<div class="wrapper-decoration">
    <section class="wrapper-full section-game">
        <div class="wrapper-fix wrapper-small wrapper-game">
            <div class="container-game">
                <div class="container-game_column-01">
                    <div>
                        <h2 class="title">ИГРА</h2>
                        <p>Готов сыграть в простую, <br>но залипательную игру?</p>
                    </div>
                    <img src="{{ asset('assets/media/image_03.png') }}" alt="Game Image">
                </div>
                <div class="container-game_column-02">
                    <h2 class="title">ИГРА</h2>
                    <p>Готов сыграть в простую, <br>но залипательную игру?</p>
                    <div class="game-caption_block">
                        <div class="game-caption_block-item">
                            <div class="image">
                                <img src="{{ asset('assets/media/game_01.svg') }}" alt="Image">
                            </div>
                            <span>
                                <img src="{{ asset('assets/media/icons/line.svg') }}" alt="">
                            </span>
                            <p>Объединяй одинаковые <br> предметы в ряд </p>
                        </div>
                        <div class="game-caption_block-item">
                            <div class="image">
                                <img src="{{ asset('assets/media/game_02.svg') }}" alt="Image">
                            </div>
                            <span>
                                <img src="{{ asset('assets/media/icons/line.svg') }}" alt="">
                            </span>
                            <p>Качайся на уровнях <br> и копи коины</p>
                        </div>
                        <div class="game-caption_block-item">
                            <div class="image">
                                <img src="{{ asset('assets/media/game_03.svg') }}" alt="Image">
                            </div>
                            <span>
                                <img src="{{ asset('assets/media/icons/line.svg') }}" alt="">
                            </span>
                            <p>Меняй коины на участие <br> в еженедельном розыгрыше</p>
                        </div>
                    </div>
                    <div class="game-action_block">
                        <div>
                            <a href="#" class="button filled">
                                Играть
                            </a>
                            <a href="#" class="button link">Правила акции</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.presents')
</div>

<section class="wrapper-full section-table">
    <div class="wrapper-fix wrapper-small wrapper-table">
        <div class="container-table">
            <div class="table-block fix-width" x-data="{
                loading: false,
                activeTab: '',
                data: {
                    'instant-prizes': [],
                    'weekly': []
                },
                pagination: {
                    links: {},
                    meta: {}
                },
                init() {
                    this.changeTab('instant-prizes');
                },
                changeTab(name) {
                    this.activeTab = name;
                    this.getData();
                },
                async getData(url = null) {
                    <!-- if (this.data[this.activeTab].length != 0) { return; } -->
                    try {
                        this.loading = true;
                        
                        const response = await fetch(url || `/api/{{ app()->getLocale() }}/${this.activeTab}`, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            }
                        });

                        const result = await response.json();
                        
                        if(result.success) {
                            this.data[this.activeTab] = result.data;
                            this.pagination.links = result.links;
                            this.pagination.meta = result.meta;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    } finally {
                        this.loading = false;
                    }
                }
            }">
                <div class="table-head">
                    <h3 class="title">ПОБЕДИТЕЛИ</h3>
                    <form class="table-form">
                        <input type="text" name="search-field" placeholder="Поиск по номеру телефона" class="input input-search">
                    </form>
                </div>
                <div class="table-body">

                    <div class="table-tabs-buttons">
                        <a href="#" name="tab1" class="tab-button" :class="{'active': activeTab == 'instant-prizes'}" @click.prevent="changeTab('instant-prizes')">Моментальные призы</a>
                        <a href="#" name="tab2" class="tab-button" :class="{'active': activeTab == 'weekly'}" @click.prevent="changeTab('weekly')">Еженедельные призы</a>
                    </div>
                    <div class="table-tabs-content">
                        <template x-if="loading == true"><span class="spinner"></span></template>
                        <template x-if="loading == false">
                            <div class="table-block__content" id="tab1" x-cloak x-show="activeTab == 'instant-prizes'">
                            
                                <div class="table-block__title" x-cloak x-show="data[activeTab].length == 0">
                                    <div class="row">
                                        <div>
                                            <p>Нет данных</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-block__title" x-cloak x-show="data[activeTab].length != 0">
                                    <div class="row">
                                        <div>
                                            <p>Дата</p>
                                        </div>
                                        <div>
                                            <p>Номер телефона</p>
                                        </div>
                                        <div>
                                            <p>Приз</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-block__rows" x-cloak x-show="data[activeTab].length != 0">
                                    <template x-for="item in data[activeTab]">
                                        <div class="row">
                                            <div>
                                                <p x-text="item.date"></p>
                                            </div>
                                            <div>
                                                <p x-text="item.phone_number"></p>
                                            </div>
                                            <div>
                                                <p x-text="item.name"></p>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <div class="table-footer center-align" x-cloak x-show="data[activeTab].length != 0">
                                    <div>
                                        <button type="button" class="button button-left" x-cloak x-show="pagination.links.prev" @click="getData(pagination.links.prev)">
                                            <img src="{{ asset('assets/media/icons/arrow_left.svg') }}">
                                        </button>
                                        <template x-for="item in pagination.meta.links.slice(1, -1)">
                                            <span x-text="item.label" :class="{'active': item.active}" @click="getData(item.url)"></span>
                                        </template>
                                        <button type="button" class="button button-right" x-cloak x-show="pagination.links.next" @click="getData(pagination.links.next)">
                                            <img src="{{ asset('assets/media/icons/arrow_right.svg') }}">
                                        </button>
                                    </div>
                                    <div>
                                        <button type="button" class="button button-right" x-cloak x-show="pagination.links.last" @click="getData(pagination.links.last)">
                                            <img src="{{ asset('assets/media/icons/arrow_duble.svg') }}">
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div class="table-block__content" id="tab2" x-cloak x-show="activeTab == 'weekly'">
                            <div class="table-block__title">
                                <div class="row">
                                    <div>
                                        <p>Дата</p>
                                    </div>
                                    <div>
                                        <p>Номер телефона</p>
                                    </div>
                                    <div>
                                        <p>Приз</p>
                                    </div>
                                </div>
                            </div>
                            <div class="table-block__rows">
                                <div class="row">
                                    <div>
                                        <p>22.12.2023</p>
                                    </div>
                                    <div>
                                        <p>+7 777 ХХХ ХХ 77</p>
                                    </div>
                                    <div>
                                        <p>Шоу-бокс Orbit®</p>
                                    </div>
                                </div>
                            </div>
                            <div class="table-footer center-align">
                                <div>
                                    <button type="button" class="button button-left">
                                        <img src="{{ asset('assets/media/icons/arrow_left.svg') }}">
                                    </button>
                                    <span>1</span>
                                    <span class="active">2</span>
                                    <span>3</span>
                                    <button type="button" class="button button-right">
                                        <img src="{{ asset('assets/media/icons/arrow_right.svg') }}">
                                    </button>
                                </div>
                                <div>
                                    <button type="button" class="button button-right">
                                        <img src="{{ asset('assets/media/icons/arrow_duble.svg') }}">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<section class="wrapper-full section-logotype">
    <div class="container-logotype">
        <div class="container-logotype__row-01">
            <h2 class="title">КУПИТЬ ОНЛАЙН</h2>
        </div>
        <div class="container-logotype__row-02">
            <div class="wrapper-fix wrapper-logotype">
                <div class="wrapper-logotype__container">
                    <a href="#" class="block-logotype">
                        <img src="{{ asset('assets/media/logotypes/logo_03.svg') }}" alt="SMALL logotype">
                    </a>
                    <a href="#" class="block-logotype">
                        <img src="{{ asset('assets/media/logotypes/logo_01.svg') }}" alt="SMALL logotype">
                    </a>
                    <a href="#" class="block-logotype">
                        <img src="{{ asset('assets/media/logotypes/logo_02.svg') }}" alt="SMALL logotype">
                    </a>
                    <a href="#" class="block-logotype">
                        <img src="{{ asset('assets/media/logotypes/logo_03.svg') }}" alt="SMALL logotype">
                    </a>
                    <a href="#" class="block-logotype">
                        <img src="{{ asset('assets/media/logotypes/logo_01.svg') }}" alt="SMALL logotype">
                    </a>
                </div>
                <div class="wrapper-logotype-mobile__container">
                    <div class="logotype-slider owl-carousel owl-theme">
                        <a href="#" class="block-logotype item">
                            <img src="{{ asset('assets/media/logotypes/logo_03.svg') }}" alt="SMALL logotype">
                        </a>
                        <a href="#" class="block-logotype item">
                            <img src="{{ asset('assets/media/logotypes/logo_01.svg') }}" alt="SMALL logotype">
                        </a>
                        <a href="#" class="block-logotype item">
                            <img src="{{ asset('assets/media/logotypes/logo_02.svg') }}" alt="SMALL logotype">
                        </a>
                        <a href="#" class="block-logotype item">
                            <img src="{{ asset('assets/media/logotypes/logo_03.svg') }}" alt="SMALL logotype">
                        </a>
                        <a href="#" class="block-logotype item">
                            <img src="{{ asset('assets/media/logotypes/logo_01.svg') }}" alt="SMALL logotype">
                        </a>
                    </div>
                </div>
            </div>
            <div class="decorations">
                <div class="decoration_01"></div>
            </div>
        </div>
    </div>
</section>

<section class="wrapper-full section-questions">
    <div class="wrapper-fix wrapper-middle wrapper-questions">
        <div class="container-questions">
            <h2 class="title">ОТВЕТЫ НА ВОПРОСЫ</h2>
            <div class="block-questions">
                <div class="block-questions__column-01">
                    <div class="questions-accordeons">
                        <div class="question-item">
                            <div class="question-title">
                                <h4>Сколько длится акция?</h4>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" class="" alt="Открыть/закрыть вопрос">
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. </p>
                        </div>

                        <div class="question-item">
                            <div class="question-title">
                                <h4>Кто может участвовать в акции?</h4>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" class="" alt="Открыть/закрыть вопрос">
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. </p>
                        </div>

                        <div class="question-item">
                            <div class="question-title">
                                <h4>Как принять участие в акции?</h4>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" class="" alt="Открыть/закрыть вопрос">
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. </p>
                        </div>

                        <div class="question-item">
                            <div class="question-title">
                                <h4>Какие продукты принимают участие в акции?</h4>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" class="" alt="Открыть/закрыть вопрос">
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. </p>
                        </div>

                        <div class="question-item">
                            <div class="question-title">
                                <h4>Где я могу узнать подробные правила акции?</h4>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" class="" alt="Открыть/закрыть вопрос">
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. </p>
                        </div>

                        <div class="question-item">
                            <div class="question-title">
                                <h4>Какие призы разыгрываются?</h4>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" class="" alt="Открыть/закрыть вопрос">
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. </p>
                        </div>

                        <div class="question-item">
                            <div class="question-title">
                                <h4>Какое количество призов я могу выиграть?</h4>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" class="" alt="Открыть/закрыть вопрос">
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. </p>
                        </div>

                        <div class="question-item">
                            <div class="question-title">
                                <h4>Когда и как будет проходить розыгрыш еженедельных призов?</h4>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" class="" alt="Открыть/закрыть вопрос">
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. </p>
                        </div>

                        <div class="question-item">
                            <div class="question-title">
                                <h4>Когда и как будет проходить розыгрыш главного приза?</h4>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" class="" alt="Открыть/закрыть вопрос">
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. </p>
                        </div>

                        <div class="question-item">
                            <div class="question-title">
                                <h4>Нужно ли сохранять упаковки и чеки?</h4>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" class="" alt="Открыть/закрыть вопрос">
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                tempor incididunt ut labore et dolore magna aliqua. </p>
                        </div>

                    </div>
                </div>
                <div class="block-questions__column-02">
                    <div class="question-form">
                        <img src="{{ asset('assets/media/questions.svg') }}" alt="">
                        <p>НЕ НАШЕЛ ОТВЕТ <br> НА СВОЙ ВОПРОС?</p>
                        <a href="#">ЗАДАТЬ ВОПРОС</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<div class="section-modal modal-form" id="select-year" x-cloak x-data="{
    showDialog: false,
    birthYear: null,
    init() {
        if (!localStorage.userBirthYear) {
            this.showDialog = true;
            this.disableSite();
        }
    },
    disableSite() {
        document.body.classList.add('disabled');
    },
    enableSite() {
        document.body.classList.remove('disabled');
        this.showDialog = false;
    },
    selectYear() {
        localStorage.setItem('userBirthYear', this.birthYear);
        this.enableSite();
    }
}" x-show="showDialog">
    <div class="wrapper-modal">
        <div class="container-form">
            <div class="head">
                <span @click="showDialog = false">close</span>
                <img src="{{ asset('assets/media/icons/stars_blue.svg') }}" alt="" class="decoration">
                <h3>РЕГИСТРИРУЙСЯ, ИГРАЙ И ВЫИГРЫВАЙ ПРИЗЫ!</h3>
                <p>В акции могут участвовать только пользователи старше 16 лет</p>
            </div>
            <div class="body noFooter">
                <form class="form">
                    <div class="input-row">
                        <label>Выбери год рождения</label>
                        <select placeholder="Год рождения" class="input" x-model="birthYear">
                            <template x-for="n in 69" :key="n">
                                <option x-text="2009 - n"></option>
                            </template>
                        </select>
                    </div>
                    <button type="button" class="button" @click="selectYear">ОТПРАВИТЬ</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="section-modal modal-form" x-data="{
    login: '77782284032',
    password: '973956',
    email: '',
    loading: false,
    forgotPassword: false,
    confirmPasswordForm: false,
    async signIn() {
        try {
            this.loading = true;
            const sendData = {
                'phone_number': this.login,
                'password': this.password
            }
            $store.service.signIn(sendData);
        } catch (error) {
            console.error('Error:', error);
        } finally {
            this.loading = false;
        }
    },
    async forgotPasswordRequest() {
        try {
            this.loading = true;
            const sendData = {
                'phone_number': this.login
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
            
            if(result.success) {
                this.email = result.data.email;
                this.confirmPasswordForm = true;
                this.startCountdown();
            }
        } catch (error) {
            console.error('Error:', error);
        } finally {
            this.loading = false;
        }
    },
    showForgotPassword() {
        this.password = '';
        this.forgotPassword = true;
    },
    showRegistrationModal() {
        this.closeModal();
        $store.modal.registration = true;
    },
    closeModal() {
        $store.modal.signIn = false;
        this.login = '';
        this.password = '';
        this.email = '';
        this.forgotPassword = false;
        this.confirmPasswordForm = false;
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
                        <input x-model="login" type="text" class="input" placeholder="Номер телефона или E-mail" autocomplete="username">
                        <span>Валидация</span>
                    </div>
                    
                    <div class="input-row" x-show="forgotPassword == false">
                        <input x-model="password" type="password" class="input" placeholder="Пароль" autocomplete="current-password">
                        <span>Валидация</span>
                    </div>
                    <a href="#" @click.prevent="showForgotPassword()" x-show="forgotPassword == false">
                        <p class="caption">Забыли пароль?</p>
                    </a>
                    <button type="button" class="button" @click="signIn()" :disabled="loading" x-show="forgotPassword == false">Войти</button>
                            

                    <a href="#" @click.prevent="forgotPassword = false" x-show="forgotPassword == true">
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
						<span>Валидация</span>
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

<div class="section-modal modal-form" x-data="{
    loading: false,
    showConfirmPage: false,

    phone_number: '',
    name: '',
    email: '',
    birthdate: '',
    password: '',

    async signUp() {
        try {
            this.loading = true;
            
            const response = await fetch('/api/{{ app()->getLocale() }}/signUp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    'phone_number': this.phone_number,
                    'name': this.name,
                    'email': this.email,
                    'birthdate': this.birthdate
                })
            });

            const result = await response.json();
            
            if(result.success) {
                this.showConfirmPage = true;
                this.startCountdown();
            }

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
            
            if(result.success) {
                this.showConfirmPage = true;
                this.startCountdown();
            }

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
                'phone_number': this.phone_number,
                'password': this.password
            }
            $store.service.signIn(sendData);
        } catch (error) {
            console.error('Error:', error);
        } finally {
            this.loading = false;
        }
    },
    
    showSignInModal() {
        this.closeModal();
        $store.modal.signIn = true;
    },
    closeModal() {
        $store.modal.registration = false;
        this.clearInterval();
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
}" x-cloak x-show="$store.modal.registration" id="register-modal">
	<div class="wrapper-modal">
		<div class="container-form" x-show="!showConfirmPage">
			<div class="head">
				<img src="{{ asset('assets/media/icons/stars_blue.svg') }}" alt="" class="decoration">
				<h3>РЕГИСТРАЦИЯ</h3>
				<img src="{{ asset('assets/media/icons/close-icon_01.svg') }}" alt="" class="close-icon" @click="closeModal()">
			</div>
			<div class="body">
				<form class="form">
					<!-- <p>В акции могут участвовать только <br>пользователи старше 16 лет</p> -->

					<div class="input-row">
						<input x-model="birthdate" type="date" name="name" class="input" placeholder="Дата рождения">
						<span>Валидация</span>
					</div>

					<div class="input-row">
						<input x-model="name" type="text" name="name" class="input" placeholder="Имя">
						<span>Валидация</span>
					</div>
					<div class="input-row">
						<input x-model="phone_number" type="number" name="phone" class="input" placeholder="Номер телефона">
						<span>Валидация</span>
					</div>
					<div class="input-row">
						<input x-model="email" type="email" name="email" class="input" placeholder="E-Mail">
						<span>Валидация</span>
					</div>
					<button type="button" class="button" @click="signUp()" :disabled="loading">ЗАРЕГИСТРИРОВАТЬСЯ</button>

					<p class="caption">Нажимая кнопку “Зарегистрироваться”, <br>
						я подтверждаю, что согласен с <br>
						Правилами Акции и Политикой Конфидициальности
					</p>
				</form>
			</div>
			<div class="footer">
				<p>УЖЕ ЗАРЕГИСТРИРОВАЛСЯ?</p>
				<a href="#" @click.prevent="showSignInModal()">Войти</a>
			</div>
		</div>
        
		<div class="container-form" x-show="showConfirmPage">
            <div class="head">
				<p>Сообщение с код паролем отправлено на номер</p>
				<h3 x-text="phone_number"></h3>
				<img src="{{ asset('assets/media/icons/close-icon_01.svg') }}" alt="" class="close-icon" @click="closeModal()">
			</div>
			<div class="body">
				<form class="form">
					<div class="input-row">
						<input x-model="password" type="number" name="code" class="input" placeholder="Код из SMS">
						<span>Валидация</span>
					</div>
					<button type="button" class="button" @click="signIn()" :disabled="loading">ПОДТВЕРДИТЬ</button>
				</form>
			</div>
			<div class="footer reverse">
                <template x-if="countdown > 0">
                    <div>
                        <p><b>НЕ ПРИШЕЛ КОД?</b></p>
                        <p>Повторно SMS можно запросить через <span x-text="countdown"></span> секунд</p>
                    </div>
                </template>
                <template x-if="countdown == 0">
				    <a href="#" @click.prevent="reSendSms()">ОТПРАВИТЬ СНОВА</a>
                </template>
			</div>
		</div>
	</div>
</div>
@endsection