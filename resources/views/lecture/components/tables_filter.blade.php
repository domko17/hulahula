<div class="col-12 grid-margin px-0 stretch-card mb-1">
    <div class="card">
        <div class="card-body p-2">
            <h4 class="card-title">Filter</h4>

            <form method="get" class="filter">
                @csrf
                <input type="hidden" name="filtered" value="1">

                <div class="row form-group mb-3">
                    <div class="col-6 col-md-2">
                        <label class="col-form-label py-0 m-0" for="f_lang">@lang('general.language')</label>
                        <select
                            class="form-control @if(isset($_GET['f_lang']) and $_GET['f_lang'] != 0) text-primary @endif"
                            name="f_lang" id="f_lang">
                            <option value="0"
                                    @if(!isset($_GET['f_lang']) or (isset($_GET['f_lang']) and $_GET['f_lang'] == 0)) selected @endif>@lang('general.select_option')</option>
                            @foreach(\App\Models\Language::all() as $l)
                                <option value="{{ $l->id }}"
                                        @if(isset($_GET['f_lang']) and $_GET['f_lang'] == $l->id) selected @endif>{{ $l->name_sk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="col-form-label py-0 m-0" for="f_type">@lang('general.Type')</label>
                        <select
                            class="form-control @if(isset($_GET['f_type']) and $_GET['f_type'] != 0) text-primary @endif"
                            name="f_type" id="f_type">
                            <option value="0"
                                    @if(!isset($_GET['f_type']) or (isset($_GET['f_type']) and $_GET['f_type'] == 0)) selected @endif>@lang('general.select_option')</option>
                            <option value="1"
                                    @if(isset($_GET['f_type']) and $_GET['f_type'] == 1) selected @endif>@lang('lecture.individual')</option>
                            <option value="2"
                                    @if(isset($_GET['f_type']) and $_GET['f_type'] == 2) selected @endif>@lang('lecture.collective')</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="col-form-label py-0 m-0" for="f_teacher">@lang('general.Teacher')</label>
                        <select
                            class="form-control @if(isset($_GET['f_teacher']) and $_GET['f_teacher'] != 0) text-primary @endif"
                            name="f_teacher" id="f_teacher">
                            <option value="0"
                                    @if(!isset($_GET['f_teacher']) or (isset($_GET['f_teacher']) and $_GET['f_teacher'] == 0)) selected @endif>@lang('general.select_option')</option>
                            <option value="-1"
                                    @if(isset($_GET['f_teacher']) and $_GET['f_teacher'] == -1) selected @endif>
                                ---
                            </option>
                            @foreach (\App\User::teachers() as $t)
                                <option value="{{ $t->id }}"
                                        @if(isset($_GET['f_teacher']) and $_GET['f_teacher'] == $t->id) selected @endif>{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="col-form-label py-0 m-0" for="f_student">@lang('general.Student')</label>
                        <select
                            class="form-control @if(isset($_GET['f_student']) and $_GET['f_student'] != 0) text-primary @endif"
                            name="f_student" id="f_student">
                            <option value="0"
                                    @if(!isset($_GET['f_student']) or (isset($_GET['f_student']) and $_GET['f_student'] == 0)) selected @endif>@lang('general.select_option')</option>
                            <option value="-1"
                                    @if(isset($_GET['f_student']) and $_GET['f_student'] == -1) selected @endif>
                                ---
                            </option>
                            @foreach (\App\User::students() as $s)
                                <option value="{{ $s->id }}"
                                        @if(isset($_GET['f_student']) and $_GET['f_student'] == $s->id) selected @endif>{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="col-form-label py-0 m-0" for="f_status">@lang('general.Status')</label>
                        <select
                            class="form-control @if(isset($_GET['f_status']) and $_GET['f_status'] != 0) text-primary @endif"
                            name="f_status" id="f_status">
                            <option value="0"
                                    @if(!isset($_GET['f_status']) or (isset($_GET['f_status']) and $_GET['f_status'] == 0)) selected @endif>@lang('general.select_option')</option>
                            <option value="1"
                                    @if(isset($_GET['f_status']) and $_GET['f_status'] == 1) selected @endif>@lang('lecture.free')</option>
                            <option value="2"
                                    @if(isset($_GET['f_status']) and $_GET['f_status'] == 2) selected @endif>@lang('lecture.not_free')</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-2 mt-3">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-filter"></i> Filter
                        </button>
                        @if(isset($_GET['filtered']))
                            <a href="{{ route('lectures.index') }}" class="btn btn-danger btn-sm"><i
                                    class="fa fa-times"></i></a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
