<div class="xoopssecure_container">
    <div>
        <fieldset class="xoopssecure_fieldset">
            <legend class="xoopssecure_legend">
                <{$smarty.const._AM_XOOPSSECURE_SHOWLOG_SHOWLOG_MALWARE_TITLE}>
            </legend>
            <span>
                <p><{$smarty.const._AM_XOOPSSECURE_SHOWLOG_SHOWLOG_MALWARE_DESC}>
				<p>
					<{$datedropdown}>
				</p>
                    <button class="xoopssecure_actionlink" id="xoopssecure_showlogmalware" href="#">
                        <{$smarty.const._AM_XOOPSSECURE_SHOWLOG_SHOWLOG_CLICK}>
                    </button>
                </p>    
            </span>
        </fieldset>
    </div>
    <div style = "display:none" id = "xoopssecure_scanmsg">
    </div>
    
    <div>
        <fieldset class="xoopssecure_fieldset">
            <legend class="xoopssecure_legend">
                <{$smarty.const._AM_XOOPSSECURE_SHOWLOG_SHOWLOG_SERVER_TITLE}>
            </legend>
            <span>
                <p>
                    <{$smarty.const._AM_XOOPSSECURE_SHOWLOG_SHOWLOG_SERVER_DESC}>
                    <button class="xoopssecure_actionlink" id="xoopssecure_showlogserver" href="#">
                        <{$smarty.const._AM_XOOPSSECURE_SHOWLOG_SHOWLOG_CLICK}>
                    </button>
                </p>    
            </span>
        </fieldset>
    </div>
    <div style = "display:none" id = "xoopssecure_scanmsg">
    </div>
    
    <div>
        <fieldset class="xoopssecure_fieldset">
            <legend class="xoopssecure_legend">
                <{$smarty.const._AM_XOOPSSECURE_SHOWLOG_SHOWLOG_SOFTWARE_TITLE}>
            </legend>
            <span>
                <p>
                    <{$smarty.const._AM_XOOPSSECURE_SHOWLOG_SHOWLOG_SOFTWARE_DESC}>
                    <button class="xoopssecure_actionlink" id="xoopssecure_showlogsoftware" href="#">
                        <{$smarty.const._AM_XOOPSSECURE_SHOWLOG_SHOWLOG_CLICK}>
                    </button>
                </p>    
            </span>
        </fieldset>
    </div>
    <div style = "display:none" id = "xoopssecure_scanmsg">
    </div>   
    <div id ="xoopssecure_loadingScreen" style="display:none">
        <span id = "xoopssecure_loader" style="display:none">
            <img src="<{$xoops_url}>/modules/xoopsSecure/assets/images/loader.gif" 
                id="xoops_securespinner" 
                title="<{$smarty.const._AM_XOOPSSECURE_SCAN_NOWPROCESSING}>"
            />
        </span>  
    </div>
</div>





<div id = "xoopssecure_log_system" style="display:none;">
    <div id = "xoopssecure_filebyfilesystem">           
        <h2><{$smarty.const._AM_XOOPSSECURE_PHPINI_SYSTEM_HEADER}></h2>
        <div id = "xoopssecure_phpini_desc">
            <p><{$smarty.const._AM_XOOPSSECURE_PHPINI_SYSTEM_DESC}></p>
        </div>
        <{section name=i loop=$phpinfo}>
        <div class="xoopssecure_phpiniissues">
            <p>
                <span class = "xoopssecure_inierrortype">
                    <{$phpinfo.errortype[i]}>
                </span><br>
                <{$smarty.const._AM_XOOPSSECURE_PHIINI_NAME}>
                    <span class = "xoopssecure_inierrorname">
                        <{$phpinfo.name[i]}>
                    </span><br>
                <{$smarty.const._AM_XOOPSSECURE_PHIINI_CUR}>
                    <span class = "xoopssecure_inierrorcur">
                        <{$phpinfo.current[i]}>
                    </span><br>
                <{$smarty.const._AM_XOOPSSECURE_PHIINI_REC}>
                    <span class = "xoopssecure_inierrorrec">
                        <{$phpinfo.recommended[i]}>
                    </span><br>
                <{$smarty.const._AM_XOOPSSECURE_PHIINI_DESC}>
                    <span class = "xoopssecure_inierrordesc">
                        <cite><{$phpinfo.description[i]}></cite>
                    </span><br>
            </p>
        </div>
        <{/section}>
    </div>
