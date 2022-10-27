        <div class='skeleton' id="captions">
            <div class="container-1200">
                <div class="row clearfix">
                    <?php if ($Captions->getTotalCount() > 0): ?>
                        <div class="box-list clearfix js-loadmore-content" data-loadmore-id="1">
                            <?php $Emojione = new \Emojione\Client(new \Emojione\Ruleset()); ?>
                            <?php foreach($Captions->getDataAs("Caption") as $c): ?>
                                <div class="box-list-item js-list-item">
                                    <div class="inner">
                                        <div class="title mr-20"><?= htmlchars($c->get("title")) ?></div>
                                        <p>
                                            <?= truncate_string($Emojione->shortnameToUnicode($c->get("caption")), 190); ?>
                                        </p>   
                                    </div>

                                    <div class="options context-menu-wrapper">
                                        <a href="javascript:void(0)" class="mdi mdi-dots-vertical"></a>

                                        <div class="context-menu">
                                            <ul>
                                                <li>
                                                    <a href="<?= APPURL."/captions/".$c->get("id") ?>">
                                                        <?= __("Edit") ?>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="javascript:void(0)" 
                                                       class="js-remove-list-item" 
                                                       data-id="<?= $c->get("id") ?>" 
                                                       data-url="<?= APPURL."/captions" ?>">
                                                        <?= __("Delete") ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div>
                                        <a class="fluid button button--footer" href="<?= APPURL."/post?caption=".$c->get("id") ?>">
                                            <?= __("Use caption") ?>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if($Captions->getPage() < $Captions->getPageCount()): ?>
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
                                <a class="fluid button button--light-outline js-loadmore-btn" data-loadmore-id="1" href="<?= $url.($Captions->getPage()+1) ?>">
                                    <span class="icon sli sli-refresh"></span>
                                    <?= __("Load More") ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="no-data">
                            <p><?= __("You haven't add any caption template yet. Click the button below to create your first caption template.") ?></p>
                            <a class="small button" href="<?= APPURL."/captions/new" ?>">
                                <span class="sli sli-plus"></span>
                                <?= __("Add New") ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>