            <form class="js-ajax-form" 
                  action="<?= APPURL . "/settings/" . $page ?>"
                  method="POST">
                <input type="hidden" name="action" value="save">

                <div class="section-header clearfix">
                    <h2 class="section-title"><?= __("Google Analytics") ?></h2>
                    <div class="section-actions clearfix hide-on-large-only">
                        <a class="mdi mdi-menu-down icon js-settings-menu" href="javascript:void(0)"></a>
                    </div>
                </div>

                <div class="section-content">
                    <div class="clearfix">
                        <div class="col s12 m6 l5">
                            <div class="form-result"></div>

                            <div class="mb-40">
                                <label class="form-label"><?= __("Google Analytics Property ID") ?></label>

                                <input class="input"
                                       name="property-id" 
                                       type="text"
                                       value="<?= htmlchars($Integrations->get("data.google.analytics.property_id")) ?>" 
                                       maxlength="100">

                                <ul class="field-tips">
                                    <li><?= __("Leave this field empty if you don't want to enable Google Analytics") ?></li>
                                </ul>
                            </div>

                            <input class="fluid button" type="submit" value="<?= __("Save") ?>">
                        </div>
                    </div>
                </div>
            </form>