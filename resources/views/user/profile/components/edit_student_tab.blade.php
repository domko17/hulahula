<div class="col-12 px-0 px-md-5">
    <h4 class="border-bottom border-danger text-danger">
        ADMIN: @lang('profile.adminStudentSettings') </h4>
</div>

<input hidden name="adminStudentSetting" value="1">

<div class="col-12 px-0">
    <div class="row">
        <div class="col-12 col-md-4 offset-md-4">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="text-muted">**Balíček študenta</p>
                </div>

                @if( $user->currentPackage )
                    <div class="form-group col-12 text-center">
                        <label for="package_user">Aktívny blíček</label>
                        <select id="package_user" type="number" name="package_user"
                                class="form-control form-control-lg">
                            @foreach(\App\Models\Helper::PACKAGES as $ix => $p)
                                <option value="{{ $ix }}" @if($user->currentPackage->type == $ix) selected @endif >
                                    {{ $p['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-12 text-center">
                        <label for="package_hours">Počet hodín v balíku</label>
                        <input id="package_hours" type="number" name="package_classes_left"
                               class="form-control form-control-lg"
                               value="{{ $user->currentPackage->classes_left }}" min="0"
                               max="100">
                    </div>
                @else
                    <div class="form-group col-12 text-center">
                        <label class="text-danger">Študent nemá žiaden aktívny balíček</label>
                        <p><strong>Aktivovať balíček</strong></p>
                        <button type="button" data-pid="1" class="btn btn-sm btn-primary set_package">SMART</button>
                        <button type="button" data-pid="2" class="btn btn-sm btn-primary set_package">PREMIUM</button>
                        <button type="button" data-pid="3" class="btn btn-sm btn-primary set_package">EXTRA</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 col-md-8 offset-md-2 text-center">
        <hr>
        <p class="text-muted">**Zmena úrovní jazyka študenta</p>
    </div>
    @foreach($user->studying as $l)
        <div class="form-group col-6 col-md-8 text-left text-md-right">
            <label for="level_lang_{{$l->id}}">
                <i class="flag-icon {{ $l->icon }}"></i> {{ $l->name_en }}
            </label>
            <select id="level_lang_{{$l->id}}" name="level_lang_{{$l->id}}"
                    class="form-control form-control-lg col-12 col-md-6 offset-md-6">
                <option
                    value="1" {{ $user->studyLevelOfLanguage($l->id) == "1" ? 'selected' : '' }}>
                    A1
                </option>
                <option
                    value="2" {{ $user->studyLevelOfLanguage($l->id) == "2" ? 'selected' : '' }}>
                    A2
                </option>
                <option
                    value="3" {{ $user->studyLevelOfLanguage($l->id) == "3" ? 'selected' : '' }}>
                    B1
                </option>
                <option
                    value="4" {{ $user->studyLevelOfLanguage($l->id) == "4" ? 'selected' : '' }}>
                    B2
                </option>
                <option
                    value="5" {{ $user->studyLevelOfLanguage($l->id) == "5" ? 'selected' : '' }}>
                    C1
                </option>
            </select>
        </div>
    @endforeach
</div>
