            <form class="js-ajax-form" 
                  action="<?= APPURL . "/settings/" . $page ?>"
                  method="POST">
                <input type="hidden" name="action" value="save">

                <div class="section-header clearfix">
                    <h2 class="section-title"><?= __("Site Settings") ?></h2>
                    <div class="section-actions clearfix hide-on-large-only">
                        <a class="mdi mdi-menu-down icon js-settings-menu" href="javascript:void(0)"></a>
                    </div>
                </div>

                <div class="section-content">
                    <div class="form-result"></div>

                    <div class="clearfix">
                        <div class="col s12 m6 l5">
                            <div class="mb-20">
                                <label class="form-label"><?= __("Site name") ?></label>

                                <input class="input"
                                       name="name" 
                                       type="text" 
                                       value="<?= htmlchars($Settings->get("data.site_name")) ?>" 
                                       placeholder="<?= __("Enter site name here") ?>"
                                       maxlength="100">
                            </div>

                            <div class="mb-20">
                                <label class="form-label"><?= __("Site description") ?></label>

                                <textarea class="input" 
                                          name="description"
                                          maxlength="255"
                                          rows="3"><?= htmlchars($Settings->get("data.site_description")) ?></textarea>

                                <ul class="field-tips">
                                    <li><?= __("Recommended length of the description is 150-160 characters") ?></li>
                                </ul>
                            </div>

                            <div class="mb-40">
                                <label class="form-label"><?= __("Keywords") ?></label>

                                <textarea class="input" 
                                          name="keywords"
                                          maxlength="500"
                                          rows="3"><?= htmlchars($Settings->get("data.site_keywords")) ?></textarea>
                            </div>
                        </div>

                        <div class="col s12 m6 l5">
                            <div class="mb-20">
                                <label class="form-label"><?= __("Active Theme") ?></label>

                                <select class="input" name="active-theme">
                                    <?php $scan = array_diff(scandir(THEMES_PATH), array('.', '..')); ?>
                                    <?php foreach ($scan as $value): ?>
                                        <?php if (is_dir(THEMES_PATH . "/" . $value)): ?>
                                            <option value="<?= $value ?>" <?= get_option("np_active_theme_idname") == $value ? "selected" : "" ?>><?= ucfirst($value) ?></option>
                                        <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix">
                        <div class="col s12 m6 l5">
                            <input class="fluid button" type="submit" value="<?= __("Save") ?>">
                        </div>
                    </div>
                </div>
            </form>