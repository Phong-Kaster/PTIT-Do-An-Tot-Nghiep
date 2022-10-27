$(function() {
    NextPost.General();
    NextPost.Skeleton();
    NextPost.ContextMenu();
    NextPost.Tooltip();
    NextPost.Tabs();
    NextPost.Forms();
    NextPost.FileManager();
    NextPost.LoadMore();
    NextPost.Popups();

    NextPost.RemoveListItem();
    NextPost.AsideList();
});


/**
 * NextPost Namespace
 */
var NextPost = {};
    
/**
 * General
 */
NextPost.General = function()
{   
    // Mobile menu
    $(".topbar-mobile-menu-icon").on("click", function() {
        $("body").toggleClass('mobile-menu-open');
    });


    // Pop State
    window.onpopstate = function(e){ 
        if(e.state) {
            window.location.reload(); 
        }
    }
}


/**
 * Main skeleton
 */
NextPost.Skeleton = function()
{
    if ($(".skeleton--full").length > 0) {
        var $elems = $(".skeleton--full").find(".skeleton-aside, .skeleton-content");
        $(window).on("resize", function() {
            var h = $(window).height() - $("#topbar").outerHeight();
            $elems.height(h);
        }).trigger("resize");

        $(".skeleton--full").show();
    }
}



/**
 * Context Menu
 */
NextPost.ContextMenu = function()
{
    $("body").on("click", ".context-menu-wrapper", function(event){
        var menu = $(this).find(".context-menu");

        $(".context-menu").not(menu).removeClass('active');
        menu.toggleClass("active");
        event.stopPropagation();
    });

    $(window).on("click", function() {
        $(".context-menu.active").removeClass("active");
    });

    $("body").on("click", ".context-menu", function(event) {
        event.stopPropagation();
    })
}

/**
 * ToolTips
 */
NextPost.Tooltip = function()
{
    $(".tippy").each(function() {
        var dom = $(this)[0];

        if ($(this).hasClass("js-tooltip-ready")) {
            var tip = $(this).data("tip");
            var popper = tip.getPopperElement(dom);

            tip.update(popper);
        } else {
            var tip = Tippy(dom);
            $(this).addClass("js-tooltip-ready");
            $(this).data("tip", tip);
        }
    });
}


/**
 * Tabs
 */
NextPost.Tabs = function()
{
    $("body").on("click", ".tabheads a", function() {
        var tab = $(this).data("tab");
        var $tabs = $(this).parents(".tabs");
        var $contents = $tabs.find(".tabcontents");
        var $content = $contents.find(">div[data-tab='"+tab+"']");

        if ($content.length != 1 || $(this).hasClass("active")) {
            return true;
        }

        $(this).parents(".tabheads").find("a").removeClass('active');
        $(this).addClass("active");

        $contents.find(">div").removeClass('active');
        $content.addClass('active');
    });
}


/**
 * General form functions
 */
NextPost.Forms = function()
{
    $("body").on("input focus", ":input", function() {
        $(this).removeClass("error");
    });

    $("body").on("change", ".fileinp", function(){
        if ($(this).val()) {
            var label = $(this).val().split('/').pop().split('\\').pop();
        } else {
            var label = $(this).data("label")
        }
        $(this).next("div").text(label).attr("title", label);
        $(this).removeClass('error');
    });

    NextPost.DatePicker();
    NextPost.Combobox();
    NextPost.AjaxForms();
}


/**
 * Combobox
 */
NextPost.Combobox = function()
{
    $("body").on("click", ".select2", function() {
        $(this).removeClass("error");
    });
    
    $(".combobox").select2({})
}


/**
 * Date time pickers
 */
NextPost.DatePicker = function()
{
    $(".js-datepicker").each(function() {
        $(this).removeClass("js-datepicker");

        if ($(this).data("min-date")) {
            $(this).data("min-date", new Date($(this).data("min-date")))
        }

        if ($(this).data("start-date")) {
            $(this).data("start-date", new Date($(this).data("start-date")))
        }

        $(this).datepicker({
            language: $("html").attr("lang"),
            dateFormat: "yyyy-mm-dd",
            timeFormat: "hh:ii",
            autoClose: true,
            timepicker: true,
            toggleSelected: false
        });
    })
}


/**
 * Add msg to the $resobj and displays it 
 * and scrolls to $resobj
 * @param {$ DOM} $form jQuery ref to form
 * @param {string} type
 * @param {string} msg
 */
