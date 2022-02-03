<div class="col-12 px-0 px-md-5">
    <h4 class="border-bottom border-danger text-danger">
        ADMIN: @lang('profile.adminTeacherSettings') </h4>
</div>

<input hidden name="adminTeacherSetting" value="1">

<div class="col-12 px-0 px-md-5">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2 text-center">
            <p class="text-muted">**Základ mzdy učiteľa</p>
        </div>
        <div class="form-group col-12 col-md-8 text-left text-md-right">
            <label for="salary_i"> @lang('profile.salary_i')
            </label>
            <input id="salary_i" type="number" name="salary_i"
                   class="form-control form-control-lg col-12 col-md-6 offset-md-6"
                   step="any"
                   value="{{ $profile->teacher_salary_i }}" min="0" max="40">
        </div>
        <div class="form-group col-12 col-md-8 text-left text-md-right">
            <label for="salary_c"> @lang('profile.salary_c')
            </label>
            <input id="salary_c" type="number" name="salary_c"
                   class="form-control form-control-lg col-12 col-md-6 offset-md-6"
                   step="any"
                   value="{{ $profile->teacher_salary_c }}" min="0" max="40">
        </div>
    </div>
</div>
