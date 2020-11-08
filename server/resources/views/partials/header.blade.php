<header id="site-header" class="site-header" role="banner" style="background-color: #ffffff">
    <div class="container">
        <div class="site-logo-wrap">
            <hgroup>
                <h1 class='site-title site-title-no-desc'><a  style="color:#000000; font-family: 'Helvetica Neue'; font-weight: bold" href='{{ route('home') }}' title='{{ config('app.name', 'Laravel Shops') }}' rel='home'>{{ config('app.name', 'Laravel Shops') }}</a></h1>
            </hgroup>
        </div>
        <nav id="primary-nav" class="primary-nav" role="navigation">
            <ul id="menu-gd-menu" class="menu">
                @guest
                    <li class="gd-menu-item menu-item menu-item-type-post_type_archive"><a href="{{ route('login') }}" style="color: #000000;">Login</a></li>
                    <li class="gd-menu-item menu-item menu-item-type-post_type_archive"><a href="{{ route('register') }}" style="color: #000000;">Register</a></li>
                @else
                    <li class="gd-menu-item menu-item menu-item-type-post_type_archive"><a href="{{ route('admin.users.show', \Auth::user()->id)}}" style="color: #000000;">Profile</a></li>
                    <li class="gd-menu-item menu-item menu-item-type-post_type_archive"><a href="{{ route('admin.shops.index') }}" style="color: #000000;">Manage Shops</a></li>
                    <li class="gd-menu-item menu-item menu-item-type-post_type_archive"><a href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();" style="color: #000000;">Logout</a></li>
                    <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                @endguest
            </ul>
        </nav>
        <div class="dt-nav-toggle  dt-mobile-nav-button-wrap"><a href="#primary-nav"><i class="fas fa-bars"></i></a></div>
    </div>
</header>