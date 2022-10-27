        <div class='skeleton' id="profile">
            <?php if ($EmailSettings->get("data.email_verification") && !$AuthUser->isAdmin() && !$AuthUser->isEmailVerified()): ?>
                <div class="container-1200">
                    <div class="row clearfix">
                        <div class="alert danger <?= \Input::get("a") ? "heartbeat" : "" ?>">
                            <div class="msg">
                                <?= __("To continue using our services, you need to confirm your email. Check the instruction sent to %s to verify your email.", "<strong>".$AuthUser->get("email")."</strong>") ?>
                                <a href="javascript:void(0)" class="js-resend-verification-email" data-url="<?= APPURL."/profile" ?>"><?= __("Resend email") ?></a>
                                <em class="js-resend-result"></em>
                            </div>

                            <img src="<?= APPURL."/assets/img/round-loading.svg" ?>" alt="loading" class="progress">
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <form class="js-ajax-form" 
                  action="<?= APPURL . "/profile" ?>"
                  method="POST">
                <input type="hidden" name="action" value="save">

                <div class="container-1200">
                    <div class="row clearfix">
                        <div class="col s12 m6 l4">
                            <section class="section">
                                <div class="section-content">
                                    <div class="form-result"></div>

                                    <div class="clearfix mb-20">
                                        <div class="col s6 m6 l6">
                                            <label class="form-label">
                                                <?= __("Firstname") ?>
                                                <span class="compulsory-field-indicator">*</span>
                                            </label>

                                            <input class="input js-required"
                                                   name="firstname" 
                                                   type="text"
                                                   value="<?= htmlchars($AuthUser->get("firstname")) ?>" 
                                                   maxlength="30">
                                        </div>

                                        <div class="col s6 s-last m6 m-last l6 l-last">
                                            <label class="form-label">
                                                <?= __("Lastname") ?>
                                                <span class="compulsory-field-indicator">*</span>
                                            </label>

                                            <input class="input js-required"
                                                   name="lastname" 
                                                   type="text"
                                                   value="<?= htmlchars($AuthUser->get("lastname")) ?>" 
                                                   maxlength="30">
                                        </div>
                                    </div>

                                    <div class="clearfix mb-20">
                                        <div class="col s6 m6 l6">
                                            <label class="form-label"><?= __("Language") ?></label>

                                            <select class="input required" name="language">
                                                <?php $l = $AuthUser->get("preferences.language"); ?>
                                                <?php foreach (Config::get("applangs") as $al): ?>
                                                    <option value="<?= $al["code"] ?>" <?= $al["code"] == $l ? "selected" : "" ?>><?= $al["name"] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col s6 s-last m6 m-last l6 l-last">
                                            <label class="form-label"><?= __("Timezone") ?></label>

                                            <select class="input required" name="timezone">
                                                <?php $t = $AuthUser->get("preferences.timezone"); ?>
                                                <?php foreach ($TimeZones as $k => $v): ?>
                                                    <option value="<?= $k ?>" <?= $k == $t ? "selected" : "" ?>><?= $v ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="clearfix mb-20">
                                        <div class="col s6 m6 l6">
                                            <label class="form-label"><?= __("Date format") ?></label>

                                            <select class="input" name="date-format">
                                                <?php $df = $AuthUser->get("preferences.dateformat") ?>

                                                <option value="Y-m-d" <?= $df == "Y-m-d" ? "selected" : "" ?>>2017-07-25</option>
                                                <option value="d-m-Y" <?= $df == "d-m-Y" ? "selected" : "" ?>>25-07-2017</option>
                                                <option value="d/m/Y" <?= $df == "d/m/Y" ? "selected" : "" ?>>25/07/2017</option>
                                                <option value="m/d/Y" <?= $df == "m/d/Y" ? "selected" : "" ?>>07/25/2017</option>
                                                <option value="d F, Y" <?= $df == "d F, Y" ? "selected" : "" ?>><?= __("01 November, 2017") ?></option>
                                                <option value="F d, Y" <?= $df == "F d, Y" ? "selected" : "" ?>><?= __("November 01, 2017") ?></option>
                                                <option value="d M, Y" <?= $df == "d M, Y" ? "selected" : "" ?>><?= __("03 Nov, 2017") ?></option>
                                                <option value="M d, Y" <?= $df == "M d, Y" ? "selected" : "" ?>><?= __("Nov 03, 2017") ?></option>
                                            </select>
                                        </div>

                                        <div class="col s6 s-last m6 m-last l6 l-last">
                                            <label class="form-label"><?= __("Time format") ?></label>

                                            <select class="input" name="time-format">
                                                <?php $tf = $AuthUser->get("preferences.timeformat") == "12" ? "12" : "24" ?>
                                                <option value="24" <?= $tf == "24" ? "selected" : "" ?>><?= __("24 hours") ?></option>
                                                <option value="12" <?= $tf == "12" ? "selected" : "" ?>><?= __("12 hours") ?></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-20">
                                        <label class="form-label">
                                            <?= __("Email") ?>
                                            <span class="compulsory-field-indicator">*</span>
                                        </label>

                                        <input class="input js-required"
                                               name="email" 
                                               type="text"
                                               value="<?= htmlchars($AuthUser->get("email")) ?>" 
                                               maxlength="80">
                                    </div>

                                    <div class="mb-20">
                                        <div class="clearfix">
                                            <div class="col s6 m6 l6">
                                                <label class="form-label"><?= __("New Password") ?></label>
                                                <input class="input" name="password" type="password" value="">
                                            </div>

                                            <div class="col s6 s-last m6 m-last l6 l-last">
                                                <label class="form-label"><?= __("Confirm Password") ?></label>
                                                <input class="input" name="password-confirm" type="password" value="">
                                            </div>
                                        </div>

                                        <ul class="field-tips">
                                            <li><?= __("If you don't want to change password then leave these password fields empty!") ?></li>
                                        </ul>
                                    </div>
                                </div>

                                <input class="fluid button button--footer" type="submit" value="<?= __("Save changes") ?>">
                            </section>
                        </div>

                        <div class="col s12 m6 m-last l8 l-last">
                            <?php   
                                $expire_date = new \Moment\Moment($AuthUser->get("expire_date"), date_default_timezone_get());
                                $expire_date->setTimezone($AuthUser->get("preferences.timezone"));
                            ?>

                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="table-big-text"><?= __("Your Package") ?></div>

                                            <div>
                                                <?php if ($Package->isAvailable()): ?>
                                                    <?= htmlchars($Package->get("title")) ?>
                                                <?php elseif ($AuthUser->get("package_id") == 0): ?>
                                                    <em><?= __("Trial Mode") ?></em>
                                                <?php else: ?>
                                                    <em><?= __("Unknown Package") ?></em>
                                                <?php endif; ?>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="table-big-text"><?= __("Expire Date") ?></div>

                                            <div>
                                                <?= $expire_date->format($AuthUser->get("preferences.dateformat")) ?>
                                                <?= $expire_date->format($AuthUser->get("preferences.timeformat") == "12" ? "h:iA" : "H:i") ?>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="table-big-text"><?= __("Payment Model") ?></div>
                                            <div>
                                                <?php
                                                    if ($recurring_payments) {
                                                        echo __("Automatic") . " (".($recurring_subscription->plan->interval == "year" ? __("Annual") : __("Monthly")).")";
                                                    } else {
                                                        echo __("Manual");
                                                    }
                                                ?>  
                                            </div>
                                        </td>

                                        <td>
                                            <?php if ($recurring_payments): ?>
                                                <?php if ($recurring_gateway == "stripe"): ?>
                                                    <div class="table-big-text"><?= __("Upcoming invoice") ?></div>
                                                    <div class="mb-20">
                                                        <?php 
                                                            $currency = strtoupper($recurring_subscription->plan->currency);
                                                            $amount = isZeroDecimalCurrency($currency) ? round($recurring_subscription->plan->amount) : $recurring_subscription->plan->amount / 100;
                                                            $invoice_date = new \Moment\Moment(date("Y-m-d H:i:s", $recurring_subscription->current_period_end), 
                                                                                       date_default_timezone_get());
                                                            $invoice_date->setTimezone($AuthUser->get("preferences.timezone"));

                                                            $date = $invoice_date->format($AuthUser->get("preferences.dateformat"));
                                                            $time = $invoice_date->format($AuthUser->get("preferences.timeformat") == "12" ? "h:iA" : "H:i");

                                                            echo __("%s on %s at %s", $amount.$currency, $date, $time);
                                                        ?>
                                                    </div>

                                                    <?= __("We'll charge your credit card automatically at the account expire date.") ?>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <?= __("After the expire date, your account will be locked until you renew your account.") ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <?php if ($recurring_payments): ?>
                                                <a class="small button js-cancel-recurring-payments" href="javascript:void(0)" data-url="<?= APPURL."/profile" ?>"><?= __("Cancel Automatic Payments") ?></a>
                                            <?php else: ?>
                                                <?php if ($Package->isAvailable()): ?>
                                                    <a class="small button" href="<?= APPURL."/renew" ?>"><?= __("Renew Account") ?></a>
                                                <?php elseif ($AuthUser->get("package_id") == 0): ?>
                                                    <a class="small button" href="<?= APPURL."/renew" ?>"><?= __("Upgrade Account") ?></a>
                                                <?php else: ?>
                                                    <a class="small button" href="<?= APPURL."/renew" ?>"><?= __("Upgrade Account") ?></a>
                                                <?php endif; ?>
                                            <?php endif ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>    
                        </div>
                    </div>
                </div>
            </form>
        </div>