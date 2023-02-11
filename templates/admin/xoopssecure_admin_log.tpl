<!-- Header -->
<link rel="stylesheet" href="<{$xoops_url}>/xoops.css">
<{includeq file='db:xoopssecure_admin_header.tpl' }>

<!-- Index Page -->
<div class="top">
    <{$index|default:false}>
</div>
<{if $lang_log_SCANDATEDELETE gt 0}>
    <div class="container mt-5 px-2">
        <fieldset>
            <div class="mb-2 d-flex justify-content-between align-items-center">
                <!-- #xoopssecure_admin_scanlog_dropdown -->
                <div class="container p-5 my-5 bg-dark text-white">
                    <select name="xoopssecure_dropdown_logs" id="xoopssecure_admin_scanlog_dropdown">
                        <option value=""><{$smarty.const._LOG_XOOPSSECURE_DROP_DEFAULT}></option>
                    </select>

                    <div id="xoopssecure_logheader_deletedate">
                        <{$lang_log_SCANDATEHUMAN}><a href="javascript:void(0)"
                                                      data-conftext="<{$lang_log_CONFDELETELOG_TEXT}>"
                                                      data-logdtime="<{$lang_log_SCANDATEDELETE}>"
                                                      id="xoopssecureDeleteLog"><i
                                    class="fa fa-trash-o fa-lg"></i><{$smarty.const._LOG_XOOPSSECURE_DELETETHISLOG}></a>
                    </div>

                </div>
            </div>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand"><{$smarty.const._LOG_XOOPSSECURE_SCANTYPE_TITLE}></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02"
                        aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                        <li class="nav-item active">
                            <a id="xoopssecureMenuIF" class="nav-link" href="">
                                <{$smarty.const._LOG_XOOPSSECURE_SCANTYPE_INDEXFILES}> <span
                                        class="sr-only"><{$smarty.const._LOG_XOOPSSECURE_SCANTYPE_CURRENTSEL}></span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a id="xoopssecureMenuFP" class="nav-link"
                               href=""><{$smarty.const._LOG_XOOPSSECURE_SCANTYPE_FILEPERMISSIONS}></a>
                        </li>
                        <li class="nav-item">
                            <a id="xoopssecureMenuMALL" class="nav-link"
                               href=""><{$smarty.const._LOG_XOOPSSECURE_SCANTYPE_MALLWARE}></a>
                        </li>
                        <li class="nav-item">
                            <a id="xoopssecureMenuDEV" class="nav-link"
                               href=""><{$smarty.const._LOG_XOOPSSECURE_SCANTYPE_CODINGSTANDARDS}></a>
                        </li>
                        <li class="nav-item">
                            <a id="xoopssecureMenuERR" class="nav-link"
                               href=""><{$smarty.const._LOG_XOOPSSECURE_SCANTYPE_ERRORS}></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </fieldset>
        <{include file="db:xoopssecure_log_permissions.tpl"}>
        <{include file="db:xoopssecure_log_indexfiles.tpl"}>
        <{include file="db:xoopssecure_log_malware.tpl"}>
        <{include file="db:xoopssecure_log_errors.tpl"}>
        <{include file="db:xoopssecure_log_codingstandards.tpl"}>
    </div>
    <{else}>
    <{include file="db:xoopssecure_log_nothinghere.tpl"}>
    <{/if}>
<!-- Footer -->
<{includeq file='db:xoopssecure_admin_footer.tpl' }>