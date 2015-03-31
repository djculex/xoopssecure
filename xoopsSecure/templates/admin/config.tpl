<div id = "xoopssecure_configpage">
    <ul class = "xoopssecure_configpage_menu">
        <li id = "xoopssecure_configpage_menuitem">
            <a id = "xoopssecure_config_ignorelist" ref = "xoopssecure_configcontainer" href = "#"><{$smarty.const._AM_XOOPSSECURE_CONFIGMENU_IGNOREITEMS}></a>
        </li>
        <li id = "xoopssecure_configpage_menuitem">
            <a id = "xoopssecure_config_automode"  ref = "xoopssecure_configautomode" href = "#"><{$smarty.const._AM_XOOPSSECURE_CONFIGMENU_AUTOMODE}></a>
        </li>
    </ul>
    <div class = "xoopssecure_configcontainer">
        <span id = "xoopssecure_ignorefiletree"></span>
            <h5><{$smarty.const._AM_XOOPSSECURE_DRAGTOADD}></h5>
            
            
            <span id = "xoopssecure_ignorefiletree_files" ref = "file" >
            <h6><{$smarty.const._AM_XOOPSSECURE_DRAGTOIGNOREFILE_SCAN}></h6>
                <ul class = "xoopssecure_ignorefileslist">
                
                <{section name=i loop=$singleFilesIgnore}>                
                    <li>
                    <a  href="#" 
                        id = "xoopssecure_deleteissue"
                        data-id = "<{$singleFilesIgnore[i].id}>"
                        data-fn = "<{$singleFilesIgnore[i].url}>"
                        data-ln = ""
                        data-what = "8"
                        data-table = "ignores"
                    >
                        <{$singleFilesIgnore[i].url}>
                        <img alt="<{$smarty.const._AM_XOOPSSECURE_DELETEISSUE_DESC}>" 
                        src="<{$xoops_url}>/modules/xoopsSecure/assets/images/delete.png" 
                    />
                    </a>
                    </li>
                <{/section}>
                </ul>
            </span>
            
            
            <span id = "xoopssecure_ignorefiletree_dirs" ref = "dir">
            <h6><{$smarty.const._AM_XOOPSSECURE_DRAGTOIGNOREDIR_SCAN}></h6>
                <ul class = "xoopssecure_ignoredirlist">
                    <{section name=i loop=$DirIgnore}>                
                        <li>
                        <a  href="#" 
                            id = "xoopssecure_deleteissue"
                            data-id = "<{$DirIgnore[i].id}>"
                            data-fn = "<{$DirIgnore[i].url}>"
                            data-ln = ""
                            data-what = "8"
                            data-table = "ignores"
                            
                        >
                            <{$DirIgnore[i].url}>
                            <img alt="<{$smarty.const._AM_XOOPSSECURE_DELETEISSUE_DESC}>" 
                            src="<{$xoops_url}>/modules/xoopsSecure/assets/images/delete.png" 
                        />
                        </a>
                        </li>
                    <{/section}>
                </ul>
            </span>
            
            <br>
            <span id = "xoopssecure_addresult"></span>
            
            <span id = "xoopssecure_ignorefiletree_chmod_files" ref = "file">
            <h6><{$smarty.const._AM_XOOPSSECURE_DRAGTOIGNOREFILE_CHMOD}></h6>
                <ul class = "xoopssecure_ignore_chmod_fileslist">
                    <{section name=i loop=$chmodFileList}>                
                        <li>
                        <a  href="#" 
                            id = "xoopssecure_deleteissue"
                            data-id = "<{$chmodFileList[i].id}>"
                            data-fn = "<{$chmodFileList[i].url}>"
                            data-ln = ""
                            data-what = "8"
                            data-table = "ignores"
                            
                        >
                            <{$chmodFileList[i].url}>
                            <img alt="<{$smarty.const._AM_XOOPSSECURE_DELETEISSUE_DESC}>" 
                            src="<{$xoops_url}>/modules/xoopsSecure/assets/images/delete.png" 
                        />
                        </a>
                        </li>
                    <{/section}>
                </ul>
            </span>
            
            <span id = "xoopssecure_ignorefiletree_chmod_dirs" ref = "dir">
            <h6><{$smarty.const._AM_XOOPSSECURE_DRAGTOIGNOREDIR_CHMOD}></h6>
                <ul class = "xoopssecure_ignore_chmod_dirlist">
                    <{section name=i loop=$chmodDirList}>                
                        <li>
                        <a  href="#" 
                            id = "xoopssecure_deleteissue"
                            data-id = "<{$chmodDirList[i].id}>"
                            data-fn = "<{$chmodDirList[i].url}>"
                            data-ln = ""
                            data-what = "8"
                            data-table = "ignores"
                            
                        >
                            <{$chmodDirList[i].url}>
                            <img alt="<{$smarty.const._AM_XOOPSSECURE_DELETEISSUE_DESC}>" 
                            src="<{$xoops_url}>/modules/xoopsSecure/assets/images/delete.png" 
                        />
                        </a>
                        </li>
                    <{/section}>
                </ul>
            </span>    
     </div>
     <div class = "xoopssecure_configautomode">
        <div class = "xoopssecure_configcontainerautomation">
            <span class = "xoopssecure_automationspan">
            <h5><{$smarty.const._AM_XOOPSSECURE_AUTOMATIONHEADER}></h5>
            <p><{$smarty.const._AM_XOOPSSECURE_AUTOMATION_LINK_DESC}></p>
            <p>
                <{$smarty.const._AM_XOOPSSECURE_AUTOMATION_LINK}> 
                <span class = "xoopssecure_configcontainerautomation_link">
                    <{$xoops_url}>/modules/xoopsSecure/run.php
                </span>
            </p>
            </span>
        </div>
     </div>
 </div>