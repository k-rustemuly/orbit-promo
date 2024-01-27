@extends('layouts.app')

@section('title', 'Orbit - Профиль')
@section('bodyClass', 'profile')

@section('headerContent')
    <div class="wrapper-fix wrapper-small wrapper-profile">
        <div class="container-profile">
            <h2 class="title">Имя Фамилия</h2>
            <div class="block-profile">
                <div class="block-profile__column-01">
                    <p>Мои коины</p>
                    <div class="badge">
                        <img src="{{ asset('assets/media/icons/star_white.svg') }}">
                        <span>10 000</span>
                    </div>
                    <a href="#">В ИГРУ</a>
                </div>
                <div class="block-profile__column-02">
                    <div class="block-profile__card block-profile__card-01">
                        <div class="card">
                            <div class="head">
                                <p>Мои чеки</p>
                                <span>
                                    <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}">
                                </span>
                            </div>
                            <div class="body">
                                <p>Сохрани все зарегистрированные <br> чеки до конца акции</p>
                                <div class="block hidden">
                                    <p>Каждый зарегистрированный чек дарит 5 дополнительных жизней</p>
                                    <div class="table">
                                        <div class="row">
                                            <div>Дата</div>
                                            <div>Номер чека</div>
                                        </div>
                                        <div class="row">
                                            <div>Дата</div>
                                            <div>Номер чека</div>
                                        </div>
                                        <div class="row">
                                            <div>Дата</div>
                                            <div>Номер чека</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="footer">
                                <img src="{{ asset('assets/media/profile_01.svg') }}" alt="">
                                <a href="#">ЗАГРУЗИ ЧЕК</a>
                            </div>
                        </div>
                    </div>
                    <div class="block-profile__card block-profile__card-02">
                        <div class="card">
                            <div class="head">
                                <p>Мои друзья</p>
                                <span>
                                    <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}">
                                </span>
                            </div>
                            <div class="body">
                                <p>Приглашай друзей и получай <br> дополнительные жизни</p>
                                <div class="block hidden">
                                    <p>Каждый зарегистрированный друг дарит дополнительную жизнь</p>
                                    <div class="table">
                                        <div class="row">
                                            <div>Дата</div>
                                            <div>Имя друга</div>
                                        </div>
                                        <div class="row">
                                            <div>Дата</div>
                                            <div>Имя друга</div>
                                        </div>
                                        <div class="row">
                                            <div>Дата</div>
                                            <div>Имя друга</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="footer">
                                <img src="{{ asset('assets/media/profile_02.svg') }}" alt="">
                                <a href="#">ПРИГЛАСИ ДРУГА</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block-notification">
                <div class="notification">
                    <p>Ты уже на <span>25</span> уровне! Так держать! У тебя в запасе еще <span>2</span> жизни!</p>
                </div>
            </div>
        </div>
    </div>

    <div class="decorations">
        <div class="decoration_01"></div>
        <img src="{{ asset('assets/media/header_decoration_03.svg') }}" alt="" class="decoration_02">
    </div>
@endsection

@section('content')

    @include('partials.presents')

    <section class="wrapper-full section-table">
        <div class="wrapper-fix wrapper-small wrapper-table">
            <div class="container-table">
                <div class="table-block">
                    <div class="table-head">
                        <h2 class="title">МОИ РОЗЫГРЫШИ</h2>
                    </div>
                    <div class="table-body">
                        <div class="table-tabs-buttons">
                            <a href="#" name="tab1" class="tab-button">Моментальные призы</a>
                            <a href="#" name="tab2" class="tab-button">Еженедельные призы</a>
                        </div>
                        <div class="table-tabs-content">
                            <div class="table-block__content" id="tab1">
                                <div class="table-block__title">
                                    <div class="row">
                                        <div>
                                            <p>Дата</p>
                                        </div>
                                        <div>
                                            <p>Количество потраченных баллов</p>
                                        </div>
                                        <div>
                                            <p>Приз</p>
                                        </div>
                                        <div>
                                            <p>Статус</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-block__rows">
                                    <div class="row">
                                        <div>
                                            <p>22.12.2023</p>
                                        </div>
                                        <div>
                                            <p>1000</p>
                                        </div>
                                        <div>
                                            <p>Колонка</p>
                                        </div>
                                        <div>
                                            <p>Продолжает участие </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div>
                                            <p>22.12.2023</p>
                                        </div>
                                        <div>
                                            <p>1000</p>
                                        </div>
                                        <div>
                                            <p>Колонка</p>
                                        </div>
                                        <div>
                                            <p>Продолжает участие </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                    <div class="row">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-block__content" id="tab2">

                                <div class="table-block__title">
                                    <div class="row">
                                        <div>
                                            <p>Дата</p>
                                        </div>
                                        <div>
                                            <p>Количество потраченных баллов</p>
                                        </div>
                                        <div>
                                            <p>Приз</p>
                                        </div>
                                        <div>
                                            <p>Статус</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-block__rows">
                                    <div class="row">
                                        <div>
                                            <p>22.12.2023</p>
                                        </div>
                                        <div>
                                            <p>1000</p>
                                        </div>
                                        <div>
                                            <p>Колонка</p>
                                        </div>
                                        <div>
                                            <p>Продолжает участие </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div>
                                            <p>22.12.2023</p>
                                        </div>
                                        <div>
                                            <p>1000</p>
                                        </div>
                                        <div>
                                            <p>Колонка</p>
                                        </div>
                                        <div>
                                            <p>Продолжает участие </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                    <div class="row">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-footer right-align">
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
    </section>
    
@endsection