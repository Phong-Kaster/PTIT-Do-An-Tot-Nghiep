        <div class='skeleton' id="calendar-day">
            <div class="container-1200">
                <div class="row pos-r">
                    <?php if ($Accounts->getTotalCount() > 0): ?>
                        <?php 
                            $Emojione = new \Emojione\Client(new \Emojione\Ruleset());

                            $dateformat = $AuthUser->get("preferences.dateformat");
                            $timeformat = $AuthUser->get("preferences.timeformat") == "24" ? "H:i" : "h:i A";
                            $format = $dateformat." ".$timeformat;
                        ?>

                        <form class="account-selector clearfix" action="<?= APPURL."/calendar/".$year."/".$month."/".$day ?>" method="GET">
                            <span class="label"><?= __("Select Account") ?></span>

                            <select class="input input--small" name="account">
                                <option value=""><?= __("All accounts") ?></option>
                                <?php foreach ($Accounts->getData() as $a): ?>
                                    <option value="<?= $a->id ?>" <?= $ActiveAccount->get("id") == $a->id ? "selected" : "" ?>>
                                        <?= htmlchars($a->username); ?>
                                    </option>
                                <?php endforeach ?>
                            </select>

                            <input class="none" type="submit" value="<?= __("Submit") ?>">
                        </form>

                        <div class="list-wrapper">
                            <h2 class="page-secondary-title">
                                <?= __("In Progress") ?>

                                <?php if ($ScheduledPosts->getTotalCount() > 0): ?>
                                    <span class="badge"><?= $ScheduledPosts->getTotalCount() ?></span>
                                <?php endif ?>
                            </h2>

                            <?php if ($ScheduledPosts->getTotalCount() > 0): ?>
                                <div class="post-list clearfix">
                                    <?php foreach ($ScheduledPosts->getDataAs("Post") as $Post): ?>
                                        <?php 
                                            $date = new \Moment\Moment(
                                                $Post->get("schedule_date"), 
                                                date_default_timezone_get());
                                            $date->setTimezone($AuthUser->get("preferences.timezone"));
                                        ?>
                                        <div class="post-list-item <?= $Post->get("status") == "publishing" ? "" : "haslink" ?> js-list-item">
                                            <div>
                                                <?php if ($Post->get("status") != "publishing"): ?>
                                                    <div class="options context-menu-wrapper">
                                                        <a href="javascript:void(0)" class="mdi mdi-dots-vertical"></a>

                                                        <div class="context-menu">
                                                            <ul>
                                                                <li>
                                                                    <a href="<?= APPURL."/post/".$Post->get("id") ?>">
                                                                        <?= __("Edit") ?>
                                                                    </a>
                                                                </li>

                                                                <li>
                                                                    <a href="javascript:void(0)" 
                                                                       class="js-remove-list-item" 
                                                                       data-id="<?= $Post->get("id") ?>" 
                                                                       data-url="<?= APPURL."/calendar" ?>">
                                                                        <?= __("Delete") ?>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                <?php endif ?>

                                                <div class="quick-info">
                                                    <?php if ($Post->get("status") == "publishing"): ?>
                                                        <span class="color-dark">
                                                            <span class="icon sli sli-energy"></span>
                                                            <?= __("Processing now...") ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <?php 
                                                            $diff = $date->fromNow(); 

                                                            if ($diff->getDirection() == "future") {
                                                                echo $diff->getRelative();
                                                            } else if (abs($diff->getSeconds()) < 60*10) {
                                                                echo __("In a few moments");
                                                            } else {
                                                                echo __("System task error");
                                                            }
                                                        ?>
                                                    <?php endif ?>
                                                </div>

                                                <div class="cover">
                                                    <?php 
                                                        $media_ids = explode(",", $Post->get("media_ids"));
                                                        $File = Controller::model("File", $media_ids[0]);
                                                    ?>

                                                    <?php if ($File->isAvailable()): ?>
                                                        <?php 
                                                            $ext = strtolower(pathinfo($File->get("filename"), PATHINFO_EXTENSION));

                                                            if (in_array($ext, ["mp4"])) {
                                                                $type = "video";                                                                
                                                            } else if (in_array($ext, ["jpg", "jpeg", "png"])) {
                                                                $type = "image";
                                                            }

                                                            $fileurl = APPURL
                                                                     . "/assets/uploads/" 
                                                                     . $AuthUser->get("id") 
                                                                     . "/" . $File->get("filename");

                                                            $filepath = ROOTPATH
                                                                      . "/assets/uploads/" 
                                                                      . $AuthUser->get("id") 
                                                                      . "/" . $File->get("filename");
                                                        ?>
                                                        <?php if (file_exists($filepath)): ?>
                                                            <?php if ($type == "image"): ?>
                                                                <div class="img" style="background-image: url('<?= $fileurl ?>')"></div>
                                                            <?php else: ?>
                                                                <video src='<?= $fileurl ?>' playsinline autoplay muted loop></video>
                                                            <?php endif ?>
                                                        <?php endif ?>
                                                    <?php endif ?>
                                                </div>

                                                <div class="caption">
                                                    <?= truncate_string($Emojione->shortnameToUnicode($Post->get("caption")), 50); ?>
                                                </div>

                                                <?php if (!$ActiveAccount->isAvailable()): ?>
                                                    <div class="quick-info mb-10">
                                                        <span class="icon sli sli-social-instagram"></span>
                                                        <?= htmlchars($Post->get("username")) ?>
                                                    </div>
                                                <?php endif ?>

                                                <div class="quick-info mb-10">
                                                    <?php if ($Post->get("type") == "album"): ?>
                                                        <span class="icon sli sli-layers"></span>
                                                        <?= __("Album") ?>
                                                    <?php elseif ($Post->get("type") == "story"): ?>
                                                        <span class="icon sli sli-plus"></span>
                                                        <?= __("Story") ?>
                                                    <?php else: ?>
                                                        <span class="icon sli sli-camera"></span>
                                                        <?= __("Regular Post") ?>
                                                    <?php endif ?>
                                                </div>

                                                <div class="quick-info">
                                                    <span class="icon sli sli-calendar"></span>
                                                    <?= $date->format($format); ?>
                                                </div>

                                                <?php if ($Post->get("status") == "scheduled"): ?>
                                                    <a class="full-link" href="<?= APPURL."/post/".$Post->get("id") ?>"></a>
                                                <?php endif ?>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            <?php else: ?>
                                <p class="nopost">
                                    <?php 
                                        if ($ActiveAccount->isAvailable()) {
                                            echo __("There is not any scheduled post for {account} on this date.", [
                                                "{account}" => "<strong>".htmlchars($ActiveAccount->get("username"))."</strong>"
                                            ]);
                                        } else {
                                            echo __("There is not any scheduled post on this date.");
                                        }
                                    ?>
                                </p>
                            <?php endif ?>
                        </div>

                        <div class="list-wrapper">
                            <h2 class="page-secondary-title">
                                <?= __("Published") ?>

                                <?php if ($PublishedPosts->getTotalCount() > 0): ?>
                                    <span class="badge"><?= $PublishedPosts->getTotalCount() ?></span>
                                <?php endif ?>
                            </h2>

                            <?php if ($PublishedPosts->getTotalCount() > 0): ?>
                                <div class="post-list clearfix">
                                    <?php foreach ($PublishedPosts->getDataAs("Post") as $Post): ?>
                                        <?php 
                                            $date = new \Moment\Moment(
                                                $Post->get("schedule_date"), 
                                                date_default_timezone_get());
                                            $date->setTimezone($AuthUser->get("preferences.timezone"));
                                        ?>
                                        <div class="post-list-item haslink js-list-item">
                                            <div>
                                                <div class="options context-menu-wrapper">
                                                    <a href="javascript:void(0)" class="mdi mdi-dots-vertical"></a>

                                                    <div class="context-menu">
                                                        <ul>
                                                            <li>
                                                                <a href="<?= "https://www.instagram.com/p/".$Post->get("data.code") ?>" target="_blank">
                                                                    <?= __("View on Instagram") ?>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="quick-info">
                                                    <span class="color-success">
                                                        <span class="icon sli sli-check"></span>
                                                        <?= __("Published") ?>
                                                    </span>
                                                </div>

                                                <div class="cover">
                                                    <?php 
                                                        $media_ids = explode(",", $Post->get("media_ids"));
                                                        $File = Controller::model("File", $media_ids[0]);
                                                    ?>

                                                    <?php if ($File->isAvailable()): ?>
                                                        <?php 
                                                            $ext = strtolower(pathinfo($File->get("filename"), PATHINFO_EXTENSION));

                                                            if (in_array($ext, ["mp4"])) {
                                                                $type = "video";                                                                
                                                            } else if (in_array($ext, ["jpg", "jpeg", "png"])) {
                                                                $type = "image";
                                                            }

                                                            $fileurl = APPURL
                                                                     . "/assets/uploads/" 
                                                                     . $AuthUser->get("id") 
                                                                     . "/" . $File->get("filename");

                                                            $filepath = ROOTPATH
                                                                      . "/assets/uploads/" 
                                                                      . $AuthUser->get("id") 
                                                                      . "/" . $File->get("filename");
                                                        ?>
                                                        <?php if (file_exists($filepath)): ?>
                                                            <?php if ($type == "image"): ?>
                                                                <div class="img" style="background-image: url('<?= $fileurl ?>')"></div>
                                                            <?php else: ?>
                                                                <video src='<?= $fileurl ?>' playsinline autoplay muted loop></video>
                                                            <?php endif ?>
                                                        <?php endif ?>
                                                    <?php endif ?>
                                                </div>

                                                <div class="caption">
                                                    <?= truncate_string($Emojione->shortnameToUnicode($Post->get("caption")), 50); ?>
                                                </div>

                                                <?php if (!$ActiveAccount->isAvailable()): ?>
                                                    <div class="quick-info mb-10">
                                                        <span class="icon sli sli-social-instagram"></span>
                                                        <?= htmlchars($Post->get("username")) ?>
                                                    </div>
                                                <?php endif ?>

                                                <div class="quick-info mb-10">
                                                    <?php if ($Post->get("type") == "album"): ?>
                                                        <span class="icon sli sli-layers"></span>
                                                        <?= __("Album") ?>
                                                    <?php elseif ($Post->get("type") == "story"): ?>
                                                        <span class="icon sli sli-plus"></span>
                                                        <?= __("Story") ?>
                                                    <?php else: ?>
                                                        <span class="icon sli sli-camera"></span>
                                                        <?= __("Regular Post") ?>
                                                    <?php endif ?>
                                                </div>

                                                <div class="quick-info">
                                                    <span class="icon sli sli-calendar"></span>
                                                    <?= $date->format($format); ?>
                                                </div>

                                                <a class="full-link" href="<?= "https://www.instagram.com/p/".$Post->get("data.code") ?>" target="_blank"></a>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            <?php else: ?>
                                <p class="nopost">
                                    <?php 
                                        if ($ActiveAccount->isAvailable()) {
                                            echo __("There is not any published post for {account} on this date yet.", [
                                                "{account}" => "<strong>".htmlchars($ActiveAccount->get("username"))."</strong>"
                                            ]);
                                        } else {
                                            echo __("There is not any published post on this date yet.");
                                        }
                                    ?>
                                </p>
                            <?php endif ?>
                        </div>

                        <div class="list-wrapper">
                            <h2 class="page-secondary-title">
                                <?= __("Failed") ?>

                                <?php if ($FailedPosts->getTotalCount() > 0): ?>
                                    <span class="badge"><?= $FailedPosts->getTotalCount() ?></span>
                                <?php endif ?>
                            </h2>

                            <?php if ($FailedPosts->getTotalCount() > 0): ?>
                                <div class="post-list clearfix">
                                    <?php foreach ($FailedPosts->getDataAs("Post") as $Post): ?>
                                        <?php 
                                            $date = new \Moment\Moment(
                                                $Post->get("schedule_date"), 
                                                date_default_timezone_get());
                                            $date->setTimezone($AuthUser->get("preferences.timezone"));
                                        ?>
                                        <div class="post-list-item haslink js-list-item">
                                            <div>
                                                <div class="options context-menu-wrapper">
                                                    <a href="javascript:void(0)" class="mdi mdi-dots-vertical"></a>

                                                    <div class="context-menu">
                                                        <ul>
                                                            <li>
                                                                <a href="<?= APPURL."/post/".$Post->get("id") ?>">
                                                                    <?= __("Edit") ?>
                                                                </a>
                                                            </li>

                                                            <li>
                                                                <a href="javascript:void(0)" 
                                                                   class="js-remove-list-item" 
                                                                   data-id="<?= $Post->get("id") ?>" 
                                                                   data-url="<?= APPURL."/calendar" ?>">
                                                                    <?= __("Delete") ?>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="quick-info">
                                                    <span class="color-danger">
                                                        <span class="icon sli sli-close"></span>
                                                        <?= __("Failed") ?>
                                                    </span>
                                                </div>

                                                <div class="cover">
                                                    <?php 
                                                        $media_ids = explode(",", $Post->get("media_ids"));
                                                        $File = Controller::model("File", $media_ids[0]);
                                                    ?>

                                                    <?php if ($File->isAvailable()): ?>
                                                        <?php 
                                                            $ext = strtolower(pathinfo($File->get("filename"), PATHINFO_EXTENSION));

                                                            if (in_array($ext, ["mp4"])) {
                                                                $type = "video";                                                                
                                                            } else if (in_array($ext, ["jpg", "jpeg", "png"])) {
                                                                $type = "image";
                                                            }

                                                            $fileurl = APPURL
                                                                     . "/assets/uploads/" 
                                                                     . $AuthUser->get("id") 
                                                                     . "/" . $File->get("filename");

                                                            $filepath = ROOTPATH
                                                                      . "/assets/uploads/" 
                                                                      . $AuthUser->get("id") 
                                                                      . "/" . $File->get("filename");
                                                        ?>
                                                        <?php if (file_exists($filepath)): ?>
                                                            <?php if ($type == "image"): ?>
                                                                <div class="img" style="background-image: url('<?= $fileurl ?>')"></div>
                                                            <?php else: ?>
                                                                <video src='<?= $fileurl ?>' playsinline autoplay muted loop></video>
                                                            <?php endif ?>
                                                        <?php endif ?>
                                                    <?php endif ?>
                                                </div>

                                                <div class="caption">
                                                    <?= truncate_string($Emojione->shortnameToUnicode($Post->get("caption")), 50); ?>
                                                </div>

                                                <?php if (!$ActiveAccount->isAvailable()): ?>
                                                    <div class="quick-info mb-10">
                                                        <span class="icon sli sli-social-instagram"></span>
                                                        <?= htmlchars($Post->get("username")) ?>
                                                    </div>
                                                <?php endif ?>

                                                <div class="quick-info mb-10">
                                                    <?php if ($Post->get("type") == "album"): ?>
                                                        <span class="icon sli sli-layers"></span>
                                                        <?= __("Album") ?>
                                                    <?php elseif ($Post->get("type") == "story"): ?>
                                                        <span class="icon sli sli-plus"></span>
                                                        <?= __("Story") ?>
                                                    <?php else: ?>
                                                        <span class="icon sli sli-camera"></span>
                                                        <?= __("Regular Post") ?>
                                                    <?php endif ?>
                                                </div>

                                                <div class="quick-info">
                                                    <span class="icon sli sli-calendar"></span>
                                                    <?= $date->format($format); ?>
                                                </div>

                                                <a class="full-link" href="<?= APPURL."/post/".$Post->get("id") ?>"></a>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            <?php else: ?>
                                <p class="nopost">
                                    <?php 
                                        if ($ActiveAccount->isAvailable()) {
                                            echo __("There is not any failed post for {account} on this date yet.", [
                                                "{account}" => "<strong>".htmlchars($ActiveAccount->get("username"))."</strong>"
                                            ]);
                                        } else {
                                            echo __("There is not any failed post on this date yet.");
                                        }
                                    ?>
                                </p>
                            <?php endif ?>
                        </div>
                    <?php else: ?>
                        <?php include APPPATH.'/views/fragments/noaccount.fragment.php' ?>
                    <?php endif ?>
                </div>
            </div>
        </div>