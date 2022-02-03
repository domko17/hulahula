<?php $__env->startSection('page_css'); ?>

    <style>
        svg > polygon {
            fill: #e91e63 !important;
        }

        button[type='submit'] {
            background: #e91e63;
            border-color: #e91e63;
        }

        .main_title {
            color: #e91e63 !important;
        }

        .auth-form-light a, .auth-form-light span.text-primary {
            color: #e91e63 !important;
        }

        .no_after {
            padding: 0;
        }

        .no_after:after {
            content: none !important;
        }

        .auth_lang_change_container {
            min-width: 2rem !important;
        }

        .auth_lang_change_container > a.preview-item {
            padding: 5px 10px;
            text-align: center;
        }

        .auth_lang_change_container > a.preview-item > p.preview-subject {
            font-size: 22px;
        }

    </style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper align-items-center auth auth-bckg_1">
                <div class="row py-0 py-md-5">
                    <div class="col-sm-12 col-md-7 col-lg-6 col-xl-4 mx-auto" style="z-index: 2">
                        <div
                            class="auth-form-light text-left p-4 p-md-5 animated
                            <?php switch(rand(1, 4)):
                            case (1): ?> fadeInDown <?php break; ?>
                            <?php case (2): ?> fadeInRight <?php break; ?>
                            <?php case (3): ?> fadeInLeft <?php break; ?>
                            <?php case (4): ?> fadeIn <?php break; ?>
                            <?php endswitch; ?>">
                            <h3 class="text-primary main_title d-flex justify-content-between"><?php echo app('translator')->get('general.zone'); ?> -
                                Hula Hula
                                <a class="nav-link count-indicator dropdown-toggle no_after" id="locale_change" href="#"
                                   data-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="flag-icon flag-icon-<?php echo e(\Illuminate\Support\Facades\App::getLocale() == 'en' ? 'gb' : \Illuminate\Support\Facades\App::getLocale()); ?>"></i>
                                </a>
                                <div
                                    class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list auth_lang_change_container"
                                    aria-labelledby="locale_change">
                                    <?php if(\Illuminate\Support\Facades\App::getLocale() != 'en'): ?>
                                        <a class="dropdown-item preview-item" href="<?php echo e(route('set_locale', 'en')); ?>">
                                            <p class="preview-subject ellipsis m-0 font-weight-normal text-small"><i
                                                    class="flag-icon flag-icon-gb"></i></p>
                                        </a>
                                    <?php endif; ?>
                                    <?php if(\Illuminate\Support\Facades\App::getLocale() != 'sk'): ?>
                                        <a class="dropdown-item preview-item" href="<?php echo e(route('set_locale', 'sk')); ?>">
                                            <p class="preview-subject ellipsis m-0 font-weight-normal text-small"><i
                                                    class="flag-icon flag-icon-sk"></i></p>
                                        </a>
                                    <?php endif; ?>
                                    <?php if(\Illuminate\Support\Facades\App::getLocale() != 'de'): ?>
                                        <a class="dropdown-item preview-item" href="<?php echo e(route('set_locale', 'de')); ?>">
                                            <p class="preview-subject ellipsis m-0 font-weight-normal text-small"><i
                                                    class="flag-icon flag-icon-de"></i></p>
                                        </a>
                                    <?php endif; ?>
                                    <?php if(\Illuminate\Support\Facades\App::getLocale() != 'ru'): ?>
                                        <a class="dropdown-item preview-item" href="<?php echo e(route('set_locale', 'ru')); ?>">
                                            <p class="preview-subject ellipsis m-0 font-weight-normal text-small"><i
                                                    class="flag-icon flag-icon-ru"></i></p>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </h3>
                            <hr>
                            <?php if(\Illuminate\Support\Facades\Request::cookie("FTWeb-User")): ?>
                                <h4><?php echo app('translator')->get('auth.login_welcome_known_greeting', ["name" => \Illuminate\Support\Facades\Request::cookie("FTWeb-User")]); ?></h4>
                                <h6 class="font-weight-light"><?php echo __('auth.login_welcome_known_message'); ?></h6>
                            <?php else: ?>
                                <h4><?php echo e(__('auth.login_welcome_unknown_greeting')); ?></h4>
                                <h6 class="font-weight-light"><?php echo __('auth.login_welcome_unknown_message'); ?></h6>
                            <?php endif; ?>
                            <form class="pt-3" method="POST" action="<?php echo e(route('login')); ?>">
                                <?php echo csrf_field(); ?>

                                <div class="form-group mb-2">
                                    <input id="email" type="email" style="border-color: #e91e63;"
                                           class="form-control px-2 px-md-3 form-control-lg border-primary rounded <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           name="email"
                                           value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus
                                           placeholder="<?php echo app('translator')->get('general.Email'); ?>">

                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="form-group mb-2">
                                    <input id="password" type="password" style="border-color: #e91e63;"
                                           class="form-control px-2 px-md-3 form-control-lg border-primary rounded <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           name="password"
                                           required autocomplete="current-password"
                                           placeholder="<?php echo app('translator')->get('general.Password'); ?>">

                                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="mt-3">
                                    <button type="submit"
                                            class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn text-uppercase">
                                        <?php echo app('translator')->get('auth.sign_in'); ?>
                                    </button>
                                </div>
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <div class="form-check">
                                        <label class="form-check-label text-muted">
                                            <input type="checkbox" class="form-check-input">
                                            <?php echo app('translator')->get('auth.keep_signed_in'); ?>
                                            <i class="input-helper"></i>
                                        </label>
                                    </div>
                                </div>
                                <div class="mt-2 text-center">
                                    <?php if(Route::has('password.request')): ?>
                                        <a class="auth-link text-black" href="<?php echo e(route('password.request')); ?>">
                                            <?php echo app('translator')->get('auth.forgot_pass'); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="text-center mt-2 font-weight-light">
                                    <?php echo app('translator')->get('auth.no_account'); ?> <a href="<?php echo e(route('register')); ?>"
                                                                class="text-primary"><?php echo app('translator')->get('general.Create'); ?></a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- SVG separator -->
        <div class="separator">
            <svg viewBox="0 0 2560 1440"
                 preserveAspectRatio="none"
                 version="1.1"
                 xmlns="http://www.w3.org/2000/svg">
                <polygon style="fill: #a02f67" fill-opacity="0.3"
                         points="4560,2000 5000,0 -1000,0"
                         id="poly-sep">
                </polygon>
            </svg>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/auth/login.blade.php ENDPATH**/ ?>