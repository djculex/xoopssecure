<div class="xoopssecure_container">
    <div>
        <fieldset class="xoopssecure_fieldset">
        <legend class="xoopssecure_legend"><{$smarty.const._AM_XOOPSSECURE_QUICKSCAN_TITLE}></legend>
        <span>
            <p>
                <{$smarty.const._AM_XOOPSSECURE_FULLSCANSTARTDEST}>
                <span class = "xoopssecure_Quickscan_dir">
                    <{$smarty.const._AM_XOOPSSECURE_DEFAULT_ROOTPATH}>
                </span>
                <a class="xoopssecure_actionChangeDest" id="xoopssecure_changeDirQuickScan" href = "#">
                    <{$smarty.const._AM_XOOPSSECURE_FULLSCANCUSTOMSCANCHANGEURL}>
                </a>
                <button class="xoopssecure_actionlink" id="xoopssecure_quickscan" href = "#">
                    <{$smarty.const._AM_XOOPSSECURE_QUICKSCANSTARTBTN}>
                </button>
            </p>
            <br>            
        </span>
        </fieldset>
    </div>
    <div style = "display:none" id = "xoopssecure_scanmsg">
    </div>
    <div id = "xoopssecure_filebrowserQuickScanChooseCont" style = "display:none;">
    <div id = "xoopssecure_filebrowserQuickScanChoose"></div>
        <p>
            <{$smarty.const._AM_XOOPSSECURE_CURRENTDIRSELECTED}>
            <span id = "xoopssecure_showselpathQuickscan"><{$smarty.const._AM_XOOPSSECURE_DEFAULT_ROOTPATH}></span>
            <button id="xoopssecure_pathSelectQuickscan"> <{$smarty.const._SELECT}></button>
        </p>
    </div>

    <div id="xoopssecure_nofilesindb" class="xoopssecure_breakmsg" style="display:none;">  
        <p>
            <{$smarty.const._AM_XOOPSSECURE_QUICKSCAN_NOFILESINDB}>
        </p>
    </div>
    
    <div>
        <fieldset class="xoopssecure_fieldset">
        <legend class="xoopssecure_legend"><{$smarty.const._AM_XOOPSSECURE_DEVELOPERSCAN_TITLE}></legend>
        <div id="xoopssecure_filetreeDevCont">
            <div id="xoopssecure_fileTreeone" class="xoopssecure_fileTreDevelop" style="display:none;"></div>
            <p>
               <span id = "xoopssecure_showselpath"><{$smarty.const._AM_XOOPSSECURE_DEFAULT_ROOTPATH}></span>
               <button id="xoopssecure_pathSelectDevelop"> <{$smarty.const._SELECT}></button>
            </p>
        </div>
        
        <div class="xoopssecure_develmsgspinner" style="display:none;">  
            <{$smarty.const._AM_XOOPSSECURE_SCAN_SCANNINGDEVELOP}>
            <br><br><br>
            <span id = "xoopssecure_loader">
                <img src="../assets/images/loader.gif" id="xoops_securespinner"/>
            </span> 
        </div>
        <span>
            <p><{$smarty.const._AM_XOOPSSECURE_DEVELOPERSCAN_DESC}>
            <br><br><form><input type="text" class="xoopssecure_choosePath" id="xoopssecure_developPath">
                <{$smarty.const._AM_XOOPSSECURE_DEVELOPERSCANCHOOSEPATH}>
            </input></form><br><br>
            </p>
        </span>
        </fieldset>
    </div>
    <div class = "xoopssecure_getdirsresp"></div>
    <div>
        <fieldset class="xoopssecure_fieldset">
        <legend class="xoopssecure_legend"><{$smarty.const._AM_XOOPSSECURE_FULLSCAN_TITLE}></legend>
        <span>
            <p><{$smarty.const._AM_XOOPSSECURE_FULLSCAN_DESC}></p>
            <p>
                <{$smarty.const._AM_XOOPSSECURE_FULLSCANSTARTDEST}>
                <span class = "xoopssecure_fullscan_dir">
                    <{$smarty.const._AM_XOOPSSECURE_DEFAULT_ROOTPATH}>
                </span>
                <a class="xoopssecure_actionChangeDest" id="xoopssecure_changeDirFullScan" href = "#">
                    <{$smarty.const._AM_XOOPSSECURE_FULLSCANCUSTOMSCANCHANGEURL}>
                </a>
                <button class="xoopssecure_actionlink" id="xoopssecure_fullscan" href = "#">
                    <{$smarty.const._AM_XOOPSSECURE_FULLSCANSTARTBTN}>
                </button>
            </p>
            <br>
        </span>
        </fieldset>
    </div>
	
	<div>
        <fieldset class="xoopssecure_fieldset">
        <legend class="xoopssecure_legend"><{$smarty.const._AM_XOOPSSECURE_BACKUP_TITLE}></legend>
        <span>
            <p><{$smarty.const._AM_XOOPSSECURE_BACKUP_DESC}>
                <button class="xoopssecure_actionlink" id="xoopssecure_backup" href = "#">
                    <{$smarty.const._AM_XOOPSSECURE_BACKUPSTARTBTN}>
                </button><button id="xoopssecure_existingbackupDelete"><{$smarty.const._AM_XOOPSSECURE_BACKUP_DELLINK}></button>
				<br>
			</p>
			<p>
				
				<div id="xoopssecure_existingbackup_cont">
					<{$smarty.const._AM_XOOPSSECURE_BACKUP_BACKUPREADY_TEXT}>
					<div id="xoopssecure_existingbackup">
					</div>
				</div>
				
			</p>
        </span>
        </fieldset>
    </div>
	
</div>

<div id = "xoopssecure_filebrowserFullScanChooseCont" style = "display:none;">
<div id = "xoopssecure_filebrowserFullScanChoose"></div>
    <p>
        <{$smarty.const._AM_XOOPSSECURE_CURRENTDIRSELECTED}>
        <span id = "xoopssecure_showselpathfullscan"><{$smarty.const._AM_XOOPSSECURE_DEFAULT_ROOTPATH}></span>
        <button id="xoopssecure_pathSelectfullscan"> <{$smarty.const._SELECT}></button>
    </p>
</div>

<div id = "xoopssecure_filebrowserQuickScanChooseCont" style = "display:none;">
<div id = "xoopssecure_filebrowserQuickScanChoose"></div>
    <p>
        <{$smarty.const._AM_XOOPSSECURE_CURRENTDIRSELECTED}>
        <span id = "xoopssecure_showselpathQuickscan"><{$smarty.const._AM_XOOPSSECURE_DEFAULT_ROOTPATH}></span>
        <button id="xoopssecure_pathSelectQuickscan"> <{$smarty.const._SELECT}></button>
    </p>
</div>

<div id ="xoopssecure_loadingScreen" style="display:none">
    <span id = "xoopssecure_loader" style="display:none">
        <img src="../assets/images/loader.gif" id="xoops_securespinner" title="<{$smarty.const._AM_XOOPSSECURE_SCAN_NOWPROCESSING}>">
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
