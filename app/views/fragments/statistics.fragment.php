        <div class="skeleton" id="statistics">
            <div class="container-1200">
                <div class="row clearfix">
                    <?php if ($Accounts->getTotalCount() > 0): ?>
                        <form action="<?= APPURL."/statistics" ?>" method="GET">
                            <div class="account-selector clearfix">
                                <span class="label"><?= __("Select Account") ?></span>

                                <select class="input input--small" name="account">
                                    <?php foreach ($Accounts->getData() as $a): ?>
                                        <option value="<?= $a->id ?>" <?= $a->id == $ActiveAccount->get("id") ? "selected" : "" ?>>
                                            <?= htmlchars($a->username); ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <input class="none" type="submit" value="<?= __("Submit") ?>">
                        </form>

                        <div class="clearfix">
                            <div class="col s12 m6 l6 mt-30">
                                <section class="section">
                                    <div class="section-content account-summary onprogress" data-url="<?= APPURL."/statistics?account=".$ActiveAccount->get("id") ?>">
                                        <h2 class="page-secondary-title"><?= __("Account Summary") ?></h2>

                                        <div class="clearfix mt-20 numbers">
                                        </div>
                                    </div>
                                </section>
                            </div>

                            <div class="col s12 m6 m-last l6 l-last mt-30">
                                <section class="section">
                                    <div class="section-content">
                                        <h2 class="page-secondary-title">
                                            <?= __("Post Summary") ?>
                                            <span class="fz-12 color-mid ml-10" style='line-height: 20px;'>(<?= __("Last 30 days") ?>)</span>    
                                        </h2>

                                        <div class="clearfix mt-20">
                                            <div class="statistics-numeric">
                                                <span class="number"><?= readableNumber($PostSummary->inprogress) ?></span>
                                                <span class="label"><?= __("In Progress") ?></span>
                                            </div>

                                            <div class="statistics-numeric">
                                                <span class="number"><?= readableNumber($PostSummary->published + $PostSummary->failed) ?></span>
                                                <span class="label"><?= __("Completed") ?></span>
                                            </div>

                                            <div class="statistics-numeric">
                                                <span class="number"><?= readableNumber($PostSummary->published) ?></span>
                                                <span class="label"><?= __("Published") ?></span>
                                            </div>

                                            <div class="statistics-numeric">
                                                <span class="number"><?= readableNumber($PostSummary->failed) ?></span>
                                                <span class="label"><?= __("Failed") ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>

                        <div class="clearfix">
                            <div class="col s12 m6 l6 mt-30">
                                <section class="section">
                                    <div class="section-content">
                                        <h2 class="page-secondary-title"><?= __("Posts") ?></h2>

                                        <div class="chart-legends clearfix">
                                            <span class="legend">
                                                <span style="background-color: #5596FF"></span>
                                                <?= __("In Progress") ?>
                                            </span>

                                            <span class="legend">
                                                <span style="background-color: #6EAFFF"></span>
                                                <?= __("Published") ?>
                                            </span>

                                            <span class="legend">
                                                <span style="background-color: #88C9FF"></span>
                                                <?= __("Failed") ?>
                                            </span>
                                        </div>

                                        <div class="bar-chart-container chart-container pos-r">
                                            <canvas id="bar-chart"></canvas>
                                        </div>
                                    </div>
                                </section>
                            </div>

                            <div class="col s12 m6 m-last l6 l-last mt-30">
                                <section class="section">
                                    <div class="section-content">
                                        <h2 class="page-secondary-title">
                                            <?= __("Posts") ?>
                                            <span class="fz-12 color-mid ml-10" style='line-height: 20px;'>(<?= __("Last 30 days") ?>)</span> 
                                        </h2>

                                        <div class="chart-legends clearfix">
                                            <span class="legend">
                                                <span style="background-color: #3B7CFF"></span>
                                                <?= __("Completed") ?>
                                            </span>

                                            <span class="legend">
                                                <span style="background-color: #5596FF"></span>
                                                <?= __("In Progress") ?>
                                            </span>

                                            <span class="legend">
                                                <span style="background-color: #6EAFFF"></span>
                                                <?= __("Published") ?>
                                            </span>

                                            <span class="legend">
                                                <span style="background-color: #88C9FF"></span>
                                                <?= __("Failed") ?>
                                            </span>
                                        </div>

                                        <div class="doughnut-chart-container chart-container pos-r">
                                            <canvas id="doughnut-chart"></canvas>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php include APPPATH.'/views/fragments/noaccount.fragment.php'; ?>
                    <?php endif ?>
                </div>
            </div>
        </div>