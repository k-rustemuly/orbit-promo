@php
    $phoneMask = region() == 'kz' ? '+7 ### ### ## ##' : '+998 ## ### ## ##';
@endphp

@extends('layouts.app')

@section('title', 'Orbit - '.trans('front.string_1'))
@section('bodyClass', 'landing')

@section('headerContent')
<div class="wrapper-fix wrapper-small wrapper-header">
    <div class="container-header">
        <div class="container-header_column-01">
            <h2 class="title">
                {!! trans('front.home_header.title') !!}
            </h2>
            <img src="{{ asset('assets/media/image_01_'.region().'.png') }}" alt="" class="image mobile">
            <h1 class="title">
                {!! trans('front.home_header.subtitle') !!}
            </h1>
            <img src="{{ asset('assets/media/image_01_'.region().'.png') }}" alt="" class="image desktop">
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
            <a href="/game" class="block-notes block-01" x-data @click="$store.nav.play">
                <img src="{{ asset('assets/media/note_01_'.app()->getLocale().'.svg') }}">
            </a>
        </div>
        <div class="container-notes_column-02">
            <a href="#" class="block-notes block-02" x-data="{
                showReceiptModal() {
                    if($store.user.token) {
                        $store.modal.receiptPage = 1;
                        $store.modal.receipt = true;
                    } else {
                        $store.modal.signIn = true;
                    }
                }
            }" @click.prevent="showReceiptModal()">
                <img src="{{ asset('assets/media/note_02_'.app()->getLocale().'.svg') }}">
            </a>
            <a href="#prizes" class="block-notes block-03">
                <img src="{{ asset('assets/media/note_03_'.app()->getLocale().'.svg') }}">
            </a>
        </div>
    </div>

    <div class="container-notes-mobile">
        <a href="/game" class="block-notes block-01" x-data @click="$store.nav.play">
            <img src="{{ asset('assets/media/note_01_'.app()->getLocale().'.svg') }}">
        </a>
        <a href="#" class="block-notes block-02" x-data="{
                showReceiptModal() {
                    if($store.user.token) {
                        $store.modal.receiptPage = 1;
                        $store.modal.receipt = true;
                    } else {
                        $store.modal.signIn = true;
                    }
                }
            }" @click.prevent="showReceiptModal()">
            <img src="{{ asset('assets/media/note_02_'.app()->getLocale().'.svg') }}">
        </a>
        <a href="#prizes" class="block-notes block-03">
            <img src="{{ asset('assets/media/note_03_'.app()->getLocale().'.svg') }}">
        </a>
    </div>

</div>
<div class="decorations">
    <div class="decoration_01"></div>
    <img src="{{ asset('assets/media/header_decoration_03.svg') }}" alt="" class="decoration_02">
</div>
@endsection

@section('content')
<div class="wrapper-decoration" id="game">
    <section class="wrapper-full section-game">
        <div class="wrapper-fix wrapper-small wrapper-game">
            <div class="container-game">
                <div class="container-game_column-01">
                    <div>
                        <h2 class="title">{!! trans('front.home_header.game') !!}</h2>
                        <p>{!! trans('front.home_header.game_subtitle') !!}</p>
                    </div>
                    <img src="{{ asset('assets/media/image_03.png') }}">
                </div>
                <div class="container-game_column-02">
                    <h2 class="title">{!! trans('front.home_header.game') !!}</h2>
                    <p>{!! trans('front.home_header.game_subtitle') !!}</p>
                    <div class="game-caption_block">
                        <div class="game-caption_block-item">
                            <div class="image">
                                <img src="{{ asset('assets/media/game_01.svg') }}">
                            </div>
                            <span>
                                <img src="{{ asset('assets/media/icons/line.svg') }}" alt="">
                            </span>
                            <p>{!! trans('front.home_header.instr1') !!}</p>
                        </div>
                        <div class="game-caption_block-item">
                            <div class="image">
                                <img src="{{ asset('assets/media/game_02.svg') }}" alt="Image">
                            </div>
                            <span>
                                <img src="{{ asset('assets/media/icons/line.svg') }}" alt="">
                            </span>
                            <p>{!! trans('front.home_header.instr2') !!}</p>
                        </div>
                        <div class="game-caption_block-item">
                            <div class="image">
                                <img src="{{ asset('assets/media/game_03.svg') }}" alt="Image">
                            </div>
                            <span>
                                <img src="{{ asset('assets/media/icons/line.svg') }}" alt="">
                            </span>
                            <p>{!! trans('front.home_header.instr3') !!}</p>
                        </div>
                    </div>
                    <div class="game-action_block">
                        <div>
                            <a href="/game" class="button filled" x-data @click="$store.nav.play">
                            {!! trans('front.home_header.play') !!}
                            </a>
                            <a href="{{ asset('assets/files/terms_'.region().'.pdf') }}" class="button link term" target="_blank">{!! trans('front.home_header.rules') !!}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.presents')
