        <div id="topbar">
            <div class="clearfix">
                <a href="javascript:void(0)" class="topbar-mobile-menu-icon mdi mdi-menu"></a>

                <?php if (!empty($TopBar->title)): ?>
                    <h1 class="topbar-title"><?= $TopBar->title ?></h1>
                <?php endif ?>

                <?php if (!empty($TopBar->subtitle)): ?>
                    <div class="topbar-subtitle"><?= $TopBar->subtitle ?></div>
                <?php endif ?>

                <?php if (!empty($TopBar->btn)): ?>
                    <a class="topbar-special-link" href="<?= !empty($TopBar->btn["link"]) ? $TopBar->btn["link"] : "javascript:void(0)" ?>">
                        <?php if (!empty($TopBar->btn["icon"])): ?>
                            <span class="icon <?= $TopBar->btn["icon"] ?>"></span>
                        <?php endif ?>

                        <?php if (!empty($TopBar->btn["title"])): ?>
                            <?= $TopBar->btn["title"] ?>
                        <?php endif ?>
                    </a>
                <?php endif ?>

                <div class="topbar-actions clearfix">
                    <?php if ($AuthUser->isAdmin()): ?>
                        <div class="item topbar-search">
                            <form action="https://getnextpost.io/modules" target="_blank">
                                <a class="sli sli-magnifier icon" href="#"></a>
                                <input class="search-box" 
                                       type="text" 
                                       name="q" 
                                       placeholder="<?= __("Search Modules") ?>"
                                       autocomplete="off">
                                <input class="none" type="submit" value="<?= __("Search") ?>">
                            </form>
                        </div>

                        <div class="item hide-on-small-only">
                            <a class="link" href="<?= APPURL."/settings" ?>">
                                <span class="sli sli-settings icon"></span>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="item">
                        <a class="link" href="<?= APPURL."/post" ?>">
                            <span class="sli sli-plus icon"></span>
                        </a>
                    </div>

                    <div class="item">
                        <div class="topbar-profile clearfix">
                            <span class="greeting">
                                <?= __("Hi, %s!", htmlchars($AuthUser->get("firstname"))) ?>
                            </span>

                            <div class="pull-left clearfix context-menu-wrapper">
                                <a href="javascript:void(0)" class="circle">
                                    <span>
                                        <?= 
                                            mb_substr($AuthUser->get("firstname"), 0, 1) .
                                            mb_substr($AuthUser->get("lastname"), 0, 1)
                                        ?>
                                    </span>
                                </a>

                                <a href="javascript:void(0)" class="mdi mdi-chevron-down arrow"></a>

                                <div class="context-menu">
                                    <ul>
                                        <li><a href="<?= APPURL."/profile" ?>"><?= __('Profile') ?></a></li>
                                        <li><a href="<?= APPURL."/logout" ?>"><?= __('Logout') ?></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>