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

    var xoopssecure_FullUrl = xoopssecure_root;
    var xoopssecure_developokimage = "<img src='" + xoopssecure_anonurl + "assets/images/okstamp.png" + "'/>";
    var xoopssecure_ActiveConfigItem = 'xoopssecure_configcontainer';
    
    $(".xoopssecure_code").hide();

    $(function() { 
            var starttime = $.now();
            var url = "#xoopssecure_loadingScreen";
            
            $('#xoopssecure_issuesfoundwrapper').hide();
            
            $(url).hide();
            $("#xoopssecure_filefromfile").hide();
            $.colorbox({
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

            $.ajax({
                type: 'GET',
                url: xoopssecure_anonurl+ 'cron_scan.php?' + Math.random(),
                dataType: 'json',
                data: 'scantype=3&ref=3&scanurl=' + xoopssecure_FullUrl,
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
        return false;
    });    

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
        
    
});
