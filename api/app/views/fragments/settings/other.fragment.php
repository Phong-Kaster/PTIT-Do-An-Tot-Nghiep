            <form class="js-ajax-form" 
                  action="<?= APPURL . "/settings/" . $page ?>"
                  method="POST">
                <input type="hidden" name="action" value="save">

                <div class="section-header clearfix">
                    <h2 class="section-title"><?= __("Other Settings") ?></h2>
                    <div class="section-actions clearfix hide-on-large-only">
                        <a class="mdi mdi-menu-down icon js-settings-menu" href="javascript:void(0)"></a>
                    </div>
                </div>

                <div class="section-content">
                    <div class="clearfix">
                        <div class="col s12 m6 l5">
                            <div class="form-result"></div>

                            <div class="mb-20">
                                <label class="form-label"><?= __("Default Currency") ?></label>

                                <select class="input" name="currency">
                                    <?php foreach ($Currencies as $k => $v): ?>
                                        <option value="<?= $k ?>" <?= $Settings->get("data.currency") == $k ? "selected" : "" ?>><?= $v ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="mb-40">
                                <label class="form-label">
                                    <?= __("Geonames Username") ?>
                                    <a href="http://geonames.org" target=_blank><span class="mdi mdi-link"></span></a>
                                </label>
                                
                                <input class="input"
                                       name="geonames-username" 
                                       type="text" 
                                       value="<?= htmlchars($Settings->get("data.geonamesorg_username")) ?>" 
                                       placeholder="<?= __("Enter your geonames.org username") ?>">

                                <ul class="field-tips">
                                    <li><?= __("Required for getting correct data for user's location to use in proxy and timezones detection") ?></li>
                                </ul>
                            </div>

                            <div class="mb-40">
                                <label>
                                    <input type="checkbox" 
                                           class="checkbox" 
                                           name="email-verification" 
                                           value="1" 
                                           <?= $EmailSettings->get("data.email_verification") ? "checked" : "" ?>>
                                    <span>
                                        <span class="icon unchecked">
                                            <span class="mdi mdi-check"></span>
                                        </span>
                                        <?= __('Require Email Verification') ?>
                                    </span>
                                </label>

                                <ul class="field-tips">
                                    <li><?= __("Enabling this settings will not affect current users until they update their email address.") ?></li>
                                    <li><?= __("Users with unverified email address will not be able to use the app until they verify the email address.") ?></li>
                                    <li><?= __("This setting will not affect admin accounts.") ?></li>
                                    <li><?= __("Enabling SMTP is highly recommended.") ?></li>
                                </ul>
                            </div>

                            <input class="fluid button" type="submit" value="<?= __("Save") ?>">
                        </div>
                    </div>
                </div>
            </form>