<link rel="stylesheet" type="text/css" href="/plugins/Member/static/css/website.css">

<div class="member register">
    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">

            <h2 class="text-center">
                <?= $this->translate('Sign up') ?>
            </h2>

            <div class="row social">
                <div class="col-xs-4">
                    <a href="#" class="btn btn-lg btn-block btn-social btn-facebook">
                        <i class="fa fa-facebook visible-xs"></i>
                        <span class="hidden-xs">Facebook</span>
                    </a>
                </div>
                <div class="col-xs-4">
                    <a href="#" class="btn btn-lg btn-block btn-social btn-twitter">
                        <i class="fa fa-twitter visible-xs"></i>
                        <span class="hidden-xs">Twitter</span>
                    </a>
                </div>
                <div class="col-xs-4">
                    <a href="#" class="btn btn-lg btn-block btn-social btn-google">
                        <i class="fa fa-google-plus visible-xs"></i>
                        <span class="hidden-xs">Google+</span>
                    </a>
                </div>
            </div>

            <div class="social-or">
                <hr>
                <span class="text"><?= $this->translate('or') ?></span>
            </div>

            <form action="<?= $this->request->getRequestUri() ?>" method="post">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group">
                            <input type="text" name="firstname" id="firstname"
                                   class="form-control input-lg" tabindex="1"
                                   placeholder="<?= $this->translate('First Name') ?>">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group">
                            <input type="text" name="lastname" id="lastname"
                                   class="form-control input-lg" tabindex="2"
                                   placeholder="<?= $this->translate('Last Name') ?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input type="email" name="email" id="email"
                           class="form-control input-lg" tabindex="3"
                           placeholder="<?= $this->translate('Email Address') ?>">
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="password" name="password" id="password"
                                   class="form-control input-lg" tabindex="4"
                                   placeholder="<?= $this->translate('Password') ?>">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="password" name="password_confirm" id="password_confirm"
                                   class="form-control input-lg" tabindex="5"
                                   placeholder="<?= $this->translate('Confirm Password') ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4 col-sm-3">
                        <div class="form-group">
                            <label class="btn btn-default" tabindex="6">
                                <input type="checkbox" name="agree" id="agree" value="1">
                                <?= $this->translate('I Agree') ?>
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-8 col-sm-9">
                        <div class="form-group">
                            By clicking <strong class="label label-primary">Register</strong>,
                            you agree to the
                            <a href="#" data-toggle="modal" data-target="#terms">
                                Terms and Conditions
                            </a>
                            set out by this site, including our Cookie Use.
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        <?= $this->translate('Register') ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="<?= $this->editmode ? '' : 'modal fade' ?>" id="terms" tabindex="-1" role="dialog"
         aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="termsModalLabel">
                        <?= $this->translate('Terms & Conditions') ?>
                    </h4>
                </div>
                <div class="modal-body">
                    <?= $this->wysiwyg('terms') ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">
                        <?= $this->translate('I Agree') ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
