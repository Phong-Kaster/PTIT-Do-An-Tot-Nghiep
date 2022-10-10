            <form class="js-ajax-form" 
                  action="<?= APPURL . "/settings/" . $page ?>"
                  method="POST"
                  id="stripe-form">
                <input type="hidden" name="action" value="save">

                <div class="section-header clearfix">
                    <h2 class="section-title"><?= __("Stripe Integration") ?></h2>
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
                                    <option value="live" <?= $Integrations->get("data.stripe.environment") == "live" ? "selected" : "" ?>><?= __("Live") ?></option>
                                </select>
                            </div>

                            <div class="mb-20">
                                <label class="form-label">
                                    <?= __("Publishable Key") ?>
                                    <span class="compulsory-field-indicator">*</span>
                                </label>

                                <input class="input"
                                       name="publishable-key" 
                                       type="text"
                                       value="<?= htmlchars($Integrations->get("data.stripe.publishable_key")) ?>" 
                                       maxlength="100">
                            </div>

                            <div class="mb-40">
                                <label class="form-label">
                                    <?= __("Secret Key") ?>
                                    <span class="compulsory-field-indicator">*</span>
                                </label>

                                <input class="input"
                                       name="secret-key" 
                                       type="text"
                                       value="<?= htmlchars($Integrations->get("data.stripe.secret_key")) ?>" 
                                       maxlength="100">
                            </div>

                            <div class="mb-40">
                                <label>
                                    <input type="checkbox" 
                                           class="checkbox" 
                                           name="recurring" 
                                           value="1" 
                                           <?= $Integrations->get("data.stripe.recurring") ? "checked" : "" ?>>
                                    <span>
                                        <span class="icon unchecked">
                                            <span class="mdi mdi-check"></span>
                                        </span>
                                        <?= __('Enable Recurring Payments') ?>
                                      
                                        <ul class="field-tips">
                                            <li><?= __("User's will have an option to make the recurring payments") ?></li>
                                        </ul>
                                    </span>
                                </label>
                            </div>

                            <div class="mb-40">
                                <label class="form-label">
                                    <?= __("Webhook Signing Secret Key") ?>
                                    <span class="compulsory-field-indicator">*</span>
                                </label>

                                <input class="input"
                                       name="webhook-key" 
                                       type="text"
                                       value="<?= htmlchars($Integrations->get("data.stripe.webhook_key")) ?>" 
                                       maxlength="100">
                            </div>
                        </div>

                        <div class="col s12 m6 m-last l5 offset-l1 mb-40 js-notes">
                            <label class="form-label mb-10"><?= __("Notes") ?></label>

                            <p>
                                <?= __("If you're going to enable the recurring payments, you should follow these steps:") ?>
                            </p>

                            <ul class="field-tips">
                                <li class="mb-15">
                                    <?= __("Go to webhook settings at Stripe account dashboard") ?>:<br>
                                    <a href="https://dashboard.stripe.com/account/webhooks" target="_blank">https://dashboard.stripe.com/account/webhooks</a>
                                </li>

                                <li class="mb-15">
                                    <?= __('Click the "+Add endpoint" button at "Endpoints receiving events from your account" section') ?>
                                </li>

                                <li class="mb-15">
                                    <?= __('Include the following address to the "URL to be called" section') ?>: <br>
                                    <a href="<?= APPURL."/webhooks/payments/stripe" ?>" target="_blank"><?= APPURL."/webhooks/payments/stripe" ?></a>
                                </li>

                                <li>
                                    <?= __('Select "Send all event types" as a value of "Filter event"') ?>
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