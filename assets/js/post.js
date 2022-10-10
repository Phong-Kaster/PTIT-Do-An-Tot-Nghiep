/**
 * Post
 */
NextPost.Post = function()
{   
    var $page = $("#post");
    var $form = $page.find("form");
    var $preview = $page.find(".post-preview");
    var filemanager = window.filemanager;
    var $mini_preview = $form.find(".mini-preview");

    var $caption = false; // $ ref. to emojioneare editor
    var $caption_preview = $preview.find(".preview-caption");
    var $caption_preview_placeholder = $preview.find(".preview-caption-placeholder");

    var $img_template = $("<div class='img'></div>");
    var $video_template = $("<video src='#' playsinline autoplay muted loop></video>");


    /**
     * Login to the Instagram on the backend for the selected account
     * This is required to get quick results on hashtag/user/location search
     */
    $form.find(":input[name='account']").on("change", function() {
        var _this = $(this);
        var account = _this.val();

        _this.prop("disabled", true);
        _this.parents(".dropdown").addClass('onprogress');
        _this.removeClass("error");
        $form.find(".account-error").addClass("none");
        $form.find(".post-submit .button").prop("disabled", true);

        $.ajax({
            url: $form.data("url"),
            type: 'POST',
            dataType: 'jsonp',
            data: {
                action: "login",
                account_id: account
            },
            success: function(resp) {
                if (resp.result == 1) {
                    $form.find(".post-submit .button").prop("disabled", false);
                } else {
                    $form.find(".account-error").html(resp.msg).removeClass("none");
                    _this.addClass("error");
                }
            },
            complete: function() {
                _this.prop("disabled", false);
                _this.parents(".dropdown").removeClass('onprogress');
            }
        });
    });

    // Fix to un-block filemanager load more
    setTimeout(function() {
        $form.find(":input[name='account']").trigger("change")
    }, 100);



    /**
     * Emoji Area (Caption & First Comment)
     */
    // Init. emoji area
    var emoji = $form.find(".caption, .first-comment").emojioneArea({
        saveEmojisAs      : "unicode",
        imageType         : "svg",
        pickerPosition: 'bottom',
        buttonTitle: __("Use the TAB key to insert emoji faster")
    });

    // Disable drag-drop content in emoji area for input filtering
    emoji[0].emojioneArea.on("drop", function(obj, event) {
        if (!$caption) {
            $caption = $form.find(".emojionearea.caption .emojionearea-editor");
        }
        event.preventDefault();
    });

    // Configure caption preview
    var _linky = function()
    {
        $caption_preview.linky({
            mentions: true,
            hashtags: true,
            urls: false,
            linkTo:"instagram"
        });
    }; 

    // Caption live preview
    emoji[0].emojioneArea.on("paste keyup input emojibtn.click", function() {
        if (!$caption) {
            $caption = $form.find(".emojionearea.caption .emojionearea-editor");
        }
        $caption_preview.html($caption.html()); 
        _linky();

        if ($.trim(emoji[0].emojioneArea.getText())) {
            $caption_preview_placeholder.stop(true).hide();
            $caption_preview.stop(true).fadeIn();
        } else {
            $caption_preview.stop(true).hide();
            $caption_preview_placeholder.stop(true).fadeIn();
        }
    });

    // Init. caption preview
    _linky();



    // Select caption template
    $("body").on("click", "#captions-overlay .simple-list-item", function() {
        var caption = $(this).find(":input").val();

        if ($form.find(".tabheads a.active").data("tab") == 2) {
            $form.find(".first-comment")[0].emojioneArea.setText(caption);
        } else {
            $form.find(".caption")[0].emojioneArea.setText(caption).trigger("keyup");
        }

        $("#captions-overlay .js-close-popup").trigger("click");
    });



    /**
     * Search for hashtags and people in caption/first-comment fields
     * and locations in location field
     */

    /**
     * Caret (selection) position in the search inputs
     * @type {Object}
     */
    var caret = {
        start: 0,
        end: 0
    }

    /**
     * jQuery reference to the search results element
     * @type {$}
     */
    var $search_results = $form.find(".search-results");

    /**
     * jQuery reference to the active search input
     */
    var $active_search_input;

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
     * Search input value just before sending search request
     * Will not be used for location search input
     */
    var search_input_val;

    /**
     * Search word at the caret position
     * Will not be used for location search input
     */
    var search_query;


    /**
     * Timer placeholder for the timeout between search requests
     */
    var search_timer;

    /**
     * Cache for search response
     */
    var search_cache = {};


    /**
     * Search and get results
     * @return {void} 
     */
    var getResults = function()
    {
        var account_id = $form.find(":input[name='account']").val();
        var cache_key = account_id + search_query;

        if (search_xhr) {
            // Abort previous ajax request
            search_xhr.abort();
        }

        if (search_timer) {
            clearTimeout(search_timer);
        }

        if (search_cache[cache_key]) {
            $search_results.html("<p></p>");
            $search_results.find("p").text(__("Searching for %s").replace("%s", search_query));
            $search_results.removeClass("none");
            
            parseSearchResponse(search_cache[cache_key]);
            return true;
        }

        search_timer = setTimeout(function() {
            search_xhr = new window.XMLHttpRequest();
            $.ajax({
                url: $form.data("url"),
                type: 'POST',
                dataType: 'jsonp',
                cache: true,
                data: {
                    action: "search",
                    account_id: account_id,
                    q: search_query
                },
                xhr: function() {
                    return search_xhr;
                },
                beforeSend: function() {
                    $search_results.html("<p></p>");
                    $search_results.find("p").text(__("Searching for %s").replace("%s", search_query));
                    $search_results.removeClass("none");
                },
                error: function() {
                    $search_results.html("").addClass("none");
                },
                success: function(resp) {
                    parseSearchResponse(resp);
                    search_cache[cache_key] = resp;
                }
            });
        }, 200);
    }

    var parseSearchResponse = function(resp) 
    {
        if (resp.result == 1) {
            $search_results.html("");

            for (var i = 0; i < resp.items.length; i++) {
                var r = resp.items[i];
                var $r = $("<a href='javascript:void(0)' class='item'></a>");

                var value = r.value;
                if (r.type == "hashtag") {
                    value = "#"+value;
                } else if (r.type == "people") {
                    value = "@"+value;
                }

                $r.text(value);
                $r.data("value", value);

                if (r.data.location) {
                    // full location object (serialized)
                    $r.data("location", r.data.location);
                }

                if (r.data.sub) {
                    $r.append("<span class='sub'></span>");
                    $r.find(".sub").text(r.data.sub);
                }

                $r.appendTo($search_results);
            }

            if ($search_results.find(".item").length > 3) {
                $search_results.find(".item").slice(3).addClass("none");
                $search_results.append('<a href="javascript:void(0)" class="view-all">'+__("+%s more").replace("%s", $search_results.find(".item").length - 3)+'</a>')
            }
        } else {
            $search_results.html("").addClass("none");
        }
    }


    /**
     * Perform search for hashtags and people 
     * in the caption and first-comment fields
     */
    $form.find(".caption, .first-comment").each(function() {
        if (!$(this).hasClass("js-search-enabled")) {
            // Search feature is disabled
            return true; // continue to the next iteration
        }

        var selector = ".emojionearea."+($(this).hasClass("caption") ? "caption" : "first-comment")+" .emojionearea-editor";

        this.emojioneArea.on("keyup", function() {
            var _this = this;
            $active_search_input = $form.find(selector);
            caret = getCaretPosition($active_search_input[0]);

            var result = /\S+$/.exec(_this.getText().slice(0, caret.end));
            search_query = result ? result[0] : null;
            search_input_val = _this.getText();

            $search_results.insertAfter($active_search_input.parents(".tabcontents"));

            if (search_query && search_query.length > 1 && (search_query[0] == "#" || search_query[0] == "@") && caret.start == caret.end) {
                getResults();
            } else {
                $search_results.addClass("none");
            }
        });

        this.emojioneArea.on("@picker.show", function() {
            $search_results.html("").addClass("none");
        });
    });

    /**
     * Perform a search in location input
     */
    $form.find(":input[name='location-search']").on("keyup", function() {
        if ($(this).prop("readonly")) {
            return false;
        }

        $active_search_input = $(this);
        $search_results.insertAfter($active_search_input.parent());

        search_query = $active_search_input.val();
        if (search_query[0] == "#" || search_query[0] == "@") {
            search_query = search_query.slice(1);
        }

        if (search_query && search_query.length > 1) {
            getResults();
        } else {
            $search_results.addClass("none");
        }
    });


    // Select an item from the results
    $search_results.on("click", ".item", function() {
        var value = $(this).data("value");
        var location = $(this).data("location") ? $(this).data("location") : null;

        if ($active_search_input.parent().hasClass('caption')) {
            var before_caret = search_input_val.slice(0, caret.end - (search_query ? search_query.length : 0));
            var after_caret = search_input_val.slice(caret.end);

            $form.find(".caption")[0].emojioneArea.setText(before_caret+value+after_caret).trigger("paste");
        } else if ($active_search_input.parent().hasClass('first-comment')) {
            var before_caret = search_input_val.slice(0, caret.end - (search_query ? search_query.length : 0));
            var after_caret = search_input_val.slice(caret.end);

            $form.find(".first-comment")[0].emojioneArea.setText(before_caret+value+after_caret).trigger("paste");
        } else {
            $form.find(".js-enable-location-search").removeClass("none");
            $form.find(":input[name='location-search']").val(value).prop("readonly", true);
            $form.find(":input[name='location']").val(location);
        }

        $search_results.addClass("none");
    });

    $form.find(".js-enable-location-search").on("click", function() {
        $form.find(":input[name='location-search']").val("").prop("readonly", false);
        $form.find(":input[name='location']").val("");
        $form.find(".js-enable-location-search").addClass("none");
    })


    // View all search results
    $search_results.on("click", ".view-all", function() {
        $search_results.find(".item").removeClass("none");
        $(this).remove();
    });



    // Toggle schedule-post now
    $form.find(":input[name='schedule']").on("change", function() {
        var $sbmtbtn = $form.find(".post-submit .button");

        if ($(this).is(":checked")) {
            $form.find(":input[name='schedule-date']").prop("disabled", false);
            $sbmtbtn.val($sbmtbtn.data("value-schedule"));
        } else {
            $form.find(":input[name='schedule-date']").prop("disabled", true);
            $sbmtbtn.val($sbmtbtn.data("value-now"));
        }
    }).trigger("change");



    // Toggle advanced settings
    $form.find(".advanced-settings-toggler").on("click", function() {
        $form.find(".advanced-settings").toggleClass('active');
    })




    // Post type changes
    $form.find(".types label").on("click", function(e) {
        if (!$(this).find(":input").is(":checked")) {
            $(this).find(":input").prop("checked", true).trigger("change");
        }
    });

    var type_timer = null;
    $form.find(":input[name='type']").on("change", function() {
        var type = $(this).val();
        $preview.attr("data-type", type);
        
        if (type == "album") {
            filemanager.setOption("multiselect", true);

            clearTimeout(type_timer);
            type_timer = setTimeout(function() {
                // Re-select active item again
                $mini_preview.find(".item--active").trigger("click");
            }, 200);

        } else {
            filemanager.setOption("multiselect", false);

            clearTimeout(type_timer);
            type_timer = setTimeout(function() {
                // Select first item and remove rest
                $mini_preview.find(".item").each(function(index, el) {
                    if (index == 0) {
                        $(this).trigger("click");
                    } else {
                        var file = $(this).data("file");
                        $(this).remove();
                        filemanager.unselectFile(file.id);
                    }
                });

                // If no item, then show placeholder for timeline posts.
                if ($mini_preview.find(".item").length < 1) {
                    $preview.find(".preview-media--timeline").html("<div class='placeholder'></div>");
                }
            }, 200);
        }

        if (type == "story") {
            $form.find(".tabs .emojionearea").addClass('disabled');
            $form.find(":input[name='location-search']").prop("disabled", true);
        } else {
            $form.find(".tabs .emojionearea").removeClass('disabled');
            $form.find(":input[name='location-search']").prop("disabled", false);
        }
    });

    
    // Check selected post type
    if ($form.find(":input[name='type']:checked").not(":disabled").length != 1) {
        $form.find(":input[name='type']").not(":disabled").eq(0)
        .prop("checked", true)
        .trigger("change");
    }


    // Toggle delete mode for the filemanager
    $page.find(".js-fm-delete-mode").on("click", function() {
        if ($page.find(".js-fm-infobox").hasClass("active")) {
            $page.find(".js-fm-infobox").trigger("click");
        }

        if (filemanager.getMode() == "delete") {
            $(this).removeClass("active mdi-delete-empty");
            filemanager.setMode("normal");
            $(this).parents(".more").removeClass("visible");
        } else {
            filemanager.setMode("delete");
            $(this).addClass("active mdi-delete-empty");
            $(this).parents(".more").addClass("visible");
        }
    });


    // Toggle infobox for the filemanager
    $page.find(".js-fm-infobox").on("click", function() {
        if ($page.find(".js-fm-delete-mode").hasClass("active")) {
            $page.find(".js-fm-delete-mode").trigger("click");
        }

        if ($(this).hasClass('active')) {
            $(this).removeClass("active");
            $(this).parents(".more").removeClass("visible");
            filemanager.hideInfobox();
        } else {
            $(this).addClass("active");
            $(this).parents(".more").addClass("visible");
            filemanager.showInfobox();
        }
    });

    filemanager.setOption("onInfoboxHide", function($file) {
        $page.find(".js-fm-infobox").removeClass("active");
    });



    // Sortable mini preview items
    $mini_preview.find(".items").sortable({
        containment: "parent",
        cursor: "-webkit-grabbing",
        distance: 10,
        items: ".item",
        placeholder: "item item--placeholder",

        stop: function(event, ui) {
            var video = ui.item.find('video');
            if (video.length == 1) {
                video.get(0).play();
            }

            if ($mini_preview.find(".item").length > 0) {
                $mini_preview.removeClass('droppable');
            } else {
                $mini_preview.addClass('droppable');
            }
        },

        receive: function(event, ui) {
            ui.helper.remove();
            filemanager.selectFile(ui.item);
        },

        update: function() {
            if ($mini_preview.find(".item").length == 0) {
                $mini_preview.addClass('none');
            }
        }
    });


    // Files retrieved in file manager
    filemanager.setOption("onFileAdd", function($file) {
        // Make files draggable in filemanager
        $file.draggable({
            addClasses: false,
            connectToSortable: $mini_preview.find(".items"),
            containment: "document",
            revert: "invalid",
            revertDuration: 200,
            distance: 10,
            appendTo: $mini_preview.find(".items"),
            cursor: "-webkit-grabbing",
            cursorAt: { 
                left: 35,
                top: 35
            },

            
            zIndex: 1000,
            helper: function() {
                var $item = $file.clone();
                var file = $file.data("file");

                $item.removeClass('ofm-file ui-draggable-handle');
                $item.addClass("item");

                $item.find(".ofm-file-ext, .ofm-file-toggle, .ofm-context-menu-wrapper, .ofm-file-icon").remove();

                $item.find(".ofm-file-preview").find("video").appendTo($item.find(">div"));
                $item.find(".ofm-file-preview").removeClass('ofm-file-preview').addClass('img');

                var $c = $item.clone();
                $c.appendTo($mini_preview);

                $item.width($c.outerWidth());
                $c.remove();

                return $item;
            },

            start: function(event, ui) {
                $mini_preview.addClass("droppable");
                $mini_preview.find(".drophere span").toggleClass("none");
                
                $mini_preview.find(".items").sortable("disable");
            },

            stop: function(event, ui) {
                if ($mini_preview.find(".item").length > 1) {
                    $mini_preview.removeClass("droppable");
                }
                $mini_preview.find(".drophere span").toggleClass("none");

                $mini_preview.find(".items").sortable("enable");
            }
        });
    });

    
    // On file select
    filemanager.setOption("onFileSelect", function($file, selected_files) {
        if (filemanager.isDeleteMode()) {
            return false;
        }

        var file = $file.data("file");

        if ($mini_preview.find(".item[data-id='"+file.id+"']").length == 0) {
            __addItemToMiniPreview(file);
        }

        //$file.draggable("disable");
    });


    // Create a add new item from file data
    // and add it to mini preview section
    var __addItemToMiniPreview = function(file)
    {
        var $item = $("<div class='item'></div>");

        $item.attr("data-id", file.id);

        $item.append("<a class='js-close mdi mdi-close-circle' href='javascript:void(0)'></a>");
        $item.append("<div />");

        if (file.ext == "mp4") {
            var $i = $video_template.clone();
            $i.attr("src", file.url);

            $i.on("loadedmetadata", function() {
                file.width = this.videoWidth;
                file.height = this.videoHeight;

                if (file.width >= file.height) {
                    $i.css({
                        "height" : "100%",
                        "width" : "auto"
                    });
                } else {
                    $i.css({
                        "width" : "100%",
                        "height" : "auto"
                    });
                }
            });
        } else {
            var $i = $img_template.clone();
            $i.css("background-image", "url("+file.url+")");
        }

        $item.find(">div").append($i);
        $mini_preview.find(".items").append($item);

        $item.data("file", file);
        $item.trigger("click");
        $mini_preview.removeClass("droppable");
    }


    // Upload and select files immediately if dragged file is dropped 
    // to the selected media dropzone ($mini_priview)
    $mini_preview.find(".drophere").on('drop', function(e) {
        filemanager.upload(e.originalEvent.dataTransfer.files, true);
    });

    // If it's mobile device, select uploaded file immediently
    filemanager.setOption("onUpload", function($file) {
        if ($(window).width() <= 600) {
            filemanager.selectFile($file);
            $(".mobile-uploader .result").stop().fadeOut();
        }
    });

    filemanager.setOption("onBeforeUpload", function() {
        if ($(window).width() <= 600) {
            $(".mobile-uploader .result").html(__("Uploading...")).stop().fadeIn();
        }
    });

    filemanager.setOption("onNotificationAdd", function(msg) {
        if ($(window).width() <= 600) {
            $(".mobile-uploader .result").html(msg).stop().fadeIn();
        }
    });

    filemanager.setOption("onNotificationHide", function() {
        $(".mobile-uploader .result").stop().fadeOut();
    });


    // On file unselect
    filemanager.setOption("onFileUnselect", function($file, selected_files) {
        if (filemanager.isDeleteMode()) {
            return false;
        }

        var file = $file.data("file");

        $mini_preview.find(".item").each(function() {
            if ($(this).data("file").id == file.id) {
                $(this).find(".js-close").trigger("click");
                return false;
            }
        });

        //$file.draggable("enable");
    });


    // Remove all files from mini preview
    filemanager.setOption("onModeChange", function(mode) {
        $mini_preview.find(".item").remove();
        $preview.find(".preview-media--timeline").html("<div class='placeholder'></div>");
        $preview.find(".img, video").remove();
        $mini_preview.addClass('droppable');

        if (filemanager.isDeleteMode()) {
            $page.find(".ofm-file").draggable("disable");
        } else {
            $page.find(".ofm-file").draggable("enable");
        }
    });


    // Unselect file
    $mini_preview.on("click", ".js-close", function(e) {
        var $item = $(this).parents(".item");
        var file = $item.data("file");

        $item.removeClass('item--active');
        if ($mini_preview.find(".item").length > 1) {
            $item.fadeOut(200, function() {
                $item.remove();
            });
        } else {
             $item.remove();
        }

        if ($mini_preview.find(".item").length > 0) {
            if ($mini_preview.find(".item--active").length == 0) {
                $mini_preview.find(".item").not($item).last().trigger("click");
            }
        } else {
            $preview.find(".preview-media--timeline").html("<div class='placeholder'></div>");
            $preview.find(".img, video").remove();
            $mini_preview.addClass('droppable');
        }

        filemanager.unselectFile(file.id);
        e.stopPropagation();
    });


    // Select an item in mini preview
    $mini_preview.on("click", ".item", function(e) {
        $preview.addClass("onprogress");

        var $item = $(this);
        $mini_preview.find(".item").removeClass('item--active');

        $item.addClass('item--active');

        var type = $form.find(":input[name='type']:checked").val();
        var file = $item.data("file");

        if (["mp4"].indexOf(file.ext) >= 0) { 
            var $i = $video_template.clone();
                $i.attr("src", file.url);

            if (["story", "album"].indexOf(type) >= 0) {
                if (type == "story") {
                    var wdelta = $preview.outerWidth();
                    var hdelta = $preview.outerHeight();
                } else {
                    var wdelta = 0;
                    var hdelta = 0;
                }

                if (file.width && file.height) {
                    if (file.width - wdelta >= file.height - hdelta) {
                        $i.css({
                            "height" : "100%",
                            "width" : "auto"
                        });
                    } else {
                        $i.css({
                            "width" : "100%",
                            "height" : "auto"
                        });
                    }

                    $preview.removeClass("onprogress");
                } else {
                    $i.on("loadedmetadata", function() {
                        if (this.videoWidth - wdelta >= this.videoHeight - hdelta) {
                            $i.css({
                                "height" : "100%",
                                "width" : "auto"
                            });
                        } else {
                            $i.css({
                                "width" : "100%",
                                "height" : "auto"
                            });
                        }

                        $preview.removeClass("onprogress");
                    });
                }
            } else {
                $preview.removeClass("onprogress");
            }
        } else {
            if (["story", "album"].indexOf(type) >= 0) {
                var $i = $img_template.clone();
                $i.css("background-image", "url("+file.url+")");

                $preview.removeClass("onprogress");
            } else if (["timeline"].indexOf(type) >= 0) {
                var $i = $("<img />");
                var $placeholder = $("<div class='placeholder'></div>");

                $i.attr("src", file.url);
                $i.on("load", function() {
                    var w = this.width;
                    var h = this.height;

                    $placeholder.css("background-image", "url("+file.url+")");
                    if (h > w) {
                        $placeholder.css("padding-top", (h/w > 1.25 ? 1.25 : h/w) * 100 + "%");
                    } else {
                        $placeholder.css("padding-top", (w/h > 1.91 ? 100/191 : h/w) * 100 + "%");
                    }

                    $preview.removeClass("onprogress");
                });
                $i = $placeholder;
            }
        }

        $preview.find(".preview-media--"+type).html($i);

        $preview.find(".preview-media--timeline, .preview-media--story, .preview-media--album")
        .not($preview.find(".preview-media--"+type)).html("");
        if (type != "timeline") {
            $preview.find(".preview-media--timeline").html("<div class='placeholder'></div>");
        }
    });


    // Retrieve default selected files (post edit)
    if ($form.find(":input[name='media-ids']").val()) {
        $.ajax({
            url: $("#filemanager").data("connector-url"),
            type: 'POST',
            dataType: 'jsonp',
            data: {
                cmd: "retrieve",
                ids: $form.find(":input[name='media-ids']").val()
            },

            success: function(resp) {
                if (resp.success) {
                    for (var i = 0; i < resp.data.files.length; i++) {
                        if ($mini_preview.find(".item[data-id='"+resp.data.files[i].id+"']").length == 0) {
                            __addItemToMiniPreview(resp.data.files[i]);
                        }
                    }

                    $form.find(":input[name='type']:checked").trigger("change");
                    if ($form.find(":input[name='type']:checked").val() != "album") {
                        var ids = filemanager.settings.selectedFileIds;
                        filemanager.settings.selectedFileIds = [ids[ids.length - 1]];
                    }
                }
            }
        });
    }



    // Submit form
    $form.on("submit", function() {
        var data = {};

        data.action = "post";
        data.type = $form.find(":input[name='type']:checked").val();
        
        var media_ids = [];
        $mini_preview.find(".item").each(function() {
            var file = $(this).data("file");
            media_ids.push(file.id);
        });
        data.media_ids = media_ids.join();
        data.remove_media = $form.find(":input[name='remove-media']").is(":checked") ? 1 : 0;
        
        data.caption = $form.find(".caption")[0].emojioneArea.getText();
        data.first_comment = $form.find(".first-comment")[0].emojioneArea.getText();
        data.account = $form.find(":input[name='account']").val();
        data.is_scheduled = $form.find(":input[name='schedule']").is(":checked") ? 1 : 0;
        data.schedule_date = data.is_scheduled 
                           ? $form.find(":input[name='schedule-date']").val() : 0;
        data.user_datetime_format = $form.find(":input[name='schedule-date']").data("user-datetime-format");
        data.location_label = $form.find(":input[name='location-search']").val();
        data.location_object = $form.find(":input[name='location']").val();

        if (data.type == "album" && media_ids.length < 2) {
            NextPost.DisplayFormResult($form, "error", __("Please select at least 2 media album post."));
            return false;
        } else if (data.type == "story" && media_ids.length != 1) {
            NextPost.DisplayFormResult($form, "error", __("Please select one media for story post."));
            return false;
        } else if (data.type == "timeline" && media_ids.length != 1) {
            NextPost.DisplayFormResult($form, "error", __("Please select one media for post."));
            return false;
        } else if (!data.account) {
            NextPost.DisplayFormResult($form, "error", __("Please select an Instagram account to post."));
            return false;
        }


        $("body").addClass("onprogress");
        $.ajax({
            url: $form.data("url") + "/" + $form.data("post-id"),
            type: 'POST',
            dataType: 'jsonp',
            data: data,
            error: function() {
                NextPost.DisplayFormResult($form, "error", __("Oops! An error occured. Please try again later!"));
                $("body").removeClass("onprogress");
            },

            success: function(resp) {
                if(resp.result == 1) {
                    $form.find(".form-result").html("<div class='success'><span class='sli sli-check icon'></span> "+resp.msg+"</div>");
                    $form.data("post-id", "");

                    clearTimeout(__form_result_timer);
                    $form.find(".form-result").stop(true).fadeIn();
                    $("html, body").animate({
                        scrollTop: $form.find(".form-result").offset().top - 85 + "px"
                    });


                    if (!data.is_scheduled && data.remove_media) {
                        // Remove selected media files from the DOM,
                        // Files are already removed in the backend
                        $page.find(".ofm-file").each(function() {
                            var $file = $(this);
                            if ($file.hasClass('selected')) {
                                filemanager.unselectFile($file);
                                $file.remove();
                            }
                        });

                        filemanager.checkNoFiles();

                        $preview.find(".preview-media--timeline").html("<div class='placeholder'></div>");
                        $preview.find(".img, video").remove();
                        $mini_preview.addClass('droppable');
                    }
                } else {
                    var msg = resp.msg || __("Oops! An error occured. Please try again later!");
                    NextPost.DisplayFormResult($form, "error", msg);
                }

                $("body").removeClass("onprogress");
            }
        });

        return false;
    });
}