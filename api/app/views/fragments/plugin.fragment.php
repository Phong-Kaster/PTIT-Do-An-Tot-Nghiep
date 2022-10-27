        <div class='skeleton' id="plugin">
            <?php if (empty($InstallResult)): ?>
                <form action="<?= APPURL . "/plugins/install" ?>" method="POST">
                    <input type="hidden" name="action" value="upload">

                    <div class="container-1200">
                        <div class="row clearfix">
                            <div class="col s12 m6 l4">
                                <section class="section">
                                    <div class="section-content">
                                        <div class="form-result"></div>

                                        <div class="mb-20">
                                            <ul class="field-tips">
                                                <li><?= __('Choose your module and click "Install" button') ?></li>
                                            </ul>
                                        </div>

                                        <div>
                                            <label>
                                                <input class="fileinp" name="file" 
                                                       data-label="<?= __("Choose Zip Archive") ?>" 
                                                       type="file" value="">
                                                <div>
                                                    <?= __("Choose Zip Archive") ?>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <input class="fluid button button--footer" type="submit" value="<?= __("Install") ?>">
                                </section>
                            </div>
                        </div>
                    </div>
                </form>
            <?php else: ?>
                <div class="minipage">
                    <?php if ($InstallResult->resp == 1): ?>
                        <div class="inner">
                            <h1 class="page-primary-title"><?= __('Success!') ?></h1>
                            
                            <p><?= __("Module has been installed successfully!") ?></p>

                            <a href="<?= APPURL."/plugins" ?>" class="small button"><?= __("View Modules") ?></a>
                        </div>
                    <?php else: ?>
                        <div class="inner">
                            <h1 class="page-primary-title"><?= __('Error!') ?></h1>
                            <p><?= __('An error occured during the installation process! Please try again later!') ?></p>

                            <div class="system-error">
                                <?= $InstallResult->msg ?>
                            </div>

                            <a href="<?= APPURL."/plugins/install" ?>" class="small button"><?= __('Try Again') ?></a>
                        </div>
                    <?php endif ?>
                </div>
            <?php endif ?>
        </div>