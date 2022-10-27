<div class='skeleton' id="calendar-month">
    <div class="container-1200">
        <?php if ($Accounts->getTotalCount() > 0): ?>
            <div class="row clearfix pos-r">
                <form class="account-selector clearfix" action="<?= APPURL."/calendar/".$year."/".$month ?>" method="GET">
                    <span class="label"><?= __("Select Account") ?></span>

                    <select class="input input--small" name="account">
                        <option value=""><?= __("All accounts") ?></option>
                        <?php foreach ($Accounts->getData() as $a): ?>
                            <option value="<?= $a->id ?>" <?= $ActiveAccount->get("id") == $a->id ? "selected": "" ?>>
                                <?= htmlchars($a->username); ?>
                            </option>
                        <?php endforeach ?>
                    </select>

                    <input class="none" type="submit" value="<?= __("Submit") ?>">
                </form>

                <div class="calendar-month-switch">
                    <?php 
                        $prevmonth = $month > 1 ? $month - 1 : "12";
                        $prevmonth = sprintf('%02d', $prevmonth);

                        $nextmonth = $month < 12 ? $month + 1 : "01";
                        $nextmonth = sprintf('%02d', $nextmonth);

                        $date = new DateTime(
                            $year . "-" . $month . "-01",
                            new DateTimeZone($AuthUser->get("preferences.timezone")));
                    ?>

                    <div class="month">
                        <a class="sli sli-arrow-left nav left" href="<?= APPURL . "/calendar/" . ($prevmonth == "12" ? $year - 1 : $year) . "/" . $prevmonth . ($ActiveAccount->isAvailable() ? "?account=".$ActiveAccount->get("id") : ""); ?>" ></a>
                        <?= __($date->format("F")) ?>
                        <a class="sli sli-arrow-right nav right" href="<?= APPURL . "/calendar/" . ($nextmonth == "01" ? $year + 1 : $year) . "/" . $nextmonth . ($ActiveAccount->isAvailable() ? "?account=".$ActiveAccount->get("id") : ""); ?>" ></a>
                    </div>

                    <div class="year"><?= $year ?></div>
                </div>
            </div>
            
            <div class="row clearfix">
                <div class="calendar">
                    <?php 
                        $short_week_days = [
                            __("Mon"), __("Tue"), __("Wed"), __("Thu"), 
                            __("Fri"), __("Sat"), __("Sun")
                        ];
                    ?>
                    <div class="head clearfix">
                        <?php foreach ($short_week_days as $wd): ?>
                            <div class='cell'><?= $wd ?></div>
                        <?php endforeach ?>
                    </div>

                    <?php 
                        $days_in_month = date("t", mktime(0, 0, 0, (int)$month, 1, $year));
                        $month_firstday_number = date("N", mktime(0, 0, 0, (int)$month, 1, $year));
                        $month_lastday_number = date("N", mktime(0, 0, 0, (int)$month, $days_in_month, $year));

                        $days_in_prev_month = date("t", mktime(0, 0, 0, (int)$prevmonth, 1, $prevmonth == "12" ? $year-1 : $year));
                        $days_in_next_month = date("t", mktime(0, 0, 0, (int)$nextmonth, 1, $nextmonth == "01" ? $year+1 : $year));

                        $now = new DateTime("now", new DateTimeZone(date_default_timezone_get()));
                        $now->setTimezone(new DateTimeZone($AuthUser->get("preferences.timezone")));
                    ?>
                    <div class="clearfix">
                        <?php if ($month_firstday_number > 1): ?>
                            <?php for ($i=1; $i < $month_firstday_number; $i++): ?>
                                <?php 
                                    $day = $days_in_prev_month - ($month_firstday_number - 1 - $i);
                                    $date = ($prevmonth == "12" ? $year - 1 : $year) . "-". $prevmonth . "-" . sprintf("%02d", $day);
                                    $date = new DateTime($date, new DateTimeZone($AuthUser->get("preferences.timezone")));
                                ?>
                                <div class='cell in-other-month'>
                                    <div class='cell-inner'>
                                        <span class="day-name"><?= $date->format("D") ?></span>
                                        <span class="day-number"><?= $day ?></span>

                                        <a href="<?= APPURL . "/calendar/" . $date->format("Y/m") ?>" class="full-link"></a>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        <?php endif ?>
                        
                        <?php for ($day=1; $day <= $days_in_month; $day++): ?>
                            <?php 
                                $date = $year . "-". $month . "-" . sprintf("%02d", $day);
                                $date = new DateTime($date, new DateTimeZone($AuthUser->get("preferences.timezone")));
                            ?>
                            <div class="cell <?= $date->format("Y-m-d") == $now->format("Y-m-d") ? "today" : "" ?>">
                                <div class='cell-inner'>
                                    <span class="day-name"><?= $date->format("D") ?></span>
                                    <span class="day-number"><?= $day ?></span>

                                    <?php if ($date->format("Y-m-d") >= $now->format("Y-m-d")): ?>
                                        <a class="add-post" href="<?= APPURL."/post?date=" . $date->format("Y-m-d") . ($ActiveAccount->isAvailable() ? "&account=".$ActiveAccount->get("id") : "") ?>">
                                            <span class="sli sli-plus icon"></span>
                                            <span class="hide-on-medium-and-down">
                                                <?= __("Add post") ?>
                                            </span>
                                        </a>
                                    <?php endif ?>

                                    <?php if (!empty($postcounts[$date->format("d")])): ?>
                                        <?php
                                            $count = $postcounts[$date->format("d")];
                                            $total = $count["scheduled"]
                                                   + $count["published"]
                                                   + $count["failed"];
                                            $count_class="";
                                            if ($total > 10) {
                                                $count_class = "high";
                                            } else if ($total > 5) {
                                                $count_class = "medium";
                                            }

                                        ?>
                                        <div class="count">
                                            <div class="bg <?= $count_class ?>"></div>

                                            <div class="intro">
                                                <?= n__("%s post", "%s posts", $total, $total) ?>
                                                <a href="<?= APPURL . "/calendar/" . $date->format("Y/m/d") . ($ActiveAccount->isAvailable() ? "?account=".$ActiveAccount->get("id") : "") ?>" class="full-link"></a>
                                            </div>

                                            <div class="details">
                                                <?php if ($date->format("Y-m-d") < $now->format("Y-m-d") && $count["scheduled"] > 0): ?>
                                                    <div class="detail delayed">
                                                        <span class="label"><?=  __("Delayed") ?>:</span>
                                                        <span class="value"><?= $count["scheduled"] ?></span>
                                                    </div>
                                                <?php elseif ($date->format("Y-m-d") >= $now->format("Y-m-d")): ?>
                                                    <div class="detail scheduled">
                                                        <span class="label"><?=  __("Scheduled") ?>:</span>
                                                        <span class="value"><?= $count["scheduled"] ?></span>
                                                    </div>
                                                <?php endif ?>

                                                <div class="detail published">
                                                    <span class="label"><?= __("Published") ?>:</span>
                                                    <span class="value"><?= $count["published"] ?></span>
                                                </div>

                                                <div class="detail failed">
                                                    <span class="label"><?= __("Failed") ?>:</span>
                                                    <span class="value"><?= $count["failed"] ?></span>
                                                </div>
                                            </div>

                                            <a href="<?= APPURL . "/calendar/" . $date->format("Y/m/d") . ($ActiveAccount->isAvailable() ? "?account=".$ActiveAccount->get("id") : "") ?>" class="full-link"></a>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        <?php endfor; ?>


                        <?php if ($month_lastday_number < 7): ?>
                            <?php $day = 1; ?>
                            <?php for ($i = $month_lastday_number; $i<7; $i++): ?>
                                <?php 
                                    $date = ($nextmonth == "01" ? $year+1 : $year) . "-". $nextmonth . "-" . sprintf("%02d", $day);
                                    $date = new DateTime($date, new DateTimeZone($AuthUser->get("preferences.timezone")));
                                ?>
                                <div class='cell in-other-month'>
                                    <div class='cell-inner'>
                                        <span class="day-name"><?= $date->format("D") ?></span>
                                        <span class="day-number"><?= $day++ ?></span>

                                        <a href="<?= APPURL . "/calendar/" . $date->format("Y/m") ?>" class="full-link"></a>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php include APPPATH.'/views/fragments/noaccount.fragment.php' ?>
        <?php endif ?>
    </div>
</div>