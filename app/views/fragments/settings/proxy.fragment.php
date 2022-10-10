            <form class="js-ajax-form" id="proxy-form" 
                  action="<?= APPURL . "/settings/" . $page ?>"
                  method="POST">
                <input type="hidden" name="action" value="save">

                <div class="section-header clearfix">
                    <h2 class="section-title"><?= __("Proxy Settings") ?></h2>
                    <div class="section-actions clearfix hide-on-large-only">
                        <a class="mdi mdi-menu-down icon js-settings-menu" href="javascript:void(0)"></a>
                    </div>
                </div>

                <div class="section-content">
                    <div class="clearfix">
                        <div class="col s12 m6 l5">
                            <div class="form-result"></div>

                            <div class="mb-40">
                                <label>
                                    <input type="checkbox" 
                                           class="checkbox" 
                                           name="enable-proxy" 
                                           value="1" 
                                           <?= $Settings->get("data.proxy") ? "checked" : "" ?>>
                                    <span>
                                        <span class="icon unchecked">
                                            <span class="mdi mdi-check"></span>
                                        </span>
                                        <?= __('Enable System Proxy') ?>
                                    </span>
                                </label>

                                <ul class="field-tips">
                                    <li><?= __("If you enable this option, system will try use most appropriate proxy from your proxy list while new acount is being added.") ?></li>
                                </ul>
                            </div>

                            <div class="mb-40">
                                <label>
                                    <input type="checkbox" 
                                           class="checkbox" 
                                           name="enable-user-proxy" 
                                           value="1" 
                                           <?= $Settings->get("data.user_proxy") ? "checked" : "" ?>>
                                    <span>
                                        <span class="icon unchecked">
                                            <span class="mdi mdi-check"></span>
                                        </span>
                                        <?= __("Users can add their own proxy address.") ?>
                                    </span>
                                </label>
                            </div>

                            <input class="fluid button" type="submit" value="<?= __("Save") ?>">
                        </div>
                    </div>
                </div>
            </form>