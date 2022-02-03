<?php $__env->startSection('content'); ?>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper auth auth-bckg_1">
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
                            <h3 class="text-primary"><?php echo app('translator')->get('general.zone'); ?> - Hula Hula</h3>
                            <hr>
                            <?php if(\Illuminate\Support\Facades\Request::cookie("FTWeb-User")): ?>
                                <h4><?php echo app('translator')->get('auth.login_welcome_known_greeting', ["name" => \Illuminate\Support\Facades\Request::cookie("FTWeb-User")]); ?></h4>
                                <h6 class="font-weight-light"><?php echo __('auth.password_reset_welcome_known_message'); ?></h6>
                            <?php else: ?>
                                <h4><?php echo e(__('auth.password_reset_welcome_unknown_greeting')); ?></h4>

                                <h6 class="font-weight-light"><?php echo __('auth.password_reset_welcome_unknown_message'); ?></h6>
                            <?php endif; ?>

                            <?php if(session()->has('status')): ?>
                                <div class="alert alert-success">
                                     <?php echo app('translator')->get('auth.password_reset_link_sent'); ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="<?php echo e(route('password.email')); ?>">
                                <?php echo csrf_field(); ?>

                                <div class="form-group">
                                    <input id="email" type="email"
                                           class="form-control px-2 px-md-3 form-control-lg <?php $__errorArgs = ['email'];
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
                                <div class="mt-3">
                                    <button type="submit"
                                            class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn text-uppercase">
                                        <?php echo app('translator')->get('auth.send_pwd_reset_link'); ?>
                                    </button>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/auth/passwords/email.blade.php ENDPATH**/ ?>