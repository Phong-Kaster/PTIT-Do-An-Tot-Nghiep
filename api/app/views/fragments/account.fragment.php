        <div class='skeleton' id="account">
            <form class="js-ajax-form" 
                  action="<?= APPURL . "/accounts/" . ($Account->isAvailable() ? $Account->get("id") : "new") ?>"
                  method="POST"
                  autocomplete="off">
                <input type="hidden" name="action" value="save">

                <div class="container-1200">
                    <div class="row clearfix">
                        <div class="col s12 m8 l4">
                            <section class="section">
                                <div class="section-content">
                                    <div class="form-result">
                                    </div>

                                    <div class="js-login">
                                        <div class="mb-20">
                                            <label class="form-label">
                                                <?= __("Username") ?>
                                                <span class="compulsory-field-indicator">*</span>    
                                            </label>

                                            <input class="input js-required"
                                                   name="username" 
                                                   type="text" 
                                                   value="<?= htmlchars($Account->get("username")) ?>" 
                                                   placeholder="<?= __("Enter username") ?>"
                                                   maxlength="30">
                                        </div>

                                        <div class="">
                                            <label class="form-label">
                                                <?= __("Password") ?>
                                                <span class="compulsory-field-indicator">*</span>    
                                            </label>

                                            <input class="input js-required"
                                                   name="password" 
                                                   type="password" 
                                                   placeholder="<?= __("Enter password") ?>">
                                        </div>

                                        <?php if ($Settings->get("data.proxy") && $Settings->get("data.user_proxy")): ?>
                                            <div class="mt-20">
                                                <label class="form-label"><?= __("Proxy") ?> (<?= ("Optional") ?>)</label>

                                                <input class="input"
                                                       name="proxy" 
                                                       type="text" 
                                                       value="<?= htmlchars($Account->get("proxy")) ?>" 
                                                       placeholder="<?= __("Proxy for your country") ?>">
                                            </div>

                                            <ul class="field-tips">
                                                <li><?= __("Proxy should match following pattern: http://ip:port OR http://username:password@ip:port") ?></li>
                                                <li><?= __("It's recommended to to use a proxy belongs to the country where you've logged in this acount in Instagram's official app or website.") ?></li>
                                            </ul>
                                        <?php endif ?>
                                    </div>

                                    <div class="js-2fa none">
                                        <input type="hidden" name="2faid" value="" disabled>

                                        <div class="mb-20">
                                            <label class="form-label">
                                                <?= __("Security Code") ?>
                                                <span class="compulsory-field-indicator">*</span>    
                                            </label>

                                            <input class="input js-required"
                                                   name="twofa-security-code"
                                                   type="text" 
                                                   value="" 
                                                   placeholder="<?= __("Enter security code") ?>"
                                                   maxlength="8"
                                                   disabled>
                                        </div>

                                        <div>
                                            <div class="js-not-received-security-code">
                                                <?= __("Didn't get a security code?") ?>
                                                <a class="resend-btn" href='javascript:void(0)'>
                                                    <?= __("Resend it") ?>
                                                    <span class="timer" data-text="<?= __("after %s seconds", "{seconds}") ?>"></span>
                                                </a>
                                            </div>
                                            <div class="resend-result color-danger fz-12"></div>
                                        </div>

                                        <p class="backup-note">
                                            <?= 
                                                __(
                                                    "If you're unable to receive a security code, use one of your <a href='{url}' target='_blank'>backup codes</a>.", 
                                                    ["{url}" => "https://help.instagram.com/1006568999411025"]
                                                );
                                            ?>
                                        </p>
                                    </div>

                                    <div class="js-challenge none">
                                        <input type="hidden" name="challengeid" value="" disabled>

                                        <div class="mb-20">
                                            <label class="form-label">
                                                <?= __("Security Code") ?>
                                                <span class="compulsory-field-indicator">*</span>    
                                            </label>

                                            <input class="input js-required"
                                                   name="challenge-security-code"
                                                   type="text" 
                                                   value="" 
                                                   placeholder="<?= __("Enter security code") ?>"
                                                   maxlength="6"
                                                   disabled>
                                        </div>

                                        <div>
                                            <div class="js-not-received-security-code">
                                                <?= __("Didn't get a security code?") ?>
                                                <a class="resend-btn" href='javascript:void(0)'>
                                                    <?= __("Resend it") ?>
                                                    <span class="timer" data-text="<?= __("after %s seconds", "{seconds}") ?>"></span>
                                                </a>
                                            </div>
                                            <div class="resend-result color-danger fz-12"></div>
                                        </div>

                                        <p class="backup-note">
                                            <?= 
                                                __("You should receive the 6 digit security code sent by Instagram.");
                                            ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="js-login">
                                    <input class="fluid button button--footer js-login" type="submit" value="<?= $Account->isAvailable() ? __("Save changes") :  __("Add account") ?>">
                                </div>

                                <div class="js-2fa js-challenge none">
                                    <input class="fluid button button--footer" type="submit" value="<?= __("Confirm") ?>">
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        