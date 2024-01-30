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
                            <a href="/{{ app()->getLocale() }}/#game">
                                {!! trans('front.header.game') !!} 
                            </a>
                        </li>
                        <li>
                            <a href="/{{ app()->getLocale() }}/#winners">
                                {!! trans('front.header.winner') !!} 
                            </a>
                        </li>
                        <li>
                            <a href="/{{ app()->getLocale() }}/#faq">
                                {!! trans('front.header.faq') !!} 
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="container-navigation_column-02" x-data>
                <a href="/{{ app()->getLocale() == 'uz' || app()->getLocale() == 'kk' ? 'ru' : (region() == 'kz' ? 'kk' : 'uz') }}" class="change-lang">
                    <img src="{{ asset('assets/media/star.svg') }}" />
                    {{ app()->getLocale() == 'uz' || app()->getLocale() == 'kk' ? 'ru' : region() }}
                </a>
                @if(Route::currentRouteName() == 'index')
                    <template x-if="$store.user.token"><a href="/{{ app()->getLocale() }}/profile" class="button button__outlined">{!! trans('front.header.profile') !!}</a></template>
                    <template x-if="!$store.user.token"><a href="#" class="button button__outlined" @click.prevent="$store.modal.signIn = true">{!! trans('front.header.sign_in') !!}</a></template>
                @else
                    <a href="#" class="button button__outlined" @click.prevent="$store.service.logOut()">{!! trans('front.header.sign_out') !!}</a>
                @endif
            </div>
        </div>
    </div>
    @yield('headerContent')
</header>