<header class="wrapper-full section-header" x-data="{showMenu: false}">
    <div class="wrapper-fix wrapper-small wrapper-navigation">
        <div class="container-navigation-mobile" @click="showMenu = !showMenu">
            <template x-if="showMenu">
                <img src="{{ asset('assets/media/icons/close-icon_03.svg') }}" />
            </template>
            <template x-if="!showMenu">
                <img src="{{ asset('assets/media/icons/menu.svg') }}" />
            </template>
        </div>
        <div class="container-navigation" :style="showMenu ? 'display: flex' : ''">
            <div class="container-navigation_column-01">
                <a href="/" class="navigation-logotype sm-hide">
                    <img src="{{ asset('assets/media/logotype.svg') }}" alt="">
                </a>
                <a href="/game" class="custom-button md-hide"  @click="$store.nav.play">
                    {!! trans('front.header.play') !!} 
                </a>
                <nav class="navigation-menu">
                    <ul>
                        <li>
                            <a href="#game">
                                {!! trans('front.header.game') !!} 
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                {!! trans('front.header.winner') !!} 
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                {!! trans('front.header.faq') !!} 
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            @if(Route::currentRouteName() == 'index')
                <div class="container-navigation_column-02" x-data>
                    <template x-if="$store.user.token"><a href="/{{ app()->getLocale() }}/profile" class="button button__outlined">{!! trans('front.header.profile') !!}</a></template>
                    <template x-if="!$store.user.token"><a href="#" class="button button__outlined" @click.prevent="$store.modal.signIn = true">{!! trans('front.header.sign_in') !!}</a></template>
                </div>
            @else
                <div class="container-navigation_column-02">
                    <a href="#" class="button button__outlined" x-data @click.prevent="$store.service.logOut()">{!! trans('front.header.sign_out') !!}</a>
                </div>
            @endif
        </div>
    </div>
    @yield('headerContent')
</header>