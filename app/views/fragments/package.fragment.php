        <div class="skeleton skeleton--full" id="package">
            <div class="clearfix">
                <aside class="skeleton-aside hide-on-medium-and-down">
                    <?php 
                        $form_action = APPURL."/packages";
                        include APPPATH."/views/fragments/aside-search-form.fragment.php"; 
                    ?>

                    <div class="js-loadmore-content">
                        <div class="aside-list js-loadmore-content" data-loadmore-id="1"></div>

                        <div class="loadmore pt-20 none">
                            <a class="fluid button button--light-outline js-loadmore-btn js-autoloadmore-btn" data-loadmore-id="1" href="<?= APPURL."/packages?aid=".$Package->get("id") ?>">
                                <span class="icon sli sli-refresh"></span>
                                <?= __("Load More") ?>
                            </a>
                        </div>
                    </div>
                </aside>

                <section class="skeleton-content">
                    <form class="js-ajax-form"
                          action="<?= APPURL."/packages/".($Package->isAvailable() ? $Package->get("id") : "new") ?>"
                          method="POST">

                        <input type="hidden" name="action" value="save">

                        <div class="section-header clearfix">
                            <h2 class="section-title"><?= $Package->isAvailable() ? htmlchars($Package->get("title")) : __("New Package") ?></h2>
                        </div>

                        <div class="section-content">
                            <div class="form-result"></div>

                            <div class="clearfix">
                                <div class="col s12 m12 l5">
                                    <div class="mb-20">
                                        <label class="form-label">
                                            <?= __("Package Name") ?>
                                            <span class="compulsory-field-indicator">*</span>
                                        </label>

                                        <input class="input js-required"
                                               name="title" 
                                               type="text"
                                               value="<?= htmlchars($Package->get("title")) ?>" 
                                               maxlength="50">
                                    </div>

                                    <div class="clearfix">
                                        <div class="col s6 m6 l6 mb-20">
                                            <label class="form-label">
                                                <?= __('Monthly Price') . " (" . $Settings->get("data.currency") . ")" ?>
                                            </label>

                                            <input class="input"
                                                   name="monthly-price" 
                                                   type="text"
                                                   value="<?= isZeroDecimalCurrency($Settings->get("data.currency")) ? round($Package->get("monthly_price")) : $Package->get("monthly_price") ?>" 
                                                   maxlength="10">
                                        </div>

                                        <div class="col s6 s-last m6 m-last l6 l-last">
                                            <label class="form-label">
                                                <?= __('Annual Price') . " (" . $Settings->get("data.currency") . ")" ?>
                                            </label>

                                            <input class="input"
                                                   name="annual-price" 
                                                   type="text"
                                                   value="<?= isZeroDecimalCurrency($Settings->get("data.currency")) ? round($Package->get("annual_price")) : $Package->get("annual_price") ?>" 
                                                   maxlength="10">
                                        </div>
                                    </div>

                                    <div class="mb-20">
                                        <div class="clearfix">
                                            <div class="col s6 m6 l6">
                                                <label class="form-label"><?= __("Max. storage size (MB)") ?></label>

                                                <input class="input"
                                                       name="storage-total" 
                                                       type="text"
                                                       value="<?= htmlchars($Package->get("settings.storage.total")) ?>" 
                                                       maxlength="10">
                                            </div>

                                            <div class="col s6 s-last m6 m-last l6 l-last">
                                                <label class="form-label"><?= __("Max. file size (MB)") ?></label>

                                                <input class="input"
                                                       name="storage-file" 
                                                       type="text"
                                                       value="<?= htmlchars($Package->get("settings.storage.file")) ?>" 
                                                       maxlength="10">
                                            </div>
                                        </div>
                                        
                                        <ul class="field-tips">
                                            <li><?= __("Include -1 for unlimted") ?></li>
                                        </ul>
                                    </div>

                                    <div class="mb-20">
                                        <label class="form-label"><?= __("Number of accounts") ?></label>

                                        <input class="input"
                                               name="accounts" 
                                               type="text"
                                               value="<?= htmlchars($Package->get("settings.max_accounts")) ?>" 
                                               maxlength="10">

                                        <ul class="field-tips">
                                            <li><?= __("Include -1 for unlimited") ?></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="col s12 m12 l5 l-last offset-l1 mb-40">
                                    <div class="mb-30">
                                        <label class="form-label form-label--secondary"><?= __("File Pickers") ?></label>
                                        <div class="clearfix mt-15">
                                            <div class="col s6 m6 l6 mb-10">
                                                <label>
                                                    <input type="checkbox" 
                                                           class="checkbox" 
                                                           name="dropbox" 
                                                           value="1" 
                                                           <?= $Package->get("settings.file_pickers.dropbox") ? "checked" : "" ?>>
                                                    <span>
                                                        <span class="icon unchecked">
                                                            <span class="mdi mdi-check"></span>
                                                        </span>
                                                        <?= __('Dropbox') ?>
                                                    </span>
                                                </label>
                                            </div>

                                            <div class="col s6 s-last m6 m-last l6 l-last mb-10">
                                                <label>
                                                    <input type="checkbox" 
                                                           class="checkbox" 
                                                           name="onedrive" 
                                                           value="1" 
                                                           <?= $Package->get("settings.file_pickers.onedrive") ? "checked" : "" ?>>
                                                    <span>
                                                        <span class="icon unchecked">
                                                            <span class="mdi mdi-check"></span>
                                                        </span>
                                                        <?= __('OneDrive') ?>
                                                    </span>
                                                </label>
                                            </div>

                                            <div class="col s6 m6 l6 mb-10">
                                                <label>
                                                    <input type="checkbox" 
                                                           class="checkbox" 
                                                           name="google-drive" 
                                                           value="1" 
                                                           <?= $Package->get("settings.file_pickers.google_drive") ? "checked" : "" ?>>
                                                    <span>
                                                        <span class="icon unchecked">
                                                            <span class="mdi mdi-check"></span>
                                                        </span>
                                                        <?= __('Google Drive') ?>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-30">
                                        <label class="form-label form-label--secondary"><?= __("Post Types") ?></label>
                                        <div class="clearfix mt-15">
                                            <div class="col s6 m6 l6 mb-10">
                                                <label>
                                                    <input type="checkbox" 
                                                           class="checkbox" 
                                                           name="timeline-photo" 
                                                           value="1" 
                                                           <?= $Package->get("settings.post_types.timeline_photo") ? "checked" : "" ?>>
                                                    <span>
                                                        <span class="icon unchecked">
                                                            <span class="mdi mdi-check"></span>
                                                        </span>
                                                        <?= __('Photo Post') ?>
                                                    </span>
                                                </label>
                                            </div>

                                            <div class="col s6 s-last m6 m-last l6 l-last mb-10">
                                                <label>
                                                    <input type="checkbox" 
                                                           class="checkbox" 
                                                           name="timeline-video" 
                                                           value="1" 
                                                           <?= $Package->get("settings.post_types.timeline_video") ? "checked" : "" ?>>
                                                    <span>
                                                        <span class="icon unchecked">
                                                            <span class="mdi mdi-check"></span>
                                                        </span>
                                                        <?= __('Video Post') ?>
                                                    </span>
                                                </label>
                                            </div>

                                            <div class="col s6 m6 l6 mb-10">
                                                <label>
                                                    <input type="checkbox" 
                                                           class="checkbox" 
                                                           name="story-photo" 
                                                           value="1" 
                                                           <?= $Package->get("settings.post_types.story_photo") ? "checked" : "" ?>>
                                                    <span>
                                                        <span class="icon unchecked">
                                                            <span class="mdi mdi-check"></span>
                                                        </span>
                                                        <?= __('Story Photo') ?>
                                                    </span>
                                                </label>
                                            </div>

                                            <div class="col s6 s-last m6 m-last l6 l-last mb-10">
                                                <label>
                                                    <input type="checkbox" 
                                                           class="checkbox" 
                                                           name="story-video" 
                                                           value="1" 
                                                           <?= $Package->get("settings.post_types.story_video") ? "checked" : "" ?>>
                                                    <span>
                                                        <span class="icon unchecked">
                                                            <span class="mdi mdi-check"></span>
                                                        </span>
                                                        <?= __('Story Video') ?>
                                                    </span>
                                                </label>
                                            </div>

                                            <div class="col s6 m6 l6 mb-10">
                                                <label>
                                                    <input type="checkbox" 
                                                           class="checkbox" 
                                                           name="album-photo" 
                                                           value="1" 
                                                           <?= $Package->get("settings.post_types.album_photo") ? "checked" : "" ?>>
                                                    <span>
                                                        <span class="icon unchecked">
                                                            <span class="mdi mdi-check"></span>
                                                        </span>
                                                        <?= __('Album') ?>
                                                    </span>
                                                </label>
                                            </div>

                                            <div class="col s6 s-last m6 m-last l6 l-last mb-10">
                                                <label>
                                                    <input type="checkbox" 
                                                           class="checkbox" 
                                                           name="album-video" 
                                                           value="1" 
                                                           <?= $Package->get("settings.post_types.album_video") ? "checked" : "" ?>>
                                                    <span>
                                                        <span class="icon unchecked">
                                                            <span class="mdi mdi-check"></span>
                                                        </span>
                                                        <?= __('Album (video)') ?>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-30">
                                        <label>
                                            <input type="checkbox" 
                                                   class="checkbox" 
                                                   name="spintax" 
                                                   value="1" 
                                                   <?= $Package->get("settings.spintax") ? "checked" : "" ?>>
                                            <span>
                                                <span class="icon unchecked">
                                                    <span class="mdi mdi-check"></span>
                                                </span>
                                                <?= __('Spintax') ?>
                                            </span>
                                        </label>

                                        <ul class="field-tips">
                                            <li><?= __("Subscribers with this option enabled, can use spintax syntax in post captions.") ?></li>
                                        </ul>
                                    </div>

                                    <div class="mb-10">
                                        <label>
                                            <input type="checkbox" 
                                                   class="checkbox" 
                                                   name="invisible" 
                                                   value="1" 
                                                   <?= $Package->get("is_public") ? "" : "checked" ?>>
                                            <span>
                                                <span class="icon unchecked">
                                                    <span class="mdi mdi-check"></span>
                                                </span>
                                                <?= __('Invisible Package') ?>
                                            </span>
                                        </label>

                                        <ul class="field-tips">
                                            <li><?= __("Will be visible only by admins inside the app") ?></li>
                                        </ul>
                                    </div>

                                    <div>
                                        <?php 
                                            $package_modules = $Package->get("settings.modules");
                                            if (empty($package_modules)) {
                                                $package_modules = [];
                                            }
                                            \Event::trigger("package.add_module_option", $package_modules) 
                                        ?>
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix">
                                <div class="col s12 m6 l5 mb-10">
                                    <input class="fluid button" type="submit" value="<?= __("Save") ?>">
                                </div>

                                <?php if ($Package->isAvailable()): ?>
                                    <div class="col s12 m6 m-last l5 l-last offset-l1">
                                        <input type="hidden" name="update-subscribers" value="0">
                                        <a class="fluid button button--light-outline js-save-and-update" href="javascript:void(0)"><?= __("Save and update subscribers") ?></a>
                                    </div>
                                <?php endif ?>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>