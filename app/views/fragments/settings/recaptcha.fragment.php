            <form class="js-ajax-form" 
                  action="<?= APPURL . "/settings/" . $page ?>"
                  method="POST">
                <input type="hidden" name="action" value="save">

                <div class="section-header clearfix">
                    <h2 class="section-title"><?= __("Google reCaptcha") ?></h2>
                    <div class="section-actions clearfix hide-on-large-only">
                        <a class="mdi mdi-menu-down icon js-settings-menu" href="javascript:void(0)"></a>
                    </div>
                </div>

                <div class="section-content">
                    <div class="clearfix">
                        <div class="col s12 m6 l5">
                            <div class="form-result"></div>

                            <div class="mb-20">
                                <label class="form-label"><?= __("Site key") ?></label>

                                <input class="input"
                                       name="site-key" 
                                       type="text"
                                       value="<?= htmlchars(get_option("np_recaptcha_site_key")) ?>" 
                                       maxlength="100">
                            </div>

                            <div class="mb-40">
                                <label class="form-label"><?= __("Secret key") ?></label>

                                <input class="input"
                                       name="secret-key" 
                                       type="text"
                                       value="<?= htmlchars(get_option("np_recaptcha_secret_key")) ?>"
                                       maxlength="100">
                            </div>

                            <div class="mb-40">
                                <label>
                                    <input type="checkbox" 
                                           class="checkbox" 
                                           name="signup-recaptcha-verification" 
                                           value="1" 
                                           <?= get_option("np_signup_recaptcha_verification") ? "checked" : "" ?>>
                                    <span>
                                        <span class="icon unchecked">
                                            <span class="mdi mdi-check"></span>
                                        </span>
                                        <?= __('Enable Recaptcha in signup page') ?>
                                    </span>
                                </label>

                                <ul class="field-tips">
                                    <li><?= __("Valid site and secret keys are required.") ?></li>
                                </ul>
                            </div>
                        </div>

                        <div class="col s12 m6 m-last l5 offset-l1">
                            <label class="form-label mb-10"><?= __("Notes") ?></label>

                            <p>
                                <?= __("You should follow these steps to get necessary keys:") ?>
                            </p>

                            <ul class="field-tips">
                                <li class="mb-15">
                                    <?= __("Go to Google reCAPTCHA website and find \"Register a new site\" section") ?>:<br>
                                    <a href="https://www.google.com/recaptcha/admin" target="_blank">https://www.google.com/recaptcha/admin</a>
                                </li>

                                <li class="mb-15">
                                    <?= __('Include any text in the "Label" field') ?>
                                </li>

                                <li class="mb-15">
                                    <?= __('Select "reCAPTCHA v2" as a site key type') ?>
                                </li>

                                <li class="mb-15">
                                    <?= __('Enter the following address to the "Domains" field in a individual line') ?>: <br>
                                    <strong><?= parse_url(APPURL, PHP_URL_HOST) ?></strong>
                                </li>

                                <li class="mb-15">
                                    <?= __('Copy & paste the site and secret keys') ?>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="clearfix">
                        <div class="col s12 m6 l5">
                            <input class="fluid button" type="submit" value="<?= __("Save") ?>">
                        </div>
                    </div>
                </div>
            </form>