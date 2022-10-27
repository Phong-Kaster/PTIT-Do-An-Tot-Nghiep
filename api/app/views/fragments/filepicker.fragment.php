        <div class="filepicker">
            <div class="filepicker-inner">
                <div class="section-header clearfix">
                    <h2 class="section-title"><?= __("Upload Media") ?></h2>

                    <div class="section-actions clearfix">
                        <a class="mdi mdi-laptop icon tippy js-fm-filebrowser" 
                           data-size="small"
                           href="javascript:void(0)"
                           title="<?= __("Your PC") ?>"></a>

                        <a class="mdi mdi-link-variant icon tippy js-fm-urlformtoggler" 
                           data-size="small"
                           href="javascript:void(0)"
                           title="<?= __("URL") ?>"></a>
                    </div>
                </div>

                <div class="filepicker-wrp">
                    <div id="filemanager" 
                         data-connector-url="<?= APPURL."/file-manager/connector?keep_original_file=true" ?>"
                         data-page-size="28"
                         data-multiselect="false"
                         data-img-assets-url="<?= APPURL."/assets/img/" ?>"></div>
                </div>

                <div class="filepicker-footer">
                    <a class="small button js-submit" href="javascript:void(0)"><?= __("Submit") ?></a>
                    <a class="small button button--simple js-close" href="javascript:void(0)"><?= __("Cancel") ?></a>
                </div>
            </div>
        </div>