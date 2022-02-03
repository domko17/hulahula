<?php
$ref = isset($_GET['ref']) ? $_GET['ref'] : false;
?>

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
                    <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4 mx-auto" style="z-index: 2">
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
                            <hr class="m-2">
                            <?php if(!$ref): ?>
                                <h4><?php echo e(__('auth.register_welcome_greeting')); ?></h4>
                                <h6 class="font-weight-light mb-0"><?php echo e(__('auth.register_welcome_message')); ?></h6>
                            <?php elseif($ref == 1): ?>
                                <h4><?php echo e(__('auth.register')); ?></h4>
                                <h6 class="font-weight-light mb-0"><?php echo e(__('auth.register_welcome_message_ref1')); ?></h6>
                            <?php endif; ?>
                            <form class="pt-3" method="POST" action="<?php echo e(route('register')); ?>" id="register_form">
                                <?php echo csrf_field(); ?>

                                <input type="hidden" name="locale" value="<?php echo e(session('locale', config('app.locale'))); ?>">
                                <div class="form-group mb-2">

                                    <input id="name" type="text" style="border-color: #e91e63;"
                                           class="form-control px-2 px-md-3 form-control-lg border-primary rounded <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           name="name" value="<?php echo e(old('name')); ?>" required autocomplete="name" autofocus
                                           placeholder="<?php echo e(__('general.Name_surname')); ?>">

                                    <?php $__errorArgs = ['name'];
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
                                           value="<?php echo e(old('email')); ?>" required autocomplete="email"
                                           placeholder="<?php echo e(__('general.Email')); ?>">

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
                                           required autocomplete="new-password"
                                           placeholder="<?php echo e(__('general.Password')); ?>">

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

                                <div class="form-group mb-2">
                                    <input id="password-confirm" type="password" style="border-color: #e91e63;"
                                           class="form-control px-2 px-md-3 form-control-lg border-primary rounded"
                                           name="password_confirmation" required autocomplete="new-password"
                                           placeholder="<?php echo e(__('general.Password_confirm')); ?>">
                                </div>

                                <div class="form-group mb-2">
                                    <input id="phone" type="text" style="border-color: #e91e63;"
                                           class="form-control px-2 px-md-3 form-control-lg border-primary rounded"
                                           name="phone" required
                                           placeholder="<?php echo e(__('general.Phone')); ?> (+421, +420, ...)">
                                </div>

                                <div class="mb-4">
                                    <div class="form-check">
                                        <label class="form-check-label text-muted">
                                            <input type="checkbox" class="form-check-input" name="gdpr">
                                        </label>
                                        <a href="https://hulahula.sk/grpr-ochrana-osobnych-udajov/"
                                           class="text-primary pl-4" target="_blank"> <?php echo e(__('auth.gdpr_consent')); ?> </a>
                                    </div>
                                </div>
                                <div class="mt-1">
                                    <button type="submit"
                                            class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn text-uppercase">
                                        <?php echo e(__('auth.sign_up')); ?>

                                    </button>
                                </div>
                                <div class="text-center mt-2 font-weight-light">
                                    <?php echo e(__('auth.already_has_account')); ?> <a href="<?php echo e(route('login')); ?>"
                                                                            class="text-primary"><?php echo e(__('auth.sign_in')); ?></a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="separator">
            <svg viewBox="0 0 2560 1440"
                 preserveAspectRatio="none"
                 version="1.1"
                 xmlns="http://www.w3.org/2000/svg">
                <polygon style="fill: #a02f67" fill-opacity="0.3"
                         points="-3000,2000 -3000,0 5000,0"
                         id="poly-sep">
                </polygon>
            </svg>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/auth/register.blade.php ENDPATH**/ ?>