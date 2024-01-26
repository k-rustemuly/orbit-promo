<header class="wrapper-full section-header" >
    <div class="wrapper-fix wrapper-small wrapper-navigation">
        <div class="container-navigation">
            <div class="container-navigation_column-01">
                <a href="#" class="navigation-logotype">
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
            <div class="container-navigation_column-02">
                <a href="#" class="button button__outlined">Войти</a>
            </div>
        </div>
        <div class="container-navigation-mobile">
            <img src="{{ asset('assets/media/icons/menu.svg') }}" alt="">
        </div>
    </div>
    @yield('headerContent')
</header>