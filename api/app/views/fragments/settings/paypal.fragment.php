            <form class="js-ajax-form" 
                  action="<?= APPURL . "/settings/" . $page ?>"
                  method="POST">
                <input type="hidden" name="action" value="save">

                <div class="section-header clearfix">
                    <h2 class="section-title"><?= __("PayPal Integration") ?></h2>
                    <div class="section-actions clearfix hide-on-large-only">
                        <a class="mdi mdi-menu-down icon js-settings-menu" href="javascript:void(0)"></a>
                    </div>
                </div>

                <div class="section-content">
                    <div class="clearfix">
                        <div class="col s12 m6 l5">
                            <div class="form-result"></div>

                            <div class="mb-20">
                                <label class="form-label"><?= __("Environment") ?></label>
                                <select name="environment" class="input">
                                    <option value="sandbox"><?= __("Sandbox (Test mode)") ?></option>
                                    <option value="live" <?= $Integrations->get("data.paypal.environment") == "live" ? "selected" : "" ?>><?= __("Live") ?></option>
                                </select>
                            </div>

                            <div class="mb-20">
                                <label class="form-label">
                                    <?= __("PayPal Client ID") ?>
                                    <span class="compulsory-field-indicator">*</span>
                                </label>

                                <input class="input"
                                       name="client-id" 
                                       type="text"
                                       value="<?= htmlchars($Integrations->get("data.paypal.client_id")) ?>" 
                                       maxlength="100">
                            </div>

                            <div class="mb-40">
                                <label class="form-label">
                                    <?= __("PayPal Client Secret") ?>
                                    <span class="compulsory-field-indicator">*</span>
                                </label>

                                <input class="input"
                                       name="client-secret" 
                                       type="text"
                                       value="<?= htmlchars($Integrations->get("data.paypal.client_secret")) ?>" 
                                       maxlength="100">
                            </div>

                            <input class="fluid button" type="submit" value="<?= __("Save") ?>">
                        </div>
                    </div>
                </div>
            </form>