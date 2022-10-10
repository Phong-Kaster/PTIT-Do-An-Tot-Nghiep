<?php 
/**
 * Get active theme path.
 * 
 * @deprecated since v4.1. Use active_theme($param) instead. See ./theme.helper.php
 * @return string 
 */
function active_theme_path()
{
    return THEMES_PATH . "/"
                       . (site_settings("theme") ? site_settings("theme") 
                                                 : "default");
}


/**
 * Get active theme url
 * @deprecated since v4.1. Use active_theme($param) instead. See ./theme.helper.php
 * @return string 
 */
function active_theme_url()
{
    return THEMES_URL . "/"
                      . (site_settings("theme") ? site_settings("theme") 
                                                : "default");
}
