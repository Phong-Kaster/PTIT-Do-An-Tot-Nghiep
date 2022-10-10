            <form class="js-ajax-form" 
                  action="<?= APPURL . "/settings/" . $page ?>"
                  method="POST">
                <input type="hidden" name="action" value="save">

                <div class="section-header clearfix">
                    <h2 class="section-title"><?= __("Email Notifications") ?></h2>
                    <div class="section-actions clearfix hide-on-large-only">
                        <a class="mdi mdi-menu-down icon js-settings-menu" href="javascript:void(0)"></a>
                    </div>
                </div>

                <div class="section-content">

                    <div class="clearfix">
                        <div class="col s12 m6 l5">
                            <div class="form-result"></div>

                            <div class="mb-40">
                                <label class="form-label"><?= __("Email Addresses") ?></label>

                                <textarea class="input" 
                                          name="emails"
                                          rows="3"
                                          placeholder="email1@example.com, email2@example.com"><?= htmlchars($EmailSettings->get("data.notifications.emails")) ?></textarea>

                                <ul class="field-tips">
                                    <li><?= __("Notification emails will be sent to these email addresses. Separate emails with comma sign.") ?></li>
                                </ul>
                            </div>

                            <div class="mb-20">
                                <label>
                                    <input type="checkbox" 
                                           class="checkbox" 
                                           name="new-user" 
                                           value="1" 
                                           <?= $EmailSettings->get("data.notifications.new_user") ? "checked" : "" ?>>
                                    <span>
                                        <span class="icon unchecked">
                                            <span class="mdi mdi-check"></span>
                                        </span>
                                        <?= __('New User') ?>
                                        
                                        <ul class="field-tips">
                                            <li><?= __("Receive notification when a new user registers to the site") ?></li>
                                        </ul>
                                    </span>
                                </label>
                            </div>

                            <div class="mb-20">
                                <label>
                                    <input type="checkbox" 
                                           class="checkbox" 
                                           name="new-payment" 
                                           value="1" 
                                           <?= $EmailSettings->get("data.notifications.new_payment") ? "checked" : "" ?>>
                                    <span>
                                        <span class="icon unchecked">
                                            <span class="mdi mdi-check"></span>
                                        </span>
                                        <?= __('Account Renew (New payment)') ?>
                                        
                                        <ul class="field-tips">
                                            <li><?= __("Receive notification when new payment received successfully to renew the user account.") ?></li>
                                        </ul>
                                    </span>
                                </label>
                            </div>

                            <input class="fluid button" type="submit" value="<?= __("Save") ?>">
                        </div>
                    </div>
                </div>
            </form>