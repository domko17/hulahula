<div class="col-lg-12 grid-margin px-0 stretch-card">
    <div class="card">
        <div class="card-body p-2 p-md-4">
            <div class="col-12">
                <h4 class="card-title">@lang('lecture.lectures_future')&nbsp;|&nbsp;
                    <small class="text-muted">@lang('lecture.lectures_future_help')</small>
                </h4>
            </div>



            <div id="lecture_future"></div>
            <script src="{{ mix('js/app.js') }}"></script>

                Vue integrations future table

{{--            <table class="table table-striped table-condensed pl-1 pl-md-0" id="lectures_future_mobile"--}}
{{--                   style="display: none; width: 100%">--}}
{{--                <thead>--}}
{{--                <tr>--}}
{{--                    <th></th>--}}
{{--                    <th></th>--}}
{{--                    <th> @lang('general.detail') </th>--}}
{{--                    <th> @lang('general.Date')</th>--}}
{{--                    <th> Balíček </th>--}}
{{--                    <th> @lang('general.actions') </th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}
{{--                @foreach($lectures_f as $l)--}}
{{--                    @if(!$l->canceled)--}}
{{--                        <tr>--}}
{{--                            <td>--}}
{{--                                <b>{{$l->class_date}}</b>--}}
{{--                            </td>--}}
{{--                            <td>{{ substr($l->hour->class_start, 0, 5) }}--}}
{{--                                - {{ substr($l->hour->class_end, 0, 5) }}</td>--}}
{{--                            <td>--}}
{{--                                @if(count($l->students) == 0 or (count($l->students) < $l->hour->class_limit))--}}
{{--                                    <span class="text-success"><b>@lang('lecture.free')</b></span>--}}
{{--                                @else--}}
{{--                                    <span class="text-danger"><b>@lang('lecture.not_free')</b></span>--}}
{{--                                @endif--}}
{{--                                <br>--}}
{{--                                U: @if($l->teacher_hour)--}}
{{--                                    <a href="{{ route('user.profile', $l->hour->teacher->id) }}" class="text-primary">--}}
{{--                                        {{ $l->hour->teacher->profile->first_name }} {{ $l->hour->teacher->profile->last_name }}--}}
{{--                                    </a>--}}
{{--                                @else--}}
{{--                                    @if($l->hour->teacher)--}}
{{--                                        <a href="{{ route('user.profile', $l->hour->teacher->id) }}"--}}
{{--                                           class="text-primary">--}}
{{--                                            {{ $l->hour->teacher->profile->first_name }} {{ $l->hour->teacher->profile->last_name }}--}}
{{--                                        </a>--}}
{{--                                    @endif--}}
{{--                                @endif <br>--}}
{{--                                S: @if(count($l->students) == 0)--}}
{{--                                    ---}}
{{--                                @elseif(count($l->students) == 1)--}}
{{--                                    {{ $l->students[0]->user->name }}--}}
{{--                                @else--}}
{{--                                    @foreach($l->students as $s)--}}
{{--                                        {{ $s->user->profile->last_name.", " }}--}}
{{--                                    @endforeach--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                <b>{{ \Carbon\Carbon::createFromFormat("Y-m-d", $l->class_date)->format("d.M.Y") }}</b><br>--}}
{{--                                {{ substr($l->hour->class_start, 0, 5) }}--}}
{{--                                - {{ substr($l->hour->class_end, 0, 5) }}--}}
{{--                            </td>--}}
{{--                            <td>{{ $l->packageUsed() < 0 ?: \App\Models\Helper::PACKAGES[$l->packageUsed()]['name'] }}</td>--}}
{{--                            <td>--}}
{{--                                <button--}}
{{--                                    onclick="window.location.href='{{ route('lectures.show', $l->id) }}'"--}}
{{--                                    class="btn btn-inverse-primary btn-sm pull-right"><i--}}
{{--                                        class="fa fa-search"></i></button>--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                    @endif--}}
{{--                @endforeach--}}
{{--                </tbody>--}}
{{--            </table>--}}

{{--            <table class="table table-striped table-condensed" id="lectures_future_pc" style="display: none">--}}
{{--                <thead>--}}
{{--                <tr>--}}
{{--                    <th> @lang('general.Date') </th>--}}
{{--                    <th> @lang('lecture.start') </th>--}}
{{--                    <th> @lang('general.Teacher') </th>--}}
{{--                    <th> @lang('general.Student') </th>--}}
{{--                    <th> @lang('general.Status')</th>--}}
{{--                    <th> Balíček </th>--}}
{{--                    <th> @lang('general.actions') </th>--}}
{{--                </tr>--}}
{{--                </thead>--}}
{{--                <tbody>--}}
{{--                @foreach($lectures_f as $l)--}}
{{--                    @if(!$l->canceled)--}}
{{--                        <tr>--}}
{{--                            <td>--}}
{{--                                <b>{{$l->class_date}}</b>--}}
{{--                            </td>--}}
{{--                            <td>{{ substr($l->hour->class_start, 0, 5) }}--}}
{{--                                - {{ substr($l->hour->class_end, 0, 5) }}</td>--}}
{{--                            <td>--}}
{{--                                @if($l->teacher_hour)--}}
{{--                                    <a href="{{ route('user.profile', $l->hour->teacher->id) }}" class="text-primary">--}}
{{--                                        {{ $l->hour->teacher->profile->first_name }} {{ $l->hour->teacher->profile->last_name }}--}}
{{--                                    </a>--}}
{{--                                @else--}}
{{--                                    @if($l->hour->teacher)--}}
{{--                                        <a href="{{ route('user.profile', $l->hour->teacher->id) }}"--}}
{{--                                           class="text-primary">--}}
{{--                                            {{ $l->hour->teacher->profile->first_name }} {{ $l->hour->teacher->profile->last_name }}--}}
{{--                                        </a>--}}
{{--                                    @endif--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                @if(count($l->students) == 0)--}}
{{--                                    ---}}
{{--                                @elseif(count($l->students) == 1)--}}
{{--                                    {{ $l->students[0]->user->name }}--}}
{{--                                @else--}}
{{--                                    @foreach($l->students as $s)--}}
{{--                                        {{ $s->user->profile->last_name.", " }}--}}
{{--                                    @endforeach--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                @if(count($l->students) == 0 or (count($l->students) < $l->hour->class_limit))--}}
{{--                                    <span class="badge badge-gradient-success">@lang('lecture.free')</span>--}}
{{--                                @else--}}
{{--                                    <span class="badge badge-gradient-danger">@lang('lecture.not_free')</span>--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                            <td>{{ $l->packageUsed() < 0 ?: \App\Models\Helper::PACKAGES[$l->packageUsed()]['name'] }}</td>--}}
{{--                            <td>--}}
{{--                                <button--}}
{{--                                    onclick="window.location.href='{{ route('lectures.show', $l->id) }}'"--}}
{{--                                    class="btn btn-inverse-primary btn-sm pull-right"><i--}}
{{--                                        class="fa fa-search"></i> @lang('general.detail')</button>--}}
{{--                                <button onclick="window.location.href='{{ route('admin.users.index') }}'"--}}
{{--                                        class="btn btn-inverse-warning btn-sm btn-icon pull-right"><i--}}
{{--                                        class="fa fa-user"></i></button>--}}
{{--                                <button onclick="window.location.href='{{ route('admin.users.index') }}'"--}}
{{--                                        class="btn btn-inverse-info btn-sm btn-icon pull-right"><i--}}
{{--                                        class="fa fa-search"></i></button>--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                    @endif--}}
{{--                @endforeach--}}
{{--                </tbody>--}}
{{--            </table>--}}
        </div>
    </div>
</div>
