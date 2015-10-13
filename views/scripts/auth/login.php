<link rel="stylesheet" type="text/css" href="/plugins/Member/static/css/website.css">

<div class="member_login">
    <h3 class="title">
        <?= $this->translate('Login') ?>
        <?= $this->translate('or') ?>
        <a href="#todo"><?= $this->translate('Sign up') ?></a>
    </h3>

    <div class="row row-sm-offset-3 social">
        <div class="col-xs-4 col-sm-2">
            <a href="#" class="btn btn-lg btn-block btn-facebook">
                <i class="fa fa-facebook visible-xs"></i>
                <span class="hidden-xs">Facebook</span>
            </a>
        </div>
        <div class="col-xs-4 col-sm-2">
            <a href="#" class="btn btn-lg btn-block btn-twitter">
                <i class="fa fa-twitter visible-xs"></i>
                <span class="hidden-xs">Twitter</span>
            </a>
        </div>
        <div class="col-xs-4 col-sm-2">
            <a href="#" class="btn btn-lg btn-block btn-google">
                <i class="fa fa-google-plus visible-xs"></i>
                <span class="hidden-xs">Google+</span>
            </a>
        </div>
    </div>

    <div class="row row-sm-offset-3 login-or">
        <div class="col-xs-12 col-sm-6">
            <hr>
            <span class="text"><?= $this->translate('or') ?></span>
        </div>
    </div>

    <div class="row row-sm-offset-3">
        <div class="col-xs-12 col-sm-6">
            <form action="<?= $this->formAction ?>" method="post" autocomplete="off">
                <div class="form-group <?= $this->error ? 'has-error' : '' ?>">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-fw fa-user"></i></span>
                        <input type="email" class="form-control" name="email"
                               placeholder="<?= $this->translate('Email') ?>">
                    </div>
                </div>

                <div class="form-group <?= $this->error ? 'has-error' : '' ?>">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                        <input type="password" class="form-control" name="password"
                               placeholder="<?= $this->translate('Password') ?>">
                    </div>
                </div>

                <?php if ($this->error): ?>
                <div class="form-group has-error">
                    <span class="help-block"><?= $this->error ?></span>
                </div>
                <?php endif; ?>

                <button class="btn btn-lg btn-primary btn-block" type="submit">
                    <?= $this->translate('Login') ?>
                </button>
            </form>
        </div>
    </div>
    <div class="row row-sm-offset-3">
        <div class="col-xs-12 col-sm-3">
            <div class="checkbox">
                <label>
                    <input type="checkbox" value="remember-me">
                    <?= $this->translate('Remember Me') ?>
                </label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-3">
            <p class="forgot-pwd">
                <a href="#todo"><?= $this->translate('Forgot password?') ?></a>
            </p>
        </div>
    </div>
</div>
