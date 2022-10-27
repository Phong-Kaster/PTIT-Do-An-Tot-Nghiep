<?php 
    /**
     * This is a temporary function used just in this file
     * It's being used to escape quotes for JS strings
     * @param  string $string 
     * @return string         
     */
    function js_str_format($string)
    {
        return str_replace("'", "\'", $string);
    }
?>
<script type="text/javascript" charset="utf-8">
    var __ = function(msgid) 
    {
        return window.i18n[msgid] || msgid;
    };

    window.i18n = {
        'Are you sure?': '<?= js_str_format(__('Are you sure?')) ?>',
        'It is not possible to get back removed data!': '<?= js_str_format(__("It is not possible to get back removed data!")) ?>',
        'Yes, Delete': '<?= js_str_format(__("Yes, Delete")) ?>',
        'Cancel': '<?= js_str_format(__("Cancel")) ?>',
        'Fill required fields': '<?= js_str_format(__("Fill required fields.")) ?>',
        'Please select at least 2 media album post.': '<?= js_str_format(__("Please select at least 2 media album post.")) ?>',
        'Please select one media for story post.': '<?= js_str_format(__("Please select one media for story post.")) ?>',
        'Please select one media for post.': '<?= js_str_format(__("Please select one media for post.")) ?>',
        'Please select an Instagram account to post.': '<?= js_str_format(__("Please select an Instagram account to post.")) ?>',
        'Oops! An error occured. Please try again later!': '<?= js_str_format(__("Oops! An error occured. Please try again later!")) ?>',
        'Use the TAB key to insert emoji faster': '<?= js_str_format(__('Use the TAB key to insert emoji faster')) ?>',
        'Total Posts': '<?= js_str_format(__("Total Posts")) ?>',
        'Followers': '<?= js_str_format(__("Followers")) ?>',
        'Following': '<?= js_str_format(__("Following")) ?>',
        'Uploading...': '<?= js_str_format(__("Uploading...")) ?>',
        'Do you really want to cancel automatic payments?': '<?= js_str_format(__("Do you really want to cancel automatic payments?")) ?>',
        'Yes, cancel automatic payments': '<?= js_str_format(__("Yes, cancel automatic payments")) ?>',
        'No': '<?= js_str_format(__("No")) ?>',
        'Verification': '<?= js_str_format(__("Verification")) ?>',
        'Searching for %s': '<?= js_str_format(__("Searching for %s...")) ?>',
        '+%s more': '<?= js_str_format(__("+%s more")) ?>'
    };

    $.fn.datepicker.language['<?= ACTIVE_LANG ?>'] = {
        days: ['<?= js_str_format(__("Sunday")) ?>', '<?= js_str_format(__("Monday")) ?>', '<?= js_str_format(__("Tuesday")) ?>', '<?= js_str_format(__("Wednesday")) ?>', '<?= js_str_format(__("Thursday")) ?>', '<?= js_str_format(__("Friday")) ?>', '<?= js_str_format(__("Saturday")) ?>'],
        daysShort: ['<?= js_str_format(__('Sun')) ?>', '<?= js_str_format(__('Mon')) ?>', '<?= js_str_format(__('Tue')) ?>', '<?= js_str_format(__('Wed')) ?>', '<?= js_str_format(__('Thu')) ?>', '<?= js_str_format(__('Fri')) ?>', '<?= js_str_format(__('Sat')) ?>'],
        daysMin: ['<?= js_str_format(__('Su')) ?>', '<?= js_str_format(__('Mo')) ?>', '<?= js_str_format(__('Tu')) ?>', '<?= js_str_format(__('We')) ?>', '<?= js_str_format(__('Th')) ?>', '<?= js_str_format(__('Fr')) ?>', '<?= js_str_format(__('Sa')) ?>'],
        months: ['<?= js_str_format(__('January')) ?>','<?= js_str_format(__('February')) ?>','<?= js_str_format(__('March')) ?>','<?= js_str_format(__('April')) ?>','<?= js_str_format(__('May')) ?>','<?= js_str_format(__('June')) ?>', '<?= js_str_format(__('July')) ?>','<?= js_str_format(__('August')) ?>','<?= js_str_format(__('September')) ?>','<?= js_str_format(__('October')) ?>','<?= js_str_format(__('November')) ?>','<?= js_str_format(__('December')) ?>'],
        monthsShort: ['<?= js_str_format(__('Jan')) ?>', '<?= js_str_format(__('Feb')) ?>', '<?= js_str_format(__('Mar')) ?>', '<?= js_str_format(__('Apr')) ?>', '<?= js_str_format(__('May')) ?>', '<?= js_str_format(__('Jun')) ?>', '<?= js_str_format(__('Jul')) ?>', '<?= js_str_format(__('Aug')) ?>', '<?= js_str_format(__('Sep')) ?>', '<?= js_str_format(__('Oct')) ?>', '<?= js_str_format(__('Nov')) ?>', '<?= js_str_format(__('Dec')) ?>'],
        today: '<?= js_str_format(__('Today')) ?>',
        clear: '<?= js_str_format(__('Clear')) ?>',
        dateFormat: 'mm/dd/yyyy',
        timeFormat: 'hh:ii aa',
        firstDay: 1
    };

    if (typeof $.fn.oneFileManager !== 'undefined') {
        $.fn.oneFileManager.i18n["<?= ACTIVE_LANG ?>"] = {
            selectFiles: '<?= js_str_format(__("Select files")) ?>',
            loadMoreFiles: '<?= js_str_format(__("Load more")) ?>',
            viewFile: '<?= js_str_format(__("View")) ?>',
            deleteFile: '<?= js_str_format(__("Delete")) ?>',
            urlInputPlaceholder: '<?= js_str_format(__("Paste your link here...")) ?>',

            emptyVolume: '<?= js_str_format(__("Your media volume is empty. <br /> Drag some files here.")) ?>',
            dropFilesHere: '<?= js_str_format(__("Drop files here to upload")) ?>',
            deleteFileConfirm: '<?= js_str_format(__("This file and all uncompleted posts which this file is assigned to will be removed. This process cannot be undone. Do you want to proceed?")) ?>',
            bigFileSizeError: '<?= js_str_format(__("File size exceeds max allowed file size.")) ?>',
            fileTypeError: '<?= js_str_format(__("File type is not allowed.")) ?>',
            noEnoughVolumeError: '<?= js_str_format(__("There is not enough storage to upload this file")) ?>',
            queueSizeLimit: '<?= js_str_format(__("There are so many files in upload queue. Please try again after current upload queue finishes.")) ?>',
            deleteMessage: '<?= js_str_format(__("{count} file(s) selected.")) ?>',
            deleteMessageBtn: '<?= js_str_format(__("Remove?")) ?>',
            removeSelectionConfirmation: '<?= js_str_format(__("Do you want to remove the selected files? This process can not be undone.")) ?>',
            usedXofY: '<?= js_str_format(__("Used {used} of {total}")) ?>',
            usedX: '<?= js_str_format(__("Used {used}")) ?>',
            total: '<?= js_str_format(__("Total")) ?>',
            xfiles: '<?= js_str_format(__("{count} files")) ?>',
            remainingStorage: '<?= js_str_format(__("Remaining storage")) ?>',
            maxUploadSize: '<?= js_str_format(__("Max. file size to upload")) ?>',
            unlimitedStorage: '<?= js_str_format(__("Unlimited storage")) ?>',
            clearStorage: '<?= js_str_format(__("Clear storage")) ?>',
            clearStorageConfirm: '<?= js_str_format(__("Do you really want to remove all of your files? Your scheduled posts will not be published. This action not be undone.")) ?>',
        };
    }
</script>