</div>
 
 <div id = "xoopssecure_log_software" style="display:none;">
    <div id = "xoopssecure_filebyfilesystem">           
        <h2><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_HEADER}></h2>
        <div id = "xoopssecure_phpini_desc">
            <p><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_HEADER_DESC}></p>
        </div>
        <div class="xoopssecure_phpiniissues">
            <p>
                <span class = "xoopssecure_inierrorname">
                    <div class = "xoopssecure_software_version_title">
                        <{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_PHP}> <{$phpver}>
                        <span class = "xoopssecure_software_desc">
                            <a id = "xoopssecure_showcve" href="javascript:void(0);">
                                <img 
                                    src="<{$xoops_url}>/modules/xoopsSecure/assets/images/info.png" 
                                    title="<{$smarty.const._AM_XOOPSSECURE_CVETITLE_ISSUES}>"
                                />
                            </a>
                        </span>
                    </div>
                </span>
                <div class = "xoopssecure_cve_list" style="display:none;">
                <{foreach item=phpvul from=$phpvul}>
                    <div class = "xoopssecure_cve-list-container">
                    <p><span id = "xoopssecure_cvelist_cveid"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_CVEID}></span><{$phpvul.cve_id}></p>
                    <p><span id = "xoopssecure_cvelist_sum"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_SUMMARY}></span><{$phpvul.summary}></p>
                    <p><span id = "xoopssecure_cvelist_score"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_CVSS_SCORE}></span><{$phpvul.cvss_score}></p>
                    <p><span id = "xoopssecure_cvelist_pdate"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_PUBDATE}></span><{$phpvul.publish_date}></p>
                    <p><span id = "xoopssecure_cvelist_udate"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_UPDATEDATE}></span><{$phpvul.update_date}></p>
                    <p><span id = "xoopssecure_cvelist_link"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_URL}></span><a href="<{$phpvul.url}>" target="_BLANK"><{$phpvul.url}></a></p>
                    </div>
                <{/foreach}>
                </div>
            </p>
            
            <p>
                <span class = "xoopssecure_inierrorname">
                    <div class = "xoopssecure_software_version_title">
                        <{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_MYSQL}> <{$mysqlver}>
                        <span class = "xoopssecure_software_desc">
                            <a id = "xoopssecure_showcve" href="javascript:void(0);">
                                <img src="<{$xoops_url}>/modules/xoopsSecure/assets/images/info.png" title="<{$smarty.const._AM_XOOPSSECURE_CVETITLE_ISSUES}>"/>
                            </a>
                        </span>
                    </div>
                </span>
                <div class = "xoopssecure_cve_list" style="display:none;">
                <{foreach item=mysqlvul from=$mysqlvul}>
                    <div class = "xoopssecure_cve-list-container">
                    <p><span id = "xoopssecure_cvelist_cveid"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_CVEID}></span><{$mysqlvul.cve_id}></p>
                    <p><span id = "xoopssecure_cvelist_sum"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_SUMMARY}></span><{$mysqlvul.summary}></p>
                    <p><span id = "xoopssecure_cvelist_score"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_CVSS_SCORE}></span><{$mysqlvul.cvss_score}></p>
                    <p><span id = "xoopssecure_cvelist_pdate"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_PUBDATE}></span><{$mysqlvul.publish_date}></p>
                    <p><span id = "xoopssecure_cvelist_udate"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_UPDATEDATE}></span><{$mysqlvul.update_date}></p>
                    <p><span id = "xoopssecure_cvelist_link"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_URL}></span><a href="<{$mysqlvul.url}>" target="_BLANK"><{$mysqlvul.url}></a></p>
                    </div>
                <{/foreach}>
                    </div>
            </p>
            
            <p>
                <span class = "xoopssecure_inierrorname">
                    <div class = "xoopssecure_software_version_title">
                        <{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_APACHE}> <{$apachever}>
                        <span class = "xoopssecure_software_desc">
                            <a id = "xoopssecure_showcve" href="javascript:void(0);">
                                <img src="<{$xoops_url}>/modules/xoopsSecure/assets/images/info.png" title="<{$smarty.const._AM_XOOPSSECURE_CVETITLE_ISSUES}>"/>
                            </a>
                        </span>
                    </div>
                </span>
                    <div class = "xoopssecure_cve_list" style="display:none;">
                <{foreach item=apachevul from=$apachevul}>
                    <div class = "xoopssecure_cve-list-container">
                    <p><span id = "xoopssecure_cvelist_cveid"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_CVEID}></span><{$apachevul.cve_id}></p>
                    <p><span id = "xoopssecure_cvelist_sum"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_SUMMARY}></span><{$apachevul.summary}></p>
                    <p><span id = "xoopssecure_cvelist_score"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_CVSS_SCORE}></span><{$apachevul.cvss_score}></p>
                    <p><span id = "xoopssecure_cvelist_pdate"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_PUBDATE}></span><{$apachevul.publish_date}></p>
                    <p><span id = "xoopssecure_cvelist_udate"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_UPDATEDATE}></span><{$apachevul.update_date}></p>
                    <p><span id = "xoopssecure_cvelist_link"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_URL}></span><a href="<{$phpvul.url}>" target="_BLANK"><{$apachevul.url}></a></p>
                    </div>
                <{/foreach}>
                </div>
            </p>
            
            <p>
                <span class = "xoopssecure_inierrorname">
                    <div class = "xoopssecure_software_version_title">
                        <{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_XOOPS}> <{$xoopsver}>
                        <span class = "xoopssecure_software_desc">
                           <a id = "xoopssecure_showcve" href="javascript:void(0);">
                                <img src="<{$xoops_url}>/modules/xoopsSecure/assets/images/info.png" title="<{$smarty.const._AM_XOOPSSECURE_CVETITLE_ISSUES}>"/>
                            </a>
                        </span>
                    </div>
                </span>
                <div class = "xoopssecure_cve_list" style="display:none;">
                <{foreach item=xoopsvul from=$xoopsvul}>
                <div class = "xoopssecure_cve-list-container">
                    <p><span id = "xoopssecure_cvelist_cveid"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_CVEID}></span><{$xoopsvul.cve_id}></p>
                    <p><span id = "xoopssecure_cvelist_sum"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_SUMMARY}></span><{$xoopsvul.summary}></p>
                    <p><span id = "xoopssecure_cvelist_score"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_CVSS_SCORE}></span><{$xoopsvul.cvss_score}></p>
                    <p><span id = "xoopssecure_cvelist_pdate"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_PUBDATE}></span><{$xoopsvul.publish_date}></p>
                    <p><span id = "xoopssecure_cvelist_udate"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_UPDATEDATE}></span><{$xoopsvul.update_date}></p>
                    <p><span id = "xoopssecure_cvelist_link"><{$smarty.const._AM_XOOPSSECURE_SERVERSOFTWARE_URL}></span><a href="<{$xoopsvul.url}>" target="_BLANK"><{$xoopsvul.url}></a></p>
                    </div>
                <{/foreach}>
                </div>
            </p>
        </div>
    </div>
