<ul class="account-nav">
    <li><a href="{{ route('user.index') }}" class="menu-link menu-link_us-s">{{ __('shop.dashboard') }}</a></li>
    <li><a href="{{ route('user.orders') }}" class="menu-link menu-link_us-s">{{ __('shop.orders') }}</a></li>
    <li><a href="{{ route('account.edit') }}" class="menu-link menu-link_us-s">{{ __('shop.account_details') }}</a></li>
    <li><a href="{{ route('wishlist.index') }}" class="menu-link menu-link_us-s">{{ __('shop.wishlist') }}</a></li>

    <li>
        <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <a href="{{ route('logout') }}" class="menu-link menu-link_us-s"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                {{ __('shop.logout') }}
            </a>
        </form>
    </li>
</ul>
