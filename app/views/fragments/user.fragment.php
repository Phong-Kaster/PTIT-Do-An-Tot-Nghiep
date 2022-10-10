        <div class="skeleton skeleton--full" id="user">
            <div class="clearfix">
                <aside class="skeleton-aside hide-on-medium-and-down">
                    <?php 
                        $form_action = APPURL."/users";
                        include APPPATH."/views/fragments/aside-search-form.fragment.php"; 
                    ?>

                    <div class="js-search-results">
                        <div class="aside-list js-loadmore-content" data-loadmore-id="1"></div>

                        <div class="loadmore pt-20 none">
                            <a class="fluid button button--light-outline js-loadmore-btn js-autoloadmore-btn" data-loadmore-id="1" href="<?= APPURL."/users?aid=".$User->get("id")."&q=".urlencode(Input::get("q")) ?>">
                                <span class="icon sli sli-refresh"></span>
                                <?= __("Load More") ?>
                            </a>
                        </div>
                    </div>
                </aside>

                <section class="skeleton-content">
                    <form class="js-ajax-form"
                          action="<?= APPURL."/users/".($User->isAvailable() ? $User->get("id") : "new") ?>"
                          method="POST">

                        <input type="hidden" name="action" value="save">

                        <div class="section-header clearfix">
                            <h2 class="section-title"><?= $User->isAvailable() ? htmlchars($User->get("firstname") ." ". $User->get("lastname")) : __("New User") ?></h2>
                        </div>

                        <div class="section-content">
                            <div class="form-result"></div>

                            <div class="clearfix">
                                <div class="col s12 m12 l5">
                                    <div class="clearfix mb-20">
                                        <div class="col s6 m6 l6">
                                            <label class="form-label"><?= __("Account Type") ?></label>

                                            <select class="input" 
                                                    name="account-type"
                                                     <?= $User->get("id") == $AuthUser->get("id") ? "disabled" : "" ?>>
                                                <option value="member" <?= $User->get("account_type") == "member" ? "selected" : "" ?>>
                                                    <?= __("Regular User") ?>
                                                </option>

                                                <option value="admin" <?= $User->get("account_type") == "admin" ? "selected" : "" ?>>
                                                    <?= __("Admin") ?>
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col s6 s-last m6 m-last l6 l-last">
                                            <label class="form-label"><?= __("Status") ?></label>

                                            <select class="input" 
                                                    name="status" 
                                                    <?= $User->get("id") == $AuthUser->get("id") ? "disabled" : "" ?>>
                                                <?php 
                                                    if ($User->isAvailable()) {
                                                        $status = $User->get("is_active") ? 1 : 0; 
                                                    } else {
                                                        $status = 1;
                                                    }
                                                ?>
                                                <option value="1" <?= $status == 1 ? "selected" : "" ?>><?= __("Active") ?></option>
                                                <option value="0" <?= $status == 0 ? "selected" : "" ?>><?= __("Deactive") ?></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-50">
                                        <label class="form-label"><?= __("Expire date") ?></label>

                                        <?php 
                                            if ($User->isAvailable()) {
                                                $date = new DateTime($User->get("expire_date"));
                                            } else {
                                                $date = new DateTime(date("Y-m-d H:i:s", time() + 30*86400));
                                            }
                                            $date->setTimezone(new DateTimeZone($AuthUser->get("preferences.timezone")));
                                        ?>
                                        <input class="input js-datepicker"
                                               name="expire-date" 
                                               data-position="bottom right"
                                               value="<?= $date->format("Y-m-d H:i") ?>" 
                                               type="text" 
                                               maxlength="20"
                                               readonly
                                               <?= $User->get("id") == $AuthUser->get("id") ? "disabled" : "" ?>>
                                    </div>

                                    <div class="clearfix mb-20">
                                        <div class="col s6 m6 l6">
                                            <label class="form-label">
                                                <?= __("Firstname") ?>
                                                <span class="compulsory-field-indicator">*</span>
                                            </label>

                                            <input class="input js-required"
                                                   name="firstname" 
                                                   type="text"
                                                   value="<?= htmlchars($User->get("firstname")) ?>" 
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
                                                   value="<?= htmlchars($User->get("lastname")) ?>" 
                                                   maxlength="30">
                                        </div>
                                    </div>

                                    <div class="clearfix mb-20">
                                        <div class="col s6 m6 l6">
                                            <label class="form-label"><?= __("Language") ?></label>

                                            <select class="input required" name="language">
                                                <?php $l = $User->get("preferences.language"); ?>
                                                <?php foreach (Config::get("applangs") as $al): ?>
                                                    <option value="<?= $al["code"] ?>" <?= $al["code"] == $l ? "selected" : "" ?>><?= $al["name"] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="col s6 s-last m6 m-last l6 l-last">
                                            <label class="form-label"><?= __("Timezone") ?></label>

                                            <select class="input required" name="timezone">
                                                <?php $t = $User->get("preferences.timezone"); ?>
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
                                                <?php $df = $User->get("preferences.dateformat") ?>

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
                                                <?php $tf = $User->get("preferences.timeformat") == "12" ? "12" : "24" ?>
                                                <option value="24" <?= $t == "24" ? "selected" : "" ?>><?= __("24 hours") ?></option>
                                                <option value="12" <?= $t == "12" ? "selected" : "" ?>><?= __("12 hours") ?></option>
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
                                               value="<?= htmlchars($User->get("email")) ?>" 
                                               maxlength="80">
                                    </div>

                                    <div class="mb-20">
                                        <div class="clearfix">
                                            <div class="col s6 m6 l6">
                                                <label class="form-label">
                                                    <?= __("New Password") ?>
                                                    <?php if (!$User->isAvailable()): ?>
                                                        <span class="compulsory-field-indicator">*</span> 
                                                    <?php endif ?>    
                                                </label>
                                                <input class="input <?= $User->isAvailable() ? "" : "js-required" ?>" name="password" type="password" value="">
                                            </div>

                                            <div class="col s6 s-last m6 m-last l6 l-last">
                                                <label class="form-label">
                                                    <?= __("Confirm Password") ?>
                                                    <?php if (!$User->isAvailable()): ?>
                                                        <span class="compulsory-field-indicator">*</span> 
                                                    <?php endif ?>    
                                                </label>
                                                <input class="input <?= $User->isAvailable() ? "" : "js-required" ?>" name="password-confirm" type="password" value="">
                                            </div>
                                        </div>

                                        <?php if ($User->isAvailable()): ?>
                                            <ul class="field-tips">
                                                <li><?= __("If you don't want to change password then leave these password fields empty!") ?></li>
                                            </ul>
                                        <?php endif ?>
                                    </div>
                                </div>

                                <div class="col s12 m12 l5 l-last offset-l1 mb-40">
                                    <div class="mb-20">
                                        <label class="form-label"><?= __('Package') ?></label>

                                        <select class="input" name="package">
                                            <option value="-1"></option>
                                            <option value="0" <?= $User->get("package_id")==0 ? "selected" : "" ?>><?= __("Free Trial") ?></option>

                                            <?php foreach ($Packages->getDataAs("Package") as $p): ?>
                                                <option value="<?= $p->get("id") ?>" <?= $p->get("id") == $User->get("package_id") ? "selected" : "" ?>>
                                                    <?= htmlchars($p->get("title")) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-30">
                                        <label>
                                            <input type="checkbox" 
                                                   class="checkbox" 
                                                   name="package-subscription" 
                                                   value="1" 
                                                   <?= $User->get("package_subscription") ? "checked" : "" ?>>
                                            <span>
                                                <span class="icon unchecked">
                                                    <span class="mdi mdi-check"></span>
                                                </span>
                                                <?= __('Subscribe to changes in package settings') ?>
                                            </span>
                                        </label>
                                    </div>

                                    <div class="package-options">
                                        <div class="mb-20">
                                            <div class="clearfix">
                                                <div class="col s6 m6 l6">
                                                    <label class="form-label"><?= __("Max. storage size (MB)") ?></label>

                                                    <input class="input"
                                                           name="storage-total" 
                                                           type="text"
                                                           value="<?= htmlchars($User->get("settings.storage.total")) ?>" 
                                                           maxlength="10">
                                                </div>

                                                <div class="col s6 s-last m6 m-last l6 l-last">
                                                    <label class="form-label"><?= __("Max. file size (MB)") ?></label>

                                                    <input class="input"
                                                           name="storage-file" 
                                                           type="text"
                                                           value="<?= htmlchars($User->get("settings.storage.file")) ?>" 
                                                           maxlength="10">
                                                </div>
                                            </div>
                                            
                                            <ul class="field-tips">
                                                <li><?= __("Include -1 for unlimted") ?></li>
                                            </ul>
                                        </div>

                                        <div class="mb-30">
                                            <label class="form-label"><?= __("Number of accounts") ?></label>

                                            <input class="input"
                                                   name="accounts" 
                                                   type="text"
                                                   value="<?= htmlchars($User->get("settings.max_accounts")) ?>" 
                                                   maxlength="10">

                                            <ul class="field-tips">
                                                <li><?= __("Include -1 for unlimited") ?></li>
                                            </ul>
                                        </div>

                                        <div class="mb-30">
                                            <label class="form-label form-label--secondary"><?= __("File Pickers") ?></label>
                                            <div class="clearfix mt-15">
                                                <div class="col s6 m6 l6 mb-10">
                                                    <label>
                                                        <input type="checkbox" 
                                                               class="checkbox" 
                                                               name="dropbox" 
                                                               value="1" 
                                                               <?= $User->get("settings.file_pickers.dropbox") ? "checked" : "" ?>>
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
                                                               <?= $User->get("settings.file_pickers.onedrive") ? "checked" : "" ?>>
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
                                                               <?= $User->get("settings.file_pickers.google_drive") ? "checked" : "" ?>>
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
                                                               <?= $User->get("settings.post_types.timeline_photo") ? "checked" : "" ?>>
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
                                                               <?= $User->get("settings.post_types.timeline_video") ? "checked" : "" ?>>
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
                                                               <?= $User->get("settings.post_types.story_photo") ? "checked" : "" ?>>
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
                                                               <?= $User->get("settings.post_types.story_video") ? "checked" : "" ?>>
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
                                                               <?= $User->get("settings.post_types.album_photo") ? "checked" : "" ?>>
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
                                                               <?= $User->get("settings.post_types.album_video") ? "checked" : "" ?>>
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
                                        
                                        <div class="mb-10">
                                            <label>
                                                <input type="checkbox" 
                                                       class="checkbox" 
                                                       name="spintax" 
                                                       value="1" 
                                                       <?= $User->get("settings.spintax") ? "checked" : "" ?>>
                                                <span>
                                                    <span class="icon unchecked">
                                                        <span class="mdi mdi-check"></span>
                                                    </span>
                                                    <?= __('Spintax') ?>
                                                </span>
                                            </label>

                                            <ul class="field-tips">
                                                <li><?= __("Users with this option enabled, can use spintax syntax in post captions.") ?></li>
                                            </ul>
                                        </div>

                                        <div>
                                            <?php 
                                                $package_modules = $User->get("settings.modules");
                                                if (empty($package_modules)) {
                                                    $package_modules = [];
                                                }
                                                \Event::trigger("package.add_module_option", $package_modules) 
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="clearfix">
                                <div class="col s12 m12 l5">
                                    <input class="fluid button" type="submit" value="<?= __("Save") ?>">
                                </div>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>