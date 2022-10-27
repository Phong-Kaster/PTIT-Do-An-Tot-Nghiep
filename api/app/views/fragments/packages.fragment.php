        <div class="skeleton skeleton--full" id="packages">
            <div class="clearfix">
                <aside class="skeleton-aside">
                    <?php 
                        $form_action = APPURL."/packages";
                        include APPPATH."/views/fragments/aside-search-form.fragment.php"; 
                    ?>

                    <div class="js-search-results">
                        <?php $active_item_id = Input::get("aid"); ?>
                        <div class="aside-list js-loadmore-content" data-loadmore-id="1">
                            <?php if ($Packages->getPage() == 1): ?>
                                <div class="aside-list-item <?= $active_item_id == "trial" ? "active" : "" ?>">
                                    <div class="clearfix">
                                        <?php $title = __("Free Trial"); ?>
                                        <span class="circle">
                                            <span><?= textInitials($title, 2); ?></span>
                                        </span>

                                        <div class="inner">
                                            <div class="title"><?= $title ?></div>
                                            <div class="sub"><?= __("Trial package for new users") ?></div>
                                        </div>

                                        <a class="full-link js-ajaxload-content" href="<?= APPURL."/packages/trial" ?>"></a>
                                    </div>
                                </div>
                            <?php endif ?>

                            <?php foreach ($Packages->getDataAs("Package") as $p): ?>
                                <div class="aside-list-item js-list-item <?= $active_item_id == $p->get("id") ? "active" : "" ?>">
                                    <div class="clearfix">
                                        <?php $title = htmlchars($p->get("title")); ?>
                                        <span class="circle">
                                            <span><?= textInitials($title, 2); ?></span>
                                        </span>

                                        <div class="inner">
                                            <div class="title"><?= $title ?></div>
                                            <div class="sub"><?= $p->get("is_public") ? __("Public Package") : __("Package for admins only") ?></div>
                                        </div>

                                        <div class="options context-menu-wrapper">
                                            <a href="javascript:void(0)" class="mdi mdi-dots-vertical"></a>

                                            <div class="context-menu">
                                                <ul>
                                                    <li>
                                                        <a href="javascript:void(0)" 
                                                           class="js-remove-list-item" 
                                                           data-id="<?= $p->get("id") ?>" 
                                                           data-url="<?= APPURL."/packages" ?>">
                                                            <?= __("Delete") ?>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <a class="full-link js-ajaxload-content" href="<?= APPURL."/packages/".$p->get("id") ?>"></a>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>

                        <?php if($Packages->getPage() < $Packages->getPageCount()): ?>
                            <div class="loadmore mt-20">
                                <?php 
                                    $url = parse_url($_SERVER["REQUEST_URI"]);
                                    $path = $url["path"];
                                    if(isset($url["query"])){
                                        $qs = parse_str($url["query"],$qsarray);
                                        unset($qsarray["page"]);

                                        $url = $path."?".(count($qsarray) > 0 ? http_build_query($qsarray)."&" : "")."page=";
                                    }else{
                                        $url = $path."?page=";
                                    }
                                ?>
                                <a class="fluid button button--light-outline js-loadmore-btn" data-loadmore-id="1" href="<?= $url.($Packages->getPage()+1) ?>">
                                    <span class="icon sli sli-refresh"></span>
                                    <?= __("Load More") ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </aside>

                <section class="skeleton-content hide-on-medium-and-down">
                    <div class="no-data">
                        <span class="no-data-icon sli sli-drawer"></span>
                        <p><?= __("Please select a package from left side list to view or modify it's details.") ?></p>
                    </div>
                </section>
            </div>
        </div>