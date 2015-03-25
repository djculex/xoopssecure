/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * XoopsSecure Javascript
 *  
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      Culex aka (Michael Albertsen)
 * @version     $Id:$
 */

jQuery(document).ready(function($){   

    var xoopssecure_developerUrl = xoopssecure_root;
    var xoopssecure_FullUrl = $(".xoopssecure_fullscan_dir").text();
    var xoopssecure_QuickUrl = $(".xoopssecure_Quickscan_dir").text();
    var xoopssecure_developokimage = "<img src='" + xoopssecure_anonurl + "assets/images/okstamp.png" + "'/>";
    var xoopssecure_ActiveConfigItem = 'xoopssecure_configcontainer';
    
    $("#xoopssecure_filetreeDevCont").hide();
    $(".xoopssecure_code").hide();
	$("#xoopssecure_existingbackup_cont").hide();
	$("#xoopssecure_existingbackupDelete").hide();	

    $(function() { 
        $( "#xoopssecure_fullscan" ).on('click', function () {
            var starttime = $.now();
            var url = "#xoopssecure_loadingScreen";
            
            $('#xoopssecure_issuesfoundwrapper').hide();
            
            $(url).hide();
            $("#xoopssecure_filefromfile").hide();
            $(this).colorbox({
                 inline:true,
                 href:url,
                 width:"70%",
                 height:"200px",
                 onOpen:function(){
                    $(url).show();
                    $("#cboxClose").hide();
                    $.colorbox.resize();
                 },
                 onCleanup:function(){
                    $(url).hide();
                 }
            }).attr("href","javascript:void(0)");
			//xoopssecure_dobackup();
            $.ajax({
                type: 'GET',
                url: xoopssecure_url+ 'scan.php?' + Math.random(),
                dataType: 'json',
                data: 'scantype=3&ref=3&scanurl=' + xoopssecure_FullUrl + '&scanstart=' + xoopssecure_scanstarttime,
                beforeSend: function () {
                    $('#xoopssecure_loader').show();
                    $("#xoopssecure_progressbarwrapper, #xoopssecure_resultsscan").hide();
                },
                complete: function (response) {
                    $("#cboxClose").show();
                    $('.xoopssecure_resulttitle').show();
                    $('#xoopssecure_progressbar').show();                     
                    $("#xoopssecure_progressbar").progressbar({
                        value:0,
                        width  : "200px",
                        height : "30px"
                    });
                },
                success: function(data){
                    $('#xoopssecure_process_filedir').hide();
                    $("#cboxTitle").text(xoopssecure_malwaretitle);
                    if (data.result == null) {
                        $("#cboxClose").show();
                        return;
                    }
                    for (var i = 0; i < data.result.filecount; i++) {
                        var a = i + 1;
                        var filename = data.result.filename[i];
                        var newfilename = filename.replace(xoopssecure_scanurl+"/", '');
                        $("#xoopssecure_resultscan_filename").empty().html(""+newfilename+"");
                        xoopssecure_setDirStat (data.result.filecount, a, newfilename, starttime);
                        xoopssecure_getData(data.result.filename[i], 3, 4);
                    }
                    if (xoopssecure_autoindexfiles === 1) {
                        $("#cboxTitle").text(xoopssecure_autoindexfilestitle);
                        for (var i = 0; i < data.result.dircount; i++) {
                            var a = i + 1;
                            var filename = data.result.dirname[i];
                            var newfilename = filename.replace(xoopssecure_scanurl+"/", '');
                            $("#xoopssecure_resultscan_filename").empty().html(""+newfilename+"");
                            xoopssecure_setDirStat (data.result.dircount, a, newfilename, starttime);
                            if (xoopssecure_indexfiletype === 0 || xoopssecure_indexfiletype === 2) {
                                xoopssecure_getData(data.result.dirname[i], 5, 5);
                            }
                            if (xoopssecure_indexfiletype === 1) {
                                xoopssecure_getData(data.result.dirname[i], 7, 7);
                            }
                        }
                    } else {
                        $("#cboxTitle").text(xoopssecure_checkindexfilestitle);
                        for (var i = 0; i < data.result.dircount; i++) {
                            var a = i + 1;
                            var filename = data.result.dirname[i];
                            var newfilename = filename.replace(xoopssecure_scanurl+"/", '');
                            $("#xoopssecure_resultscan_filename").empty().html(""+newfilename+"");
                            xoopssecure_setDirStat (data.result.dircount, a, newfilename, starttime);
                            xoopssecure_getData(data.result.dirname[i], 11, 11);
                        }
                        
                        
                    }
                    if (xoopssecure_autochmod === 1) {
                        $("#cboxTitle").text(xoopssecure_autochmodtitle);    
                        //Do files
                        for (var i = 0; i < data.result.filecount; i++) {
                            var a = i + 1;
                            var filename = data.result.filename[i];
                            var newfilename = filename.replace(xoopssecure_scanurl+"/", '');
                            $("#xoopssecure_resultscan_filename").empty().html(""+newfilename+"");
                            xoopssecure_setDirStat (data.result.filecount, a, newfilename, starttime);
                            xoopssecure_getData(data.result.filename[i], 6, 6);
                        }
                        
                        //Do dirs
                        for (var i = 0; i < data.result.dircount; i++) {
                            var a = i + 1;
                            var filename = data.result.dirname[i];
                            var newfilename = filename.replace(xoopssecure_scanurl+"/", '');
                            $("#xoopssecure_resultscan_filename").empty().html(""+newfilename+"");
                            xoopssecure_setDirStat (data.result.dircount, a, newfilename, starttime);
                            xoopssecure_getData(data.result.dirname[i], 6, 6);
                        }
                        $("#cboxTitle").text('');    
                        
                    }
                    return false;
                },
                error: function(response){
                },
            });
			
        });
        return false;
    });    

    // Change start dir for full scan
    $("#xoopssecure_changeDirFullScan").on('click', function(event){
        event.preventDefault();
        $("#xoopssecure_filebrowserFullScanChooseCont").show();
        $.colorbox({
            inline:true,
            href:"div #xoopssecure_filebrowserFullScanChooseCont",
            width:"70%",
            onOpen:function(){
            $("#xoopssecure_loader").show();
                $("#cboxClose").hide();
            },
            onClose:function() {
                $("#xoopssecure_filebrowserFullScanChooseCont").hide();
            },
            onCleanup:function(){
                $("#xoopssecure_filebrowserFullScanChooseCont").hide();
            }
        }).attr("href","javascript:void(0)"); 
    });
    
	//Get download manually (ref id: xoopssecure_backup)
	    $(function() {        
        $( "#xoopssecure_backup" ).on('click', function () {
            $("#xoopssecure_existingbackup").empty();
			
			$.ajax({
                type: 'GET',
                url: xoopssecure_url+ 'scan.php?' + Math.random(),
                dataType: 'html',
                data: 'scantype=18&ref=18&scanurl=""&scanstart=""',
                beforeSend: function () {
                    $('#xoopssecure_loader').show();
                    $("#xoopssecure_progressbarwrapper, #xoopssecure_resultsscan").hide();
                },
                complete: function (response) {	
					$.colorbox({
						 inline:true,
						 href:"#xoopssecure_existingbackup_cont",
						 width:"70%",
						 height:"50%",
						 onOpen:function(){
							$("#xoopssecure_existingbackup_cont").show();
							$("#cboxClose").hide();
							$.colorbox.resize();
							// click download link
							$("#xoopssecure_bdl").on('click', function(){
								$.fn.colorbox.close();
								$("#xoopssecure_existingbackup_cont").hide();
								$("#xoopssecure_existingbackupDelete").show();
							});
						 },
						 onCleanup:function(){
							$("#xoopssecure_existingbackup_cont").hide();

						 }
					}).attr("href","javascript:void(0)");		
                },
                success: function(data){
                    $('#xoopssecure_existingbackup').html(data);
					return false;
                },
                error: function(response){
                },
            });
        });
        return false;
    }); 
	
	$("#xoopssecure_existingbackupDelete").on('click', function() {
		xoopssecure_deletebackup();
	});
    
	// Quick scan
    $(function() {        
        $( "#xoopssecure_quickscan" ).on('click', function () {
        if (xoopssecure_dbhasfiles === 0) {
            //$('#xoopssecure_quickscan').unbind("click");
            $(this).colorbox({
                 inline:true,
                 href:"#xoopssecure_nofilesindb",
                 width:"20%",
                 onOpen:function(){
                    $("#xoopssecure_nofilesindb").show();
                    $("#cboxClose").hide();
                 },
                 onCleanup:function(){
                    $("#xoopssecure_nofilesindb").hide();
                 }
            }).attr("href","javascript:void(0)");
        } else {
            var starttime = $.now();
            var url = "#xoopssecure_loadingScreen";
            $('#xoopssecure_issuesfoundwrapper').hide();
            
            $(url).hide();
            $("#xoopssecure_filefromfile").hide();
            $(this).colorbox({
                 inline:true,
                 href:url,
                 width:"70%",
                 onOpen:function(){
                    $(url).show();
                    $("#cboxClose").hide();
                 },
                 onCleanup:function(){
                    $(url).hide();
                 }
            }).attr("href","javascript:void(0)");

            $.ajax({
                type: 'GET',
                url: xoopssecure_url+ 'scan.php?' + Math.random(),
                dataType: 'json',
                data: 'scantype=1&ref=1&scanurl=' + xoopssecure_QuickUrl + '&scanstart=' + xoopssecure_scanstarttime,
                beforeSend: function () {
                    $('#xoopssecure_loader').show();
                    $("#xoopssecure_progressbarwrapper, #xoopssecure_resultsscan").hide();
                },
                complete: function (response) {
                    $("#cboxClose").show();
                    $("#cboxClose").on('click', function() {
                        window.location = xoopssecure_url + "showlog.php";
                    });
                    $('.xoopssecure_resulttitle').show();
                    $('#xoopssecure_progressbar').show();  
                    $("#xoopssecure_progressbar").progressbar({
                        value:0,
                        width  : "200px",
                        height : "30px"
                    });
                },
                success: function(data){
                    $('#xoopssecure_process_filedir').hide();
                    for (var i = 0; i < data.result.filecount; i++) {
                        var a = i + 1;
                        var filename = data.result.filename[i];
                        var newfilename = filename.replace(xoopssecure_scanurl+"/", '');
                        $("#xoopssecure_resultscan_filename").empty().html(""+newfilename+"");
                        xoopssecure_setDirStat (data.result.filecount, a, newfilename, starttime);
                        xoopssecure_getData(data.result.filename[i], 1, 4);
                    }
                    return false;
                },
                error: function(response){
                },
            });
        }
        });
        
        return false;
    });    

    // Change start dir for quick scan
    $("#xoopssecure_changeDirQuickScan").on('click', function(event){
        event.preventDefault();
        $("#xoopssecure_filebrowserQuickScanChooseCont").show();
        $.colorbox({
            inline:true,
            href:"div #xoopssecure_filebrowserQuickScanChooseCont",
            width:"70%",
            onOpen:function(){
            $("#xoopssecure_loader").show();
                $("#cboxClose").hide();
            },
            onClose:function() {
                $("#xoopssecure_filebrowserQuickScanChooseCont").hide();
            },
            onCleanup:function(){
                $("#xoopssecure_filebrowserQuickScanChooseCont").hide();
            }
        }).attr("href","javascript:void(0)"); 
    });    
    
    // delete issues
    $('body').on('click', '#xoopssecure_deleteissue', function(evt) {
        var id = $(this).data('id');
        var fn = $(this).data('fn');
        var ln = $(this).data('ln');
        var what = $(this).data('what');
        var table = $(this).data('table');

        if (table === 'issues') {
            if ($(this).closest('#xoopssecure_filebyfile').find('div.xoopssecure_issues').length > 1) {
               $(this).closest('.xoopssecure_issues').remove();
            } else {
               
               $(this).closest('#xoopssecure_filebyfile').remove();
            }
        }
        
        if (table === 'ignores') {
            if ($(this).closest('ul').find('li').length > 1) {
               $(this).closest('li').remove();
            } else {
               
               $(this).closest('li').remove();
            }
        }
        
        $.ajax({
            async: false,
            cache: false,
            url: xoopssecure_url+ 'scan.php?' + Math.random(),
            dataType: 'json',
            data: 'scantype='+what+'&ref=' + what + '&file=' + fn + '&id='+id+'&ln='+ln+'&table='+table,
            success: function(data){
            }
        });
        evt.preventDefault();
    });
    
    // Add to Issue to ignore
    $('body').on('click', '#xoopssecure_AddIssueToIGN', function(evt) {
        var id = $(this).data('id');
        var fn = $(this).data('fn');
        var ln = $(this).data('ln');
        var what = $(this).data('what');
        var table = $(this).data('table');

        if ($(this).closest('#xoopssecure_filebyfile').find('div.xoopssecure_issues').length > 1) {
           $(this).closest('.xoopssecure_issues').remove();
        } else {
           
           $(this).closest('#xoopssecure_filebyfile').remove();
        }
        $.ajax({
            async: false,
            cache: false,
            url: xoopssecure_url+ 'scan.php?' + Math.random(),
            dataType: 'json',
            data: 'scantype='+what+'&ref="' + what + '&file="' + fn + '"&val='+table+'&id='+id+'&ln='+ln,
            success: function(data){
            }
        });
        evt.preventDefault();
    });  

    // Add FILE to ignores table
    $('body').on('click', '.xoopssecure_addignore', function(evt) {
        var id = $(this).data('id');
        var fn = $(this).data('fn');
        var what = $(this).data('what');
        var table = $(this).data('table');
        
        $(this).parent().next("div#xoopssecure_filebyfile").remove();
        $(this).parent().remove();
        // If ignore div remove all with same dir from issue list
        if (id === 'dir') {
            $(".xoopssecure_addignore[data-fn='" + fn.replace('"', '') + "']").parent().next("div#xoopssecure_filebyfile").remove();
            $(".xoopssecure_addignore[data-fn='" + fn.replace('"', '') + "']").parent().remove();
        }
        $.ajax({
            async: false,
            cache: false,
            url: xoopssecure_url+ 'scan.php?' + Math.random(),
            dataType: 'json',
            data: 'scantype='+what+'&ref=' + what + '&file="' + fn + '"&val='+table+'&id='+id,
            success: function(data){
               
            }
        });
        evt.preventDefault();
    });        
    
    // Show mallware log
    $("#xoopssecure_showlogmalware").on("click", function() {
        $("div#xoopssecure_log_system").hide();
        $("div#xoopssecure_log_software").hide();
        $("div#xoopssecure_mallware_log").show();
        $.colorbox({
            inline:true,
            href:"div#xoopssecure_mallware_log",
            width:"70%",
            height:"600px",
            onOpen:function(){
                $("#xoopssecure_loader").show();
                $("#cboxClose").hide();
            },
            onClose:function() { 
                $("div#xoopssecure_mallware_log").hide();    
            },
            onCleanup:function(){
                $("div .xoopssecure_develmsgspinner").hide();
                $("#xoopssecure_loader").hide();
                $("div#xoopssecure_mallware_log").hide();    
            },
            onComplete: function() {
                $.colorbox.resize();
            }
        }).attr("href","javascript:void(0)");
    });
    
    // Show Server log
    $("#xoopssecure_showlogserver").on("click", function() {
        $("div#xoopssecure_log_system").show();
        $("div#xoopssecure_log_software").hide();
        $("div#xoopssecure_mallware_log").hide();        
        $.colorbox({
            inline:true,
            href:"div #xoopssecure_log_system",
            width:"70%",
            height:"500px",
            onOpen:function(){
            $("#xoopssecure_loader").show();
                $("#cboxClose").hide();
            },
            onClose:function() {
                $("div#xoopssecure_log_system").hide();
            },
            onCleanup:function(){
                $("div .xoopssecure_develmsgspinner").hide();
                $("#xoopssecure_loader").hide();
                $("div#xoopssecure_log_system").hide();
            },
            onComplete: function() {
                $.colorbox.resize();
            }
        }).attr("href","javascript:void(0)");   
    });
    
    // Show Software log
    $("#xoopssecure_showlogsoftware").on("click", function() {
        $("div#xoopssecure_log_system").hide();
        $("div#xoopssecure_log_software").show();
        $("div#xoopssecure_mallware_log").hide();    
        $.colorbox({
            inline:true,
            href:"div #xoopssecure_log_software",
            width:"70%",
            height: "500px",
            onOpen:function(){
            $("#xoopssecure_loader").show();
                $("#cboxClose").hide();
            },
            onClose:function() {
                $("div#xoopssecure_log_software").hide();
            },
            onCleanup:function(){
                $("div .xoopssecure_develmsgspinner").hide();
                $("#xoopssecure_loader").hide();
                $("div#xoopssecure_log_software").hide();
            },
            onComplete: function() {
                $.colorbox.resize();
            }
        }).attr("href","javascript:void(0)");
    });
    
    // Developer scan
    $(function() { 
        $( "#xoopssecure_developPath" ).on('click', function () {
            $("#xoopssecure_loader").hide();
            $("div .xoopssecure_fileTreDevelop").show();
            $('#xoopssecure_filetreeDevCont').show();
            xoopssecure_developerUrl = xoopssecure_root;
            $.colorbox({
                    inline:true,
                    href:"div #xoopssecure_filetreeDevCont",
                    width:"70%",
                    onOpen:function(){
                    $("#xoopssecure_loader").show();
                        $("#cboxClose").hide();
                    },
                    onClose:function() {
                        
                    },
                    onCleanup:function(){
                        $("div .xoopssecure_develmsgspinner").hide();
                        $("#xoopssecure_loader").hide();
                        $("#xoopssecure_filetreeDevCont").hide();
                    }
                }).attr("href","javascript:void(0)"); 
        })
    });
    
    // show hide file choose for develop
    $('body').on('click', '#xoopssecure_pathSelectDevelop', function() {
        $('#xoopssecure_filetreeDevCont').hide();        
        $(".xoopssecure_develmsgspinner").show();
        $.colorbox({
            inline:true,
            href:".xoopssecure_develmsgspinner",
            width:"70%",
            onOpen:function(){
                $(".xoopssecure_develmsgspinner").show();
                $("#cboxClose").hide();
            },
            onClose:function() {
                
            },
            onCleanup:function(){
                $(".xoopssecure_develmsgspinner").hide(); 
            }
        }).attr("href","javascript:void(0)"); 
        xoopssecure_showdevelop(xoopssecure_developerUrl);
    });

    
   // Show hide example code for mallware scan
   $(".xoopssecure_info_code").on("click", function(event) {
        event.preventDefault();
        $(this).parent().parent().next('p').find('.xoopssecure_code').toggle( "5000", "easeInSine", function() {
            // Animation complete.
        });
    });
    
    // Show hide cve details in software info
    $(document).on("click", "#xoopssecure_showcve", function(event){
        event.preventDefault();
        $(this).closest('div').next('.xoopssecure_cve_list').toggle( "5000", "easeInSine", function() {
            // Animation complete.
        });
        $(this).colorbox.resize();
    }); 
    
    // Filebrowser show - for full scan
    if ($("#xoopssecure_filebrowserFullScanChoose").length > 0) {
        $(function() { 
            $('#xoopssecure_filebrowserFullScanChoose').fileTree({
                root: '/',
                script: xoopssecure_url + 'jqueryFileTree.php?config=scanner',
                expandSpeed: 1000,
                collapseSpeed: 1000,
                multiFolder: false
            }, function(file) {
                xoopssecure_FullUrl = xoopssecure_root+file;
                $.ajax({
                    type: 'GET',
                    url: xoopssecure_url+ 'scan.php?' + Math.random(),
                    dataType: 'json',
                    data: 'scantype=12&ref=12&developpath='+xoopssecure_FullUrl,
                    complete: function (response) {
                        $('#xoopssecure_showselpathfullscan').empty().html(xoopssecure_FullUrl);
                        $.colorbox.resize();
                    },
                    success: function(data){
                        $("div .xoopssecure_develmsgspinner").hide();
                        $.colorbox.resize();
                        return false;
                    },
                    error: function(response){
                    },
                });
            }, function(dir){
                xoopssecure_FullUrl = xoopssecure_root+dir;
                $.ajax({
                    type: 'GET',
                    url: xoopssecure_url+ 'scan.php?' + Math.random(),
                    dataType: 'json',
                    data: 'scantype=12&ref=12&developpath='+xoopssecure_FullUrl,
                    complete: function (response) {
                        $('#xoopssecure_showselpathfullscan').empty().html(xoopssecure_FullUrl);
                        $.colorbox.resize();
                    },
                    success: function(data){
                        $("div .xoopssecure_develmsgspinner").hide();
                        $.colorbox.resize();
                        return false;
                    },
                    error: function(response){
                    },
                });
            });
            $("#xoopssecure_pathSelectfullscan").on('click', function () {
                var temp_url = $("#xoopssecure_showselpathfullscan").html();
                $(".xoopssecure_fullscan_dir").empty().html(temp_url);
                $.colorbox.close();
            });
        });
    }

    // Filebrowser show - for quick scan
    if ($("#xoopssecure_filebrowserQuickScanChoose").length > 0) {
        $(function() { 
            $('#xoopssecure_filebrowserQuickScanChoose').fileTree({
                root: '/',
                script: xoopssecure_url + 'jqueryFileTree.php?config=scanner',
                expandSpeed: 1000,
                collapseSpeed: 1000,
                multiFolder: false
            }, function(file) {
                xoopssecure_QuickUrl = xoopssecure_root+file;
                $.ajax({
                    type: 'GET',
                    url: xoopssecure_url+ 'scan.php?' + Math.random(),
                    dataType: 'json',
                    data: 'scantype=12&ref=12&developpath='+xoopssecure_QuickUrl,
                    complete: function (response) {
                        $('#xoopssecure_showselpathQuickscan').empty().html(xoopssecure_QuickUrl);
                        $.colorbox.resize();
                    },
                    success: function(data){
                        $("div .xoopssecure_develmsgspinner").hide();
                        $.colorbox.resize();
                        return false;
                    },
                    error: function(response){
                    },
                });
            }, function(dir){
                xoopssecure_QuickUrl = xoopssecure_root+dir;
                $.ajax({
                    type: 'GET',
                    url: xoopssecure_url+ 'scan.php?' + Math.random(),
                    dataType: 'json',
                    data: 'scantype=12&ref=12&developpath='+xoopssecure_QuickUrl,
                    complete: function (response) {
                        $('#xoopssecure_showselpathQuickscan').empty().html(xoopssecure_QuickUrl);
                        $.colorbox.resize();
                    },
                    success: function(data){
                        $("div .xoopssecure_develmsgspinner").hide();
                        $.colorbox.resize();
                        return false;
                    },
                    error: function(response){
                    },
                });
            });
            $("#xoopssecure_pathSelectQuickscan").on('click', function () {
                var Quicktemp_url = $("#xoopssecure_showselpathQuickscan").html();
                $(".xoopssecure_Quickscan_dir").empty().html(Quicktemp_url);
                $.colorbox.close();
            });
        });
    }
    
    // Filebrowser show - for developer scan
    if ($("#xoopssecure_fileTreeone").length > 0) {
        $(function() { 
            $('#xoopssecure_fileTreeone').fileTree({
                root: '/',
                script: xoopssecure_url + 'jqueryFileTree.php',
                expandSpeed: 1000,
                collapseSpeed: 1000,
                multiFolder: false
            }, function(file) {
                xoopssecure_developerUrl = xoopssecure_root+file;
                $.ajax({
                    type: 'GET',
                    url: xoopssecure_url+ 'scan.php?' + Math.random(),
                    dataType: 'json',
                    data: 'scantype=12&ref=12&developpath='+xoopssecure_developerUrl,
                    complete: function (response) {
                        $('#xoopssecure_showselpath').empty().html(xoopssecure_developerUrl);
                        $.colorbox.resize();
                    },
                    success: function(data){
                        $("div .xoopssecure_develmsgspinner").hide();
                        return false;
                    },
                    error: function(response){
                    },
                });
            }, function(dir){
                xoopssecure_developerUrl = xoopssecure_root+dir;
                $.ajax({
                    type: 'GET',
                    url: xoopssecure_url+ 'scan.php?' + Math.random(),
                    dataType: 'json',
                    data: 'scantype=12&ref=12&developpath='+xoopssecure_developerUrl,
                    complete: function (response) {
                        $('#xoopssecure_showselpath').empty().html(xoopssecure_developerUrl);
                        $.colorbox.resize();
                    },
                    success: function(data){
                        $("div .xoopssecure_develmsgspinner").hide();
                        return false;
                    },
                    error: function(response){
                    },
                });
            });
        });
    }
       
    // Change colors for progress bar
    $("#xoopssecure_progressbar").bind('progressbarchange', function(event, ui) {
        var selector = "#" + this.id + " > div";
        var value = this.getAttribute( "aria-valuenow" );
        if (value < 10){
            $(selector).css({ 'background': 'Red' });
        } else if (value < 30){
            $(selector).css({ 'background': 'Orange' });
        } else if (value < 50){
            $(selector).css({ 'background': 'Yellow' });
        } else{
            $(selector).css({ 'background': 'LightGreen' });
        }
    });
    
    // Compress page if number of divs is bigger than 5
    if ( $("div .xoopssecure_issues").length > 3) {
       $("div#xoopssecure_mallware_log").css({'overflow':'scroll'}); 
       $("div#xoopssecure_mallware_log").css({'height':'600px'}); 
    }
        
    // Config fileTree
    if ($("#xoopssecure_ignorefiletree").length > 0) {
        $('#xoopssecure_ignorefiletree:visible').fileTree({
            root: "/",
            script: xoopssecure_url + 'jqueryFileTree.php',
            expandSpeed: 1000,
            collapseSpeed: 1000,
            multiFolder: true
        }, function(file) {
            //alert(file);
        }, function(dir){
            // do something when a dir is clicked
            //alert(dir);
        });
    };
    
    // Show divs on config page menu click
    if ($('.xoopssecure_configpage_menu').is(':visible')) {
        xoopssecure_handleConfigItems (xoopssecure_ActiveConfigItem);
        $('.xoopssecure_configpage_menu li a').on('click', function(){
            var xoopssecure_ActiveConfigItem = $(this).attr('ref');
            xoopssecure_handleConfigItems (xoopssecure_ActiveConfigItem);
        });
    }

    
    
    
    
});
    
    // Do animation on config containers showing
    function xoopssecure_handleConfigItems (ref) {
        $(".xoopssecure_configcontainer").hide(2000, 'easeOutBounce', function() {
            $.colorbox.resize();
        });
        $(".xoopssecure_configindexfiles").hide(2000, 'easeOutBounce', function() {
            $.colorbox.resize();
        });
        $(".xoopssecure_configscansetup").hide(2000, 'easeOutBounce', function() {
            $.colorbox.resize();
        });
        $(".xoopssecure_configautomode").hide(2000, 'easeOutBounce', function() {
            $.colorbox.resize();
        });
        $("."+ref).show(2000, 'easeOutBounce', function() {
            $.colorbox.resize();
        });
    }
    
    // Show developer div and do search
    function xoopssecure_showdevelop(xoopssecure_developerUrl) {
        $.ajax({
            type: 'GET',
            url: xoopssecure_url+ 'scan.php?' + Math.random(),
            dataType: 'html',
            data: 'scantype=2&ref=2&path='+xoopssecure_developerUrl,
            beforeSend: function () {
            },
            complete: function (response) {
                var test = $(".xoopssecure_csf_report").get().innerHTML;
                if ( $('.xoopssecure_csf_report code').is(':empty') ) {
                    $("#xoopssecure_showoksign").show();
                }
            },
            success: function(data){
                $.colorbox({
                    html:data,
                    width:'625px',
                    height:'500px',
                    onComplete : function() { 
                        $(this).colorbox.resize(); 
                        $("div .xoopssecure_develmsgspinner").hide();
                        $("#xoopssecure_loader").hide();
                    },
                    onClose:function() {
                        $("xoopssecure_showoksign").hide();               
                    }                    
                }); 
                
                return false;
            },
            error: function(response){
            },
            done: function () {
            }
        }); 
    }
 