</div>
 
<div id="xoopssecure_mallware_log" style = "display:none;"> 
<{if $dbHasMallIssues == 1}>
<{if count($fileinfo) > 0}>
<div class="xoopssecure_latestscanlog">
        <div class="xoopssecure_title_scan_header">
            <h3><{$smarty.const._AM_XOOPSSECURE_TITLE_MALWARE_FULL}></h3>
            <span class = "xoopssecure_title_scan_date">
                <{$smarty.const._AM_XOOPSSECURE_SCANDATEBETWEEN}> <{$fileinfo[0].time}>
            </span>
        </div>
    <{foreach item=fi from=$fileinfo}>
        <span class = "xoopssecure_fileicon">
            <a class = 'xoopssecure_addignore' href="#"
                data-id = "file"
                data-fn = "<{$fi.fileicon}>"
                data-what = "14"
                data-table = "clear"
            > Clear file issues
                <img src = "<{$xoops_url}>/modules/xoopsSecure/assets/images/folder_clear.png"/>
            </a>
            <a class = 'xoopssecure_addignore' href="#"
                data-id = "file"
                data-fn = "<{$fi.fileicon}>"
                data-what = "14"
                data-table = "ignore"
            > Ignore File
                <img src = "<{$xoops_url}>/modules/xoopsSecure/assets/images/file.png"/>
            </a>
            <a class = 'xoopssecure_addignore' href="#"
                data-id = "dir"
                data-fn = "<{$fi.diricon}>"
                data-what = "14"
                data-table = "ignore"
            > Ignore DIR     
                <img src = "<{$xoops_url}>/modules/xoopsSecure/assets/images/directory.png"/>
            </a>
        </span>
        <div id = "xoopssecure_filebyfile">
            <h2><{$fi.filename}></h2>
            <p>
                <span class="xoopssecure_issCnt">
                    <{$fi.issuecount}> Issue(s) found!
                </span>
                <span class="xoopssecure_fileinfobox"><{$fi.shortfilename}> was created <{$fi.accessed}>,
                        changed <{$fi.changed}>, last modified <{$fi.modified}> & filepermissions set to <{$fi.permission}>
                </span>
            </p>

            <{section name=i loop=$fi.issuearray}>
                <div class="xoopssecure_issues">
                <div class = "xoopssecure_issueControls">
                    <span class="xoopssecure_issueCtrHolder">
                    <span class="xoopssecure_imgDelete">
                        <img alt="<{$smarty.const._AM_XOOPSSECURE_DELETEISSUE_DESC}>" 
                            src="<{$xoops_url}>/modules/xoopsSecure/assets/images/delete.png" 
                        />
                        <a  href="javascript:void(0);" 
                            id = "xoopssecure_deleteissue"
                            data-id = "<{$fi.issuearray[i].issueid}>"
                            data-fn = "<{$fi.issuearray[i].issuefn}>"
                            data-ln = "<{$fi.issuearray[i].linenumber}>"
                            data-what = "8"
                            data-table = "issues"
                            
                        > 
                            <{$smarty.const._AM_XOOPSSECURE_DELETEISSUE}>
                        </a>
                    </span>
                    <span class="xoopssecure_imgIgnore">
                        <img alt="<{$smarty.const._AM_XOOPSSECURE_IGNOREISSUE_DESC}>"
                            src="<{$xoops_url}>/modules/xoopsSecure/assets/images/addignore.png" 
                        />

                        <a href="javascript:void(0);" 
                            id = "xoopssecure_AddIssueToIGN"
                            data-id = "<{$fi.issuearray[i].issueid}>"
                            data-fn = "<{$fi.issuearray[i].issuefn}>"
                            data-ln = "<{$fi.issuearray[i].linenumber}>"
                            data-what = "9"
                            data-table = "issues"
                        ><{$smarty.const._AM_XOOPSSECURE_IGNOREISSUE}></a>
                    </span>
                    <span class="xoopssecure_imgEmpty">
                        <img alt="<{$smarty.const._AM_XOOPSSECURE_EMPTYIGNORELIST_DESC}>" 
                            src="<{$xoops_url}>/modules/xoopsSecure/assets/images/emptyignore.png" 
                        />
                        
                        <a href="javascript:void(0);" 
                            id = "xoopssecure_EmptyIgn" 
                            data-id = "<{$fi.issuearray[i].issueid}>"
                            data-fn = "<{$fi.issuearray[i].issuefn}>"
                            data-ln = "<{$fi.issuearray[i].linenumber}>"
                            data-what = "10"
                            data-table = "issues"
                        ><{$smarty.const._AM_XOOPSSECURE_EMPTYIGNORELIST}></a>
                    </span>
                    </span>
                </div>
                <br><br>
                <p>
                    <span class="xoopssecure_logIssueTitle">Found : <{$fi.issuearray[i].issuetype}>
                        <img    class="xoopssecure_info_code" 
                                src="<{$xoops_url}>/modules/xoopsSecure/assets/images/info.png" 
                                title="<{$smarty.const._AM_XOOPSSECURE_CVETITLE_ISSUES}>"
                        />
                    </span>
                    <br><br>
                    <span class='xoopssecure_logLinenumber'><strong>Line # <{$fi.issuearray[i].linenumber}> : </strong></span>
                
                    <span class='xoopssecure_logIssueDesc'><{$fi.issuearray[i].issuedesc}></span>
                </p>
                    <p><span class="xoopssecure_issuecode"><br><code class="xoopssecure_code"><{$fi.issuearray[i].issuecode}></code></br></span></p>
                </div>
            <{/section}>    
        </div>
    <{/foreach}>    
 </div>  
