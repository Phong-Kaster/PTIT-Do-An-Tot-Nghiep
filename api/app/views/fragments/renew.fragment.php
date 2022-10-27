        <?php 
            $currency = $Settings->get("data.currency");
            $iszdc = isZeroDecimalCurrency($currency);
        ?>

        <div id="renew">
            <?php if ($SelectedPackage->isAvailable()): ?>
                <div class="subscriptions">
                    <div class="inner">
                        <div class="header">
                            <h1 class="page-primary-title"><?= htmlchars($SelectedPackage->get("title")) ?></h1>
                            <p>
                                <?php 
                                    if ($SelectedPackage->get("id") == $ActivePackage->get("id")) {
                                        echo __('You\'ve already subscribed to %s. <br> Please select package options and payment gateway to renew your account!', htmlchars($SelectedPackage->get("title")));
                                    } else {
                                        echo __('Great! You\'ve selected %s. <br> Now please select package options and payment gateway to renew your account!', htmlchars($SelectedPackage->get("title")));
                                    }
                                ?>
                            </p>
                        </div>

                        <form class="payment-form" 
                              action="javascript:void(0)" 
                              data-url="<?= APPURL."/renew?package=".$SelectedPackage->get("id") ?>"
                              data-stripe-key="<?= htmlchars($Integrations->get("data.stripe.publishable_key")) ?>"
                              data-stripe-img="<?= APPURL."/assets/img/shield.png" ?>"
                              data-stripe-panel-label = "<?= __("Pay {{amount}}") ?>"
                              data-email="<?= $AuthUser->get("email") ?>"
                              data-site="<?= htmlchars($Settings->get("data.site_name")) ?>"
                              data-currency="<?= $Settings->get("data.currency") ?>">
                            <div class="container-1100">
                                <div class="row clearfix">
                                    <div class="options">
                                        <div class="option-group">
                                            <label class="group-label">
                                                <span class="sli sli-social-dropbox icon"></span>
                                                <?= __("Please select your plan") ?>
                                            </label>

                                            <div class="clearfix">
                                                <div class="option-group-item">
                                                    <label>
                                                        <input class="custom-radio" name="plan" 
                                                               data-amount="<?= $iszdc ? round($SelectedPackage->get("monthly_price")) : round($SelectedPackage->get("monthly_price"), 2) * 100 ?>" 
                                                               data-desc="<?= htmlchars($SelectedPackage->get("title"))." - ".__("Monthly Plan") ?>"
                                                               type="radio" value="monthly" checked>
                                                        <div>
                                                            <div>
                                                                <div class="price">
                                                                    <span class="number"><?= format_price($SelectedPackage->get("monthly_price"), $iszdc) ?></span>
                                                                    <?= $Settings->get("data.currency") ?>
                                                                </div>

                                                                <div class="title"><?= __("Monthly plan") ?></div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>

                                                <div class="option-group-item">
                                                    <label>
                                                        <?php 
                                                            $annual_price = $SelectedPackage->get("annual_price");
                                                            if ($annual_price <= 0) {
                                                                $annual_price = 12 * $SelectedPackage->get("monthly_price");
                                                            }
                                                        ?>
                                                        <input class="custom-radio" name="plan" 
                                                               data-amount="<?= $iszdc ? round($annual_price) : round($annual_price, 2) * 100 ?>" 
                                                               data-desc="<?= htmlchars($SelectedPackage->get("title"))." - ".__("Annual Plan") ?>"
                                                               type="radio" value="annual">
                                                        <div>
                                                            <div>
                                                                <div class="price">
                                                                    <span class="number"><?= format_price($annual_price, $iszdc) ?></span>
                                                                    <?= $Settings->get("data.currency") ?>
                                                                </div>

                                                                <div class="title"><?= __("Annual plan") ?></div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="option-group none payment-gateways" style="margin-bottom:45px">
                                            <label class="group-label">
                                                <span class="sli sli-credit-card icon"></span>
                                                <?= __("Choose a payment method") ?>
                                            </label>

                                            <div class="clearfix">
                                                <?php if ($Integrations->get("data.stripe.publishable_key") && $Integrations->get("data.stripe.secret_key")): ?>
                                                    <div class="option-group-item">
                                                        <label>
                                                            <input class="custom-radio" name="payment-gateway" type="radio" value="stripe" data-recurring="<?= $Integrations->get("data.stripe.recurring") ? "true" : "false" ?>">
                                                            <div>
                                                                <div class="text-c">
                                                                    <img src="<?= APPURL."/assets/img/cc/visa.svg" ?>" alt="Visa">
                                                                    <img src="<?= APPURL."/assets/img/cc/mastercard.svg" ?>" alt="MasterCard">
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                <?php endif ?>

                                                <?php if ($Integrations->get("data.paypal.client_id") && $Integrations->get("data.paypal.client_secret")): ?>
                                                    <div class="option-group-item">
                                                        <label>
                                                            <input class="custom-radio" name="payment-gateway" type="radio" value="paypal" data-recurring="<?= $Integrations->get("data.paypal.recurring") ? "true" : "false" ?>">
                                                            <div>
                                                                <div class="text-c">
                                                                    <img src="<?= APPURL."/assets/img/cc/paypal.svg" ?>" alt="Visa">
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </div>
                                                <?php endif ?>

                                                <?php Event::trigger("renew.add_custom_payment_gateways"); ?>
                                            </div>
                                        </div>

                                        <div class="option-group none payment-cycle">
                                            <label class="group-label">
                                                <span class="sli sli-reload icon"></span>
                                                <?= __("Payment Cycle") ?>
                                            </label>

                                            <div class="clearfix">
                                                <div class="option-group-item">
                                                    <label>
                                                        <input class="custom-radio" name="payment-cycle" type="radio" value="onetime" checked>
                                                        
                                                        <div>
                                                            <div>
                                                                <div class="price fz-26"><?= __("One time") ?></div>
                                                                <div class="fz-12 mt-5"><?= __("This is one time payment") ?><br><br></div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>

                                                <div class="option-group-item">
                                                    <label>
                                                        <input class="custom-radio" name="payment-cycle" type="radio" value="recurring">
                                                        
                                                        <div>
                                                            <div>
                                                                <div class="price fz-26"><?= __("Recurring Payment") ?></div>
                                                                <div class="fz-12 mt-5"><?= __("You'll be charged at the end of each cycle automatically") ?></div>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="clearfix">
                                            <div class="col s12 m6 l4">
                                                <input class="fluid button button--dark button--oval" type="submit" value="<?= __("Place Order") ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="container-1100">
                    <div class="row">
                        <div class="header">
                            <h1 class="page-primary-title"><?= __('Renew Account') ?></h1>
                            <p><?= __('Please select one of the packages listed below to subscribe!') ?></p>
                        </div>

                        <div class="package-list clearfix">
                            <?php 
                                $show_modules = false;

                                $available_modules = [];
                                foreach ($Plugins->getDataAs("Plugin") as $p) {
                                    $available_modules[] = $p->get("idname");
                                }

                                foreach ($Packages->getDataAs("Package") as $p) {
                                    $package_modules = $p->get("settings.modules");
                                    if ($package_modules &&
                                        is_array($package_modules) &&
                                        array_intersect($package_modules, $available_modules)) 
                                    {
                                        $show_modules = true;
                                        break;
                                    }
                                }
                            ?>

                            <?php foreach ($Packages->getDataAs("Package") as $p): ?>
                                <div class="package-list-item">
                                    <div>
                                        <div class="price">
                                            <span class="number"><?= format_price($p->get("monthly_price"), $iszdc) ?></span>
                                            <span class="per">
                                                <?= htmlchars($currency) ?>/<?= __("per month") ?>    
                                            </span>        
                                        </div>

                                        <div class="title"><?= htmlchars($p->get("title")) ?></div>

                                        <div class="annual">
                                            <?= __("Annual Price") ?>:
                                            <?php 
                                                if ($p->get("annual_price") > 0) {
                                                    $annual = $p->get("annual_price");
                                                } else {
                                                    $annual = 12 * $p->get("monthly_price");
                                                }
                                            ?>
                                            <?= format_price($annual, $iszdc) ?>
                                            <?= htmlchars($currency) ?>

                                            <div class="save">
                                                <?php 
                                                    if ($iszdc) {
                                                        $total = 12 * round($p->get("monthly_price"));
                                                        $dif = $total - round($annual);
                                                    } else {
                                                        $total = 12 * $p->get("monthly_price");
                                                        $dif = $total - $annual; 
                                                    }

                                                    if ($dif > 0) {
                                                        echo __("You save %s", "<span>" . format_price($dif, $iszdc) ." ".htmlchars($currency)."</span>");
                                                    }
                                                ?>
                                            </div>
                                        </div>

                                        <div class="features">
                                            <div class="feature">
                                                <?php 
                                                    $max = (int)$p->get("settings.max_accounts");
                                                    if ($max > 0) {
                                                        echo n__("Only 1 account", "Up to %s accounts", $max, $max);
                                                    } else if ($max == "-1") {
                                                        echo __("Unlimited accounts");
                                                    } else {
                                                        echo __("Zero accounts");
                                                    }
                                                ?>
                                            </div>

                                            <div class="feature">
                                                <div class="feature-title"><?= __("Post Types") ?>:</div>

                                                <div>
                                                    <span class="<?= $p->get("settings.post_types.timeline_photo") ? "" : "not" ?>"><?= __("Photo") ?></span>, 
                                                    <span class="<?= $p->get("settings.post_types.timeline_video") ? "" : "not" ?>"><?= __("Video") ?></span>, 

                                                    <br>
                                                    
                                                    <?php 
                                                        $story_photo = $p->get("settings.post_types.story_photo");
                                                        $story_video = $p->get("settings.post_types.story_video");
                                                    ?>
                                                    <?php if ($story_photo && $story_video): ?>
                                                        <span><?= __("Story")." (".__("Photo+Video").")" ?></span>,
                                                    <?php elseif ($story_photo): ?>
                                                        <span><?= __("Story")." (".__("Photo only").")" ?></span>,
                                                    <?php elseif ($story_video): ?>
                                                        <span><?= __("Story")." (".__("Video only").")" ?></span>,
                                                    <?php else: ?>
                                                        <span class="not"><?= __("Story")." (".__("Photo+Video").")" ?></span>,
                                                    <?php endif ?>

                                                    <br>

                                                    <?php 
                                                        $album_photo = $p->get("settings.post_types.album_photo");
                                                        $album_video = $p->get("settings.post_types.album_video");
                                                    ?>
                                                    <?php if ($album_photo && $album_video): ?>
                                                        <span><?= __("Album")." (".__("Photo+Video").")" ?></span>
                                                    <?php elseif ($album_photo): ?>
                                                        <span><?= __("Album")." (".__("Photo only").")" ?></span>
                                                    <?php elseif ($album_video): ?>
                                                        <span><?= __("Album")." (".__("Video only").")" ?></span>
                                                    <?php else: ?>
                                                        <span class="not"><?= __("Album")." (".__("Photo+Video").")" ?></span>
                                                    <?php endif ?>
                                                </div>
                                            </div>

                                            <div class="feature">
                                                <div class="feature-title"><?= __("Cloud Import") ?>:</div>

                                                <div style="height: 35px;">
                                                    <?php $none = true; ?>
                                                    <?php if ($p->get("settings.file_pickers.google_drive")): ?>
                                                        <?php $none = false; ?>
                                                        <span class="icon m-5 mdi mdi-google-drive tippy" title="Google Drive" data-size="small"></span>
                                                    <?php endif ?>

                                                    <?php if ($p->get("settings.file_pickers.dropbox")): ?>
                                                        <?php $none = false; ?>
                                                        <span class="icon m-5 mdi mdi-dropbox tippy" title="Dropbox" data-size="small"></span>
                                                    <?php endif ?>

                                                    <?php if ($p->get("settings.file_pickers.onedrive")): ?>
                                                        <?php $none = false; ?>
                                                        <span class="icon m-5 mdi mdi-onedrive tippy" title="OneDrive" data-size="small"></span>
                                                    <?php endif ?>

                                                    <?php if ($none): ?>
                                                        <span class="m-5 inline-block" style="line-height: 24px;"><?= __("Not available") ?></span>
                                                    <?php endif ?>
                                                </div>
                                            </div>

                                            <?php if ($show_modules): ?>
                                                <div class="feature">
                                                    <div class="feature-title"><?= __("Modules") ?></div>
                                                    <div class="modules clearfix">
                                                        <?php $package_modules = $p->get("settings.modules") ?>
                                                        <?php foreach ($Plugins->getDataAs("Plugin") as $m): ?>
                                                            <?php 
                                                                $idname = $m->get("idname");

                                                                $config_path = PLUGINS_PATH . "/" . $idname . "/config.php"; 
                                                                if (!file_exists($config_path)) {
                                                                    continue;
                                                                }
                                                                $config = include $config_path;
                                                                $name = empty($config["plugin_name"]) ? $idname : $config["plugin_name"];
                                                            ?>
                                                            <span class="module tooltip tippy <?= in_array($m->get("idname"), $package_modules) ? "" : "disabled" ?>"
                                                                  style="<?= empty($config["icon_style"]) ? "" : $config["icon_style"] ?>"
                                                                  title="<?= htmlchars($name) ?>"
                                                                  data-size="small">
                                                                    
                                                                <?php if (in_array($m->get("idname"), ["auto-follow", "auto-unfollow"])): ?>
                                                                    <?php 
                                                                        $name = empty($config["plugin_name"]) ? $idname : $config["plugin_name"];
                                                                        echo textInitials($name, 2);
                                                                    ?>
                                                                <?php elseif($m->get("idname") == "auto-like") : ?>
                                                                    <span class="mdi mdi-heart"></span>
                                                                <?php elseif($m->get("idname") == "auto-comment") : ?>
                                                                    <span class="mdi mdi-comment-processing"></span>
                                                                <?php elseif($m->get("idname") == "welcomedm") : ?>
                                                                    <span class="sli sli-paper-plane"></span>
                                                                <?php elseif($m->get("idname") == "auto-repost") : ?>
                                                                    <span class="sli sli-reload"></span>
                                                                <?php endif ?>
                                                            </span>
                                                        <?php endforeach ?>
                                                    </div>
                                                </div>
                                            <?php endif ?>

                                            <div class="feature">
                                                <span class="<?= $p->get("settings.spintax") ? "" : "not" ?>"><?= __("Spintax Support") ?></span>
                                            </div>

                                            <div class="feature">
                                                <?= __("Storage") ?>:
                                                <span class="color-primary fw-500">
                                                    <?= readableFileSize($p->get("settings.storage.total") * 1024 * 1024) ?>
                                                </span>
                                            </div>
                                        </div>

                                        <div class="choose">
                                            <a href="<?= APPURL."/renew?package=".$p->get("id") ?>" class="button button--dark button--oval"><?= __("Select") ?></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>