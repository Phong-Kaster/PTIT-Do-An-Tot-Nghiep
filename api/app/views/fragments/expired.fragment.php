        <div id="expired" class="minipage">
            <div class="inner" style="max-width: 550px">
                <span class="sli sli-reload icon"></span>

                <?php if ($AuthUser->get("expire_date") <= $AuthUser->get("date")): ?>
                    <h2 class="page-primary-title"><?= __('Get Started') ?></h2>
                    <p><?= __('Please click "View Plans" button to view pricing packages. After choosing appropriate package you can start to use the app.') ?></p>

                    <a class="small button" href="<?= APPURL."/renew" ?>"><?= __('View Plans') ?></a>
                <?php else: ?>
                    <h2 class="page-primary-title"><?= __('Account Expired') ?></h2>
                    <?php if ($recurring_payments): ?>
                        <p class="mb-10"><?= __("You've been subscribed to the automatic payment model.") ?></p>
                        <p class="small-text"><?= __("As soon as we charge the payment from your credit card to renew the account, your account will be activated automatially.") ?></p>

                        <p>
                            <span class="color-dark fz-16"><?= __("Upcoming Invoice") ?>:</span><br>
                            <?php 
                                $amount = $recurring_subscription->plan->amount / 100;
                                $currency = strtoupper($recurring_subscription->plan->currency);
                                $invoice_date = new \Moment\Moment(date("Y-m-d H:i:s", $recurring_subscription->current_period_end), 
                                                           date_default_timezone_get());
                                $invoice_date->setTimezone($AuthUser->get("preferences.timezone"));

                                $date = $invoice_date->format($AuthUser->get("preferences.dateformat"));
                                $time = $invoice_date->format($AuthUser->get("preferences.timeformat") == "12" ? "h:iA" : "H:i");

                                echo __("%s on %s at %s", $amount.$currency, $date, $time);
                            ?>
                        </p>
                        <a class="small button js-cancel-recurring-payments" href="javascript:void(0)" data-url="<?= APPURL."/profile" ?>"><?= __("Cancel Automatic Payments") ?></a>
                    <?php else: ?>
                        <p><?= __('Your account has been expired! <br> Please renew your account to use the app.') ?></p>
                        <a class="small button" href="<?= APPURL."/renew" ?>"><?= __('Renew Account') ?></a>
                    <?php endif ?>
                <?php endif ?>

                <a class="small button button--simple" href="<?= APPURL."/logout" ?>">
                    <span class="mdi mdi-logout"></span>
                    <?= __('Logout') ?>    
                </a>
            </div>
        </div>