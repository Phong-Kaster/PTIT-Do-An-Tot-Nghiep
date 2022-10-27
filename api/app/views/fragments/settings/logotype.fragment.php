            <form class="js-ajax-form" 
                  action="<?= APPURL . "/settings/" . $page ?>"
                  method="POST">
                <input type="hidden" name="action" value="save">

                <div class="section-header clearfix">
                    <h2 class="section-title"><?= __("Logotype") ?></h2>
                    <div class="section-actions clearfix hide-on-large-only">
                        <a class="mdi mdi-menu-down icon js-settings-menu" href="javascript:void(0)"></a>
                    </div>
                </div>

                <div class="section-content">
                    <div class="clearfix">
                        <div class="col s12 m6 l5">
                            <div class="form-result"></div>

                            <div class="mb-20">
                                <label class="form-label"><?= __("Logo") ?></label>

                                <div class="pos-r">
                                    <input class="input rightpad"
                                           name="logotype" 
                                           type="text" 
                                           value="<?= htmlchars($Settings->get("data.logotype")) ?>" 
                                           maxlength="100">
                                    <a class="mdi mdi-image field-icon--right js-fm-filepicker"
                                       data-fm-acceptor=":input[name='logotype']"
                                       href="javascript:void(0)"></a>
                                </div>
                            </div>

                            <div class="mb-40">
                                <label class="form-label"><?= __("Logomark") ?></label>

                                <div class="pos-r">
                                    <input class="input rightpad"
                                           name="logomark" 
                                           type="text" 
                                           value="<?= htmlchars($Settings->get("data.logomark")) ?>" 
                                           maxlength="100">
                                    <a class="mdi mdi-image field-icon--right js-fm-filepicker"
                                       data-fm-acceptor=":input[name='logomark']"
                                       href="javascript:void(0)"></a>
                                </div>

                                <ul class="field-tips">
                                    <li><?= __("Will be used as minimized menu and favicon") ?></li>
                                    <li><?= __("128px x 128px PNG image is recomended") ?></li>
                                </ul>
                            </div>

                            <input class="fluid button" type="submit" value="<?= __("Save") ?>">
                        </div>
                    </div>
                </div>
            </form>


            <?php require_once(APPPATH.'/views/fragments/filepicker.fragment.php');  ?>