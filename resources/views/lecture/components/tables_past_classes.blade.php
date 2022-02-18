<div class="col-12 grid-margin px-0 stretch-card">
    <div class="card">
        <div class="card-body p-2 p-md-4">
            <div class="col-12">
                <h4 class="card-title">@lang('lecture.lectures_past') &nbsp;|&nbsp;
                    <small class="text-muted">@lang('lecture.lectures_past_help')</small>
                </h4>
            </div>
            <table class="table table-striped pl-1 pl-md-0" id="lectures_past_mobile"
                   style="display: none; width: 100%;">
                <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th> @lang('general.detail') </th>
                    <th> @lang('general.Date') </th>
                    <th> @lang('general.package') </th>
                    <th> @lang('general.actions') </th>
                </tr>
                </thead>
                <tbody>
                @foreach($lectures_p as $l)
                    @if(count($l->students) > 0 and !$l->canceled)
                        <tr>
                            <td>
                                <b>{{$l->class_date}}</b>
                            </td>
                            <td>{{ substr($l->hour->class_start, 0, 5) }}
                                - {{ substr($l->hour->class_end, 0, 5) }}</td>
                            <td>
                                @if($l->canceled)
                                    @lang('lecture.canceled')
                                @endif
                                <br>
                                @if(count($l->students) == 1)
                                    S: {{ $l->students[0]->user->name }}
                                @else
                                    S:
                                    @foreach($l->students as $s)
                                        {{ $s->user->profile->last_name.", " }}
                                    @endforeach
                                @endif
                                <br>
                                T: <a href="{{ route('user.profile', $l->hour->teacher->id) }}" class="text-primary">
                                    {{ $l->hour->teacher->profile->first_name }} {{ $l->hour->teacher->profile->last_name }}
                                </a>
                            </td>
                            <td>
                                <b>{{ \Carbon\Carbon::createFromFormat("Y-m-d", $l->class_date)->format("d.M.Y") }}</b><br>
                                {{ substr($l->hour->class_start, 0, 5) }}
                                - {{ substr($l->hour->class_end, 0, 5) }}
                            </td>
                            <td>{{ $l->packageUsed() < 0 ?: \App\Models\Helper::PACKAGES[$l->packageUsed()]['name'] }}</td>
                            <td>
                                <button
                                    onclick="window.location.href='{{ route('lectures.show', $l->id) }}'"
                                    class="btn btn-inverse-primary btn-sm pull-right"><i
                                        class="fa fa-search"></i></button>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
            <table class="table table-striped sortable-table" id="lectures_past_pc" style="display: none;">
                <thead>
                <tr>
                    <th></th>
                    <th> @lang('general.Student') </th>
                    <th> @lang('general.Teacher') </th>
                    <th> @lang('general.Date') </th>
                    <th> @lang('lecture.start') </th>
                    <th> Balíček </th>
                    <th> @lang('general.actions') </th>
                </tr>
                </thead>
                <tbody>
                @foreach($lectures_p as $l)
                    @if(count($l->students) > 0 and !$l->canceled)
                        <tr>
                            <td class="py-1" style="font-size: 1.5em">

                            </td>
                            <td>
                                @if($l->canceled)
                                    !ZRUŠENÁ!
                                @endif
                                @if(count($l->students) == 1)
                                    {{ $l->students[0]->user->name }}
                                @else
                                    @foreach($l->students as $s)
                                        {{ $s->user->profile->last_name.", " }}
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('user.profile', $l->hour->teacher->id) }}" class="text-primary">
                                    {{ $l->hour->teacher->profile->first_name }} {{ $l->hour->teacher->profile->last_name }}
                                </a>
                            </td>
                            <td>
                                <b>{{\Carbon\Carbon::createFromFormat("Y-m-d", $l->class_date)->format("d,M Y")}}</b>
                            </td>
                            <td>{{ substr($l->hour->class_start, 0, 5) }}
                                - {{ substr($l->hour->class_end, 0, 5) }}</td>
                            <td>{{ $l->packageUsed() < 0 ?: \App\Models\Helper::PACKAGES[$l->packageUsed()]['name'] }}</td>
                            <td>
                                <button
                                    onclick="window.location.href='{{ route('lectures.show', $l->id) }}'"
                                    class="btn btn-inverse-primary btn-sm pull-right"><i
                                        class="fa fa-search"></i> @lang('general.detail')</button>
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
