        <div class='skeleton' id="plugins">
            <div class="container-1200">
                <div class="row clearfix">
                    <?php if ($Plugins->getTotalCount() > 0): ?>
                        <table class="plugins-table">
                            <tbody class="js-loadmore-content" data-loadmore-id="1">
                                <?php foreach ($Plugins->getDataAs("Plugin") as $p): ?>
                                    <?php 
                                        $config_path = PLUGINS_PATH . "/" . $p->get("idname") . "/config.php"; 
                                        if (file_exists($config_path)) {
                                            $config = include $config_path;
                                            $valid = isset($config["idname"]) && $config["idname"] == $p->get("idname") 
                                                   ? true : false;
                                        } else {
                                            $valid = false;
                                        }
                                    ?>
                                    <tr class="js-list-item">
                                        <?php if ($valid): ?>
                                            <td>
                                                <div class="table-big-text mb-5">
                                                    <span class="mr-5">
                                                        <?= htmlchars(empty($config["plugin_name"]) ? $p->get("idname") : $config["plugin_name"]) ?>
                                                    </span>

                                                    <?php if (!empty($config["plugin_uri"])): ?>
                                                        <a class="mdi mdi-link" href="<?= htmlchars($config["plugin_uri"]) ?>" target='_blank'></a>
                                                    <?php endif ?>
                                                </div>
                                                <?php 
                                                    $sub = [];
                                                    if (!empty($config["version"])) {
                                                        $sub[] = __("Version %s", htmlchars($config["version"]));
                                                    }

                                                    if (!empty($config["author"])) {
                                                        $author = htmlchars($config["author"]);
                                                        if (!empty($config["author_uri"])) {
                                                            $author = "<a href='".htmlchars($config["author_uri"])."' target='_blank'>".$author."</a>";
                                                        }
                                                        $sub[] = __("By %s", $author);
                                                    }

                                                    if ($sub) {
                                                        echo implode(" | ", $sub);
                                                    }
                                                ?>

                                                <?php if (!empty($config["settings_page_uri"])): ?>
                                                    <div class="mt-15">
                                                        <a href="<?= htmlchars($config["settings_page_uri"]) ?>">
                                                            <?= __("Module Settings") ?>
                                                        </a>
                                                    </div>
                                                <?php endif ?>
                                            </td>

                                            <td>
                                                <?= empty($config["desc"]) ? "&nbsp;" : htmlchars($config["desc"]) ?>
                                            </td>

                                            <td>
                                                <a class="js-deactivate small button button--light-outline <?= $p->get("is_active") ? "" : "none" ?>"
                                                   data-id="<?= $p->get("id") ?>"
                                                   data-url="<?= APPURL."/plugins" ?>"
                                                   href="javascript:void(0)"><?= __("Deactivate") ?></a>

                                                <a class="js-activate small button button--light-outline <?= $p->get("is_active") ? "none" : "" ?>"
                                                   data-id="<?= $p->get("id") ?>"
                                                   data-url="<?= APPURL."/plugins" ?>"
                                                   href="javascript:void(0)"><?= __("Activate") ?></a>

                                                <a class="js-remove-list-item small button button--light-outline"
                                                       data-id="<?= $p->get("id") ?>"
                                                       data-url="<?= APPURL."/plugins" ?>"
                                                       href="javascript:void(0)"><?= __("Delete") ?></a>
                                            </td>
                                        <?php else: ?>
                                            <td>
                                                <div class="table-big-text mb-5"><?= $p->get("idname") ?></div>
                                                <span class="color-danger"><?= __("Invalid module") ?></span>
                                            </td>

                                            <td>&nbsp;</td>
                                            <td>
                                                <a class="js-remove-list-item small button button--light-outline"
                                                       data-id="<?= $p->get("id") ?>"
                                                       data-url="<?= APPURL."/plugins" ?>"
                                                       href="javascript:void(0)"><?= __("Delete") ?></a>
                                            </td>
                                        <?php endif ?>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>


                        <?php if($Plugins->getPage() < $Plugins->getPageCount()): ?>
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
                                <a class="fluid button button--light-outline js-loadmore-btn" data-loadmore-id="1" href="<?= $url.($Plugins->getPage()+1) ?>">
                                    <span class="icon sli sli-refresh"></span>
                                    <?= __("Load More") ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="no-data">
                            <p><?= __("You haven't installed any module yet. Click the button below to install first module.") ?></p>
                            <a class="small button" href="<?= APPURL."/plugins/install" ?>">
                                <span class="sli sli-plus"></span>
                                <?= __("Add New") ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>