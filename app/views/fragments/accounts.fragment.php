        <div class='skeleton' id="accounts">
            <div class="container-1200">
                <div class="row clearfix">
                    <?php if ($Accounts->getTotalCount() > 0): ?>
                        <div class="box-list clearfix js-loadmore-content" data-loadmore-id="1">
                            <?php $number = ($Accounts->getPage() - 1) * $Accounts->getPageSize() + 1 ?>
                            <?php foreach($Accounts->getDataAs("Account") as $a): ?>
                                <div class="box-list-item text-c js-list-item">
                                    <div class="inner">
                                        <div class="circle"><span><?= $number++ ?></span></div>
                                        <div class="title"><?= htmlchars($a->get("username")) ?></div>
                                        <?php 
                                            $date = new Moment\Moment($a->get("date"), date_default_timezone_get());
                                            $date->setTimezone($AuthUser->get("preferences.timezone"));
                                            $format = $AuthUser->get("preferences.dateformat");
                                        ?>
                                        <div class="sub" title="<?= $date->format("c") ?>">
                                            <?= __("Added on %s", $date->format($format)) ?>
                                        </div>

                                        <div class="quick-info">
                                            <?php if ($a->get("login_required")): ?>
                                                <a class="color-danger" href="<?= APPURL."/accounts/".$a->get("id") ?>">
                                                    <span class='mdi mdi-information'></span>
                                                    <?= __("Re-login required!") ?>
                                                </a>
                                            <?php endif ?>
                                        </div>    
                                    </div>

                                    <div class="options context-menu-wrapper">
                                        <a href="javascript:void(0)" class="mdi mdi-dots-vertical"></a>

                                        <div class="context-menu">
                                            <ul>
                                                <li>
                                                    <a href="<?= APPURL."/accounts/".$a->get("id") ?>">
                                                        <?= __("Edit") ?>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="javascript:void(0)" 
                                                       class="js-remove-list-item" 
                                                       data-id="<?= $a->get("id") ?>" 
                                                       data-url="<?= APPURL."/accounts" ?>">
                                                        <?= __("Delete") ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div>
                                        <a class="fluid button button--footer" href="<?= "https://instagram.com/".$a->get("username") ?>" target="_blank">
                                            <?= __("View timeline") ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if($Accounts->getPage() < $Accounts->getPageCount()): ?>
                            <div class="loadmore">
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
                                <a class="fluid button button--light-outline js-loadmore-btn" data-loadmore-id="1" href="<?= $url.($Accounts->getPage()+1) ?>">
                                    <span class="icon sli sli-refresh"></span>
                                    <?= __("Load More") ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="no-data">
                            <?php if ($AuthUser->get("settings.max_accounts") == -1 || $AuthUser->get("settings.max_accounts") > 0): ?>
                                <p><?= __("You haven't add any Instagram account yet. Click the button below to add your first account.") ?></p>
                                <a class="small button" href="<?= APPURL."/accounts/new" ?>">
                                    <span class="sli sli-user-follow"></span>
                                    <?= __("New Account") ?>
                                </a>
                            <?php else: ?>
                                <p><?= __("You don't have a permission to add any Instagram account.") ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>