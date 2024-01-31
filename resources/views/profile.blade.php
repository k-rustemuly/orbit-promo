@extends('layouts.app')

@section('title', 'Orbit - '.trans('front.string_13'))
@section('bodyClass', 'profile')

@section('headerContent')
<div class="wrapper-fix wrapper-small wrapper-profile">
    <div class="container-profile" x-data="{
            loading: false,
            async init() {
                try {
                    this.loading = true;
                    
                    const response = await fetch('/api/{{ app()->getLocale() }}/profile', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${$store.user.token}`,
                            'Accept': 'application/json',
                        }
                    });

                    const result = await response.json();
                    
                    if(result.success) {
                        $store.user.setInfo(result.data);
                    }

                } catch (error) {
                    console.error('Error:', error);
                } finally {
                    this.loading = false;
                }
            }
        }" id="profile-block" @update-data="init()">
        <h2 class="title" x-text="$store.user.info?.name"></h2>
        <div class="block-profile">
            <div class="block-profile__column-01">
                <p></p>
                <div class="badge">
                    <img src="{{ asset('assets/media/icons/star_white.svg') }}">
                    <span x-text="$store.user.info?.coin"></span>
                </div>
                <a href="/game">{!! trans('front.string_15') !!}</a>
            </div>
            <div class="block-profile__column-02">
                <div class="block-profile__card block-profile__card-01">
                    <div class="card" x-data="{
                            loading: false,
                            showContent: false,
                            data: [],
                            toggleContent() {
                                this.showContent = !this.showContent;
                                if(this.showContent) {
                                    this.getData();
                                }
                            },
                            async getData() {
                                if (this.data.length != 0) { return; }
                                try {
                                    this.loading = true;
                                    
                                    const response = await fetch('/api/{{ app()->getLocale() }}/receipts', {
                                        method: 'GET',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'Authorization': `Bearer ${$store.user.token}`,
                                            'Accept': 'application/json',
                                        }
                                    });

                                    const result = await response.json();
                                    
                                    if(result.success) {
                                        this.data = result.data;
                                    }
                                } catch (error) {
                                    console.error('Error:', error);
                                } finally {
                                    this.loading = false;
                                }
                            },
                            showReceiptModal() {
                                $store.modal.receiptPage = 1;
                                $store.modal.receipt = true;
                            }
                        }">
                        <div class="head" @click="toggleContent()">
                            <p>{!! trans('front.string_16') !!}</p>
                            <span>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" x-cloak x-show="showContent == false">
                                <img src="{{ asset('assets/media/icons/arrow_up.svg') }}" x-cloak x-show="showContent == true" style="margin-top: -4px;">
                            </span>
                        </div>
                        <div class="body">
                            <template x-if="showContent == false">
                                <p>{!! trans('front.string_17') !!}</p>
                            </template>
                            <template x-if="showContent == true">
                                <div class="block">
                                    <p>{!! trans('front.string_18') !!}</p>
                                    <template x-if="loading == true"><span class="spinner"></span></template>
                                    <template x-if="loading == false">
                                        <div class="table">
                                            <div class="row">
                                                <div>{!! trans('front.string_19') !!}</div>
                                                <div>{!! trans('front.string_20') !!}</div>
                                            </div>
                                            <template x-for="item in data">
                                                <div class="row">
                                                    <div x-text="item.date"></div>
                                                    <div x-text="item.number"></div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                        <div class="footer">
                            <img src="{{ asset('assets/media/profile_01.svg') }}" alt="">
                            <a href="#" @click.prevent="showReceiptModal()">{!! trans('front.string_21') !!}</a>
                        </div>
                    </div>
                </div>
                <div class="block-profile__card block-profile__card-02">
                    <div class="card" x-data="{
                            loading: false,
                            showContent: false,
                            data: [],
                            toggleContent() {
                                this.showContent = !this.showContent;
                                if(this.showContent) {
                                    this.getData();
                                }
                            },
                            async getData() {
                                if (this.data.length != 0) { return; }
                                try {
                                    this.loading = true;
                                    
                                    const response = await fetch('/api/{{ app()->getLocale() }}/invitations', {
                                        method: 'GET',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'Authorization': `Bearer ${$store.user.token}`,
                                            'Accept': 'application/json',
                                        }
                                    });

                                    const result = await response.json();
                                    
                                    if(result.success) {
                                        this.data = result.data;
                                    }
                                } catch (error) {
                                    console.error('Error:', error);
                                } finally {
                                    this.loading = false;
                                }
                            }
                        }">
                        <div class="head" @click="toggleContent()">
                            <p>{!! trans('front.string_22') !!}</p>
                            <span>
                                <img src="{{ asset('assets/media/icons/arrow_bottom.svg') }}" x-cloak x-show="showContent == false">
                                <img src="{{ asset('assets/media/icons/arrow_up.svg') }}" x-cloak x-show="showContent == true" style="margin-top: -4px;">
                            </span>
                        </div>
                        <div class="body">
                            <template x-if="showContent == false">
                                <p>{!! trans('front.string_23') !!}</p>
                            </template>
                            <template x-if="showContent == true">
                                <div class="block">
                                    <p>{!! trans('front.string_24') !!}</p>
                                    <template x-if="loading == true"><span class="spinner"></span></template>
                                    <template x-if="loading == false">
                                        <div class="table">
                                            <div class="row">
                                                <div>{!! trans('front.string_25') !!}</div>
                                                <div>{!! trans('front.string_26') !!}</div>
                                            </div>
                                            <template x-for="item in data">
                                                <div class="row">
                                                    <div x-text="item.date"></div>
                                                    <div x-text="item.name"></div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                        <div class="footer">
                            <img src="{{ asset('assets/media/profile_02.svg') }}" alt="">
                            <a href="#" @click.prevent="$store.modal.referral = true">{!! trans('front.string_27') !!}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block-notification">
            <div class="notification">
                <p>{!! trans('front.string_28') !!}</p>
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
@include('partials.modals.referral')

<section class="wrapper-full section-table" id="my-prizes">
    <div class="wrapper-fix wrapper-small wrapper-table">
        <div class="container-table">
            <div class="table-block fix-width" x-data="{
                loading: false,
                activeTab: '',
                data: {
                    'my-instant-prizes': [],
                    'my-vouchers': []
                },
                pagination: {
                    links: {},
                    meta: {}
                },
                init() {
                    this.changeTab('my-vouchers');
                },
                changeTab(name) {
                    this.activeTab = name;
                    this.getData();
                },
                async getData(url = null) {
                    try {
                        this.loading = true;
                        const baseUrl = url || `/api/{{ app()->getLocale() }}/${this.activeTab}`;
                        
                        const response = await fetch(baseUrl, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${$store.user.token}`,
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
                    <h3 class="title">{!! trans('front.string_29') !!}</h3>
                </div>
                <div class="table-body">

                    <div class="table-tabs-buttons">
                        <a href="#" name="tab1" class="tab-button" :class="{'active': activeTab == 'my-instant-prizes'}" @click.prevent="changeTab('my-instant-prizes')">{!! trans('front.string_30') !!}</a>
                        <a href="#" name="tab2" class="tab-button" :class="{'active': activeTab == 'my-vouchers'}" @click.prevent="changeTab('my-vouchers')">{!! trans('front.string_31') !!}</a>
                    </div>
                    <div class="table-tabs-content">
                        <template x-if="loading == true"><span class="spinner"></span></template>
                        <template x-if="loading == false">
                            <div class="table-block__content 1" id="tab1">
                                <div class="table-block__title" x-cloak x-show="data[activeTab].length == 0">
                                    <div class="row">
                                        <div>
                                            <p>{!! trans('front.string_32') !!}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-block__title" x-cloak x-show="data[activeTab].length != 0">
                                    <template x-if="activeTab == 'my-instant-prizes'">
                                        <div class="row">
                                            <div>
                                                <p>{!! trans('front.string_33') !!}</p>
                                            </div>
                                            <div>
                                                <p>{!! trans('front.string_34') !!}</p>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="activeTab == 'my-vouchers'">
                                        <div class="row">
                                            <div>
                                                <p>{!! trans('front.string_33') !!}</p>
                                            </div>
                                            <div>
                                                <p>{!! trans('front.string_35') !!}</p>
                                            </div>
                                            <div>
                                                <p>{!! trans('front.string_34') !!}</p>
                                            </div>
                                            <div>
                                                <p>{!! trans('front.string_36') !!}</p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <div class="table-block__rows" x-cloak x-show="data[activeTab].length != 0">
                                    <template x-if="activeTab == 'my-instant-prizes'">
                                        <template x-for="item in data[activeTab]">
                                            <div class="row">
                                                <div>
                                                    <p x-text="item.date"></p>
                                                </div>
                                                <div>
                                                    <p x-text="item.name"></p>
                                                </div>
                                            </div>
                                        </template>
                                    </template>
                                    <template x-if="activeTab == 'my-vouchers'">
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
                                                    <p x-text="item.is_winned ? '{!! trans('front.string_37') !!}' : '{!! trans('front.string_38') !!}'"></p>
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
<script type="text/javascript">
    document.addEventListener('alpine:init', () => {
        if(!Alpine.store('user')?.token) {
            window.location.href = `/{{ app()->getLocale() }}`;
        }
    });
</script>
@endsection