var __form_result_timer = null;
NextPost.DisplayFormResult = function($form, type, msg)
{
    var $resobj = $form.find(".form-result");
    msg = msg || "";
    type = type || "error";

    if ($resobj.length != 1) {
        return false;
    }


    var $reshtml = "";
    switch (type) {
        case "error":
            $reshtml = "<div class='error'><span class='sli sli-close icon'></span> "+msg+"</div>";
            break;

        case "success":
            $reshtml = "<div class='success'><span class='sli sli-check icon'></span> "+msg+"</div>";
            break;

        case "info":
            $reshtml = "<div class='info'><span class='sli sli-info icon'></span> "+msg+"</div>";
            break;
    }

    $resobj.html($reshtml).stop(true).fadeIn();

    clearTimeout(__form_result_timer);
    __form_result_timer = setTimeout(function() {
        $resobj.stop(true).fadeOut();
    }, 10000);

    var $parent = $("html, body");
    var top =$resobj.offset().top - 85;
    if ($form.parents(".skeleton-content").length == 1) {
        $parent = $form.parents(".skeleton-content");
        top = $resobj.offset().top - $form.offset().top - 20;
    }

    $parent.animate({
        scrollTop: top + "px"
    });
}


/**
 * Ajax forms
 */
NextPost.AjaxForms = function()
{   
    var $form;
    var $result;
    var result_timer = 0;

    $("body").on("submit", ".js-ajax-form", function(){
        $form = $(this);
        $result = $form.find(".form-result")
        submitable = true;

        $form.find(":input.js-required").not(":disabled").each(function(){
            if (!$(this).val()) {
                $(this).addClass("error");
                submitable = false;
            }
        });

        if (submitable) {
            $("body").addClass("onprogress");

            $.ajax({
                url: $form.attr("action"),
                type: $form.attr("method"),
                dataType: 'jsonp',
                data: $form.serialize(),
                error: function() {
                    $("body").removeClass("onprogress");
                    NextPost.DisplayFormResult($form, "error", __("Oops! An error occured. Please try again later!"));
                },

                success: function(resp) {
                    if (typeof resp.redirect === "string") {
                        window.location.href = resp.redirect;
                    } else if (typeof resp.msg === "string") {
                        var result = resp.result || 0;
                        var reset = resp.reset || 0;
                        switch (result) {
                            case 1: // 
                                NextPost.DisplayFormResult($form, "success", resp.msg);
                                if (reset) {
                                    $form[0].reset();
                                }
                                break;

                            case 2: // 
                                NextPost.DisplayFormResult($form, "info", resp.msg);
                                break;

                            default:
                                NextPost.DisplayFormResult($form, "error", resp.msg);
                                break;
                        }
                        $("body").removeClass("onprogress");
                    } else {
                        $("body").removeClass("onprogress");
                        NextPost.DisplayFormResult($form, "error", __("Oops! An error occured. Please try again later!"));
                    }
                }
            });
        } else {
            NextPost.DisplayFormResult($form, "error", __("Fill required fields"));
        }

        return false;
    });
}



/**
 * Load More
 * @var window.loadmore Global object to hold callbacks etc.
 */
window.loadmore = {}
NextPost.LoadMore = function()
{
    $("body").on("click", ".js-loadmore-btn", function(){
        var _this = $(this);
        var _parent = _this.parents(".loadmore");
        var id = _this.data("loadmore-id");

        if(!_parent.hasClass("onprogress")){
            _parent.addClass("onprogress");

            $.ajax({
                url: _this.attr("href"),
                dataType: 'html',
                error: function(){
                    _parent.fadeOut(200);

                    if (typeof window.loadmore.error === "function") {
                        window.loadmore.error(); // Error callback
                    }
                },
                success: function(Response){
                    var resp = $(Response);
                    var $wrp = resp.find(".js-loadmore-content[data-loadmore-id='"+id+"']");

                    if($wrp.length > 0){
                        var wrp = $(".js-loadmore-content[data-loadmore-id='"+id+"']");
                        wrp.append($wrp.html());

                        if (typeof window.loadmore.success === "function") {
                            window.loadmore.success(); // Success callback
                        }
                    }

                    if(resp.find(".js-loadmore-btn[data-loadmore-id='"+id+"']").length == 1){
                        _this.attr("href", resp.find(".js-loadmore-btn[data-loadmore-id='"+id+"']").attr("href"));
                        _parent.removeClass('onprogress none');
                    }else{
                        _parent.hide();
                    }
                }
            });
        }

        return false;
    });

    $(".js-loadmore-btn.js-autoloadmore-btn").trigger("click");
}


/**
 * Popups
 */
NextPost.Popups = function()
{
    var w = scrollbarWidth();

    $(window).on("resize", function(){
        $('#popupstyles').remove();

        if($("body").outerHeight() > $(window).height()){
            $("head").append(
                "<style id='popupstyles'>"+
                    "body.js-popup-opened { padding-right:" + w + "px; overflow:hidden; }"+
                    ".js-popup { overflow-y:scroll; }"+
                "</style>"
            );
        }
    }).trigger("resize");

    $("body").on("click", ".js-open-popup", function() {
        var $popup = $($(this).data("popup"));

        if ($popup.length != 1) {
            return true;
        }

        $("body").addClass('js-popup-opened');
        $popup.removeClass('none');

        return false;
    });

    $("body").on("click", ".js-close-popup", function(){
        $("body").removeClass('js-popup-opened');
        $(this).parents(".js-popup").addClass("none");
    });
}


