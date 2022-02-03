{{--<div class="alert alert-primary">
    --}}{{--\ (•◡•) / | Vitajte {{ Auth::user()->name }}!<br><br>
    Prezrite si <a href="{{ route("admin.languages.index") }}"> aké jazyky tu učíme</a>,
    nastavte si Váš
    <a href="{{ route("user.profile", Auth::id()) }}"> profil</a> alebo ak máte akúkoľvek
    otázku
    napíšte niektorému z <a href="{{ route("dashboard.contact") }}">našich adminov</a>.
    <br>
    <hr>
    Ak ste sa rozhodli aký jazyk chcete u nás študovať, jednoducho kliknite na vlajku daného jazyka na tejto stránke...
    a dajte o tom vedieť niektorému z<a href="{{ route("dashboard.contact") }}">našich adminov</a> a možno Vám prihodia aj
    hodinu
    zdarma :)
    <br><br>

    Prajeme pekný deň,<br>
    Váš tím Hula Hula.--}}{{--
    @lang('dashboard.guest_first_message')
</div>--}}

<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-12 stretch-card my-4 my-md-1">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-uppercase">@lang('dashboard.first_language_choose')</h4>
                        <p class="card-description">@lang('dashboard.first_language_choose_hint')</p>
                        <hr>
                        <div class="row rounded py-4 py-md-0">

                            @foreach(\App\Models\Language::all() as $l)
                                <a href="{{ route("dashboard.select_first_lang", $l->id) }}" class="col-12 col-md-3 my-3 text-primary"
                                   role="link">
                                    <div class="text-center">
                                        <h1 class="display-1"><i
                                                class="flag-icon {{ $l->icon }}"></i>
                                        </h1>
                                        <h4>
                                            {{ $l->name_sk }}
                                        </h4>
                                    </div>
                                </a>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
