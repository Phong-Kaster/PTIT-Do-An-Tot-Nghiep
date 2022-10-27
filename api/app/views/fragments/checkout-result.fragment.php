        <div id="checkout-result" class="minipage">
            <?php if (empty($Success)): ?>
                <div class="inner">
                    <span class="icon">
                        <span class="sli sli-dislike color-danger"></span>
                    </span>
                    <h1 class="page-primary-title"><?= __('Payment Error!') ?></h1>
                    <p><?= __('An error occured during the payment process! Please try again later!') ?></p>

                    <?php if (!empty($ErrMsg)): ?>
                        <div class="system-error">
                            <?= htmlchars($ErrMsg) ?>
                        </div>
                    <?php endif ?>

                    <a href="<?= APPURL."/renew" ?>" class="small button"><?= __('Try Again') ?></a>
                    <a href="<?= APPURL ?>" class="small button button--simple"><?= __('Go to Home') ?></a>
                </div>
            <?php else: ?>
                <div class="inner">
                    <span class="icon">
                        <span class="sli sli-like color-success"></span>
                    </span>
                    <h1 class="page-primary-title"><?= __('Success!') ?></h1>
                    <?php if ($Order->get("status") == "subscribed"): ?>
                        <p>
                            <?= __("You've successfully activated the Automatic Payment Model for your account.") ?>
                        </p>
                        <p class="small-text"><?= __("We'll renew your account as soon as payment processing finished successfully.") ?></p>
                    <?php else: ?>
                        <p>
                            <?php 
                                $expire_date = new \Moment\Moment($AuthUser->get("expire_date"), date_default_timezone_get());
                                $expire_date->setTimezone($AuthUser->get("preferences.timezone"));

                                $format = $AuthUser->get("preferences.dateformat");
                            ?>

                            <?= 
                                __('Payment accepted successfully! Your account will be active until %s', 
                                   $expire_date->format($format));
                            ?>           
                       </p>
                    <?php endif ?>

                    <a href="<?= APPURL."/post" ?>" class="small button"><?= __("Add post") ?></a>
                    <a href="<?= APPURL."/profile/" ?>" class="small button button--simple"><?= __('Go to Account') ?></a>
                </div>
            <?php endif ?>
        </div>