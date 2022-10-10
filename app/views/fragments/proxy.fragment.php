        <div class="skeleton skeleton--full" id="user">
            <div class="clearfix">
                <aside class="skeleton-aside hide-on-medium-and-down">
                    <div class="aside-list js-loadmore-content" data-loadmore-id="1"></div>

                    <div class="loadmore pt-20 none">
                        <a class="fluid button button--light-outline js-loadmore-btn js-autoloadmore-btn" 
                           data-loadmore-id="1" href="<?= APPURL."/proxies?aid=".$Proxy->get("id") ?>">
                            <span class="icon sli sli-refresh"></span>
                            <?= __("Load More") ?>
                        </a>
                    </div>
                </aside>

                <section class="skeleton-content">
                    <form class="js-ajax-form"
                          action="<?= APPURL."/proxies/".($Proxy->isAvailable() ? $Proxy->get("id") : "new") ?>"
                          method="POST">

                        <input type="hidden" name="action" value="save">

                        <div class="section-header clearfix">
                            <h2 class="section-title"><?= $Proxy->isAvailable() ? htmlchars($Proxy->get("proxy").($Proxy->get("country_code") ? " - ".$Proxy->get("country_code") : "")) : __("New Proxy") ?></h2>
                        </div>

                        <div class="section-content">
                            <div class="form-result"></div>

                            <div class="clearfix">
                                <div class="col s12 m6 l5">
                                    <div class="mb-20">
                                        <label class="form-label">
                                            <?= __("Proxy") ?>
                                            <span class="compulsory-field-indicator">*</span>    
                                        </label>

                                        <input class="input js-required"
                                               name="proxy" 
                                               value="<?= htmlchars($Proxy->get("proxy")) ?>" 
                                               type="text" 
                                               maxlength="255">

                                        <ul class="field-tips">
                                            <li><?= __("Proxy should match following pattern: http://ip:port OR http://username:password@ip:port") ?></li>
                                        </ul>
                                    </div>

                                    <div class="mb-20">
                                        <label class="form-label"><?= __("Country") ?></label>

                                        <select class="input combobox" name="country">
                                            <option value=""><?= __("Unknown") ?></option>
                                            <?php foreach ($Countries as $k => $v): ?>
                                                <option value="<?= $k ?>" <?= $k == $Proxy->get("country_code") ? "selected" : "" ?>><?= $v ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <input class="fluid button" type="submit" value="<?= __("Save") ?>">
                                </div>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>