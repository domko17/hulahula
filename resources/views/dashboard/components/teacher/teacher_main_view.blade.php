<br>
@include('calendar.teacher.calendar_base')
@include('calendar.teacher.calendar_modals')

@if(count($teacher->classRequests))
    <div class="row">
        <div class="col-12">
            <hr>
            <h5>Žiadosti o hodinu</h5>
        </div>
        @foreach($teacher->classRequests as $cr)
            <div class="col-12">
                <img src="{{$cr->student->profile->getProfileImage()}}" class="img-xs">
                <a href="{{ route('user.profile', $cr->student_id) }}"
                   class="text-primary"><b>{{ $cr->student->name }}</b></a>
                @if($cr->language)
                    <i class="flag-icon flag-icon-{{ \App\Models\Language::find($cr->language)->abbr == "EN" ? "gb" : strtolower(\App\Models\Language::find($cr->language)->abbr) }}"></i>
                @endif
                |
                {{ \Carbon\Carbon::createFromFormat('Y-m-d', $cr->date)->format('d.m.Y') }}
                {{ substr($cr->start_time, 0, 5) }}
                <a href="{{ route('takeLectureRequest', $cr->id) }}" class="text-success font-weight-bold"><i
                        class="fa fa-check"></i> Vziať hodinu</a>
            </div>
        @endforeach
    </div>
@endif