/**
 * Remove List Item (Data entry)
 * 
 * Sends remove request to the backend 
 * for selected list item (data entry)
 */
NextPost.RemoveListItem = function()
{
    $("body").on("click", "a.js-remove-list-item", function() {
        var item = $(this).parents(".js-list-item");
        var id = $(this).data("id");
        var url = $(this).data("url");

        NextPost.Confirm({
            confirm: function() {
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'jsonp',
                    data: {
                        action: "remove",
                        id: id
                    }
                });

                item.fadeOut(500, function() {
                    item.remove();
                });
            }
        })
    });
}


/**
 * Actions related to aside list items
 */
NextPost.AsideList = function()
{
    // Load content for selected aside list item
    $(".skeleton-aside").on("click", ".js-ajaxload-content", function() {
        var item = $(this).parents(".aside-list-item");
        var url = $(this).attr("href");

        if (!item.hasClass('active')) {
            $(".aside-list-item").removeClass("active");
            item.addClass("active");

            $(".skeleton-aside").addClass('hide-on-medium-and-down');

            $(".skeleton-content")
            .addClass("onprogress")
            .removeClass("hide-on-medium-and-down")
            .load(url + " .skeleton-content>", function() {
                $(".skeleton-content").removeClass('onprogress');
            });

            window.history.pushState({}, $("title").text(), url);
        }

        return false;
    });

    NextPost.AsideListSearch();
}


/**
 * Search aside list items
 */
NextPost.AsideListSearch = function() 
{
    /**
     * Previous search query
     * Don't perform a search if the new search query is 
     * same as previous one
     */
    var prev_search_query;

    /**
     * Timer placeholder for the timeout between search requests
     */
    var search_timer;

    /**
     * XMLHttpRequest
     *
     * Handler for XMLHttpRequest. This will be used cancel/abort 
     * the ajax requests when needed. This is a workaround for jQuery 3+
     * As of jQuery 3, the ajax method returns a promise 
     * without extra methods (like abort). So ajax_handler.abort() is invalid
     * in jQuery 3+
     */
    var search_xhr;

    /**
     * jQuery ref. to the DOM element of the aside search form
     * @type {[type]}
     */
    var $form = $(".skeleton-aside .search-box");


    /**
     * Perform search on keyup on search input
     */
    $form.find(":input[name='q']").on("keyup", function() {
        var _this = $(this);
        var search_query = $.trim(_this.val());

        if (search_query == prev_search_query) {
            return true;
        }

        if (search_xhr) {
            // Abort previous ajax request
            search_xhr.abort();
        }

        if (search_timer) {
            clearTimeout(search_timer);
        }

        prev_search_query = search_query;
        var duration = search_query.length > 0 ? 200 : 0;
        search_timer = setTimeout(function(){
            _this.addClass("onprogress");

            $.ajax({
                url: $form.attr("action"),
                type: $form.attr("method"),
                dataType: 'html',
                data: {
                    q: search_query
                },
                complete: function() {
                    _this.removeClass('onprogress');
                },

                success: function(resp) {
                    $resp = $(resp);

                    if ($resp.find(".skeleton-aside .js-search-results").length == 1) {
                        $(".skeleton-aside .js-search-results")
                            .html($resp.find(".skeleton-aside .js-search-results").html());
                    }

                    if (search_query.length > 0) {
                        $form.addClass("search-performed");
                    } else {
                        $form.removeClass("search-performed");
                    }
                }
            });
        }, duration);
    });


    /**
     * Cancel search
     */
    $form.find(".cancel-icon").on("click", function() {
        $form.find(":input[name='q']")
            .val("")
            .trigger('keyup');
    });
}


/**
 * File Manager
 */
NextPost.FileManager = function()
{
    var fmwrapper = $("#filemanager");
    if (fmwrapper.length != 1) {
        return false;
    }
    
    fmwrapper.oneFileManager({
        lang: $("html").attr("lang") || "en",
        onDoubleClick: function($file) {
            window.filemanager.selectFile($file);

            $(".filepicker").find(".js-submit").trigger("click")
        }
    });

    window.filemanager = fmwrapper.data("ofm");

    // Device file browser
    $("body").on("click", ".js-fm-filebrowser", function() {
        window.filemanager.browseDevice();
    });

    // URL Input form toggler
    $("body").on("click", ".js-fm-urlformtoggler", function() {
        window.filemanager.toggleUrlForm();
    });


    // Dropbox Chooser
    NextPost.DropboxChooser();
    
    // OneDrive Picker
    NextPost.OnedrivePicker();
    
    // Google Drive Picker
    // 
    // Will be initialized automatically,
    // there is no need to call method here.

    // File Pickers (Browse buttons)
    NextPost.FilePickers();
}


/**
 * File Pickers (Browse buttons)
 */
