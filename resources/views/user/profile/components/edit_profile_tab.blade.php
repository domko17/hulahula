<div class="col-12 px-0 px-md-5">
    <h4 class="border-bottom border-secondary"> @lang('general.Base_info') </h4>
</div>
<div class="col-12 px-0 px-md-5">
    <div class="row">
        <div class="form-group col-6 col-md-3">
            <label for="title_before">@lang('general.title_before')</label>
            <input id="title_before" type="text" placeholder="Mgr"
                   value="{{ $profile->title_before }}" name="title_before"
                   class="form-control form-control-sm col-12">
        </div>
        <div class="form-group col-6 col-md-3">
            <label for="title_after">@lang('general.title_after')</label>
            <input id="title_after" type="text" placeholder="PhD."
                   value="{{ $profile->title_after }}" name="title_after"
                   class="form-control form-control-sm col-12">
        </div>
        <div class="form-group col-6 col-md-6">
            <label for="email"><span
                    class="input_req">*</span> @lang('general.Email')
            </label>
            <input id="email" type="email" placeholder="example@domain.com"
                   value="{{ $user->email }}" name="email"
                   data-inputmask="'alias': 'email'"
                   class="form-control form-control-sm col-12"
                   @if(Auth::id() == $user->id)
                   required
                   @endif readonly
                   aria-readonly="true">
        </div>

        <div class="form-group col-6 col-md-4">
            <label for="first_name"><span
                    class="input_req">*</span> @lang('general.first_name')
            </label>
            <input id="first_name" type="text" placeholder="John"
                   value="{{ $profile->first_name }}" name="first_name"
                   class="form-control form-control-sm col-12"
                   @if(Auth::id() == $user->id)
                   required
                @endif>
        </div>
        <div class="form-group col-6 col-md-4">
            <label for="last_name">@lang('general.last_name') <span
                    class="input_req">*</span></label>
            <input id="last_name" type="text" placeholder="Smith"
                   value="{{ $profile->last_name }}" name="last_name"
                   class="form-control form-control-sm col-12"
                   @if(Auth::id() == $user->id)
                   required
                   @endif
                   aria-required="true">
        </div>
        <div class="form-group col-6 col-md-4">
            <label for="gender">@lang('general.Gender') <span
                    class="input_req">*</span> </label>
            <select id="gender" name="gender"
                    class="form-control form-control-sm col-12"
                    @if(Auth::id() == $user->id)
                    required
                @endif>
                <option value="0" disabled
                        selected>@lang('general.select_option')</option>
                <option value="M"
                        @if($profile->gender == 'M') selected @endif>
                    @lang('general.male')</option>
                <option value="F"
                        @if($profile->gender == 'F') selected @endif>
                    @lang('general.female')</option>
            </select>
        </div>
        <div class="form-group col-6 col-md-4">
            <label for="phone"><span
                    class="input_req">*</span> @lang('general.Phone')
            </label>
            <input id="phone" type="text" placeholder=""
                   value="{{ $profile->phone }}" name="phone"
                   class="form-control form-control-sm col-12 col-md-8"
                   @if(Auth::id() == $user->id)
                   required
                @endif>
        </div>
        <div class="form-group col-6 col-md-4">
            <label for="birthday">@lang('general.Birthday') <span
                    class="input_req col-form-label">*</span> </label>
            <div class="input-group date">
                <input type="text" class="form-control form-control-sm"
                       name="birthday" id="birthday"
                       data-inputmask="'alias': 'date'"
                       im-insert="true"
                       placeholder="dd/mm/yyyy"
                       @if(Auth::id() == $user->id)
                       required
                       @if($profile->birthday)
                       readonly
                       @endif
                       @endif
                       value="{{ $profile->birthday ? \Carbon\Carbon::createFromFormat("Y-m-d",$profile->birthday)->format("d/m/Y") : "" }}">
            </div>
        </div>
        <div class="form-group col-6 col-md-4">
            <label for="birthday">@lang('general.nationality') <span
                    class="input_req col-form-label">*</span> </label>
            <input type="text"
                   class="form-control form-control-sm col-12"
                   name="nationality" id="nationality"
                   @if(Auth::id() == $user->id)
                   required
                   @endif
                   value="{{ $profile->nationality }}">
        </div>
        @if($user->hasRole('teacher'))
            <div class="form-group col-6">
                <label for="iban">@lang('profile.iban') <span
                        class="input_req col-form-label">*</span> </label>
                <input type="text" class="form-control form-control-sm"
                       name="iban" id="iban"
                       @if(Auth::id() == $user->id)
                       required
                       @endif
                       value="{{ $profile->iban }}">
            </div>
            <div class="form-group col-6">
                <label for="zune">ZOOM
                    link </label>
                <input type="url" class="form-control form-control-sm"
                       name="zune" id="zune"
                       value="{{ $profile->zune_link }}">
            </div>
            <div class="form-group col-6">
                <label for="time_before_class">Študent sa môže zapísat na moju hodinu najneskôr [x] hodín pred začatím
                    lekcie</label>
                <input type="number" min="0" max="48" class="form-control form-control-sm"
                       name="time_before_class" id="time_before_class"
                       value="{{ $profile->time_before_class }}">
            </div>
        @endif
    </div>
