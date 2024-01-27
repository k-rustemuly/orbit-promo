<header class="wrapper-full section-header" >
    <div class="wrapper-fix wrapper-small wrapper-navigation">
        <div class="container-navigation">
            <div class="container-navigation_column-01">
                <a href="/" class="navigation-logotype">
                    <img src="{{ asset('assets/media/logotype.svg') }}" alt="">
                </a>
                <nav class="navigation-menu">
                    <ul>
                        <li>
                            <a href="#">
                                Игра
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Победители
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                FAQ
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            @if(Route::currentRouteName() == 'index')
                <div class="container-navigation_column-02" x-data>
                    <template x-if="$store.user.token"><a href="/{{ app()->getLocale() }}/profile" class="button button__outlined">Профиль</a></template>
                    <template x-if="!$store.user.token"><a href="#" class="button button__outlined" @click.prevent="$store.modal.signIn = true">Войти</a></template>
                </div>
            @else
                <div class="container-navigation_column-02" x-data>
                    <a href="#" class="button button__outlined" @click.prevent="">Выйти</a>
                </div>
            @endif
        </div>
        <div class="container-navigation-mobile">
            <img src="{{ asset('assets/media/icons/menu.svg') }}" alt="">
        </div>
    </div>
    @yield('headerContent')
</header>