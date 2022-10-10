/**
 * jQuery client for One PHP Filemanager
 * 
 * @date 07 June 2017
 * @version 1.0
 * @copyright Onelab <hello@onelab.co>
 * @author Vusal Orujov (https://github.com/vorujov)
 */

(function($) {
    "use strict";

    var pluginName = "oneFileManager";
    var shortPluginName = "ofm";


    /**
     * Main class
     * @param  {DOM Element} domelement The DOM Element where plugin is applied
     * @param  {Object} options Options passed to the plugin
     */
    var FileManager = function(element, options) {

        // plugin's default options
        var defaults = {
            // URL of backend connector
            connectorUrl: "", // required

            lang: "en",
            width: "auto",
            height: "auto",
            
            // Amount of files to retrieve at one request
            pageSize: 15,
            multiselect: true,
            maxselect: false,
            selectedFileIds: [],

            // Hide notifications after notificationTimer milliseconds
            notificationTimer: 2000,

            // Url to the file manager's internal images directory
            // This is not the url of upload directory
            // It's for internal use only, for ex: ofm-nofile.png file
            imgAssetsUrl: "./assets/img",

            debug: false,

            // Will be fired on (after) init
            onInit: function() {},

            // Will be fired after adding new file data
            // $file: jQuery reference to new file
            onFileAdd: function($file) {},

            // Will be fired after successfully retrieving the files 
            // form the backend 
            // respdata: data value in response object from the server
            onRetrieve: function(respdata) {},

            // Will be fired after browse button clicked
            onDeviceBrowse: function() {},

            // Will be fired when file select toggled
            // $file: jQuery ref. to toggled file
            // selectedFiles: internal object of all selected files
            onFileToggle: function($file, selectedFiles) {},

            // Wll be fired when file selected
            // $file: jQuery ref. to toggled file
            // selectedFiles: internal object of all selected files
            onFileSelect: function($file, selectedFiles) {},

            // Wll be fired when file unselected
            // $file: jQuery ref. to toggled file
            // selectedFiles: internal object of all selected files
            onFileUnselect: function($file, selectedFiles) {},

            // Will be fired after removing the file
            // $file: jQuery ref. to removed filed
            // selectedFiles: internal object of all selected files
            onRemove: function($file, selectedFiles) {},

            // Will be fired after bulk remove
            onBulkRemove: function(ids) {},

            // Will be fired after sending the cmd successfull 
            // (not waiting for response)
            onClearStorage: function() {},

            // Will be fired when any file in upload queue
            // has been uploaded successfully
            // $file: jQuery ref. to new added file
            onUpload: function($file) {},

            // Will be fired just before starting the queued file
            // queued_file: file or string (url)
            onBeforeUpload: function(queued_file) {},

            // Will be fired when URL form opens
            // $form: jQuery ref. to URL input form
            onUrlFormOpen: function($form) {},

            // Will be fired when URL form closes
            // $form: jQuery ref. to URL input form
            onUrlFormClose: function($form) {},

            // Will be fired when URL form toggles
            // $form: jQuery ref. to URL input form
            onUrlFormToggle: function($form) {},

            // Will be fired when new notification msg added
            // msg: Message
            // $notification_box: jQuery ref. to notification box
            onNotificationAdd: function(msg, $notification_box) {},

            // Will be fired when notifications hide
            // $notification_box: jQuery ref. to notification box
            onNotificationHide: function($notification_box) {},

            // Will be fired when double click the $item
            onDoubleClick: function($file) {},

            /**
             * Will be fired on mode change
             * @param  {string} mode mode after change [normal|delete]
             */
            onModeChange: function(mode) {},

            /**
             * Will be fired after showing the infobox
             */
            onInfoboxShow: function() {},


            /**
             * Will be fired after hiding the infobox
             */
            onInfoboxHide: function() {},
        };

        // reference to the current instance of the object
        var plugin = this;

        // reference to the jQuery version of DOM element
        var $element = $(element); 

        // Original inner html of the element
        var original_html = $element.html(); 

        // Notification box
        var $notification_box = $("<div />");

        // URL Iinput form
        var $url_form = $("<div />");

        // Container for files
        var $files_wrp = $("<div />");
        var $files = $("<div />");
        var $loadmore = $("<div />");
            
        // No files 
        var $nofiles = $("<div />");

        // info box (holds detailed info about the storage)
        var $infobox = $("<div />");

        // Dropzone for dragged files
        var $dropzone = $("<div />");

        // Files input to click and upload
        var $files_input = $("<input type='file' name='ofm-files' value='' multiple />");

        // File template
        var $file = $("<div />");



        // internal settings
        var internal = {};
            internal.notification_timer = 0;
            internal.upload_queue = [];
            internal.is_upload_queue_processing = false;
            internal.allow = [];
            internal.deny = [];
            internal.max_file_size = 0;
            internal.last_retrieved = 0;
            internal.queue_size = 0;
            internal.selected_files = {};
            internal.mode = "normal"; // Possible values: normal, delete


        // this will hold the merged default, and user-provided options
        plugin.settings = {};



        /**
         * Set Language
         */
        var Language;
        var _setLanguage = function(langcode)
        {
            Language = $.fn[pluginName].i18n[langcode] || $.fn[pluginName].i18n.en;
        };

        /**
         * Language output
         */
        var __ = function(msgid) 
        {
            return Language[msgid] || msgid;
        };


        /**
         * Validate settins 
         * @return {bool} s
         */
        var _validateSettings = function()
        {
            var valid = true;

            if (!plugin.settings.connectorUrl) {
                _log("Connector URL is required");
                valid = false;
            }

            return valid;
        };


        /**
         * Log data (dev mode)
         * @param  {string} msg 
         * @return {void}     
         */
        var _log = function(msg) {
            if (msg.message && typeof msg.message === "string") {
                plugin.addNotification(msg.message);
            } else if (typeof msg == "string") {
                plugin.addNotification(msg);
            }

            if (plugin.settings.debug) {
                console.log(msg);
            }
        };


        /**
         * Connections to the connector
         * @return {[type]} [description]
         */
        var _get = function(data, success_callback, fail_callback, additional_settings)
        {
            data = data || null;
            success_callback = success_callback || null;
            fail_callback = fail_callback || null;
            additional_settings = additional_settings || {};

            var default_settings = {
                url: plugin.settings.connectorUrl,
                type: 'POST',
                dataType: 'jsonp',
                data: data || {},

                error: function() {
                    _log("Couldn't connect to the connector!");
                },

                success: function(resp) {
                    if (resp.success === true) {
                        if (typeof success_callback == "function") {
                            success_callback(resp.data, resp);
                        }
                    } else {
                        _log(resp);

                        if (typeof fail_callback == "function") {
                            fail_callback(resp.data, resp);
                        }
                    }
                }
            };

            $.ajax($.extend({}, default_settings, additional_settings));
        };


        /**
         * Adds file to the upload queue
         * @param {mixed} file 
         */
        var _addToUploadQueue = function(file, autoselect=false)
        {
            if (internal.queue_size < 1 || 
                internal.upload_queue.length < internal.queue_size) 
            {
                var isok = false;
                if (typeof file === "string") {
                    isok = true;
                } else if (file.size >= 0) {
                    if (internal.max_file_size == null) {
                        isok = true;
                    } else if (internal.max_file_size >= 0 && 
                               file.size <= internal.max_file_size) {
                        isok = true;
                    } else {
                        plugin.addNotification(__("bigFileSizeError"));
                    }
                }

                if (isok) {
                    var $f = $file.clone();
                    $f.addClass('onprogress');
                    $f.find(".ofm-file-ext").remove();
                    $f.find(".ofm-file-icon").remove();

                    $f.data("autoselect", autoselect)

                    $files.prepend($f);

                    internal.upload_queue.push([file, $f]);
                    _processUploadQueue();
                }
            } else {
                plugin.addNotification(__("queueSizeLimit"));
            }

            return plugin;
        };


        var _processUploadQueue = function()
        {
            if (!internal.is_upload_queue_processing && internal.upload_queue.length > 0) {
                internal.is_upload_queue_processing = true;

                var queue = internal.upload_queue[0];
                var queued_file = queue[0];
                var $file_ref = queue[1];

                var data = new FormData();
                    data.append("cmd", "upload");
                    data.append("type", typeof queued_file === "string" ? "url" : "file");
                    data.append("file", queued_file);

                if (typeof plugin.settings.onBeforeUpload === "function") {
                    plugin.settings.onBeforeUpload(queued_file);
                }

                $nofiles.stop(true).hide();

                _get(data, 
                     function(respdata, resp) {
                        // Uploaded successfully
                        plugin.checkNoFiles();

                        respdata.file.autoselect = $file_ref.data("autoselect") ? true : false;
                        var $newfile = plugin.addFile(respdata.file, $file_ref);

                        internal.is_upload_queue_processing = false;
                        internal.upload_queue.shift();
                        _processUploadQueue();

                        if (typeof plugin.settings.onUpload === "function") {
                            plugin.settings.onUpload($newfile);
                        }
                    },

                    function(respdata, resp) {
                        // Failed
                        $file_ref.stop(true).fadeOut(200, function() {
                            $file_ref.remove();
                        });

                        internal.is_upload_queue_processing = false;
                        internal.upload_queue.shift();
                        plugin.checkNoFiles();
                        _processUploadQueue();
                    },

                    {
                        cache: false,
                        contentType: false,
                        processData: false
                    }
                );
            }
        };


        /**
         * Toggle the visibility of the delete message box 
         * depending on the amount of the selected files
         * @return {self} 
         */
        var _deleteMessage = function()
        {
            if (!plugin.isDeleteMode()) {
                return plugin;
            }

            var count = Object.keys(internal.selected_files).length;

            if (count < 1) {
                plugin.hideNotification();
            } else {
                var msg = __("deleteMessage").replace("{count}", count) + " "
                        + "<a href='javascript:void(0)' class='ofm-remove-selection'>"
                        + "<strong>" + __("deleteMessageBtn") + "</strong>"
                        + "</a>";
                plugin.addNotification(msg, false, false);
            }

            return plugin;
        }



        // the "constructor" method that gets called when the object is created
        var _init = function() 
        {
            // the plugin's final properties are the merged default and 
            // user-provided options (if any)
            plugin.settings = $.extend({}, defaults, options, $element.data());

            // Set language
            _setLanguage(plugin.settings.lang);

            // Init html
            _initHtml();
            
            if (_validateSettings()) {
                _notifications();
                _deleteSelection();
                _clearStorage();
                _contextMenu();
                _getInitialData();
                _deviceBrowser();
                _dropzone();
                _urlForm();
                _loadMore();
            }

            if (typeof plugin.settings.onInit === "function") {
                plugin.settings.onInit();
            }
        };

        /**
         * Initialize HTML
         * @return {[type]} [description]
         */
        var _initHtml = function()
        {
            $element.addClass('ofm onprogress')
                    .html("<div class='ofm-progress'></div>");

            if (plugin.settings.width == parseInt(plugin.settings.width, 10)) {
                $element.width(plugin.settings.width);
            }

            if (plugin.settings.height == parseInt(plugin.settings.height, 10)) {
                $element.height(plugin.settings.height);
            }

            // Notification box
            $notification_box.addClass('ofm-notification');
            $notification_box.append("<div></div>");
            $element.append($notification_box);

            // URL input form
            $url_form.addClass('ofm-url-form');
            $url_form.append("<div></div>");
            $url_form.find(">div").append("<input class='ofm-input leftpad' type='text' placeholder='"+__("urlInputPlaceholder")+"'/>");
            $url_form.find(">div").append("<span class='mdi mdi-link-variant ofm-field-icon--left'></span>");
            $element.append($url_form);


            // Files input
            $files_input.hide();
            $element.append($files_input);


            // Files wrapper
            $files_wrp.addClass('ofm-files');
            $files.addClass("ofm-files-inner").appendTo($files_wrp);
            $element.append($files_wrp);

            // Load more
            $loadmore.append("<a href='javascript:void(0)' class='ofm-button'>"+__("loadMoreFiles")+"</a>");
            $loadmore.addClass("ofm-loadmore none").appendTo($files_wrp);

            // Nofiles
            $nofiles.addClass('ofm-nofiles');
            $nofiles.append("<div class='ofm-msgbox'>" + 
                                "<img src='"+plugin.settings.imgAssetsUrl+"/ofm-nofiles.png' alt='' />" +
                                "<p>" +__("emptyVolume")+ "</p>" + 
                                "<a href='javascript:void(0)' class='ofm-button'>" +__("selectFiles")+ "</a>" +
                            "</div>").hide();
            $element.append($nofiles);

            // Info box
            $infobox.addClass('ofm-infobox');
            $infobox.append("<h2 class='title'></h2>");
            $infobox.append("<div class='progress'></div>");
                $infobox.find(".progress").append("<div class='bar'><div class='used'></div></div>");
                $infobox.find(".progress").append("<div class='label'></div>");
                $infobox.find(".progress").append("<a href='javascript:void(0)' class='clear-storage'>"+__("clearStorage")+"</a>");
            $infobox.append("<div class='details'></div>");
                $infobox.find(".details").append(
                    "<div>" +
                        "<span class='name'>"+ __("total") +":</span>" +
                        "<span class='value'>"+ __("xfiles").replace("{count}", "<span class='ofm-infobox-total-files'></span>") +"</span>" +
                    "</div>"
                );
                $infobox.find(".details").append(
                    "<div>" +
                        "<span class='name'>"+ __("maxUploadSize") +":</span>" +
                        "<span class='value ofm-infobox-max-upload-size'></span>" +
                    "</div>"
                );
            $element.append($infobox);


            // Dropzone
            $dropzone.addClass('ofm-dropzone');
            $dropzone.append("<div><div class='ofm-msgbox'>" + 
                                "<span class='icon sli sli-cloud-upload'></span>" +
                                "<p>" +__("dropFilesHere")+ "</p>" + 
                            "</div></div>");
            $element.append($dropzone);


            // File element template
            $file.addClass('ofm-file');
            $file.append("<div></div>");
            $file.find(">div").append("<div class='ofm-file-preview'></div>");
            $file.find(">div").append("<span class='ofm-file-ext'></span>");
            $file.find(">div").append("<span class='ofm-file-icon'></span>");
            $file.find(">div").append("<a href='javascript:void(0)' class='ofm-file-toggle mdi mdi-check'></a>");
            var $file_context_menu = $("<div />");
                $file_context_menu.appendTo($file.find(">div"));
                $file_context_menu.addClass("ofm-context-menu-wrapper");
                $file_context_menu.append("<a href='javascript:void(0)' class='toggler mdi mdi-dots-vertical'></a>");
                $file_context_menu.append("<div class='ofm-context-menu'></div>");
                $file_context_menu.find(".ofm-context-menu").append("<ul></ul>");
                $file_context_menu.find("ul").append("<li><a class='js-view-file' href='javascript:void(0)' target='_blank'>"+__("viewFile")+"</a></li>");
                $file_context_menu.find("ul").append("<li><a class='js-delete-file' href='javascript:void(0)'>"+__("deleteFile")+"</a></li>");
        };


        /**
         * Init notifications
         * @return {self} 
         */
        var _notifications = function()
        {
            $notification_box.on("click", function() {
                if ($(this).hasClass('hideonclick')) {
                    plugin.hideNotification();
                }
            });

            return plugin;
        };


        /**
         * Bind a click event to the remove selection button (.ofm-remove-selection)
         *
         * Display confirmation message, 
         * and send bulkRemove (bulk remove) request
         * @return {self} 
         */
        var _deleteSelection = function()
        {
            $element.on("click", ".ofm-remove-selection", function() {
                if (!confirm(__("removeSelectionConfirmation"))) {
                    return false;
                }

                var ids = Object.keys(plugin.getSelection());
                plugin.bulkRemove(ids);
            })
        }


        var _clearStorage = function()
        {
            $element.on("click", ".clear-storage", function() {
                if (!confirm(__("clearStorageConfirm"))) {
                    return false;
                }

                $element.addClass('onprogress');

                var data = {};
                    data.cmd = "clearStorage";
                _get(data, function(respdata, resp) {
                    $files.find(".ofm-file").stop(true).fadeOut(200, function() {
                        $(this).remove();
                        plugin.checkNoFiles();
                    });
                    
                    $loadmore.removeClass('onprogress').addClass('none');
                    plugin.showInfobox();

                    $element.removeClass('onprogress');
                });

                if (typeof plugin.settings.onClearStorage === "function") {
                    plugin.settings.onClearStorage();
                }

                return plugin;
            });
        }


        /**
         * Initialize file picker
         * @return {self} 
         */
        var _deviceBrowser = function()
        {
            $nofiles.find("a").on("click", function() {
                $files_input.trigger("click");
            });

            $files_input.on("change", function() {
                plugin.upload($(this)[0].files);
            });
            
            return plugin;
        };


        /**
         * Init dropzone
         * @return {self}
         */
        var _dropzone = function() 
        {
            $("body")
            .on('drag dragstart dragend dragover dragenter dragleave drop', function(e){
                if (!$(e.target).hasClass('ofm-file')) {
                    // preventing the unwanted behaviours
                    e.preventDefault();
                    e.stopPropagation();
                }
            })
            .on('dragover dragenter', function() {
                $element.addClass('ofm-dragover');
            })
            .on('dragleave dragend drop', function() {
                $element.removeClass('ofm-dragover');
            });
            
            $element.on('drop', function(e) {
                plugin.upload(e.originalEvent.dataTransfer.files);
            });

            return plugin;
        };

        /**
         * URL input form to upload from URL
         * @return {self}
         */
        var _urlForm = function()
        {
            var $url_input = $url_form.find(".ofm-input");

            $url_input
            .on("input", function() {
                $(this).removeClass('error');
            }).on("keyup keydown keypress", function(e) {
                if (e.keyCode == 13) {
                    if ($url_input.val()) {
                        plugin.upload($url_input.val());
                        $url_input.val("");
                        plugin.closeUrlForm();
                    } else {
                        $url_input.addClass('error');
                    }

                    e.stopPropagation();
                    return false;
                }
            });

            return plugin;
        };
      

        /**
         * Init load more button
         * @return {self}
         */
        var _loadMore = function()
        {
            $loadmore.find("a").on("click", function() {
                if (!$loadmore.hasClass('onprogress')) {
                    $loadmore.addClass('onprogress');
                    plugin.retreiveFiles(internal.last_retrieved, plugin.settings.pageSize);
                }
            });

            return plugin;
        };


        /**
         * Context menus
         * @return {self} 
         */
        var _contextMenu = function()
        {
            $("body").on("click", ".ofm-context-menu-wrapper", function(event){
                var menu = $(this).find(".ofm-context-menu");

                $(".ofm-context-menu").not(menu).removeClass('active');
                menu.toggleClass("active");
                event.stopPropagation();
            });

            $(window).on("click", function() {
                $(".ofm-context-menu.active").removeClass("active");
            });

            $("body").on("click", ".ofm-context-menu", function(event) {
                event.stopPropagation();
            });
        };



        /* == BACKEND CONNECTIONS == */
        /**
         * Get initial data
         * @return {self} 
         */
        var _getInitialData = function()
        {
            var data = {};
                data.cmd = "init";

            _get(data, function(respdata) {
                internal.allow = respdata.allow;
                internal.deny = respdata.deny;
                internal.max_file_size = respdata.max_file_size;
                internal.queue_size = respdata.queue_size;

                if (internal.allow && internal.allow.length > 0) {
                    var exts = [];
                    for (var i = 0; i < internal.allow.length; i++) {
                        exts.push("."+internal.allow[i]);
                    }
                    $files_input.attr("accept", exts.join(","));
                }

                $element.removeClass('onprogress');
                if (respdata.hasmore) {
                    $loadmore.find("a").trigger("click");
                } else {
                    plugin.checkNoFiles();
                }
            });

            return plugin;
        };


        /* == PUBLIC METHODS == */
        /**
         * Destroy the plugin
         */
        plugin.destroy = function()
        {
            $element.removeClass('ofm');
            $element.html(original_html);
            $element.removeData('ofm');

            return $element;
        };

        /**
         * Set option after initialization
         * @param {string} name  
         * @param {mixed} value 
         */
        plugin.setOption = function(name, value) {
            plugin.settings[name] = value;
            _validateSettings();
        };

        /**
         * Open device's default browser 
         * @return {self} 
         */
        plugin.browseDevice = function() 
        {
            $files_input.trigger("click");

            if (typeof plugin.settings.onDeviceBrowse === "function") {
                plugin.settings.onDeviceBrowse();
            }
            return plugin;
        };


        /**
         * Check the number of retreived files and toggles nofiles 
         * @return {self}
         */
        plugin.checkNoFiles = function()
        {
            if ($files.find(".ofm-file").length < 1) {
                if ($loadmore.hasClass('none')) {
                    $nofiles.stop(true).fadeIn(500);
                } else {
                    $loadmore.find("a").trigger("click");
                }
            } else {
                $nofiles.stop(true).hide();
            }

            return plugin;
        };


        /**
         * Retrieve files from backend
         * @param  {integer} last_retrieved Id of the lastest loaded file id. 
         * @param  {integer} limit 
         * @return {self}        
         */
        plugin.retreiveFiles = function(last_retrieved, limit) {
            var data = {};
                data.cmd = "retrieve";
                data.last_retrieved = last_retrieved;
                data.limit = limit;

            _get(data, function(respdata, resp) {
                internal.last_retrieved = respdata.last_retrieved;

                plugin.addFiles(respdata.files);
                plugin.checkNoFiles();

                $loadmore.removeClass('onprogress');
                if (respdata.hasmore) {
                    $loadmore.removeClass('none');
                } else {
                    $loadmore.addClass('none');
                }

                if (typeof plugin.settings.onRetrieve === "function") {
                    plugin.settings.onRetrieve(respdata);
                }
            });

            return plugin;
        };


        /**
         * Add files or URL to upload queue and start to processing the queue
         * @param  {string|FileList} files 
         * @return {self}       
         */
        plugin.upload = function(files, autoselect = false)
        {
            if (typeof files === "string") {
                _addToUploadQueue(files, autoselect);
            } else if (files.length) {
                for (var i = 0; i < files.length; i++) {
                    _addToUploadQueue(files[i], autoselect);
                }
            }

            return plugin;
        };


        /**
         * Remove file
         * @param  {id} id [description]
         * @return {[type]}    [description]
         */
        plugin.removeFile = function($_file) {
            if (confirm(__("deleteFileConfirm"))) {
                $_file.addClass('onprogress');
                var file = $_file.data("file");

                var data = {};
                    data.cmd = "remove";
                    data.id = file.id;

                _get(data, function(respdata, resp) {
                    plugin.unselectFile($_file);

                    $_file.stop(true).fadeOut(200, function() {
                        $_file.remove();
                        plugin.checkNoFiles();
                    });

                    if (typeof plugin.settings.onRemove === "function") {
                        plugin.settings.onRemove($_file, internal.selected_files);
                    }
                });
            }

            return plugin;
        };


        /**
         * Bulk remove
         * @param  {Array} ids An array of the id of the files to remove
         * @return {self}     
         */
        plugin.bulkRemove = function(ids)
        {
            var valid_ids = [];
            for (var i = 0; i < ids.length; i++) {
                var id = parseInt(ids[i], 10);
                if (id < 1) {
                    continue;
                }

                var $file = $files.find(".ofm-file[data-id='"+id+"']");

                if ($file.length != 1) {
                    continue;
                }

                $file.addClass('onprogress js-bulk-removing');
                valid_ids.push(id);
            }

            if (valid_ids.length < 1) {
                return plugin;
            }

            var data = {};
                data.cmd = "bulkRemove";
                data.ids = valid_ids;


            _get(data, function(respdata, resp) {
                plugin.unselectAll();

                $files.find(".js-bulk-removing").stop(true).fadeOut(200, function() {
                    $(this).remove();
                    plugin.checkNoFiles();
                });

                if (typeof plugin.settings.onBulkRemove === "function") {
                    plugin.settings.onBulkRemove(valid_ids);
                }
            });


            return plugin;
        }


        /**
         * Add new files
         * @param  {array} files Array of files data
         * @return {self}       
         */
        plugin.addFiles = function(files) {
            for (var i = 0; i < files.length; i++) {
                plugin.addFile(files[i]);
            }

            return plugin;
        };


        /**
         * Add single file
         * @param  {obj} file file data object
         * @param  {$} jQuery ref. to existed file element, 
         *             usefull to replace file placeholders
         *             after upload
         * @return {$}   jQuery ref. to new added file;
         */
        plugin.addFile = function(file, $replacewith) 
        {
            $replacewith = $replacewith || null;
            var $f = $file.clone();

            if (["jpg", "jpeg", "png"].indexOf(file.ext) >= 0) {
                $f.find(".ofm-file-preview").css("background-image", "url("+file.url+")");
            } else if (["mp4"].indexOf(file.ext) >= 0) {
                $f.find(".ofm-file-preview").append("<video src='"+file.url+"' playsinline muted loop>");

                $f.find("video").on("loadedmetadata", function() {
                    if (this.videoWidth >= this.videoHeight) {
                        $f.find("video").css({
                            "height" : "100%",
                            "width" : "auto"
                        });
                    } else {
                        $f.find("video").css({
                            "width" : "100%",
                            "height" : "auto"
                        });
                    }
                });
            }

            if (file.ext) {
                $f.find(".ofm-file-ext").text(file.ext);
            } else {
                $f.find(".ofm-file-ext").remove();
            }

            if (file.icon) {
                $f.find(".ofm-file-icon").addClass(file.icon);
            } else {
                $f.find(".ofm-file-icon").remove();
            }

            $f.find(".js-view-file").attr("href", file.url);
            $f.data("file", file);
            $f.attr("data-id", file.id);

            $f.find(".ofm-file-toggle").on("click", function() {
                plugin.toggleFile($f);
            });

            $f.find(".js-delete-file").on("click", function() {
                plugin.removeFile($f);
            });

            $f.hide();
            if ($replacewith) {
                $replacewith.replaceWith($f);
            } else {
                $files.append($f);
            }
            $f.fadeIn(500);

            if (typeof plugin.settings.onFileAdd === "function") {
                plugin.settings.onFileAdd($f);
            }

            if (plugin.settings.selectedFileIds.indexOf(parseInt(file.id, 10)) >= 0) {
                plugin.selectFile($f);
            }

            if (file.autoselect) {
                plugin.selectFile($f);   
            }

            $f.find(".ofm-file-preview").on("dblclick", function() {
                if (typeof plugin.settings.onDoubleClick === "function") {
                    plugin.settings.onDoubleClick($f);
                }
            });

            return $f;
        };

        /**
         * Select file
         * @param  {$} $_file jQuery ref. to file dom element
         * @return {self}       
         */
        plugin.selectFile = function($_file)
        {
            if (!plugin.settings.multiselect && !plugin.isDeleteMode()) {
                /**
                 * Unselect all files if delete mode is not active 
                 * and multiselect is not true
                 *
                 * There is not maxselect value for the delete mode
                 */
                plugin.unselectAll();
            }

            if (plugin.settings.maxselect === false || 
                Object.keys(internal.selected_files).length < plugin.settings.maxselect ||
                plugin.isDeleteMode()) {

                if ($_file == parseInt($_file, 10)) {
                    var id = $_file;
                    $_file = null;
                    $files.find(".ofm-file").each(function() {
                        if (typeof $(this).data("file") !== "undefined" && $(this).data("file").id == id) {
                            $_file = $(this);
                            return 0;
                        }
                    });
                }

                if ($_file) {
                    var file = $_file.data("file");

                    if (!$_file.hasClass('selected')) {
                        $_file.addClass('selected');

                        internal.selected_files[file.id.toString()] = file;
                        _deleteMessage();

                        if (typeof plugin.settings.onFileSelect === "function") {
                            plugin.settings.onFileSelect($_file, internal.selected_files);
                        }

                        if (typeof plugin.settings.onFileToggle === "function") {
                            plugin.settings.onFileToggle($_file, internal.selected_files);
                        }
                    }
                }
            }

            return plugin;
        };

        /**
         * UnSelect file
         * @param  {$} $_file jQuery ref. to file dom element
         * @return {self}       
         */
        plugin.unselectFile = function($_file)
        {
            if ($_file == parseInt($_file, 10)) {
                var id = $_file;
                $_file = null;
                $files.find(".ofm-file").each(function() {
                    if (typeof $(this).data("file") !== "undefined" && $(this).data("file").id == id) {
                        $_file = $(this);
                        return 0;
                    }
                });
            }

            if ($_file) {
                var file = $_file.data("file");

                if ($_file.hasClass('selected')) {
                    $_file.removeClass('selected');

                    delete internal.selected_files[file.id.toString()];
                    _deleteMessage();

                    if (typeof plugin.settings.onFileUnselect === "function") {
                        plugin.settings.onFileUnselect($_file, internal.selected_files);
                    }

                    if (typeof plugin.settings.onFileToggle === "function") {
                        plugin.settings.onFileToggle($_file, internal.selected_files);
                    }
                }
            }

            return plugin;
        };

        /**
         * Toggle (select-unselect) file
         * @param  {$} $_file jQuery ref. to file dom element
         * @return {self}       
         */
        plugin.toggleFile = function($_file)
        {
            if ($_file == parseInt($_file, 10)) {
                var id = $_file;
                $_file = null;
                $files.find(".ofm-file").each(function() {
                    if (typeof $(this).data("file") !== "undefined" && $(this).data("file").id == id) {
                        $_file = $(this);
                        return 0;
                    }
                });
            }

            if ($_file) {
                if ($_file.hasClass('selected')) {
                    plugin.unselectFile($_file);
                } else {
                    plugin.selectFile($_file);
                }
            }

            return plugin;
        };


        /**
         * Unselect all files
         * @return {[type]} [description]
         */
        plugin.unselectAll = function()
        {
            $files.find(".ofm-file").each(function() {
                plugin.unselectFile($(this));
            });
        }


        /**
         * Open Url Form (form to input URL for uploading)
         * @return {self} 
         */
        plugin.openUrlForm = function()
        {
            if (!$element.hasClass('url-form-open')) {
                plugin.hideInfobox();
                $element.addClass('url-form-open').removeClass('notification-open');

                $url_form.find(".ofm-input").trigger("focus");

                if (typeof plugin.settings.onUrlFormOpen === "function") {
                    plugin.settings.onUrlFormOpen($url_form);
                }

                if (typeof plugin.settings.onUrlFormToggle === "function") {
                    plugin.settings.onUrlFormToggle($url_form);
                }
            }

            return plugin;
        };


        /**
         * Close Url Form (form to input URL for uploading)
         * @return {self} 
         */
        plugin.closeUrlForm = function()
        {
            if ($element.hasClass('url-form-open')) {
                $element.removeClass('url-form-open');
                $url_form.find(".ofm-input").trigger("blur");

                // Check delete message
                _deleteMessage();

                if (typeof plugin.settings.onUrlFormClose === "function") {
                    plugin.settings.onUrlFormClose($url_form);
                }

                if (typeof plugin.settings.onUrlFormToggle === "function") {
                    plugin.settings.onUrlFormToggle($url_form);
                }
            }

            return plugin;
        };

        /**
         * Toggle Url Form (form to input URL for uploading)
         * @return {self} 
         */
        plugin.toggleUrlForm = function()
        {
            if ($element.hasClass('url-form-open')) {
                plugin.closeUrlForm();
            } else {
                plugin.openUrlForm();
            }

            return plugin;
        };


        /**
         * Add notification
         * @return {self} 
         */
        plugin.addNotification = function(msg, autohide, hideonclick)
        {
            autohide = typeof autohide == "undefined" ? true : autohide;
            hideonclick = typeof hideonclick == "undefined" ? true : hideonclick;

            $notification_box.find(">div").html(msg);
            clearTimeout(internal.notification_timer);

            if (hideonclick) {
                $notification_box.addClass("hideonclick");
            } else {
                $notification_box.removeClass("hideonclick");
            }

            $element.addClass('notification-open').removeClass('url-form-open onprogress');

            if (autohide) {
                internal.notification_timer = setTimeout(
                    plugin.hideNotification, 
                    plugin.settings.notificationTimer);
            }

            if (typeof plugin.settings.onNotificationAdd === "function") {
                plugin.settings.onNotificationAdd(msg, $notification_box);
            }

            return plugin;
        };


        /**
         * Hide notification
         * @return {self} 
         */
        plugin.hideNotification = function()
        {
            if ($element.hasClass('notification-open')) {
                $element.removeClass('notification-open');
                clearTimeout(internal.notification_timer);

                // Check delete message
                _deleteMessage();

                if (typeof plugin.settings.onNotificationHide === "function") {
                    plugin.settings.onNotificationHide($notification_box);
                }
            }

            return plugin;
        };


        /**
         * Get selected files
         * @return {array} An array of selected files
         */
        plugin.getSelection = function()
        {
            return internal.selected_files;
        }


        /**
         * Set new mode [normal|deleter]
         * @param {string} mode 
         * @return self
         */
        plugin.setMode = function(mode)
        {
            if (typeof mode === "string") {
                mode = mode.toLowerCase();
            }

            if (["normal", "delete"].indexOf(mode) < 0) {
                mode = "normal";
            }

            var mode_changed = false;
            if (internal.mode != mode) {
                mode_changed = true;
            }

            if (!mode_changed) {
                return plugin;
            }

            $element.removeClass(function(index, classattr) {
                return (classattr.match (/\ofm-mode-\S+/g) || []).join(' ');
            });
            $element.addClass("ofm-mode-"+mode);

            // Remove all selected files on mode change
            plugin.unselectAll();

            // change mode
            internal.mode = mode;

            if (typeof plugin.settings.onModeChange === "function") {
                plugin.settings.onModeChange(mode);
            }

            return plugin;
        }


        /**
         * Get current mode
         * @return {string} 
         */
        plugin.getMode = function()
        {
            return internal.mode;
        }


        /**
         * Check if delete mode is active
         * @return {bool} true if delete mode is active, otherwise false
         */
        plugin.isDeleteMode = function()
        {
            return internal.mode == "delete";
        }


        /**
         * Get live storage info and show info box
         * @return {self} 
         */
        plugin.showInfobox = function()
        {
            var data = {};
                data.cmd = "info";

            $element.addClass('onprogress');
            _get(data, function(respdata) {
                if (respdata.remaining_storage === false) {
                    $infobox.find(".title").html(__("unlimitedStorage"))
                } else {
                    $infobox.find(".title").html(respdata.remaining_storage_readable + "<span>"+__("remainingStorage")+"</span>")
                }

                var used_percent = "1";
                if (respdata.total_storage > 0) {
                    used_percent = respdata.used_storage / respdata.total_storage * 100;
                }
                $infobox.find(".progress .used").css("width", used_percent+"%");
                

                if (respdata.total_storage) {
                    $infobox.find(".progress .label").html(
                        __("usedXofY").replace("{used}", "<span>"+respdata.used_storage_readable+"</span>")
                                      .replace("{total}", "<span>"+respdata.total_storage_readable+"</span>"));
                } else {
                    $infobox.find(".progress .label").html(
                        __("usedX").replace("{used}", respdata.used_storage_readable));
                }

                $infobox.find(".ofm-infobox-total-files").text(respdata.total_files);
                $infobox.find(".ofm-infobox-max-upload-size").text(respdata.max_file_size_readable);

                if (respdata.total_files > 0 || respdata.used_storage > 0) {
                    $infobox.find(".clear-storage").show();
                } else {
                    $infobox.find(".clear-storage").hide();
                }

                $element.removeClass('onprogress');
                $element.addClass('infobox-open');

                if (typeof plugin.settings.onInfoboxShow === "function") {
                    plugin.settings.onInfoboxShow();
                }
            });

            return plugin;
        }

        /**
         * Hide infobox
         * @return {self} 
         */
        plugin.hideInfobox = function()
        {
            $element.removeClass('infobox-open');

            if (typeof plugin.settings.onInfoboxHide === "function") {
                plugin.settings.onInfoboxHide();
            }

            return plugin;
        }


        // fire up the plugin!
        // call the "constructor" method
        _init();
    };


    // add the plugin to the jQuery.fn object
    $.fn[pluginName] = function(options) {
        var args = arguments;

        // iterate through the DOM elements we are attaching the plugin to
        return this.each(function() {
            if (options === undefined || typeof options === "object") {
                // if plugin has not already been attached to the element
                if (undefined === $(this).data(shortPluginName)) {

                    // create a new instance of the plugin
                    // pass the DOM element and the user-provided options as arguments
                    var plugin = new FileManager(this, options);

                    // in the jQuery version of the element
                    // store a reference to the plugin object
                    $(this).data(shortPluginName, plugin);
                }
            } else if (typeof options == "string" && options.charAt(0) !== '_') {
                var instance = $(this).data(shortPluginName);

                if (instance instanceof FileManager && typeof instance[options] ===  "function") {
                    instance[options].apply(instance, Array.prototype.slice.call(args, 1));
                }
            }
        });
    };


    $.fn[pluginName].Constructor = FileManager;
    $.fn[pluginName].i18n = {};
    $.fn[pluginName].i18n.en = {
        selectFiles: "Select files",
        loadMoreFiles: "Load more",
        viewFile: "View",
        deleteFile: "Delete",
        urlInputPlaceholder: "Paste your link here...",

        emptyVolume: "Your media volume is empty. <br /> Drag some files here.",
        dropFilesHere: "Drop files here to upload",
        deleteFileConfirm: "This file and all uncompleted posts which this file is assigned to will be removed. This process cannot be undone. Do you want to proceed?",
        bigFileSizeError: "File size exceeds max allowed file size.",
        fileTypeError: "File type is not allowed.",
        noEnoughVolumeError: "There is not enough storage to upload this file",
        queueSizeLimit: "There are so many files in upload queue. Please try again after current upload queue finishes.",
        deleteMessage: "{count} files selected.",
        deleteMessageBtn: "Remove?",
        removeSelectionConfirmation: "Do you want to remove the selected files? This process can not be undone.",
        usedXofY: "Used {used} of {total}",
        usedX: "Used {used}",
        total: "Total",
        xfiles: "{count} files",
        remainingStorage: "Remaining storage",
        maxUploadSize: "Max. file size to upload",
        unlimitedStorage: "Unlimited storage",
        clearStorage: "Clear storage",
        clearStorageConfirm: "Do you really want to remove all of your files? Your scheduled posts will not be published. This action not be undone."
    };
})(jQuery);


// New language
/*$.fn.oneFileManager.i18n["langcode"] = {
    foo: "bar"
};*/