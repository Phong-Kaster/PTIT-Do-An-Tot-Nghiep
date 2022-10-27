        <div class='skeleton' id="account">
            <form class="js-ajax-form" 
                  action="<?= APPURL . "/accounts/challenge/" . $Challenge->get("id") . "." . $Challenge->get("choice") . "." . md5($Challenge->get("id").NP_SALT) ?>"
                  method="POST">
                <input type="hidden" name="action" value="approve">

                <div class="container-1200">
                    <div class="row clearfix">
                        <?php if (empty($error)): ?>
                            <div class="col s12 m8 l4">
                                <section class="section">
                                    <div class="section-content">
                                        <div class="form-result">
                                        </div>

                                        <div class="mb-20">
                                            <label class="form-label">
                                                <?= __("Security Code") ?>
                                                <span class="compulsory-field-indicator">*</span>    
                                            </label>

                                            <input class="input js-required"
                                                   name="security-code" 
                                                   type="text" 
                                                   value="" 
                                                   placeholder="<?= __("Enter Your Security Code") ?>">
                                        </div>

                                        <ul class="field-tips">
                                            <?php if ($Challenge->get("choice") === ""): ?>
                                                <li><?= __("Enter the 6-digit code sent to your email/phone") ?></li>
                                            <?php elseif ($Challenge->get("choice") == 0): ?>
                                                <li><?= __("Enter the 6-digit code sent to the number ending in %s", htmlchars($Challenge->get("contact_preview"))) ?></li>
                                            <?php else: ?>
                                                <li><?= __("Enter the 6-digit code sent to the email address %s", htmlchars($Challenge->get("contact_preview"))) ?></li>
                                            <?php endif ?>
                                        </ul>
                                    </div>

                                    <input class="fluid button button--footer" type="submit" value="<?= $Account->isAvailable() ? __("Save changes") :  __("Add account") ?>">
                                </section>
                            </div>
                        <?php else: ?>
                            <div class="minipage">
                                <div class="inner">
                                    <span class="icon">
                                        <span class="sli sli-dislike color-danger"></span>
                                    </span>
                                    <h1 class="page-primary-title"><?= __('Error!') ?></h1>
                                    <p><?= __('An error occured while sending the verification code! Please try again later!') ?></p>

                                    <div class="system-error">
                                        <?= htmlchars($error) ?>
                                    </div>

                                    <a href="<?= APPURL."/accounts" ?>" class="small button"><?= __('Try Again') ?></a>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </form>
        </div>
        