NextPost.FilePickers = function()
{
    var acceptor;

    $("body").on("click", ".js-fm-filepicker", function() {
        acceptor = $(this).data("fm-acceptor");
        $(".filepicker").stop(true).fadeIn();
    });

    $(".filepicker").find(".js-close").on("click", function() {
        $(".filepicker").stop(true).fadeOut();
    });

    $(".filepicker").find(".js-submit").on("click", function() {
        if (acceptor) {
            var selection = window.filemanager.getSelection();
            var file = selection[Object.keys(selection)[0]];
            $(acceptor).val(file.url);
        }
        $(".filepicker").stop(true).fadeOut();
    })
}


/**
 * Dropbox Chooser
 */
NextPost.DropboxChooser = function(settings)
{
    $("body").on("click", "a.cloud-picker[data-service='dropbox']", function() {
        var _this = $(this);

        Dropbox.choose({
            linkType: "direct",
            multiselect: true,
            extensions: ['.jpg', '.jpeg', ".png", '.mp4'],
            success: function(files) {
                for (var i = 0; i < files.length; i++) {
                    if (i >= 10) {
                        break;
                    }

                    if (!files[i].isDir) {
                        window.filemanager.upload(files[i].link);
                    }
                }
            },
        })
    })
}


/**
 * Onedrive Picker
 */
NextPost.OnedrivePicker = function(settings)
{   
    $("body").on("click", "a.cloud-picker[data-service='onedrive']", function() {
        var _this = $(this);

        OneDrive.open({
            clientId: _this.data("client-id"),
            action: "download",
            multiSelect: true,
            openInNewWindow: true,
            advanced: {
                filter: '.jpeg,.jpg,.png,.mp4'
            },
            success: function(files) {
                for (var i = 0; i < files.value.length; i++) {
                    if (i >= 10) {
                        break;
                    }

                    window.filemanager.upload(files.value[i]["@microsoft.graph.downloadUrl"]);
                }
            }
        });
    })
}


/**
 * Google Drive Picker
 */
GoogleDrivePickerInitializer = function()
{   
    if ($("a.cloud-picker[data-service='google-drive']").length == 1) {
        var _this = $("a.cloud-picker[data-service='google-drive']");

        var picker = new GoogleDrivePicker({
            apiKey: _this.data("api-key"),
            clientId: _this.data("client-id").split(".")[0],
            buttonEl: _this[0],
            onSelect: function(file) {
                window.filemanager.upload("https://www.googleapis.com/drive/v3/files/?id="+file.id+"&token="+gapi.auth.getToken().access_token+"&ext="+file.fileExtension+"&size="+file.size);
            }
        });
    }
}


/**
 * Confirm
 */
NextPost.Confirm = function(data = {})
{
    data = $.extend({}, {
        title: __("Are you sure?"),
        content: __("It is not possible to get back removed data!"),
        confirmText: __("Yes, Delete"),
        cancelText: __("Cancel"),
        confirm: function() {},
        cancel: function() {},
    }, data);

    
    $.confirm({
        title: data.title,
        content: data.content,
        theme: 'supervan',
        animation: 'opacity',
        closeAnimation: 'opacity',
        buttons: {
            confirm: {
                text: data.confirmText,
                btnClass: "small button button--danger mr-5",
                keys: ['enter'],
                action: typeof data.confirm === 'function' ? data.confirm : function(){}
            },
            cancel: {
                text: data.cancelText,
                btnClass: "small button button--simple",
                keys: ['esc'],
                action: typeof data.cancel === 'function' ? data.cancel : function(){}
            },
        }
    });
}


/**
 * Alert
 */
NextPost.Alert = function(data = {})
{
    data = $.extend({}, {
        title: __("Error"),
        content: __("Oops! An error occured. Please try again later!"),
        confirmText: __("Close"),
        confirm: function() {},
    }, data);

    $.alert({
        title: data.title,
        content: data.content,
        theme: 'supervan',
        animation: 'opacity',
        closeAnimation: 'opacity',
        buttons: {
            confirm: {
                text: data.confirmText,
                btnClass: "small button button--dark",
                keys: ['enter'],
                action: typeof data.confirm === 'function' ? data.confirm : function(){}
            },
        }
    });
}




/**
 * Captions
 */
NextPost.Captions = function()
{
    var wrapper = $("#captions");

    var _linky = function()
    {
        wrapper.find(".box-list-item p").not(".js-linky-done")
               .addClass("js-linky-done")
               .linky({
                    mentions: true,
                    hashtags: true,
                    urls: false,
                    linkTo:"instagram"
                });
    }

    // Linky captions
    _linky();
    window.loadmore.success = function($item) 
    {
        _linky();
    }
}

/**
 * Caption
 */
NextPost.Caption = function()
{
    var $form = $("#caption form");
    
    // Emoji
    var emoji = $(".caption-input").emojioneArea({
        saveEmojisAs      : "shortname", // unicode | shortname | image
        imageType         : "svg", // Default image type used by internal CDN
        pickerPosition: 'bottom',
        buttonTitle: __("Use the TAB key to insert emoji faster")
    });

    // Caption input filter
    emoji[0].emojioneArea.on("drop", function(obj, event) {
        event.preventDefault();
    });

    emoji[0].emojioneArea.on("paste keyup input emojibtn.click", function() {
        $form.find(":input[name='caption']").val(emoji[0].emojioneArea.getText());
    });
}