// Get json on file by file scan 
function xoopssecure_getData(file, scanref, scantype) {
    $("#xoopssecure_resultsscan").show();
    $("#xoopssecure_filefromfile").show();
    $('#xoopssecure_loader').hide();
    
    var xoopssecurity_issuecount = parseInt ( $ ("#xoopssecure_lastissuefoundNum").text());
    
    $.ajax({
        async: false,
        cache: false,
        url: xoopssecure_url+ 'scan.php?' + Math.random(),
        dataType: 'json',
        data: 'scantype='+scantype+'&file="' + file + '"&ref='+scanref+'&scanstart=' + xoopssecure_scanstarttime,
        success: function(data){
            $.each(data, function(id, val) {
                if (data[id].issuecount) {
                    $('#xoopssecure_issuesfoundwrapper').show();                    
                }
                var nn = xoopssecurity_issuecount + data[id].issuecount;
                var nni = parseInt(nn,10);
                $ ("#xoopssecure_lastissuefoundNum").empty().html(nni);
                $ ("#xoopssecure_lastissuefound").empty().html(data[id].issuename);
            });
        }
    });
    return false;
}

// do backup
function xoopssecure_dobackup() {
	$.ajax({
		async: true,
        cache: false,
		type: 'GET',
		url: xoopssecure_url+ 'scan.php?' + Math.random(),
		dataType: 'json',
		data: 'scantype=18&file=""&ref=""&scanstart=""',
		beforeSend: function () {
		},
		complete: function (response) {
		},
		success: function(response){
		},
		error: function(response){
		},
	});
}

