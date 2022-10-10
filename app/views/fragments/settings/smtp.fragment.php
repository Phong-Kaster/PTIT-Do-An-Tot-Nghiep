            <form class="js-ajax-form" id="smtp-form"
                  action="<?= APPURL . "/settings/" . $page ?>"
                  method="POST">
                <input type="hidden" name="action" value="save">

                <div class="section-header clearfix">
                    <h2 class="section-title"><?= __("SMTP Settings") ?></h2>
                    <div class="section-actions clearfix hide-on-large-only">
                        <a class="mdi mdi-menu-down icon js-settings-menu" href="javascript:void(0)"></a>
                    </div>
                </div>

                <div class="section-content">
                    <div class="form-result"></div>

                    <div class="clearfix">
                        <div class="col s12 m6 l5">
                            <div class="mb-20">
                                <label class="form-label"><?= __("SMTP Server") ?></label>

                                <input class="input"
                                       name="host" 
                                       type="text" 
                                       value="<?= htmlchars($EmailSettings->get("data.smtp.host")) ?>" 
                                       maxlength="200">

                                <ul class="field-tips">
                                    <li><?= __("If you left this field empty then other field values will be ignored and server's default configuration will be used.") ?></li>
                                </ul>
                            </div>

                            <div class="clearfix mb-40">
                                <div class="col s6 m6 l6">
                                    <label class="form-label"><?= __("Port") ?></label>

                                    <select name="port" class="input">
                                        <?php $port = $EmailSettings->get("data.smtp.port") ?>
                                        <option value="25" <?= $port == "25" ? "selected" : "" ?>>25</option>
                                        <option value="465" <?= $port == "465" ? "selected" : "" ?>>465</option>
                                        <option value="587" <?= $port == "587" ? "selected" : "" ?>>587</option>
                                    </select>
                                </div>

                                <div class="col s6 s-last m6 m-last l6 l-last">
                                    <label class="form-label"><?= __("Encryption") ?></label>

                                    <select name="encryption" class="input">
                                        <?php $encryption = $EmailSettings->get("data.smtp.encryption") ?>
                                        <option value=""><?= __("None") ?></option>
                                        <option value="ssl" <?= $encryption == "ssl" ? "selected" : "" ?>>SSL</option>
                                        <option value="tls" <?= $encryption == "tls" ? "selected" : "" ?>>TLS</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-20">
                                <label>
                                    <input type="checkbox" 
                                           class="checkbox" 
                                           name="auth" 
                                           value="1" 
                                           <?= $EmailSettings->get("data.smtp.auth") ? "checked" : "" ?>>
                                    <span>
                                        <span class="icon unchecked">
                                            <span class="mdi mdi-check"></span>
                                        </span>
                                        <?= __('SMTP Auth') ?>
                                    </span>
                                </label>
                            </div>

                            <div class="mb-20">
                                <label class="form-label"><?= __("Auth. username") ?></label>

                                <input class="input"
                                       name="username" 
                                       type="text" 
                                       value="<?= htmlchars($EmailSettings->get("data.smtp.username")) ?>" 
                                       maxlength="200">
                            </div>

                            <div class="mb-20">
                                <label class="form-label"><?= __("Auth. password") ?></label>

                                <?php 
                                    try {
                                        $password = \Defuse\Crypto\Crypto::decrypt($EmailSettings->get("data.smtp.password"), 
                                                    \Defuse\Crypto\Key::loadFromAsciiSafeString(CRYPTO_KEY));
                                    } catch (Exception $e) {
                                        $password = $EmailSettings->get("data.smtp.password");
                                    }
                                ?>

                                <input class="input"
                                       name="password" 
                                       type="password" 
                                       value="<?= htmlchars($password) ?>" 
                                       maxlength="200">
                            </div>
                        </div>

                        <div class="col s12 m6 m-last l5">
                            <div class="mb-20">
                                <label class="form-label"><?= __("From") ?></label>

                                <input class="input"
                                       name="from" 
                                       type="text" 
                                       value="<?= htmlchars($EmailSettings->get("data.smtp.from")) ?>" 
                                       maxlength="200">

                                <ul class="field-tips">
                                    <li><?= __("All emails will be sent from this email address.") ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix">
                        <div class="col s12 m6 l5">
                            <input class="fluid button" type="submit" value="<?= __("Save") ?>">
                        </div>
                    </div>
                </div>
            </form>