/**
 * Package Form
 */
NextPost.PackageForm = function()
{
    $("body").on("click", ".js-save-and-update", function() {
        var form = $(this).parents("form");

        form.find(":input[name='update-subscribers']").val(1);
        form.trigger("submit");
        form.find(":input[name='update-subscribers']").val(0);
    });
}


/**
 * User Form
 */
NextPost.UserForm = function()
{
    $("body").on("change", ":input[name='package-subscription']", function() {
        if ($(this).is(":checked")) {
            $(".package-options").find(":input").prop("disabled", true);
            $(".package-options").css("opacity", ".35");
        } else {
            $(".package-options").find(":input").prop("disabled", false);
            $(".package-options").css("opacity", "");
        }
    });

    $("body").on("change", ":input[name='package']", function() {
        if ($(this).val() < 0) {
            $(":input[name='package-subscription']").prop({
                "checked": false,
                "disabled": true
            });
        } else {
            $(":input[name='package-subscription']").prop("disabled", false);
        }

        $(":input[name='package-subscription']").trigger("change");
    });

    $(":input[name='package-subscription']").trigger("change");

    $(document).ajaxComplete(function(event, xhr, settings) {
        var rx = new RegExp("(users\/[0-9]+(\/)?)$");
        if (rx.test(settings.url)) {
            NextPost.DatePicker();
            $(":input[name='package-subscription']").trigger("change");
        }
    })
}



/**
 * Calendar
 */
NextPost.Calendar = function()
{   
    $("#calendar-day").find("video").each(function(index, el) {
        var _this = $(this);
        _this.on("loadedmetadata", function() {
            if (this.videoWidth >= this.videoHeight) {
                _this.css({
                    "height" : "100%",
                    "width" : "auto"
                });
            } else {
                _this.css({
                    "width" : "100%",
                    "height" : "auto"
                });
            }
        });
    });

    $("#calendar-month, #calendar-day").find(":input[name='account']").on("change", function() {
        $(this).parents("form").trigger("submit");
    });
}  


/**
 * Settings
 */
NextPost.Settings = function()
{
    $(".js-settings-menu").on("click", function() {
        $(".asidenav").toggleClass("mobile-visible");
        $(this).toggleClass("mdi-menu-down mdi-menu-up");

        $("html, body").delay(200).animate({
            scrollTop: "0px"
        });
    });


    // Proxy form
    if ($("#proxy-form").length == 1) {
        $("#proxy-form :input[name='enable-proxy']").on("change", function() {
            $("#proxy-form :input[name='enable-user-proxy']").prop("disabled", !$(this).is(":checked"));

            if ($("#proxy-form :input[name='enable-user-proxy']").is(":disabled")) {
                $("#proxy-form :input[name='enable-user-proxy']").prop("checked", false);
            }
        }).trigger("change");
    }

    if ($("#smtp-form").length == 1) {
        $("#smtp-form :input[name='auth']").on("change", function() {
            if ($(this).is(":checked")) {
                $("#smtp-form :input[name='username'], :input[name='password']")
                .prop("disabled", false);
            } else {
                $("#smtp-form :input[name='username'], :input[name='password']")
                .prop("disabled", true)
                .val("");
            }
        }).trigger("change");
    }

    if ($("#stripe-form").length == 1) {
        $("#stripe-form :input[name='recurring']").on("change", function() {
            if ($(this).is(":checked")) {
                $("#stripe-form :input[name='webhook-key']")
                .prop("disabled", false);

                $("#stripe-form :input[name='webhook-key']").parent().css("opacity", 1);
            } else {
                $("#stripe-form :input[name='webhook-key']")
                .prop("disabled", true);

                $("#stripe-form :input[name='webhook-key']").parent().css("opacity", 0.2);
            }
        }).trigger("change");
    }
}


/**
 * Statistics
 */
NextPost.Statistics = function()
{
    var $page = $("#statistics");
    var $form = $page.find("form");

    $form.find(":input[name='account']").on("change", function() {
        $form.trigger("submit");
    });


    // Get account summary
    var $account_summary = $page.find(".account-summary");

    $.ajax({
        url: $account_summary.data("url"),
        type: 'POST',
        dataType: 'jsonp',
        data: {
            action: "account-summary"
        },

        error: function() {
            $account_summary.find(".numbers").html("<div class='error'>"+__("Oops! An error occured. Please try again later!")+"</div>");
            $account_summary.removeClass('onprogress');
        },

        success: function(resp) {
            if (resp.result == 1) {
                var $temp = $("<div class='statistics-numeric'></div>");
                    $temp.append("<span class='number'></span>");
                    $temp.append("<span class='label'></span>");

                var $media_count = $temp.clone();
                    $media_count.find(".number").text(resp.data.media_count)
                    $media_count.find(".label").text(__("Total Posts"));
                    $media_count.appendTo($account_summary.find(".numbers"));

                var $followers = $temp.clone();
                    $followers.find(".number").text(resp.data.follower_count)
                    $followers.find(".label").text(__("Followers"));
                    $followers.appendTo($account_summary.find(".numbers"));

                var $following = $temp.clone();
                    $following.find(".number").text(resp.data.following_count)
                    $following.find(".label").text(__("Following"));
                    $following.appendTo($account_summary.find(".numbers"));
            } else {
                $account_summary.find(".numbers").html("<div class='error'>"+resp.msg+"</div>");
            }
            
            $account_summary.removeClass('onprogress');
        }
    });
    


    $("canvas").each(function() {
        $(this).width($(this).width());
        $(this).height($(this).height());

        $(this).parents(".chart-container").css("height", "auto");
        $(this).css("position", "relative")
    });
}



