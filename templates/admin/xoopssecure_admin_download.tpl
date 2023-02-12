<div class="xoopssecureIssueContainer">
    <div class="xoopssecure_system_panelcontainer ">
        <div class="xoopssecure_system_panelrow row">
            <div class="vertical-center-row" id="main">
                <div class="panel-group " id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">

                        <{include file="db:xoopssecure_download_backups.tpl"}>

                        <{if !empty($downloadfiles)}>
                        <{include file="db:xoopssecure_download_downloads.tpl"}>
                        <{/if}>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>