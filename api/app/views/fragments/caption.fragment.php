        <div class='skeleton' id="caption">
            <form class="js-ajax-form" 
                  action="<?= APPURL . "/captions/" . ($Caption->isAvailable() ? $Caption->get("id") : "new") ?>"
                  method="POST">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="caption" value="<?= htmlchars($Caption->get("caption")) ?>">

                <div class="container-1200">
                    <div class="row clearfix">
                        <div class="col s12 m8 l4">
                            <section class="section">
                                <div class="section-content">
                                    <div class="form-result"></div>

                                    <div class="mb-20">
                                        <label class="form-label">
                                            <?= __("Title") ?>
                                            <span class="compulsory-field-indicator">*</span>    
                                        </label>

                                        <input class="input js-required"
                                               name="title" 
                                               type="text" 
                                               value="<?= htmlchars($Caption->get("title")) ?>" 
                                               placeholder="<?= __("Enter caption title") ?>">
                                    </div>

                                    <div>
                                        <label class="form-label"><?= __("Caption") ?></label>

                                        <div class="mb-20 pos-r">
                                            <div class="caption-input input" 
                                                 data-placeholder="<?= __("Write a caption") ?>"
                                                 contenteditable="true"><?= htmlchars($Caption->get("caption")) ?></div>
                                        </div>
                                    </div>
                                </div>

                                <input class="fluid button button--footer" type="submit" value="<?= __("Save caption") ?>">
                            </section>
                        </div>
                    </div>
                </div>
            </form>
        </div>