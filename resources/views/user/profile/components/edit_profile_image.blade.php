<div class="border-bottom text-center pb-4 hide_mobile">
    <img src="{{ $profile->getProfileImage() }}" alt="profile"
         class="img-lg rounded-circle mb-3">
    <p>{{ $user->roles[0]->display_name }}</p>
</div>

<div class="row">
    <div class="col-12" style="min-height: 250px">
        <img class="img-thumbnail rounded-circle w-100" id="profile_image_crop"
             src="{{ $profile->getProfileImage() }}">
    </div>
</div>
<br><br><br>
<div class="row">
    <div class="col-12" id="upload_progress_info" style="display: none;">
        <p class="text-primary">@lang('general.uploading_progress')</p>
    </div>
    <div class="col-6">
        <div class="form-group">
            <input type="file" id="imageUp" name="img" class="file-upload-default"
                   accept="image/*">
            <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info"
                       placeholder="Upload Image" style=" display: none;">
                <span class="input-group-append">
                    <button class="file-upload-browse btn btn-inverse-success"
                            type="button">@lang('general.upload')</button>
                </span>
            </div>
        </div>
    </div>
    <div class="col-6">
        <button type="button" id="profile_image_crop_save"
                class="btn btn-gradient-success">@lang('general.Save')
        </button>
    </div>
</div>