</div>

<section class="wrapper-full section-table" id="winners">
    <div class="wrapper-fix wrapper-small wrapper-table">
        <div class="container-table">
            <div class="table-block fix-width" x-data="{
                loading: false,
                activeTab: '',
                search: '',
                data: {
                    'instant-prizes': [],
                    'vouchers': []
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
                    this.search = '';
                    this.getData();
                },
                async getData(url = null) {
                    try {
                        this.loading = true;
                        const baseUrl = url || `/api/{{ app()->getLocale() }}/${this.activeTab}`;
                        const fullUrl = this.search ? `${baseUrl}?phone_number=${encodeURIComponent(this.search.replace(/\D/g, ''))}` : baseUrl;

                        const response = await fetch(fullUrl, {
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
                    <h3 class="title">{!! trans('front.home_header.winners') !!}</h3>
                    <form class="table-form" @submit.prevent="getData()">
                        <input x-model="search" type="text" x-mask="{{ $phoneMask }}" name="search-field" placeholder="{!! trans('front.string_2') !!}" class="input input-search">
                    </form>
                </div>
                <div class="table-body">

                    <div class="table-tabs-buttons">
                        <a href="#" name="tab1" class="tab-button" :class="{'active': activeTab == 'instant-prizes'}" @click.prevent="changeTab('instant-prizes')">{!! trans('front.string_3') !!}</a>
                        <a href="#" name="tab2" class="tab-button" :class="{'active': activeTab == 'vouchers'}" @click.prevent="changeTab('vouchers')">{!! trans('front.string_4') !!}</a>
                    </div>
                    <div class="table-tabs-content">
                        <template x-if="loading == true"><span class="spinner"></span></template>
                        <template x-if="loading == false">
                            <div class="table-block__content 1" id="tab1">
                                <div class="table-block__title" x-cloak x-show="data[activeTab].length == 0">
                                    <div class="row">
                                        <div>
                                            <p>{!! trans('front.string_5') !!}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="table-block__title" x-cloak x-show="data[activeTab].length != 0">
                                    <template x-if="activeTab == 'instant-prizes'">
                                        <div class="row">
                                            <div>
                                                <p>{!! trans('front.string_6') !!}</p>
                                            </div>
                                            <div>
                                                <p>{!! trans('front.string_7') !!}</p>
                                            </div>
                                            <div>
                                                <p>{!! trans('front.string_8') !!}</p>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="activeTab == 'vouchers'">
                                        <div class="row">
                                            <div>
                                                <p>{!! trans('front.string_6') !!}</p>
                                            </div>
                                            <div>
                                                <p>{!! trans('front.string_9') !!}</p>
                                            </div>
                                            <div>
                                                <p>{!! trans('front.string_8') !!}</p>
                                            </div>
                                            <div>
                                                <p>{!! trans('front.string_10') !!}</p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <div class="table-block__rows" x-cloak x-show="data[activeTab].length != 0">
                                    <template x-if="activeTab == 'instant-prizes'">
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
                                    </template>
                                    <template x-if="activeTab == 'vouchers'">
                                        <template x-for="item in data[activeTab]">
                                            <div class="row">
                                                <div>
                                                    <p x-text="item.date"></p>
                                                </div>
                                                <div>
                                                    <p x-text="item.spent_bal"></p>
                                                </div>
                                                <div>
                                                    <p x-text="item.prize"></p>
                                                </div>
                                                <div>
                                                    <p x-text="item.is_winned ? '{!! trans('front.string_11') !!}' : '{!! trans('front.string_12') !!}'"></p>
                                                </div>
                                            </div>
                                        </template>
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
                                        <button type="button" class="button button-right" x-cloak x-show="pagination.links.last && pagination.meta.last_page > 2" @click="getData(pagination.links.last)">
                                            <img src="{{ asset('assets/media/icons/arrow_duble.svg') }}">
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if(region() == 'kz')
<section class="wrapper-full section-logotype">
    <div class="container-logotype">
        <div class="container-logotype__row-01">
            <h2 class="title">{!! trans('front.home_header.buy_online') !!}</h2>
        </div>
        <div class="container-logotype__row-02">
            <div class="wrapper-fix wrapper-logotype">
                <div class="wrapper-logotype__container">
                    <a href="https://arbuz.kz/{{ app()->getLocale() }}/almaty/search/show#/?%5B%7B%22component%22%3A%22search%22,%22slug%22%3A%22where%5Bname%5D%5Bc%5D%22,%22value%22%3A%22orbit%22%7D,%7B%22name%22%3A%22Orbit%22,%22slug%22%3A%22brands%22,%22type%22%3A%22check%22,%22component%22%3A%22filters%22%7D%5D " class="block-logotype" target="_blank">
                        <img src="{{ asset('assets/media/logotypes/Arbuz.png') }}" style="width: 170px">
                    </a>
                    <a href="https://magnum.kz/MagnumGo?city=almaty" class="block-logotype" target="_blank">
                        <img src="{{ asset('assets/media/logotypes/Magnum.png') }}" style="width: 150px">
                    </a>
                    <a href="https://glovoapp.com/kz/{{ app()->getLocale() }}/almaty/" class="block-logotype" target="_blank">
                        <img src="{{ asset('assets/media/logotypes/Glovo.png') }}" style="width: 200px">
                    </a>
                    <a href="https://wolt.com/ru/kaz" class="block-logotype" target="_blank">
                        <img src="{{ asset('assets/media/logotypes/WOLT.png') }}" style="width: 150px">
                    </a>
                    <a href="https://ryadom.kz/search?search=orbit" class="block-logotype" target="_blank">
                        <img src="{{ asset('assets/media/logotypes/Рядом.png') }}" style="width: 150px">
                    </a>
                </div>
                <div class="wrapper-logotype-mobile__container">
                    <div class="logotype-slider owl-carousel owl-theme">
                        <a href="https://arbuz.kz/{{ app()->getLocale() }}/almaty/search/show#/?%5B%7B%22component%22%3A%22search%22,%22slug%22%3A%22where%5Bname%5D%5Bc%5D%22,%22value%22%3A%22orbit%22%7D,%7B%22name%22%3A%22Orbit%22,%22slug%22%3A%22brands%22,%22type%22%3A%22check%22,%22component%22%3A%22filters%22%7D%5D " class="block-logotype" target="_blank">
                            <img src="{{ asset('assets/media/logotypes/Arbuz.png') }}">
                        </a>
                        <a href="https://magnum.kz/MagnumGo?city=almaty" class="block-logotype" target="_blank">
                            <img src="{{ asset('assets/media/logotypes/Magnum.png') }}">
                        </a>
                        <a href="https://glovoapp.com/kz/{{ app()->getLocale() }}/almaty/" class="block-logotype" target="_blank">
                            <img src="{{ asset('assets/media/logotypes/Glovo.png') }}">
                        </a>
                        <a href="https://wolt.com/ru/kaz" class="block-logotype" target="_blank">
                            <img src="{{ asset('assets/media/logotypes/WOLT.png') }}">
                        </a>
                        <a href="https://ryadom.kz/search?search=orbit" class="block-logotype" target="_blank">
                            <img src="{{ asset('assets/media/logotypes/Рядом.png') }}">
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
@endif

<section class="wrapper-full section-questions" id="faq">
    <div class="wrapper-fix wrapper-middle wrapper-questions">
        <div class="container-questions">
            <h2 class="title">{!! trans('front.faq.title') !!}</h2>
            <div class="block-questions">
                <div class="block-questions__column-01">
                    <div class="questions-accordeons">
                        <div class="question-item">
                            <div class="question-title">
                                <h4>{!! trans('front.faq.quest1') !!}</h4>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" class="">
                            </div>
                            <p>{!! trans('front.faq.ans1') !!}</p>
                        </div>

                        <div class="question-item">
                            <div class="question-title">
                                <h4>{!! trans('front.faq.quest2') !!}</h4>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" class="">
                            </div>
                            <p>{!! trans('front.faq.ans2'.(region() == 'uz' ? '_uz' : '')) !!} </p>
                        </div>

                        <div class="question-item">
                            <div class="question-title">
                                <h4>{!! trans('front.faq.quest3') !!}</h4>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" class="">
                            </div>
                            <p>{!! trans('front.faq.ans3'.(region() == 'uz' ? '_uz' : '')) !!} </p>
                        </div>

                        <div class="question-item">
                            <div class="question-title">
                                <h4>{!! trans('front.faq.quest4') !!}</h4>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" class="">
                            </div>
                            <p>{!! trans('front.faq.ans4'.(region() == 'uz' ? '_uz' : '')) !!} </p>
                        </div>

                        <div class="question-item">
                            <div class="question-title">
                                <h4>{!! trans('front.faq.quest5') !!}</h4>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" class="">
                            </div>
                            <p>{!! trans('front.faq.ans5'.(region() == 'uz' ? '_uz' : '')) !!} </p>
                        </div>

                        <div class="question-item">
                            <div class="question-title">
                                <h4>{!! trans('front.faq.quest6') !!}</h4>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" class="">
                            </div>
                            <p>{!! trans('front.faq.ans6'.(region() == 'uz' ? '_uz' : '')) !!} </p>
                        </div>

                        <div class="question-item">
                            <div class="question-title">
                                <h4>{!! trans('front.faq.quest7') !!}</h4>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" class="">
                            </div>
                            <p>{!! trans('front.faq.ans7') !!} </p>
                        </div>

                        <div class="question-item">
                            <div class="question-title">
                                <h4>{!! trans('front.faq.quest8') !!}</h4>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" class="">
                            </div>
                            <p>{!! trans('front.faq.ans8'.(region() == 'uz' ? '_uz' : '')) !!} </p>
                        </div>

                        <div class="question-item">
                            <div class="question-title">
                                <h4>{!! trans('front.faq.quest9') !!}</h4>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" class="">
                            </div>
                            <p>{!! trans('front.faq.ans9') !!} </p>
                        </div>

                        <div class="question-item">
                            <div class="question-title">
                                <h4>{!! trans('front.faq.quest10') !!}</h4>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" class="">
                            </div>
                            <p>{!! trans('front.faq.ans10'.(region() == 'uz' ? '_uz' : '')) !!} </p>
                        </div>

                        

                    </div>
                </div>
                <!-- <div class="block-questions__column-02">
                    <div class="question-form">
                        <img src="{{ asset('assets/media/questions.svg') }}" alt="">
                        <p>{!! trans('front.faq.no_answer') !!}</p>
                        <a href="#">{!! trans('front.faq.ask') !!}</a>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</section>


@include('partials.modals.select-year')

@include('partials.modals.sign-in')

@include('partials.modals.sign-up')

@endsection