</div>
<div class="col-12 px-0 px-md-5">
    <h4 class="border-bottom border-secondary"> @lang('general.Address') </h4>
</div>
<div class="col-12 px-0 px-md-5">
    <div class="row">
        <div class="form-group col-6 col-md-3">
            <label for="street"><span
                    class="input_req">*</span> @lang('general.Street')
            </label>
            <input id="street" type="text"
                   value="{{ $profile->street }}" name="street"
                   class="form-control form-control-sm col-12"
                   @if(Auth::id() == $user->id)
                   required
                @endif>
        </div>
        <div class="form-group col-6 col-md-3">
            <label for="street_number">@lang('general.Street_number') <span
                    class="input_req">*</span></label>
            <input id="street_number" type="text"
                   value="{{ $profile->street_number }}"
                   name="street_number"
                   class="form-control form-control-sm col-12"
                   @if(Auth::id() == $user->id)
                   required
                @endif
            >
        </div>
        <div class="form-group col-6 col-md-3">
            <label for="city"><span
                    class="input_req">*</span> @lang('general.City')
            </label>
            <input id="city" type="text"
                   value="{{ $profile->city }}" name="city"
                   class="form-control form-control-sm col-12"
                   @if(Auth::id() == $user->id)
                   required
                @endif>
        </div>
        <div class="form-group col-6 col-md-3">
            <label for="zip">@lang('general.Zip') <span
                    class="input_req">*</span></label>
            <input id="zip" type="text"
                   value="{{ $profile->zip }}" name="zip"
                   class="form-control form-control-sm col-12"
                   @if(Auth::id() == $user->id)
                   required
                @endif
            >
        </div>
    </div>
</div>
@if($user->hasRole('teacher'))
    <div class="col-12 px-0 px-md-5">
        <h4 class="border-bottom border-secondary"> @lang('profile.bio') </h4>
    </div>
    <div class="col-12 px-0 px-md-5">
        <div class="row form-group">
            <div class="col-12">
                <textarea id="bio" name="bio">{!! $profile->bio !!}</textarea>
            </div>
        </div>
    </div>
@endif
<div class="col-12 px-0 px-md-5">
    <h4 class="border-bottom border-secondary"> @lang('profile.password_change') </h4>
</div>
<div class="col-12 px-0 px-md-5">
    <div class="row">
        <div class="form-group col-12">
            <label for="old_pass">@lang('general.Password_old')</label>
            <input id="old_pass" type="password"
                   name="old_pass" autocomplete="new-password"
                   class="form-control form-control-sm col-12">
        </div>
    </div>
    <div class="row">
        <div class="form-group col-12 col-md-6">
            <label for="password">@lang('general.Password')</label>
            <input id="password" type="password"
                   name="password"
                   class="form-control form-control-sm col-12">
        </div>
        <div class="form-group col-12 col-md-6">
            <label for="password_confirm">@lang('general.Password_confirm')</label>
            <input id="password_confirm" type="password"
                   name="password_confirm"
                   class="form-control form-control-sm col-12">
        </div>
    </div>
</div>