// Change value for progress bar file by file
function xoopssecure_setDirStat (count, total, xoopssecure_scan_filename, starttime) {
    var xoops_secure_cnt = (total / count) * 100;
    var filetime = $.now();
    var totaltime = (filetime - starttime) / 1000;
    var timetogo = (((count / total) * totaltime) - totaltime) - 3600;
    var num = new Number(xoops_secure_cnt);
    num = num.toPrecision(4);
    $ ("#xoopssecure_progressbar" ). progressbar({
        value : xoops_secure_cnt
    });
    $ ("#xoopssecure_progressbarpercent").empty().html(" " + num + "%");
    $ ("#xoopssecure_filefromfilenumber").empty().html(total + "/" + count);
    $ ("#xoopssecure_timeremain_stamp").empty().html(xoopssecure_timeConverter(timetogo));
    return false;
}

// Convert time stamps
function xoopssecure_timeConverter(UNIX_timestamp){
    var a = new Date(UNIX_timestamp*1000);
    var hour = a.getHours();
    var min = a.getMinutes();
    var sec = a.getSeconds();
    var time = (hour <= 0 ? ('0' + hour) : hour) + ':' + (min <= 9 ? ('0' + min) : min) + ':' + (sec <= 9 ? '0' + sec : sec);
    return time;
 }

