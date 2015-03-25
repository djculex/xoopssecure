<div class="xoopssecure_container">
    <div style = "display:none" id = "xoopssecure_scanmsg">
    </div>
    <div id="xoopssecure_nofilesindb" class="xoopssecure_breakmsg" style="display:none;">  
        <p>
            <{$smarty.const._XOOPSSECURE_QUICKSCAN_NOFILESINDB}>
        </p>
    </div>
    
    <div class = "xoopssecure_getdirsresp"></div>
</div>

<div id ="xoopssecure_loadingScreen" style="display:none">
    <span id = "xoopssecure_loader" style="display:none">
        <img src="../xoopsSecure/assets/images/loader.gif" id="xoops_securespinner" title="<{$smarty.const._AM_XOOPSSECURE_SCAN_NOWPROCESSING}>">
    </span>    
    <br>
    <div class = "xoopssecure_progressbarwrapper">
        <span id = "xoopssecure_progressbar"> <p id = "xoopssecure_progressbarpercent"></p></span>
    </div>
    <br>
    <span id = "xoopssecure_filefromfile"><{$smarty.const._AM_XOOPSSECURE_SCAN_READINGFILEFROMFILE}>
        <span id ="xoopssecure_filefromfilenumber"></span>
        <span id ="xoopssecure_timeremaining">
            <{$smarty.const._AM_XOOPSSECURE_SCAN_TIMEREMAININGSTAMP}> <span id = "xoopssecure_timeremain_stamp"></span>
        </span>
    </span>

    <br>
    <span id = "xoopssecure_resultsscan">
        <{$smarty.const._AM_XOOPSSECURE_SCAN_NOWPROCESSING}> <span id ="xoopssecure_resultscan_filename"></span>
    </span>
    <br>
    <span id="xoopssecure_process_filedir">
        <p class="xoopssecure_resulttitle">
            <{$smarty.const._AM_XOOPSSECURE_SCAN_SCANNINGMSG}> : <span id ="xoopssecure_filedirname"></span>
        </p>
        <p class="xoopssecure_resulttitleIFC" style="display:none">
            <{$smarty.const._AM_XOOPSSECURE_SCAN_SCANNINGMSGIFC}> : <span id ="xoopssecure_filedirname"></span>
        </p>
         <p class="xoopssecure_resulttitlechmod" style="display:none">
            <{$smarty.const._AM_XOOPSSECURE_SCAN_SCANNINGMSGCHMOD}> : <span id ="xoopssecure_filedirname"></span>
        </p>       
    </span>
    <br><br>
    <span id = "xoopssecure_issuesfoundwrapper">
        <span id = "xoopssecure_lastissuefoundNum">0</span> <{$smarty.const._AM_XOOPSSECURE_SCANISSUECOUNTTOTAL}>        
        <{$smarty.const._AM_XOOPSSECURE_LASTISSUEFOUND}> <span id = "xoopssecure_lastissuefound"></span>
    </span>
</div>