/**
 * Renew
 */
NextPost.Renew = function()
{
    var $form = $(".payment-form");
    if ($form.length == 1) {
        if ($form.find(":input[name='payment-gateway']").length > 0) {
            $form.find(".payment-gateways, .payment-cycle").removeClass("none");
            $form.find(":input[name='payment-gateway']").eq(0).prop("checked", true);
        }


        $form.find(":input[name='payment-gateway']").on("change", function() {
            if (!$form.find(":input[name='payment-gateway']:checked").data("recurring")) {
                $form.find(":input[name='payment-cycle'][value='recurring']").parents(".option-group-item").addClass('none');
            } else {
                $form.find(":input[name='payment-cycle'][value='recurring']").parents(".option-group-item").removeClass('none');
            }

            if ($form.find(":input[name='payment-cycle']:checked").parents(".option-group-item").hasClass('none')) {
                $form.find(":input[name='payment-cycle']").eq(0).prop("checked", true);
            }
        });
        $form.find(":input[name='payment-gateway']").eq(0).trigger("change");


        // Initialize Stripe
        var data = {};

        if ($form.find(":input[name='payment-gateway'][value='stripe']").length == 1) {
            var stripe = StripeCheckout.configure({
                key: $form.data("stripe-key"),
                image: $form.data("stripe-img"),
                email: $form.data("email"),
                locale: $("html").attr("lang"),
                token: function(token) {
                    data.token = token.id;
                    _placeOrder();      
                }
            });

            window.addEventListener('popstate', function() {
                stripe.close();
            });
        }

        $form.on("submit", function() {
            data.plan = $form.find(":input[name='plan']:checked").val();
            data.payment_gateway = $form.find(":input[name='payment-gateway']:checked").val();
            data.payment_cycle = $form.find(":input[name='payment-cycle']:checked").val();

            if (data.payment_gateway == "paypal") {
                _placeOrder();
            } else if (data.payment_gateway == "stripe") {
                stripe.open({
                    name: $form.data("site"),
                    description: $form.find(":input[name='plan']:checked").data("desc"),
                    amount: $form.find(":input[name='plan']:checked").data("amount"),
                    currency: $form.data("currency")
                });
            }
        })
    }


    var _placeOrder = function()
    {
        data.action = "pay";

        $("body").addClass("onprogress");
        $.ajax({
            url: $form.data("url"),
            type: 'POST',
            dataType: 'jsonp',
            data: data,
            error: function(){
                NextPost.Alert();
                $("body").removeClass("onprogress");
            },

            success: function(resp) {
                if (resp.result == 1) {
                    window.location.href = resp.url;
                } else {
                    NextPost.Alert({
                        content: resp.msg
                    });

                    $("body").removeClass("onprogress");
                }
            }
        });
    }
}


/**
 * Expired
 */
NextPost.CancelRecurringPayments = function()
{
    $(".js-cancel-recurring-payments").on("click", function() {
        var $this = $(this);

        NextPost.Confirm({
            title: __("Are you sure?"),
            content: __("Do you really want to cancel automatic payments?"),
            confirmText: __("Yes, cancel automatic payments"),
            cancelText: __("No"),
            confirm: function() {
                $("body").addClass("onprogress");

                $.ajax({
                    url: $this.data("url"),
                    type: 'POST',
                    dataType: 'jsonp',
                    data: {
                        "action": "cancel-recurring"
                    },
                    error: function(){
                        NextPost.Alert();
                        $("body").removeClass("onprogress");
                    },

                    success: function(resp) {
                        if (resp.result == 1) {
                            window.location.reload();
                        } else {
                            NextPost.Alert({
                                content: resp.msg
                            });

                            $("body").removeClass("onprogress");
                        }
                    }
                });
            }
        });
    });
}


/**
 * Plugins
 */
NextPost.Plugins = function()
{
    $("body").on("click", "a.js-deactivate, a.js-activate", function() {
        var $item = $(this).parents("tr")
        var id = $(this).data("id");
        var url = $(this).data("url");
        var action = $(this).hasClass('js-deactivate') ? "deactivate" : "activate";

        $("body").addClass("onprogress");

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'jsonp',
            data: {
                action: action,
                id: id
            },

            error: function() {
                NextPost.Alert();
                $("body").removeClass("onprogress");
            },

            success: function(resp) {
                if (resp.result == 1) {
                    $item.find("a.js-deactivate, a.js-activate").toggleClass("none");
                } else {
                    NextPost.Alert({
                        content: resp.msg
                    });
                }
                $("body").removeClass("onprogress");
            }
        });
    });
}



