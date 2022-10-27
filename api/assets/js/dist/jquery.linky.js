/**
 * jquery.linky.js v0.1.8
 * https://github.com/AnSavvides/jquery.linky
 * The MIT License (MIT)
 *
 * Copyright (c) 2013 - 2015 Andreas Savvides
 *
 * Edit:  - usernames with one period
 *        - instagram tag link
 *     
 */
(function($) {

    "use strict";

    $.fn.linky = function(options) {
        return this.each(function() {
            var $el = $(this),
                linkifiedContent = _linkify($el, options);

            $el.html(linkifiedContent);
        });
    };

    function _linkify($el, options) {
        var links = {
                twitter: {
                    baseUrl: "https://twitter.com/",
                    hashtagSearchUrl: "hashtag/"
                },
                instagram: {
                    baseUrl: "http://instagram.com/",
                    hashtagSearchUrl: "explore/tags/"
                },
                github: {
                    baseUrl: "https://github.com/",
                    hashtagSearchUrl: null
                }
            },
            defaultOptions = {
                mentions: false,
                hashtags: false,
                urls: true,
                linkTo: "twitter" // Let's default to Twitter
            },
            extendedOptions = $.extend(defaultOptions, options),
            elContent = $el.html(),
            // Regular expression courtesy of Matthew O'Riordan, see: http://goo.gl/3syEKK
            urlRegEx = /((([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)((?:\/[\+~%\/\.\w\-]*)?\??(?:[\-\+=&;%@\.\w]*)#?(?:[\.\!\/\\\w]*))?)/g,
            matches;

            // Linkifying URLs
            if (extendedOptions.urls) {
                matches = elContent.match(urlRegEx);
                if (matches) {
                    elContent = _linkifyUrls(matches, $el);
                }
            }

            // Linkifying mentions
            if (extendedOptions.mentions) {
                elContent = _linkifyMentions(elContent, links[extendedOptions.linkTo].baseUrl);
            }

            // Linkifying hashtags
            if (extendedOptions.hashtags) {
                elContent = _linkifyHashtags(elContent, links[extendedOptions.linkTo]);
            }

        return elContent;
    }

    // For any URLs present, unless they are already identified within
    // an `a` element, linkify them.
    function _linkifyUrls(matches, $el) {
        var elContent = $el.html();

        $.each(matches, function() {
            // Only linkify URLs that are not already identified as
            // `a` elements with an `href`.
            if ($el.find("a[href='" + this + "']").length === 0) {
                elContent = elContent.replace(this, "<a href='" + this + "' target='_blank'>" + this + "</a>");
            }
        });

        return elContent;
    }

    // Find any mentions (e.g. @andrs) and turn them into links that
    // refer to the appropriate social profile (e.g. twitter or instagram).
    function _linkifyMentions(text, baseUrl) {
        return text.replace(/(^|\s|\(|>)@(\w+(\.?\w+)?)/g, "$1<a href='" + baseUrl + "$2' target='_blank'>@$2</a>");
    }

    // Find any hashtags (e.g. #linkyrocks) and turn them into links that refer
    // to the appropriate social profile.
    function _linkifyHashtags(text, links) {
        // If there is no search URL for a hashtag, there isn't much we can do
        if (links.hashtagSearchUrl === null) return text;
        return text.replace(/(^|\s|\(|>)#((\w|[\u00A1-\uFFFF])+)/g, "$1<a href='" + links.baseUrl + links.hashtagSearchUrl + "$2' target='_blank'>#$2</a>");
    }

}(jQuery));