<{else}>
    <div class="xoopssecure_title_scan_header">
        <h3><{$smarty.const._AM_XOOPSSECURE_TITLE_MALWARE_FILEISSUES_CLEAR}></h3>
        <div id = "xoopssecure_scannoresults"></div>
    </div>
<{/if}> 
 
 
 <br><br>
 
 
 <div class="xoopssecure_latestscanlog">
    <{if count($dirinfo) > 0}>
        <div class="xoopssecure_title_scan_header">
            <h3><{$smarty.const._AM_XOOPSSECURE_TITLE_MALWARE_INDEXFILES}></h3>
            <span class = "xoopssecure_title_scan_date">
                <{$smarty.const._AM_XOOPSSECURE_SCANDATEBETWEEN}> <{$fileinfo[0].time}>
            </span>
        </div>
    <{foreach item=di from=$dirinfo}>
        <div id = "xoopssecure_filebyfile">
            <h2><{$di.filename}></h2>
            <p>
                <span class="xoopssecure_issCnt"><{$di.issuecount}> Issue(s) found!
                    </span><span class="xoopssecure_fileinfobox"><{$di.filetype}> was created <{$di.accessed}>,
                            changed <{$di.changed}>, last modified <{$di.modified}> & filepermissions set to <{$di.permission}>
                    </span>
            </p>

            <{section name=i loop=$di.issuearray}>
                <div class="xoopssecure_issues">
                <br><br>
                <p>                
                    <span class='xoopssecure_logIssueDesc'><{$di.issuearray[i].issuedesc}></span>
                </p>
                    <p></p>
                    <p><span class="xoopssecure_issuecode"><br><code class="xoopssecure_code"><{$fd.issuearray[i].issuecode}></code></br></span></p>
                </div>
            <{/section}>    
        </div>
    <{/foreach}>  
<{else}>
    <div class="xoopssecure_title_scan_header">
        <h3><{$smarty.const._AM_XOOPSSECURE_TITLE_MALWARE_DIRISSUES_CLEAR}></h3>
        <div id = "xoopssecure_scannoresults"></div>
    </div>
<{/if}>     
 </div>   
    <{elseif $dbhasfiles == 1 && $dbHasMallIssues == 0}>
        <div class="xoopssecure_title_scan_header">
            <h3><{$smarty.const._AM_XOOPSSECURE_TITLE_MALWARE_FULL_CLEAR}></h3>
            <div id = "xoopssecure_scannoresults"></div>
        </div>
    <{/if}> 
    <{if $dbhasfiles == 0 && $dbHasMallIssues == 0}>
    <div class="xoopssecure_title_scan_header">
        <h3><{$smarty.const._AM_XOOPSSECURE_TITLE_MALWARE_FULL_EMPTY}></h3>
        <span class = "xoopssecure_title_scan_date">
            <{$smarty.const._AM_XOOPSSECURE_TITLE_MALWARE_FULL_EMPTY_INSTRUCTIONS}>
        </span>
    </div>
    <{/if}>
 </div>