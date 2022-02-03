<table class="table table-striped pl-1" id="table_teachers_mobile" style="display: none; width: 100%;">
    <thead>
    <tr>
        <th></th>
        <th>@lang('general.Teacher')</th>
        <th><i class="fa fa-money"></i></th>
        <th>@lang('general.actions')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($teachers as $t)
        <tr>
            <td class="p-1 text-center" style="font-size: 1.5em">
                <img src="{{ $t->profile->getProfileImage() }}"
                     class="{{ $t->is_online() ? ($t->is_online() == 1 ? 'profile_img_online':($t->is_online()? 'profile_img_inactive' :'profile_img_idle')):'' }}"
                     alt="{{$t->profile->first_name}} {{$t->profile->last_name}}'s profile picture">
            </td>
            <td>
                <a href="{{ route("user.profile", $t->id) }}" class="text-primary">{{ $t->name }}</a><br>
                <i class="mdi mdi-currency-eur"></i>: {{ $t->profile->teacher_salary_i }}€
                <i class="fa fa-star-o"></i>: {{ count($t->classes_i_unpaid()) }}
            </td>
            <td><b>{{ $t->pending_salary() }} €</b></td>
            <td class="text-right">
                @if($t->pending_salary() > 0)
                    <button
                        type="button" data-item-id="{{$t->id}}" data-toggle="modal"
                        data-target="#makePaymentModal"
                        class="btn btn-inverse-primary btn-sm pay-alert"><i
                            class="fa fa-money"></i>
                    </button>
                @endif
                {{--<button onclick="window.location.href='{{ route('user.profile', $t->id) }}'"
                        class="btn btn-inverse-info btn-sm"><i
                        class="fa fa-search"></i>
                </button>--}}
                <button
                    onclick="window.location.href='{{ route('admin.teachers.teachers_hours', $t->id) }}'"
                    class="btn btn-inverse-danger btn-sm"
                    data-custom-class="tooltip-danger" data-toggle="tooltip"
                    data-placement="top" title=""
                    data-original-title="@lang('general.teachers_hours', ['name'=>$t->name])">
                    <i class="fa fa-star-o"></i>
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<table class="table table-striped" id="table_teachers_pc" style="display: none;">
    <thead>
    <tr>
        <th></th>
        <th>@lang('general.Name_surname')</th>
        <th>Mzda</th>
        <th><i class="fa fa-star text-golden"></i></th>
        <th><i class="fa fa-money"></i></th>
        <th>@lang('general.actions')</th>
    </tr>
    </thead>
    <tbody>
    @foreach($teachers as $t)
        <tr>
            <td class="py-1">
                <img
                    class="{{ $t->is_online() ? ($t->is_online() == 1 ? 'profile_img_online':($t->is_online()? 'profile_img_inactive' :'profile_img_idle')):'' }}"
                    src="{{ $t->profile->getProfileImage() }}">
            </td>
            <td><a href="{{ route("user.profile", $t->id) }}" class="text-primary">{{ $t->name }}</a></td>
            <td>{{ $t->profile->teacher_salary_i }}€</td>
            <td>{{ count($t->classes_i_unpaid()) }}</td>
            <td><b>{{ $t->pending_salary() }} €</b></td>
            <td>
                <button
                    onclick="window.location.href='{{ route('admin.teachers.teachers_hours', $t->id) }}'"
                    class="btn btn-inverse-danger btn-sm mx-1 pull-right"
                    data-custom-class="tooltip-danger" data-toggle="tooltip"
                    data-placement="top" title=""
                    data-original-title="@lang('general.teachers_hours', ['name'=>$t->name])">
                    <i class="fa fa-star-o"></i></button>
                <button onclick="window.location.href='{{ route('user.profile', $t->id) }}'"
                        class="btn btn-inverse-info btn-sm mx-1 pull-right"><i
                        class="fa fa-search"></i> @lang('general.profile')</button>
                @if($t->pending_salary() > 0)
                    <button
                        type="button" data-item-id="{{$t->id}}" data-toggle="modal"
                        data-target="#makePaymentModal"
                        class="btn btn-inverse-primary btn-sm mx-1 pull-right pay-alert"><i
                            class="fa fa-money"></i> Zaplatiť
                    </button>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
