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

    <section class="wrapper-full section-present">
        <div class="wrapper-fix wrapper-small wrapper-present">
            <div class="container-present">
                <h3 class="title">ВИТРИНА призов</h3>

                <div class="present-block">

                    <div class="present-block_item">
                        <div class="caption">
                            <div>
                                <img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
                                <span>350</span>
                            </div>
                            <p>Колонка</p>
                        </div>
                        <a href="#">УЧАСТВОВАТЬ</a>
                        <div class="image image-01">
                            <img src="{{ asset('assets/media/present_01.png') }}" alt="">
                        </div>
                        <div class="decoration">
                            <img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
                        </div>
                    </div>

                    <div class="present-block_item">
                        <div class="caption">
                            <div>
                                <img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
                                <span>500</span>
                            </div>
                            <p>Наушники</p>
                        </div>
                        <a href="#">УЧАСТВОВАТЬ</a>
                        <div class="image image-02">
                            <img src="{{ asset('assets/media/present_02.png') }}" alt="">
                        </div>
                        <div class="decoration">
                            <img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
                        </div>
                    </div>

                    <div class="present-block_item">
                        <div class="caption">
                            <div>
                                <img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
                                <span>1000</span>
                            </div>
                            <p>Планшет</p>
                        </div>
                        <a href="#">УЧАСТВОВАТЬ</a>
                        <div class="image image-03">
                            <img src="{{ asset('assets/media/present_03.png') }}" alt="">
                        </div>
                        <div class="decoration">
                            <img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
                        </div>
                    </div>
                </div>

                <div class="present-block-mobile">
                    <div class="present-slider owl-carousel owl-theme">
                        <div class="present-block_item item">
                            <div class="caption">
                                <div>
                                    <img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
                                    <span>350</span>
                                </div>
                                <p>Колонка</p>
                            </div>
                            <a href="#">УЧАСТВОВАТЬ</a>
                            <div class="image image-01">
                                <img src="{{ asset('assets/media/present_01.png') }}" alt="">
                            </div>
                            <div class="decoration">
                                <img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
                            </div>
                        </div>

                        <div class="present-block_item item">
                            <div class="caption">
                                <div>
                                    <img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
                                    <span>500</span>
                                </div>
                                <p>Наушники</p>
                            </div>
                            <a href="#">УЧАСТВОВАТЬ</a>
                            <div class="image image-02">
                                <img src="{{ asset('assets/media/present_02.png') }}" alt="">
                            </div>
                            <div class="decoration">
                                <img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
                            </div>
                        </div>

                        <div class="present-block_item item">
                            <div class="caption">
                                <div>
                                    <img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
                                    <span>1000</span>
                                </div>
                                <p>Планшет</p>
                            </div>
                            <a href="#">УЧАСТВОВАТЬ</a>
                            <div class="image image-03">
                                <img src="{{ asset('assets/media/present_03.png') }}" alt="">
                            </div>
                            <div class="decoration">
                                <img src="{{ asset('assets/media/icons/star_white.svg') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
</div>
</section>
</div>

<section class="wrapper-full section-table">
    <div class="wrapper-fix wrapper-small wrapper-table">
        <div class="container-table">
            <div class="table-block fix-width">
                <div class="table-head">
                    <h3 class="title">ПОБЕДИТЕЛИ</h3>
                    <form action="./" class="table-form">
                        <input type="text" name="search-field" placeholder="Поиск по номеру телефона" class="input input-search">
                    </form>
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
@endsection