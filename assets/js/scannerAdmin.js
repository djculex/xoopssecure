/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
/**
 * Xoops xoopsSecure module for xoops
 *
 * @copyright    2021 XOOPS Project (https://xoops.org)
 * @license      GPL 2.0 or later
 * @package      xoopsSecure
 * @since        1.0
 * @min_xoops    2.5.11
 * @author       Culex - Email:culex@culex.dk - Website:https://www.culex.dk
 */
$(document).ready(
    function () {

        var scannerstarttime = 0;
        var scannerendtime = 0;
        var scannerpertotal = 0;
        var scannercountedfiles = 0;
        var scannertotalfilestoprocess = 0;
        var scannermalwaretotalfilestoprocess = 0;
        var scannerprocessedfiles = 0;
        var scannerprocessedMalwarefiles = 0;
        var scannerprocessedCSfiles = 0;
        var scannerPercentDone = 0;
        var scannerSingleFilecounter = 0;
        var scannerSingleFileMalwarecounter = 0;
        var scannerSingleFileCScounter = 0;
        var scannerTypeSelected = $('#xoopssecure_admin_scanaction_dropdown').val(); // Get value of dropdown
        var scannerUseFastMethod = true;
        var scannerFullFiles = [];
        var scanFileStackNum = 0;
        var scanGetLatestScanTime = latestScan();
        var SendStatCall = true;
        var permRunning = false;
        var indexRunning = false;
        var malwareRunning = false;
        var ajaxisrunning = false;
        var codingstandardRunning = false;
        var timeBefore = 0;

        updateLastInfor();
    // Check for cron scan and backup
        xoopssecure_timeforcron();
        xoopssecure_timeforbackup();

    // Page specific load for log.php
        if (location.pathname.indexOf('log.php') != -1) {
            populateScanDates(); // populate dropdown with dates
            // When changing dropdown set new value
            $('#xoopssecure_admin_scanlog_dropdown').change(
                function () {
                    $(window).attr('location', 'log.php?starttime=' + $('#xoopssecure_admin_scanlog_dropdown').val());
                }
            );
        }

        $('#checkIndexfiles').prop('checked', false);
        $('#checkPermissions').prop('checked', false);
        $('#xoopssecureScannerJsonSizeContainer').hide();
        $(".xoopssecureDisPlaySourceCode").hide();

    // When changing dropdown set new value
        $('#xoopssecure_admin_scanaction_dropdown').change(
            function () {
                var scannerTypeSelected = $('#xoopssecure_admin_scanaction_dropdown').val();
                xoopssecure_SelectChanger(scannerTypeSelected);
            //alert(scannerTypeSelected);
            }
        );

    // Toggle menu items in log page
        $('a#xoopssecureMenuMALL').click(
            function (e) {
                e.preventDefault();
                $("#xoopssecure_mallware").show();
                $('#xoopssecure_indexfiles').hide();
                $('#xoopssecure_permissions').hide();
                $('#xoopssecure_codingstandards').hide();
                $("#xoopssecure_errors").hide();
            }
        );
    
    // Show index files hide other
        $('a#xoopssecureMenuIF').click(
            function (e) {
                e.preventDefault();
                $("#xoopssecure_indexfiles").show();
                $('#xoopssecure_mallware').hide();
                $('#xoopssecure_permissions').hide();
                $('#xoopssecure_codingstandards').hide();
                $("#xoopssecure_errors").hide();
            }
        );

    // Show File permissions hide other
        $('a#xoopssecureMenuFP').click(
            function (e) {
                e.preventDefault();
                $("#xoopssecure_permissions").show();
                $('#xoopssecure_mallware').hide();
                $('#xoopssecure_indexfiles').hide();
                $('#xoopssecure_codingstandards').hide();
                $("#xoopssecure_errors").hide();
            }
        );
    
    // Show Coding standards hide other
        $('a#xoopssecureMenuDEV').click(
            function (e) {
                e.preventDefault();
                $("#xoopssecure_codingstandards").show();
                $('#xoopssecure_mallware').hide();
                $('#xoopssecure_indexfiles').hide();
                $('#xoopssecure_permissions').hide();
                $("#xoopssecure_errors").hide();
            }
        );
    
    // Show Errors hide other
        $('a#xoopssecureMenuERR').click(
            function (e) {
                e.preventDefault();
                $("#xoopssecure_errors").show();
                $("#xoopssecure_codingstandards").hide();
                $('#xoopssecure_mallware').hide();
                $('#xoopssecure_indexfiles').hide();
                $('#xoopssecure_permissions').hide();
            }
        );

    // Do manual backup
        $("#xoopssecure_domanualbackup").click(
            function (e) {
                e.preventDefault();
                $("#xoopssecureBACKUPDoingBackupWaitModal").modal('show');
                doManualBackup();
            }
        );
    
    // Click to delete zip
        $('#xoopssecure_delete_zip').click(
            function (e) {
                e.preventDefault();
                attachDeleteBackup();
            }
        );

    // Attach all buttons with this id to open source
        $('button[id="xoopssecureLogshowSourceCode"]').each(
            function (index) {
                $(this).click(
                    function (event) {
                        $(".xoopssecureDisPlaySourceCode").hide();
                        var ln = $(this).data('linenumber');
                        var fn = $(this).data('filename');
                        var selector = $(this).data('id');
                        var html = loadSourcecode(fn, ln, selector);
                    //$(this).next().find("pre").html(html);
                    }
                );
            }
        );

    // Delete log by ID
        $('button[id="xoopssecureLogDeleteIssueByID"]').each(
            function (index) {
                $(this).click(
                    function (event) {
                        var id = $(this).data('issueid');
                        var html = DeleteIssueByID(id);
                        var contid = $(this).closest('tr');
                        var parid = $(this).closest('.xoopssecureIssueContainer');
                        $(contid).slideUp(
                            1000,
                            function () {
                                $(this).remove();
                                if ($(parid).find('tr').length < 2) { // If only 1 row left remove all table.
                                    $(parid).slideUp(
                                        1000,
                                        function () {
                                            $(this).remove();
                                        }
                                    );
                                }

                            }
                        );
                    }
                );
            }
        );

    // Link delete log
        $('#xoopssecureDeleteLog').each(
            function (index) {
                $(this).click(
                    function (event) {
                        var fn = $(this).attr('id');
                        var context = $(this).data('conftext');
                        var conok = $(this).data('confyes');
                        var concancel = $(this).data('confno');
                        var dtime = $(this).data('logdtime');
                        bootbox.confirm(
                            {
                                message: context,
                                buttons: {
                                    confirm: {
                                        label: conok,
                                        className: 'btn-success'
                                    },
                                    cancel: {
                                        label: concancel,
                                        className: 'btn-danger'
                                    }
                                },
                                callback: function (result) {
                                    if (!result) {
                                        var html = "OK I guess NOT!!!";
                                    } else {
                                        var html = deleteLogFromDb(dtime, result);
                                    }
                                }
                            }
                        );
                    }
                );
            }
        );

    //Delete Backup zip
        $('a#xoopssecure_delete_zip').each(
            function (index) {
                $(this).click(
                    function (event) {
                        var fn = $(this).data('id');
                        var context = $(this).data('conftext');
                        var conok = $(this).data('confyes');
                        var concancel = $(this).data('confno');
                        bootbox.confirm(
                            {
                                message: context,
                                buttons: {
                                    confirm: {
                                        label: conok,
                                        className: 'btn-success'
                                    },
                                    cancel: {
                                        label: concancel,
                                        className: 'btn-danger'
                                    }
                                },
                                callback: function (result) {
                                    if (!result) {
                                        var html = "OK I guess NOT!!!";
                                    } else {
                                        var html = deleteBackup(fn, result);
                                        //$("#xoopssecure_backup_downloadtable").html(html);
                                    }
                                }
                            }
                        );
                    }
                );
            }
        );


    // Button delete all issues by filename
        $('.xoopssecuredeleteIssueByFN').each(
            function (index) {
                $(this).click(
                    function (event) {
                        var fn = $(this).attr('id');
                        var contid = $(this).closest('.xoopssecureIssueContainer');
                        var context = $(this).data('conftext');
                        var conok = $(this).data('confyes');
                        var concancel = $(this).data('confno');
                        bootbox.confirm(
                            {
                                message: context,
                                buttons: {
                                    confirm: {
                                        label: conok,
                                        className: 'btn-success'
                                    },
                                    cancel: {
                                        label: concancel,
                                        className: 'btn-danger'
                                    }
                                },
                                callback: function (result) {
                                    if (!result) {
                                        var html = "OK I guess NOT!!!";
                                    } else {
                                        var html = addToOmitfilesByFN(fn, result);
                                        $(contid).slideUp(
                                            1000,
                                            function () {
                                                $(this).remove();
                                            }
                                        );
                                    }
                                }
                            }
                        );
                    }
                );
            }
        );

    // Button IGNOR this file in future scans
        $('.xoopssecureaddToOmitByFN').each(
            function (index) {
                $(this).click(
                    function (event) {
                        var fn = $(this).attr('id');
                        var contid = $(this).closest('.xoopssecureIssueContainer');
                        var context = $(this).data('conftext');
                        var conok = $(this).data('confyes');
                        var concancel = $(this).data('confno');
                        bootbox.confirm(
                            {
                                message: context,
                                buttons: {
                                    confirm: {
                                        label: conok,
                                        className: 'btn-success'
                                    },
                                    cancel: {
                                        label: concancel,
                                        className: 'btn-danger'
                                    }
                                },
                                callback: function (result) {
                                    if (!result) {
                                        var html = "OK I guess NOT!!!";
                                    } else {
                                        var html = addToOmitfilesByFN(fn, result);
                                        $(contid).slideUp(
                                            1000,
                                            function () {
                                                $(this).remove();
                                            }
                                        );
                                    }
                                }
                            }
                        );
                    }
                );
            }
        );

    // Button IGNOR parent DIR in future scans
        $('.xoopssecureaddToOmitByDirN').each(
            function (index) {
                $(this).click(
                    function (event) {
                        var fn = $(this).attr('id');
                        var contid = $(this).closest('.xoopssecureIssueContainer');
                        var context = $(this).data('conftext');
                        var conok = $(this).data('confyes');
                        var concancel = $(this).data('confno');
                        bootbox.confirm(
                            {
                                message: context,
                                buttons: {
                                    confirm: {
                                        label: conok,
                                        className: 'btn-success'
                                    },
                                    cancel: {
                                        label: concancel,
                                        className: 'btn-danger'
                                    }
                                },
                                callback: function (result) {
                                    if (!result) {
                                        var html = "OK I guess NOT!!!";
                                    } else {
                                        var html = addToOmitdirsByDirN(fn, result);
                                        xoopssecureRemoveIdByString('fieldset', fn);
                                        $(contid).slideUp(
                                            1000,
                                            function () {
                                                $(this).remove();
                                            }
                                        );
                                    }
                                }
                            }
                        );
                    }
                );
            }
        );

    // Click to get file stack    
        $('#xoopssecure_scanner_test').click(
            function (event) {
                getFileStack();
            }
        );

    // Click to do part initial scan
        $("#xoopssecure_scanner_healtyInstall").click(
            function (event) {
                $("#xoopssecureScannerFirstTime").modal("hide");
            }
        );

    // Click to do FULL initial scan
        $("#xoopssecure_scanner_nothealtyInstall").click(
            function (event) {
                $("#xoopssecureScannerFirstTime").modal("hide");
            }
        );

    // Suggestions for form in config
        if (window.location.href.indexOf("admin.php?fct=preferences") > -1) {
            $('#XCISSTARTPATH, #XCISDEVSTARTPATH').attr("autocomplete", "off");
            $('#XCISSTARTPATH, #XCISDEVSTARTPATH').typeahead(
                {
                    source: function (query, result) {
                        $.ajax(
                            {
                                url: xoopsSecureSysUrl + "agent.php",
                                data: 'type="suggest"&query=' + query,
                                dataType: "json",
                                type: "GET",
                                success: function (data) {
                                    result(
                                        $.map(
                                            data,
                                            function (item) {
                                                return item;
                                            }
                                        )
                                    );
                                }
                            }
                        );
                    }
                }
            ); //end function
        };

    // Start the scanner
        $('#xoopssecure_scanner_start').click(
            function (event) {
            //start button is clicked
                timeBefore = 0;
                scannerTypeSelected = $('#xoopssecure_admin_scanaction_dropdown').val();
                $("#xoopssecure_scanner_start").attr('disabled', 'disabled'); //Disable button
                $("#xoopssecure_scanner_processbar_if").css("width", "0"); //Set processbar at null
                $("#xoopssecure_scanner_processbar_ps").css("width", "0"); //Set processbar at null
                $("#xoopssecure_scanner_processbar_mal").css("width", "0"); //Set processbar at null
                $("#xoopssecure_scanner_processbar_cs").css("width", "0"); //Set processbar at null

                scannerstarttime = new Date().getTime(); // Start the clock
                if (scannerTypeSelected == 0 || scannerTypeSelected == 2) {
                    if (!indexRunning) {
                        startScan(); //Start scan
                    }
                }
                if (scannerTypeSelected == 0 || scannerTypeSelected == 1) {
                    if (!permRunning) {
                        checkpermissions();
                        doBackup();
                    }
                }
                if (scannerTypeSelected == 0 || scannerTypeSelected == 3) {
                    if (!malwareRunning) {
                        startScanMalware();
                    }
                }
                if (scannerTypeSelected == 4) {
                    if (!codingstandardRunning) {
                        startcodingstandard();
                    }
                }
            }
        );

    // Get a count of folders in json
        function getFolderCount()
        {
            $.getJSON(
                'agent.php?type=getdirnum',
                (data) => {
                    scannertotalfilestoprocess = data;
                }
            );
        }

    // If coding standard hide check buttons else enable them
        function xoopssecure_SelectChanger(value)
        {
            if (value == 4) {
                $("#checkIndexfiles, #checkPermissions").prop("disabled", true);
            } else {
                $("#checkIndexfiles, #checkPermissions").prop("disabled", false);
            }
        }

    // Function reacting to start scan click
        function startScan()
        {
            $('#xoopssecureScannerGettingFilesWaitModal').modal('show');
            timeBefore = Date.now();
            indexRunning = true;
            scannercountedfiles = 1;
            $.ajax(
                {
                    url: xoopsSecureSysUrl + 'agent.php?type=jsonfiledir',
                    dataType: 'json',
                    async: true,
                    success: function (data) {
                        $('#xoopssecureScannerGettingFilesWaitModal').modal('hide');
                        scannertotalfilestoprocess = data.length;
                        $.each(
                            data,
                            function (i, item) {
                                $('#xoopssecureScannerGettingFilesWaitModal').modal('hide');
                                scannerprocessedfiles = scannercountedfiles + i;
                                if (scannerTypeSelected == 0 || scannerTypeSelected == 2) {
                                    singleFileScan(item, scannerprocessedfiles);
                                }
                            }
                        );

                    },
                    complete: function () {
                        doStats();
                    }
                }
            );
            indexRunning = false;
            scannerendtime = new Date().getTime(); // Stop the clock
            return;
        }

    // Start malware scan
        function startScanMalware()
        {
            $('#xoopssecureScannerGettingFilesWaitModal').modal('show');
            timeBefore = Date.now();
            malwareRunning = true;
            scannercountedfiles = 1;
            $.ajax(
                {
                    url: xoopsSecureSysUrl + 'agent.php?type=getFilesJson',
                    dataType: 'json',
                    async: true,
                    success: function (data) {
                        $('#xoopssecureScannerGettingFilesWaitModal').modal('hide');
                        scannermalwaretotalfilestoprocess = data.length;
                        if (scannermalwaretotalfilestoprocess < 1) {
                            $("#xoopssecure_scanner_processbar_mal").css("width", "100%");
                        }
                        $.each(
                            data,
                            function (i, item) {
                                scannerprocessedMalwarefiles = scannerprocessedMalwarefiles + i;
                                if (scannerTypeSelected == 0 || scannerTypeSelected == 3) {
                                    singleFileScanMalware(item, scannerprocessedMalwarefiles);
                                }
                            }
                        );

                    },
                    complete: function () {
                        doStats();
                    }
                }
            );
            malwareRunning = false;
            scannerendtime = new Date().getTime(); // Start the clock

            return;
        }

        function startcodingstandard()
        {
            //$('#xoopssecureScannerGettingFilesWaitModal').modal('show');
            timeBefore = Date.now();
            codingstandardRunning = true;
            scannercountedfiles = 1;
            $.ajax(
                {
                    url: xoopsSecureSysUrl + 'cs.php?type=getFilesJson',
                    dataType: 'json',
                    async: true,
                    beforeSend: function () {
                        $('#xoopssecureScannerGettingFilesWaitModal').modal('show');
                    },
                    success: function (data) {
                        // Wierd bug ...but works for now ?!?
                        //$('#xoopssecureScannerGettingFilesWaitModal').modal('hide');
                        $('#xoopssecureScannerGettingFilesWaitModal').hide();
                        $('.modal-backdrop').hide();
                        scannerCStotalfilestoprocess = data.length;
                        if (scannerCStotalfilestoprocess < 1) {
                            $("#xoopssecure_scanner_processbar_CS").css("width", "100%");
                        }
                        $.each(
                            data,
                            function (i, item) {

                                scannerprocessedCSfiles = scannerprocessedCSfiles + i;
                                if (scannerTypeSelected == 4) {
                                    singleFileScanCS(item, scannerprocessedCSfiles);
                                }
                            }
                        );

                    },
                    complete: function () {
                        doStats();
                    }
                }
            );
            codingstandardRunning = false;
            scannerendtime = new Date().getTime(); // Start the clock

            return;
        };

    // Check latest scan
        function getIssueCount(time, issue)
        {
            var v = 0;
            $.ajax(
                {
                    url: xoopsSecureSysUrl + 'agent.php?type=getIssueCount&time=' + time + '&issue=' + issue,
                    dataType: 'json',
                    async: true,
                    success: function (data) {
                        v = data;
                    },
                    complete: function (data) {
                        v = data;
                    }
                }
            );
            return v;
        }

    //Calculate time of ajax loop
        function doTime(selector, item, stackNum, timeBefore)
        {
            $(selector).show();
            var timeNow = Date.now();
            var TimePassed = timeNow - timeBefore;
            var TimePerItem = TimePassed / item;
            var TimeForStack = (stackNum - item) * TimePerItem;
            var SecondsLeft = Math.round(TimeForStack / 1000);
            $(selector).text("Eta " + formatTime(SecondsLeft));
        }

        function formatTime(seconds)
        {
            const h = Math.floor(seconds / 3600);
            const m = Math.floor((seconds % 3600) / 60);
            const s = Math.round(seconds % 60);
            return [
            h,
            m > 9 ? m : (h ? '0' + m : m || '0'),
            s > 9 ? s : '0' + s
            ].filter(Boolean).join(':');
        }

        function loadSourcecode(filename, linenumber, selector)
        {
            var v = 0;
            $.ajax(
                {

                    url: xoopsSecureSysUrl + "agent.php?type=getSourceCode&filename=" + filename + "&linenumber=" +
                    linenumber,
                    dataType: 'html',
                    async: false,
                    success: function (data) {
                        $("div[data-id='" + selector + "']").html(data);
                        $("div[data-id='" + selector + "']").show();
                    },
                    complete: function (data) {
                        v = data;
                    }
                }
            );
        }

        function checkpermissions()
        {
            var psBefore = 0;
            permRunning = true;
            $.ajax(
                {
                    url: xoopsSecureSysUrl + 'agent.php?type=checkpermissions&counter=' + 1 + "/" + 3 +
                    '&checkIndexfiles=' + isChecked('#checkIndexfiles') + '&checkPermissions=' +
                    isChecked('#checkPermissions') + '&scanstart=' + scannerstarttime,
                    dataType: 'json',
                    async: true,
                    success: function (data) {

                    },
                    complete: function (data) {
                        upDateProcessScanner(3, 3, "#xoopssecure_scanner_processbar_ps");
                        doStats();
                    }
                }
            );
            permRunning = false;
            scannerpertotal = (isChecked('#checkPermissions')) ? 3 : 0;
            scannerendtime = new Date().getTime(); // Stop the clock

        }

        function populateScanDates()
        {
            $.ajax(
                {
                    url: xoopsSecureSysUrl + 'agent.php?type=scanDatesForDropdown',
                    dataType: 'json',
                    async: true,
                    success: function (data) {
                        $.each(
                            data,
                            function (index, value) {
                                // APPEND OR INSERT DATA TO SELECT ELEMENT.
                                $('#xoopssecure_admin_scanlog_dropdown').append(
                                    '<option value="' + value.id +
                                    '">' + value.name + '</option>'
                                );
                            }
                        );
                    },
                    complete: function () {}
                }
            );
        }

    /*
     * Delete ALL issues by filename
     *
     */
        function deleteIssueByFN(fn, conf)
        {
            if (!conf) {
                return false;
            } else {
                $.ajax(
                    {
                        url: xoopsSecureSysUrl + 'agent.php?type=xoopsSecuredeleteIssueByFN' + '&id=' + fn + '&conf=' +
                        conf,
                        dataType: 'json',
                        async: true,
                        success: function (data) {},
                        complete: function (data) {
                        }
                    }
                );
            }
        }

    /*
     * Delete issue by ID
     *
     */
        function DeleteIssueByID(id)
        {
            $.ajax(
                {
                    url: xoopsSecureSysUrl + 'agent.php?type=xoopsSecureDeleteIssueByID' + '&id=' + id,
                    dataType: 'json',
                    async: true,
                    success: function (data) {},
                    complete: function (data) {
                    }
                }
            );
        }

    /* Time to run the con file ? */
        function xoopssecure_timeforcron()
        {
            $.ajax(
                {
                    url: xoopsSecureSysUrl + 'agent.php?type=doCronScan',
                    dataType: 'json',
                    async: true
                }
            );
        }
    /* Time to run auto backup ? */
        function xoopssecure_timeforbackup()
        {
            $.ajax(
                {
                    url: xoopsSecureSysUrl + 'agent.php?type=doAutoCreatezip',
                    dataType: 'json',
                    async: true
                }
            );
        }
    /*
     * Delete log from database
     * - Delete all issues from db using scandate and conform
     * @param dtime timestamp for scan
     * @param result of confirmbox (true or false)
     */
        function deleteLogFromDb(dtime, result)
        {
            if (!result) {
                return false;
            } else {
                $.ajax(
                    {
                        url: xoopsSecureSysUrl + 'agent.php?type=xoopsSecureLogfromDbByTime' + '&dtime=' + dtime,
                        dataType: 'json',
                        async: false,
                        success: function (data) {

                        },
                        complete: function (data) {
                            setTimeout(
                                function () {
                                    $(location).prop('href', xoopsSecureSysUrl + "scanhome.php");
                                },
                                3000
                            );
                        }
                    }
                );
            }
        }

    /* Delete zip from drive
     * @param fn the name of zip
     * @param result of confirm
     * @return void
     */
        function deleteBackup(fn, result)
        {
            if (!result) {
                return false;
            } else {
                $.ajax(
                    {
                        url: xoopsSecureSysUrl + 'agent.php?type=deleteZip' + '&fn=' + fn,
                        //dataType: 'text/html',
                        async: false,
                        success: function (data) {
                            $("#xoopssecure_backup_downloadtable").html(data);
                        },
                        complete: function (data) {
                            // Buggy but reattach click event to DOM inserted new html
                            attachDeleteBackup();

                        }
                    }
                );
            }
        }

    /* Get html for backup table */
        function getBackupTableHtml()
        {
            $.ajax(
                {
                    url: xoopsSecureSysUrl + 'agent.php?type=getZipHtml',
                    //dataType: 'text/html',
                    async: false,
                    success: function (data) {
                        $("#xoopssecure_backup_downloadtable").html(data);
                    },
                    complete: function (data) {
                        // Buggy but reattach click event to DOM inserted new html
                        attachDeleteBackup();

                    }
                }
            );
        }

    //Attach delete backup click selector,
    // set in a function as it has to be re-attached after DOM inserted new html.
        function attachDeleteBackup()
        {
            $('a#xoopssecure_delete_zip').each(
                function (index) {
                    $(this).click(
                        function (event) {
                            var fn = $(this).data('id');
                            var context = $(this).data('conftext');
                            var conok = $(this).data('confyes');
                            var concancel = $(this).data('confno');
                            bootbox.confirm(
                                {
                                    message: context,
                                    buttons: {
                                        confirm: {
                                            label: conok,
                                            className: 'btn-success'
                                        },
                                        cancel: {
                                            label: concancel,
                                            className: 'btn-danger'
                                        }
                                    },
                                    callback: function (result) {
                                        if (!result) {
                                            var html = "OK I guess NOT!!!";
                                        } else {
                                            var html = deleteBackup(fn, result);
                                        }
                                    }
                                }
                            );
                        }
                    );
                }
            );
        }

    /*
     * ADD to OMIT files in future scans
     *
     */
        function addToOmitfilesByFN(fn, conf)
        {
            if (!conf) {
                return false;
            } else {
                $.ajax(
                    {
                        url: xoopsSecureSysUrl + 'agent.php?type=xoopsSecureAddtoOmitfilesByFilename' + '&id=' + fn +
                        '&conf=' + conf,
                        dataType: 'json',
                        async: true,
                        success: function (data) {},
                        complete: function (data) {
                        }
                    }
                );
            }
        }

    /*
     * Remove all tr selectores containing string in id
     * @param sel type of selector
     * @param string contained in id
     * @return void
     *
     */
        function xoopssecureRemoveTrIdByString(sel, string)
        {
            $("tr[id*='xoopssecure_trbackup_" + string + "']").each(
                function (i, e) {
                    $("tr[id='xoopssecure_trbackup_" + string + "']").remove();
                }
            );
        }

    /*
     * Remove all fieldset selectores containing string in id
     * @param sel type of selector
     * @param string contained in id
     * @return void
     *
     */
        function xoopssecureRemoveIdByString(sel, string)
        {
            $("fieldset[id*='" + string + "']").each(
                function (i, e) {
                    $("fieldset[id='" + $(e).attr('id') + "']").remove();
                }
            );
        }

    //addToOmitdirsByDirN
    /*
     * ADD to OMIT files in future scans
     *
     */
        function addToOmitdirsByDirN(fn, conf)
        {
            if (!conf) {
                return false;
            } else {
                $.ajax(
                    {
                        url: xoopsSecureSysUrl + 'agent.php?type=xoopssecureaddToOmitByDirN' + '&id=' + fn +
                        '&conf=' + conf,
                        dataType: 'json',
                        async: true,
                        success: function (data) {

                        },
                        complete: function (data) {}
                    }
                );
            }
        }

        function singleFileScan(fn, i)
        {
            $.ajax(
                {
                    url: xoopsSecureSysUrl + 'agent.php?type=singleFileTest&Dir=' + fn + '&counter=' + i + "/" +
                    scannertotalfilestoprocess + '&checkIndexfiles=' + isChecked('#checkIndexfiles') +
                    '&checkPermissions=' + isChecked('#checkPermissions') + '&scanstart=' +
                    scannerstarttime,
                    dataType: 'json',
                    async: true,
                    success: function (data) {},
                    complete: function (data) {
                        scannerSingleFilecounter = scannerSingleFilecounter + 1;
                        upDateProcessScanner(
                            scannertotalfilestoprocess,
                            scannerSingleFilecounter,
                            "#xoopssecure_scanner_processbar_if"
                        );
                        if (i % 10 == 0) {
                            doTime(
                                '#xoopssecure_scanner_eta_if',
                                scannerSingleFilecounter,
                                scannertotalfilestoprocess,
                                timeBefore
                            );
                        }
                    }
                }
            );
        }

        function singleFileScanMalware(fn, i)
        {
            $('#xoopssecureScannerGettingFilesWaitModal').modal('hide');
            $.ajax(
                {
                    url: xoopsSecureSysUrl + 'agent.php?type=singleMalwareScan&filePath=' + fn + '&counter=' + i +
                    "/" + scannermalwaretotalfilestoprocess + '&checkIndexfiles=' + isChecked(
                        '#checkIndexfiles'
                    ) + '&checkPermissions=' + isChecked('#checkPermissions') +
                '&scanstart=' + scannerstarttime,
                dataType: 'json',
                async: true,
                success: function (data) {

                },
                    complete: function (data) {
                        scannerSingleFileMalwarecounter = scannerSingleFileMalwarecounter + 1;
                        upDateProcessScanner(
                            scannermalwaretotalfilestoprocess,
                            scannerSingleFileMalwarecounter,
                            "#xoopssecure_scanner_processbar_mal"
                        );
                        if (i % 50 == 0) {
                            doTime(
                                '#xoopssecure_scanner_eta_mal',
                                scannerSingleFileMalwarecounter,
                                scannermalwaretotalfilestoprocess,
                                timeBefore
                            );
                        }
                        doStats();
                    },
                }
            );
        }

        function singleFileScanCS(fn, i)
        {
            $('#xoopssecureScannerGettingFilesWaitModal').modal('hide');
            $.ajax(
                {
                    url: xoopsSecureSysUrl + 'cs.php?type=singleCsScan&filePath=' + fn + '&counter=' + i +
                    "/" + scannerCStotalfilestoprocess +
                    '&scanstart=' + scannerstarttime,
                    dataType: 'json',
                    async: true,
                    success: function (data) {

                    },
                    complete: function (data) {
                        scannerSingleFileCScounter = scannerSingleFileCScounter + 1;
                        upDateProcessScanner(
                            scannerCStotalfilestoprocess,
                            scannerSingleFileCScounter,
                            "#xoopssecure_scanner_processbar_cs"
                        );
                        if (i % 2 == 0) {
                            doTime(
                                '#xoopssecure_scanner_eta_cs',
                                scannerSingleFileCScounter,
                                scannerCStotalfilestoprocess,
                                timeBefore
                            );
                        }
                        doStats();
                    },
                }
            );
        }

    // Get all files in folder as json array
        function getFileStack()
        {
            spinner.show();
            $("#xoopssecureScannerGettingFilesWaitModal").modal(
                {
                    backdrop: 'static',
                    keyboard: false
                }
            );
            $('#xoopssecureScannerGettingFilesWaitModal').modal('show');
            $.ajax(
                {
                    url: xoopsSecureSysUrl + 'agent.php?type=getFilesJson',
                    dataType: 'json',
                    async: true,
                    success: function (data) {
                        scannerFullFiles = data;
                        scanFileStackNum = data.length;

                    },
                    complete: function (data) {
                        if (scanFileStackNum < 10) {
                            $('#xoopssecureScannerJsonSizeContainer').addClass("alert alert-primary");
                        } else if (scanFileStackNum > 10 && scanFileStackNum <= 50) {
                            $('#xoopssecureScannerJsonSizeContainer').addClass("alert alert-secondary");
                        } else if (scanFileStackNum > 50 && scanFileStackNum <= 200) {
                            $('#xoopssecureScannerJsonSizeContainer').addClass("alert alert-warning");
                        } else if (scanFileStackNum > 200) {
                            $('#xoopssecureScannerJsonSizeContainer').addClass("alert alert-danger");
                        }
                        $('#xoopssecureScannerJsonSizeNumber').html(scanFileStackNum);
                        $('#xoopssecureScannerJsonSizeContainer').show();
                    }
                }
            ).done(
                function (data) {
                    $('xoopssecureScannerGettingFilesWaitModal').modal('hide');
                    spinner.hide();
                }
            );
        }

    // Check latest scan
        function latestScan()
        {
                $.ajax(
                    {
                        url: xoopsSecureSysUrl + 'agent.php?type=getScanDate',
                        dataType: 'json',
                        async: true,
                        success: function (data) {
                            scanGetLatestScanTime = data;
                        },
                        complete: function (data) {
                            if (scanGetLatestScanTime > 0) {
                                $('#xoopssecureScannerFirstTime').modal('hide');
                            } else {
                                $('#xoopssecureScannerFirstTime').modal('show');
                            }
                        }
                    }
                );
        }

    //do stats
        function doStats()
        {
            var ps = 0;
            var is = 0;
            var ms = 0;
            var cs = 0;
            $(document).ajaxStop(
                function () {
                    if (SendStatCall == true) {
                        setTimeout(
                            function () {

                                // We wait only permission scan
                                if (scannerTypeSelected == 1) {
                                    if (permRunning == false) {
                                        ps = 3;
                                        is = 0;
                                        ms = 0;
                                    }
                                }

                                //We wait for indexscan to finish
                                if (scannerTypeSelected == 2) {
                                    if (indexRunning == false) {
                                        ps = 0;
                                        is = scannertotalfilestoprocess;
                                        ms = 0;
                                    }
                                }
                                // We wait for Malwarescan to finish
                                if (scannerTypeSelected == 3) {
                                    if (malwareRunning == false) {
                                    }

                                    ps = 0;
                                    is = 0;
                                    ms = scannermalwaretotalfilestoprocess;
                                }

                                // We wait for CSscan to finish
                                if (scannerTypeSelected == 4) {
                                    if (codingstandardRunning == false) {
                                    }

                                    ps = 0;
                                    is = 0;
                                    ms = 0;
                                    cs = scannerCStotalfilestoprocess;
                                }

                                // We wait all. Last scan is malware
                                if (scannerTypeSelected == 0) {
                                    if (malwareRunning == false && indexRunning == false && permRunning ==
                                    false) {
                                        ps = 3;
                                        is = scannertotalfilestoprocess;
                                        ms = scannermalwaretotalfilestoprocess;
                                    }
                                }
                                if (SendStatCall) {
                                    sendStats(
                                        scannerstarttime,
                                        scannerendtime,
                                        scannerTypeSelected,
                                        ps,
                                        is,
                                        ms,
                                        cs
                                    );
                                } else {
                                    return false;
                                }
                            },
                            4000
                        );
                    }
                }
            );

        }

        function sendStats(starttime, endtime, type, ps, is, ms, cs)
        {
            SendStatCall = false;
            $.ajax(
                {
                    url: xoopsSecureSysUrl + "agent.php?type=DoStatsEnd&starttime=" + starttime + "&endtime=" +
                    endtime + "&scantype=" + type + "&ps=" + ps + "&is=" + is + "&ms=" + ms + "&cs=" + cs
                }
            ).done(
                function (data) {
                    if (data) {
                        $('#xoopssecure_last_log_div').html(data);
                    }
                }
            );
            return false;
        }

        function updateLastInfor()
        {
            $.ajax(
                {
                    url: xoopsSecureSysUrl + "agent.php?type=GetLatestInfoforScanpage"
                }
            ).done(
                function (data) {
                    if (data) {
                        $('#xoopssecure_last_log_div').html(data);
                    }
                }
            );
            return false;
        }

        function doBackup()
        {
            $.ajax(
                {
                    url: xoopsSecureSysUrl + "agent.php?type=doAutoCreatezip"
                }
            );
            return false;
        }

        function doManualBackup()
        {
            $.ajax(
                {
                    url: xoopsSecureSysUrl + "agent.php?type=createzip"
                }
            ).done(
                function (data) {
                    $("#xoopssecureBACKUPDoingBackupWaitModal").modal('hide');
                    getBackupTableHtml();
                }
            );
            return false;
        }

        function upDateProcessScanner(scannertotalfilestoprocess, scannerprocessedfiles, name)
        {
            var percent = (scannerprocessedfiles / scannertotalfilestoprocess) * 100;
            $(name).css("width", Math.round(percent) + "%"); //update processbar width
            //update processbar text
            $(name).html(scannerprocessedfiles + "/" + scannertotalfilestoprocess + " (" + Math.round(percent) + "%)");
        }

        function isChecked(val)
        {
            if ($(val).is(':checked')) {
                return true;
            } else {
                return false;
            }
        }

        function test(i)
        {
            $.getJSON(xoopsSecureSysUrl + 'agent.php?type=test?id=' + i, (data) => {});
        }

    }
);