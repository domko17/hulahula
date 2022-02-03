@foreach ($notif_arr as $notif)
    <a class="dropdown-item preview-item"
       href="{{ $notif['link'] }}">
        <div class="preview-thumbnail">
            <div class="preview-icon bg-{{ $notif['color'] }}">
                <i class="{{ $notif['icon'] }}"></i>
            </div>
        </div>
        <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
            @switch($notif['code_id'])
                @case('UN_1')
                <h6 class="preview-subject font-weight-normal mb-1">@lang('notifications.profile_not_set')</h6>
                <p class="text-gray mb-0" style="white-space: normal!important;">
                    @lang('notifications.profile_not_set_hint')
                </p>
                @break
                @case('ST_1')
                <h6 class="preview-subject font-weight-normal mb-1">@lang('notifications.stars_amount_zero')</h6>
                <p class="text-gray mb-0" style="white-space: normal!important;">
                    @lang('notifications.stars_amount_zero_hint')
                </p>
                @break
                @case('ST_2')
                <h6 class="preview-subject font-weight-normal mb-1">@lang('notifications.stars_amount_low')</h6>
                <p class="text-gray mb-0" style="white-space: normal!important;">
                    @lang('notifications.stars_amount_low_hint')
                </p>
                @break
                @case('AD_1')
                @if(Auth::user()->hasRole("admin"))
                    <h6 class="preview-subject font-weight-normal mb-1">Nevybavené objednávky</h6>
                    <p class="text-gray mb-0" style="white-space: normal!important;">
                        @lang('notifications.new_orders_hint', ["count" => $notif['data']['count']])
                    </p>
                @endif
                @break
                @case('AD_2')
                @if(Auth::user()->hasRole("admin"))
                    <h6 class="preview-subject font-weight-normal mb-1">Blížia sa niekoho narodeniny!</h6>
                    <p class="text-gray mb-0" style="white-space: normal!important;">
                        Niekto má o chvíľu narodeniny. Kliknite sem a zistite kto.
                    </p>
                @endif
                @break
                @case('TH_1')
                @if(Auth::user()->hasRole("teacher"))
                    <h6 class="preview-subject font-weight-normal mb-1">@lang('notifications.teacher_nearest_meeting')</h6>
                    <p class="text-gray mb-0" style="white-space: normal!important;">
                        <b>{{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $notif['data']['meeting_data']->start)->format("d.m.Y") }}</b>
                         @lang('notifications.teacher_nearest_meeting_hint')
                    </p>
                @endif
                @break
                @default
                <h6 class="preview-subject font-weight-normal mb-1">err.notif_unkwn</h6>
                <p class="text-gray mb-0" style="white-space: normal!important;">
                    {{ $notif['code'] }}
                </p>
                @break
            @endswitch
        </div>
    </a>
    <div class="dropdown-divider"></div>
@endforeach

{{-- Manual notifications --}}
@if(Auth::user()->hasRole("admin") or Auth::user()->hasRole("teacher"))
    {{--<a href="{{ route('materials.index') }}" class="dropdown-item preview-item">
        <div class="preview-thumbnail">
            <div class="preview-icon bg-success">
                <i class="mdi mdi-file-document-box"></i>
            </div>
        </div>
        <div
            class="preview-item-content d-flex align-items-start flex-column justify-content-center">
            <h6 class="preview-subject font-weight-normal mb-1">
                Novinka: Nahrávajte študíjné materiály do
                zdieľanej databázy.
            </h6>
        </div>
    </a>
    <div class="dropdown-divider"></div>
    <a class="dropdown-item preview-item" href="{{ route('word_cards.teacher.index') }}">
        <div class="preview-thumbnail">
            <div class="preview-icon bg-info">
                <i class="mdi mdi-cards"></i>
            </div>
        </div>
        <div
            class="preview-item-content d-flex align-items-start flex-column justify-content-center">
            <h6 class="preview-subject font-weight-normal mb-1">
                Novinka: Pridávajte kartičky so slovíčkami pre váš jazyk
            </h6>
        </div>
    </a>
    <div class="dropdown-divider"></div>--}}
@endif
