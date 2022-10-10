            <form class="js-ajax-form" 
                  action="<?= APPURL . "/settings/" . $page ?>"
                  method="POST">
                <input type="hidden" name="action" value="save">

                <div class="section-header clearfix">
                    <h2 class="section-title"><?= __("Experimental Features") ?></h2>
                    <div class="section-actions clearfix hide-on-large-only">
                        <a class="mdi mdi-menu-down icon js-settings-menu" href="javascript:void(0)"></a>
                    </div>
                </div>

                <div class="section-content">
                    <div class="clearfix">
                        <div class="col s12 m6 l5">
                            <p class="color-danger mb-40 fz-12">Following features are experimental. They aren't quite ready for primetime which means some bugs are possible. These features may <strong>change</strong>, <strong>break</strong> or <strong>disappear</strong> at any time.</p>

                            <div class="form-result"></div>

                            <div class="mb-40">
                                <label>
                                    <input type="checkbox" 
                                           class="checkbox" 
                                           name="video-processing" 
                                           value="1" 
                                           <?= get_option("np_video_processing") ? "checked" : "" ?>>
                                    <span>
                                        <span class="icon unchecked">
                                            <span class="mdi mdi-check"></span>
                                        </span>
                                        <?= __('Video processing') ?>
                                    </span>
                                </label>

                                <ul class="field-tips">
                                    <li>Allows to upload video files with following new formats: mov, m4v, mpg</li>
                                    <li>Does some resize/clib operations during the upload process</li>
                                    <li>Requires FFMPEG/FFPROBE</li>
                                    <li>Uses to many server resources. Video processing might take too many times and RAM/CPU overload.</li>
                                </ul>
                            </div>

                            <div class="mb-40">
                                <label>
                                    <input type="checkbox" 
                                           class="checkbox" 
                                           name="search-in-caption" 
                                           value="1" 
                                           <?= get_option("np_search_in_caption") ? "checked" : "" ?>>
                                    <span>
                                        <span class="icon unchecked">
                                            <span class="mdi mdi-check"></span>
                                        </span>
                                        <?= __('Search in post caption and first comment') ?>
                                    </span>
                                </label>

                                <ul class="field-tips">
                                    <li>Allows to search for hashtags and users directly in caption and first comment inputs in the <a href='<?= APPURL."/post" ?>'>post page</a></li>
                                    <li>BUG: Doesn't work properly when an emoji used before the search text</li>
                                </ul>
                            </div>

                            <input class="fluid button" type="submit" value="<?= __("Save") ?>">
                        </div>
                    </div>
                </div>
            </form>