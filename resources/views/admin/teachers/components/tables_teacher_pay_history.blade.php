<table class="table table-striped pl-1" id="history_table_mobile" style="display: none; width: 100%;">
    <thead>
    <tr>
        <th></th>
        <th>@lang('general.Teacher')</th>
        <th>Suma</th>
        <th>@lang('general.Date')</th>
        <th>#</th>
    </tr>
    </thead>
    <tbody>
    @foreach($history as $h)
        <tr>
            <td class="p-1 text-center" style="font-size: 1.5em">
                <img src="{{ $h->user->profile->getProfileImage() }}"
                     class="{{ $h->user->is_online() ? ($h->user->is_online() == 1 ? 'profile_img_online':($h->user->is_online()? 'profile_img_inactive' :'profile_img_idle')):'' }}"
                     alt="{{$h->user->profile->first_name}} {{$h->user->profile->last_name}}'s profile picture">
            </td>
            <td>
                <a href="{{ route("user.profile", $h->user->id) }}" class="text-primary">{{ $h->user->name }}</a><br>
                <i class="fa fa-star-o"></i>: {{ $h->stars_i }} @if($h->stars_c) / {{ $h->stars_c }} @endif<br>
                <b>{{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $h->created_at)->format("d.m.Y") }}</b>
            </td>
            <td>{{ $h->paid }} €</td>
            <td>{{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $h->created_at)->format("d.m.Y") }}</td>
            <td>{{ $h->id }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<table class="table table-striped" id="history_table_pc" style="display: none;">
    <thead>
    <tr>
        <th></th>
        <th>@lang('general.Name_surname')</th>
        <th>Vyplatene hodiny</th>
        <th>Vyplatena suma</th>
        <th>@lang('general.Date')</th>
        <th>#</th>
    </tr>
    </thead>
    <tbody>
    @foreach($history as $h)
        <tr>
            <td>
                <img class="img-sm" src="{{ $h->user->profile->getProfileImage() }}">
            </td>
            <td><a href="{{ route("user.profile", $h->user->id) }}" class="text-primary">{{ $h->user->name }}</a></td>
            <td>{{ $h->stars_i }} @if($h->stars_c) / {{ $h->stars_c }} @endif</td>
            <td>{{ $h->paid }} €</td>
            <td>{{ \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $h->created_at)->format("d.m.Y") }}</td>
            <td>{{ $h->id }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