// Check if choosen ignored item already exists 
function xoopssecure_checkignoreitem (ignoreurl, ignoreref, spec) {
    if (spec === 'ignore') {
        var scantype = 14;
    } else {
        var scantype = 16;
    }
    
    $.ajax({
        async: false,
        cache: false,
        url: xoopssecure_url+ 'scan.php?' + Math.random(),
        dataType: 'html',
        data: 'scantype='+scantype+'&file="' + ignoreurl + '"&ref='+ignoreref + '&val=' + spec,
        error:function(xhr, status, error) { 
            $("#xoopssecure_addresult").empty().fadeIn();
            $("#xoopssecure_addresult").html(error).fadeOut(1500);
        },
        complete: function(data){
            xoopssecure_fill_config_div ("dir", "ignore");
            xoopssecure_fill_config_div ("file", "ignore");
            xoopssecure_fill_config_div ("dir", "chmod");
            xoopssecure_fill_config_div ("file", "chmod");
        },
        success: function(data){   
        }
    });
}
 
 // fill config divs with values from db
 function xoopssecure_fill_config_div (ignoreref, spec) {
    $.ajax({
        async: false,
        cache: false,
        url: xoopssecure_url+ 'scan.php?' + Math.random(),
        dataType: 'html',
        data: 'scantype=17&ref='+ignoreref + '&val=' + spec,
        success: function(data){
            if (ignoreref === "file" && spec === 'ignore') {
                $("ul.xoopssecure_ignorefileslist").empty().html(data);
            }
            if (ignoreref === "dir"  && spec === 'ignore') {
                $("ul.xoopssecure_ignoredirlist").empty().html(data);
            }
            if (ignoreref === "file" && spec === 'chmod') {
                $("ul.xoopssecure_ignore_chmod_fileslist").empty().html(data);
            }
            if (ignoreref === "dir" && spec === 'chmod') {
                $("ul.xoopssecure_ignore_chmod_dirlist").empty().html(data);
            }
        }
    });
 }
 
 // Drop items for ignore
 function xoopssecure_ignoreDragDrop () {
    // drag 'n drop
    $('ul.jqueryFileTree li a').draggable({
        cursor: 'move',
        helper: 'clone',
    });
    
    $('span#xoopssecure_ignorefiletree_files,span#xoopssecure_ignorefiletree_dirs').droppable({
        accept: 'a',
        tolerance: 'pointer',
        drop: function (event, ui) {
            var ignoreurl = xoopssecure_xoopsurl + ui.draggable.attr("rel");
            var ignoreref = ui.draggable.attr("ref");
            var spec = "ignore";
            //alert (ignoreurl + " " + ignoreref);
            xoopssecure_checkignoreitem (ignoreurl, ignoreref, spec);
        },
        over: function (event, ui) {
            //
        },
        out: function (event, ui) {
            //
        }
    });

    $('span#xoopssecure_ignorefiletree_chmod_files,span#xoopssecure_ignorefiletree_chmod_dirs').droppable({
        accept: 'a',
        tolerance: 'pointer',
        drop: function (event, ui) {
            var ignoreurl = xoopssecure_xoopsurl + ui.draggable.attr("rel");
            var ignoreref = ui.draggable.attr("ref");
            var spec = "chmod";
            //alert (ignoreurl + " " + ignoreref);
            xoopssecure_checkignoreitem (ignoreurl, ignoreref, spec);
        },
        over: function (event, ui) {
            //
        },
        out: function (event, ui) {
            //
        }
    });    
    
    // Show in colorbox
    $.colorbox({
        inline:true,
        href:"div#xoopssecure_configpage",
        width:"90%",
        height:"600px",
        onOpen:function(){
            $("#cboxClose").hide();
        },
        onClosed:function() { 
         $(location).attr('href',xoopssecure_url);   
        },
        onCleanup:function(){ 
        },
        onComplete: function() {
            $.colorbox.resize();
        }
    }).attr("href","javascript:void(0)");
 }
 
 // Function to send ajax to delete backupfiles
 function xoopssecure_deletebackup() {
	// Confirmation for deleting backupfile from server	
	$.prompt(xoopssecure_delConf_areyousuretext, {
		title: xoopssecure_delConf_areyousuredesc,
		buttons: backupbuttons,
		submit: function(e,v,m,f){
			// use e.preventDefault() to prevent closing when needed or return false. 
			// e.preventDefault(); 
			if (v) {
				$.ajax({
					async: false,
					cache: false,
					url: xoopssecure_url+ 'scan.php?' + Math.random(),
					dataType: 'html',
					data: 'scantype=20&scanurl=""&ref=""&scanstart=""',
					success: function(data){
						$("#xoopssecure_existingbackupDelete").hide();
						return false;
					}
				}); 
			} 
		}
	});
	
	/*
	$.ajax({
        async: false,
        cache: false,
        url: xoopssecure_url+ 'scan.php?' + Math.random(),
        dataType: 'html',
        data: 'scantype=20',
        success: function(data){
			return false;
        }
    }); 
	*/
 }
 
  // Function to send ajax to download backupfiles
 function xoopssecure_DowloadBackup() {
	$.ajax({
        async: false,
        cache: false,
        url: xoopssecure_url+ 'scan.php?' + Math.random(),
        dataType: 'html',
        data: 'scantype=19&scanurl=""&ref=""&scanstart=""',
        success: function(data){
			return false;
        }
    }); 
 }