/**
 * Upload new plugin
 */
NextPost.Plugin = function()
{
    var $page = $("#plugin");
    var $form = $page.find("form");

    $form.on("submit", function() {
        var submitable = true;

        if (!$form.find(":input[name='file']").val()) {
            $form.find(":input[name='file']").addClass("error");
            submitable = false;
        }

        if (submitable && $form.find(":input[name='file']")[0].files.length > 0) {
            $("body").addClass("onprogress");

            var data = new FormData();
                data.append("action", "upload");
                data.append("file", $form.find(":input[name='file']")[0].files[0]);

            $.ajax({
                url: $form.attr("action"),
                type: "POST",
                dataType: 'jsonp',
                data: data,
                cache: false,
                contentType: false,
                processData: false,

                error: function() {
                    $("body").removeClass("onprogress");
                    NextPost.DisplayFormResult($form, "error", __("Oops! An error occured. Please try again later!"));
                },

                success: function(resp) {
                    if (resp.result == 1) {
                        window.location.href = resp.redirect;
                    } else {
                        NextPost.DisplayFormResult($form, "error", resp.msg);
                        $("body").removeClass("onprogress");
                    }

                }
            });
        }

        return false;
    })
}



/**
 * Account form
 */
NextPost.Account = function()
{
    var $form = $("#account form");
    var resend_ok_timer;
    var verification_type;

    $(document).ajaxComplete(function(event, xhr, settings) {
        var rx_edit = new RegExp("(\/accounts\/[0-9]+(\/)?)");
        var rx_new = new RegExp("(\/accounts\/new(\/)?)");

        if (!rx_new.test(settings.url) && !rx_edit.test(settings.url)) {
            return;
        }

        if (xhr.responseJSON.hasOwnProperty("changes_saved") &&
            xhr.responseJSON.changes_saved) {
            // account (not new) saved successfully
            $form.find(".js-challenge, .js-2fa").addClass("none");

            $form.find(":input[name='password']").val("");
            $form.find(".js-login").removeClass("none");
            $form.find(".js-login :input").prop("disabled", false);

            return;
        }

        if (xhr.responseJSON.hasOwnProperty("login_failed") &&
            xhr.responseJSON.login_failed) {
            // Unable to login
            $form.find(".js-challenge, .js-2fa").addClass("none");

            $form.find(":input[name='password']").val("");
            $form.find(".js-login").removeClass("none");
            $form.find(".js-login :input").prop("disabled", false);

            // Clear timeout for form result timer
            if (__form_result_timer) {
                clearTimeout(__form_result_timer);
            }

            return;
        }

        verification_type = false;
        if (xhr.responseJSON.hasOwnProperty("twofa_required") &&
            xhr.responseJSON.twofa_required) {
            verification_type = "2fa";
        }

        if (xhr.responseJSON.hasOwnProperty("challenge_required") &&
            xhr.responseJSON.challenge_required) {
            verification_type = "challenge";
        }

        if (!verification_type) {
            return;
        }

        // Clear timeout for form result timer
        if (__form_result_timer) {
            clearTimeout(__form_result_timer);
        }

        $form.find(".form-result .icon").attr("class", "sli sli-lock icon");
        $form.find(".js-login, .js-challenge, .js-2fa").addClass("none");
        $form.find(".js-login, .js-challenge, .js-2fa").find(":input").prop("disabled", true);

        if (verification_type == "2fa") {
            // Update login form state to 2FA
            $form.find(":input[name='action']").val("2fa");

            $form.find(":input[name='2faid']").val(xhr.responseJSON.identifier)
            $form.find(".js-2fa").removeClass("none")
            $form.find(".js-2fa :input").prop("disabled", false);
        } else {
            // Update login form state to challenge
            $form.find(":input[name='action']").val("challenge");

            $form.find(":input[name='challengeid']").val(xhr.responseJSON.identifier)
            $form.find(".js-challenge").removeClass("none")
            $form.find(".js-challenge :input").prop("disabled", false);
        }
        _resendtimer();
    });


    // Resend Security Code
    // Compatible for both of 2FA and challenge
    var resend_count = {
        "2fa": 0,
        "challenge": 0
    };
    $form.find(".resend-btn").on("click", function() {
        if ($(this).hasClass('inactive')) {
            return true;
        }

        var _this = $(this);
        var $parent = _this.parents(".js-2fa");
        if ($parent.length != 1) {
            $parent = _this.parents(".js-challenge");
        }
        var type = $parent.hasClass('js-2fa') ? "2fa" : "challenge";


        _this.addClass("inactive");
        $.ajax({
            url: $form.data("action"),
            type: 'POST',
            dataType: 'jsonp',
            data: {
                action: type == "2fa" ? "resend-2fa" : "resend-challenge",
                id: type == "2fa" ? $form.find(":input[name='2faid']").val() : $form.find(":input[name='challengeid']").val()
            },
            error: function() {
                $parent.find(".resend-btn").addClass('none');
                $parent.find(".resend-result").html(__("Oops! An error occured. Please try again later!")).removeClass('none');
            },

            success: function(resp) {
                $parent.find(".resend-btn").addClass('none');
                $parent.find(".resend-result").html(resp.msg).removeClass('none');

                if (resp.result == 1) {
                    if (type == "2fa") {
                        $form.find(":input[name='2faid']").val(resp.identifier);
                    } else {
                        $form.find(":input[name='challengeid']").val(resp.identifier);
                    }

                    if (resend_ok_timer) {
                        clearTimeout(resend_ok_timer);
                    }

                    resend_count[type]++;
                    if (resend_count[type] >= 2) {
                        $parent.find(".js-not-received-security-code").remove();
                        
                        resend_ok_timer = setTimeout(function() {
                            $parent.find(".resend-result").addClass('none');
                        }, 5000);
                    } else {
                        resend_ok_timer = setTimeout(function() {
                            $parent.find(".resend-btn").removeClass('none');
                            $parent.find(".resend-result").addClass('none');
                            _resendtimer();
                        }, 5000);
                    }
                }
            }
        });
    });


    /**
     * Handle resend timer text
     * Compatible for both of 2FA and challenge
     * @return void
     */
    var _resendtimer = function()
    {
        var $form = $("#account form");
        var $timer = $form.find(".resend-btn .timer");
        var resend_seconds = 60;
        var resend_timer;

        $form.find(".resend-btn").addClass("inactive");
        $timer.text("(" + $timer.data("text").replace("{seconds}", resend_seconds) + ")");

        if (resend_timer) {
            clearInterval(resend_timer);
        }
        
        resend_timer = setInterval(function(){
            resend_seconds--;
            if (resend_seconds > 0) {
                $timer.text("(" + $timer.data("text").replace("{seconds}", resend_seconds) + ")");
            } else {
                $form.find(".resend-btn").removeClass("inactive");
                $form.find(".resend-btn .timer").html("");
                clearInterval(resend_timer);
            }
        }, 1000);
    }
}


