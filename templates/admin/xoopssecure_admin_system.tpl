<!-- Header -->
<{includeq file='db:xoopssecure_admin_header.tpl' }>

<!-- Index Page -->
<div class="top"><{$index|default:false}></div>
<div class="xoopssecureIssueContainer">

    <div class="xoopssecure_system_panelcontainer ">
        <div class="xoopssecure_system_panelrow row">
            <div class="vertical-center-row" id="main">
                <h3><{$smarty.const._MECH_XOOPSSECURE_SERVER_STATUS}></h3>
                <div class="xoopssecure_localhostdiv row">
                    <div class="xoopssecure_localhostinner">
                        <p><{$smarty.const._MECH_XOOPSSECURE_SERVER_NAME}><{$pcname}></p>
                        <p><{$smarty.const._MECH_XOOPSSECURE_SERVER_OS}><{$os}></p>
                        <p><{$smarty.const._MECH_XOOPSSECURE_SERVER_BUILTDATE}><{$os_builtdate}></p>
                        <p><{$smarty.const._MECH_XOOPSSECURE_SERVER_PROCARCH}><{$processorarchetecture}></p>
                        <p><{$smarty.const._MECH_XOOPSSECURE_SERVER_NUMOFPROCES}><{$numberofprocessors}></p>
                        <p><{$smarty.const._MECH_XOOPSSECURE_SERVER_RUNNINGTIME}><{$serveruptime}></p>
                        <p><{$smarty.const._MECH_XOOPSSECURE_SERVER_SERVERUSE}><{$serveruse}></p>
                        <p><{$smarty.const._MECH_XOOPSSECURE_SERVER_SERVERIP}><{$serveripadress}></p>
                    </div>
                </div>
                <div class="panel-group " id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                        <{foreach from=$systeminfo key=id item=i}>

                        <{if $i.type == 'php'}>
                        <div class="xoopssecure_system_panel-heading" role="tab" id="heading<{$i.type}>">
                            <div data-toggle="collapse" data-parent="#accordion" href="#collapse<{$i.type}>"
                                 aria-expanded="true" aria-controls="collapse<{$i.type}>"
                                 class="xoopssecure_system_accordionicon_<{$i.type}> collapsed">

                            </div>
                            <{else}>
                            <div class="xoopssecure_system_panel-heading" role="tab" id="heading<{$i.type}>">
                                <{/if}>
                                <h4 class="xoopssecure_system_iconheader">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion"
                                       href="#collapse<{$i.type}>" aria-expanded="true"
                                       aria-controls="collapse<{$i.type}>">
                                        <img id="xoopssecure_systemIcon" src="<{$i.icon}>"></img>
                                    </a>
                                </h4>
                                <div class="xoopssecure_systemItemInfo">
                                    <ul>
                                        <li>
                                            <{$smarty.const._MECH_XOOPSSECURE_SOFTWARE_VERSION}><{$i.version}>
                                        </li>
                                        <li>
                                            <{$smarty.const._MECH_XOOPSSECURE_SOFTWARE_VERSIONRELEASE}><{$i.release}>
                                        </li>
                                        <{if $i.vulner|@count gt 0}>
                                        <li>
                                            <button data-toggle="collapse" href="#cvedetail<{$i.type}>"
                                                    aria-expanded="false" aria-controls="cvedetail<{$i.type}>">
                                                <{$smarty.const._MECH_XOOPSSECURE_CVEISSUES}>
                                            </button>
                                        </li>
                                        <div id="cvedetail<{$i.type}>" class="collapse xoopssecure_cvedetails"
                                             aria-labelledby="cvedetail<{$i.type}>" data-parent="#accordion">
                                            <{section name=vul loop=$i.vulner}>
                                            <div class="xoopssecure_cvedetail_row"><{$i.vulner[vul].cve_id}></div>
                                            <div class="xoopssecure_cvedetail_summary"><{$i.vulner[vul].summary}></div>
                                            <div class="xoopssecure_cvedetail_publishdate">
                                                <{$i.vulner[vul].publish_date}>
                                            </div>
                                            <div class="xoopssecure_cvedetail_url">
                                                <a href="<{$i.vulner[vul].url}>">
                                                    <{$smarty.const._MECH_XOOPSSECURE_CLICK}>
                                                </a>
                                            </div>
                                            <{/section}>
                                        </div>
                                        <{/if}>
                                        </li></li>
                                    </ul>
                                </div>
                            </div>
                            <{if $i.type == 'php'}>
                            <div id="collapse<{$i.type}>"
                                 class="xoopssecure_system_collapsediv panel-collapse collapse in" role="tabpanel"
                                 aria-labelledby="heading<{$i.type}>">
                                <div class="xoopssecure_system_collapsepanelbody panel-body">
                                    <{foreach item=arr name=loop from=$phpini}>
                                    <{if $smarty.foreach.loop.first}>
                                    <div class="xoopssecure_system_settingissuesholderheader row">
                                        <div class="xoopssecure_system_settingissuesholdercol col text-left">
                                            <{$smarty.const._MECH_XOOPSSECURE_ISSUEISSUE}>
                                        </div>
                                        <div class="xoopssecure_system_settingissuesholdercol col text-left">
                                            <{$smarty.const._MECH_XOOPSSECURE_ISSUEYOURSETTING}>
                                        </div>
                                        <div class="xoopssecure_system_settingissuesholdercol col text-left">
                                            <{$smarty.const._MECH_XOOPSSECURE_ISSUERECOMSET}>
                                        </div>
                                        <div class="xoopssecure_system_settingissuesholdercol col text-right">
                                            <{$smarty.const._MECH_XOOPSSECURE_ISSUEMESSAGETYPE}>
                                        </div>
                                    </div>
                                    <{/if}>
                                    <div class="xoopssecure_system_settingissuesholder row">
                                        <div class="xoopssecure_system_settingissuesholdercol col text-left">
                                            <{$arr.name}>
                                        </div>
                                        <div class="xoopssecure_system_settingissuesholdercol col"><{$arr.current}>
                                        </div>
                                        <div class="xoopssecure_system_settingissuesholdercol col">
                                            <{$arr.recommended}>
                                        </div>
                                        <div class="xoopssecure_system_settingissuesholdercol col text-right">
                                            <{$arr.errortype}>
                                        </div>

                                    </div>
                                    <div class="xoopssecure_system_settingissuesdescholder row">
                                        <div class="xoopssecure_system_settingissuesdescholderrow col">
                                            <{$arr.description}>
                                        </div>
                                    </div>
                                    <{/foreach}>

                                </div>
                            </div>
                            <{/if}>
                            <{/foreach}>
                        </div>
                    </div>
                </div>
            </div>
            <div style="clear:both"></div>
        </div>
        <!-- Footer -->
        <{includeq file='db:xoopssecure_admin_footer.tpl' }>
