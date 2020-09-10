<aside class="menu">
    <p class="menu-label">
        General
    </p>
    <ul class="menu-list">
        <li><a class="{{ (request()->route()->named('settings.profile')) ? 'is-active' : '' }}"
               href="{{route('settings.profile')}}">Profile</a>
        </li>
        <li><a class="{{ (request()->route()->named('settings.password')) ? 'is-active' : '' }}"
               href="{{route('settings.password')}}">Password</a></li>
    </ul>
    <p class="menu-label">
        Developer
    </p>
    <ul class="menu-list">
        <li><a class="{{ (request()->route()->named('app-oauth.clients')) ? 'is-active' : '' }}"
               href="{{route('app-oauth.clients')}}">OAuth Clients</a></li>

        <li><a class="{{ (request()->route()->named('app-oauth.personal-access-tokens')) ? 'is-active' : '' }}"
               href="{{route('app-oauth.personal-access-tokens')}}">Personal Access Tokens</a></li>

        <li><a class="{{ (request()->route()->named('app-oauth.authorized-services')) ? 'is-active' : '' }}"
               href="{{route('app-oauth.authorized-services')}}">Authorized services</a></li>
    </ul>
</aside>