/**
 * Profile
 */
NextPost.Profile = function()
{
    $(".js-resend-verification-email").on("click", function() {
        var $this = $(this);
        var $alert = $this.parents(".alert");

        if ($alert.hasClass("onprogress")) {
            return;
        }

        $alert.addClass('onprogress');
        $.ajax({
            url: $this.data("url"),
            type: 'POST',
            dataType: 'jsonp',
            data: {
                action: 'resend-email'
            },

            error: function() {
                $this.remove();
                $alert.find(".js-resend-result").html(__("Oops! An error occured. Please try again later!"));
                $alert.removeClass("onprogress");
            },

            success: function(resp) {
                $this.remove();
                $alert.find(".js-resend-result").html(resp.msg);
                $alert.removeClass("onprogress");
            }
        });
    });
}



/* Functions */

/**
 * Validate Email
 */
function isValidEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}

/**
 * Get scrollbar width
 */
function scrollbarWidth(){
    var scrollDiv = document.createElement("div");
    scrollDiv.className = "scrollbar-measure";
    document.body.appendChild(scrollDiv);
    var w = scrollDiv.offsetWidth - scrollDiv.clientWidth;
    document.body.removeChild(scrollDiv);

    return w;
}


/**
 * Validate URL
 */
function isValidUrl(url) {
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
}


/**
 * Get the position of the caret in the contenteditable element
 * @param  {DOM}  DOM of the input element
 * @return {obj}  start and end position of the caret position (selection)
 */
function getCaretPosition(element) {
    var start = 0;
    var end = 0;
    var doc = element.ownerDocument || element.document;
    var win = doc.defaultView || doc.parentWindow;
    var sel;

    if (typeof win.getSelection != "undefined") {
        sel = win.getSelection();
        if (sel.rangeCount > 0) {
            var range = win.getSelection().getRangeAt(0);
            var preCaretRange = range.cloneRange();
            preCaretRange.selectNodeContents(element);
            preCaretRange.setEnd(range.startContainer, range.startOffset);
            start = preCaretRange.toString().length;
            preCaretRange.setEnd(range.endContainer, range.endOffset);
            end = preCaretRange.toString().length;
        }
    } else if ( (sel = doc.selection) && sel.type != "Control") {
        var textRange = sel.createRange();
        var preCaretTextRange = doc.body.createTextRange();
        preCaretTextRange.moveToElementText(element);
        preCaretTextRange.setEndPoint("EndToStart", textRange);
        start = preCaretTextRange.text.length;
        preCaretTextRange.setEndPoint("EndToEnd", textRange);
        end = preCaretTextRange.text.length;
    }
    return { start: start, end: end };
}