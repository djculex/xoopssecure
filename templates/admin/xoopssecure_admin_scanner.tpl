<!-- Header -->
<{includeq file='db:xoopssecure_admin_header.tpl' }>

<!-- Index Page -->
<div class="top"><{$index|default:false}></div>

<div class="container contact">
    <fieldset enabled>
        <div class="row justify-content-center g-0">
            <div class="col-8">
                <h4 class="text-center"><{$lang_scanner_title}></h4>
                <p class="text-center"><{$lang_scanner_title_desc}></p>
                <p class="mt-3 text-info text-center">
                    <strong>
                        <{$lang_scanner_title_desc_tip}>
                    </strong>
                    <{$lang_scanner_title_desc_tip_desc}>
                </p>

                <fieldset class="fieldset warning">
                    <p>
                    <div id="processbar_ps">
                        <{$lang_scanner_perm_title}>
                    </div>
                    <div class="progress" style="width:90%; float:left;">
                        <div class="progress-bar bg-warning" id="xoopssecure_scanner_processbar_ps" style="width: 100%">
                            100%
                        </div>
                    </div>
                    <div id="xoopssecure_scanner_eta_ps"></div>
                    </p>
                    <p>
                    <div id="processbar_if">
                        <{$lang_scanner_if_title}>
                    </div>
                    <div class="progress" style="width:90%; float:left;">
                        <div class="progress-bar bg-success" id="xoopssecure_scanner_processbar_if" style="width: 100%">
                            100%
                        </div>
                    </div>
                    <div id="xoopssecure_scanner_eta_if"></div>
                    </p>
                    <p>
                    <div id="processbar_mall">
                        <{$lang_scanner_mallware_title}>
                    </div>
                    <div class="progress" style="width:90%; float:left;">
                        <div class="progress-bar bg-danger" id="xoopssecure_scanner_processbar_mal" style="width: 100%">
                            100%
                        </div>
                    </div>
                    <div id="xoopssecure_scanner_eta_mal"></div>
                    </p>
                    <p>
                    <div id="processbar_mall">
                        <{$lang_scanner_cs_title}>
                    </div>
                    <div class="progress" style="width:90%; float:left;">
                        <div class="progress-bar bg-info" id="xoopssecure_scanner_processbar_cs" style="width: 100%">
                            100%
                        </div>
                    </div>
                    <div id="xoopssecure_scanner_eta_cs"></div>
                    </p>
                    <p>
                    <div class="row align-items-center">
                        <div class="form-check form-check-inline scanner_check_box">
                            <input type="checkbox" class="form-check-input" name="actions" id="checkIndexfiles">
                            <label class="form-check-label" for="checkIndexfiles">
                                <{$lang_scanner_checkbox_create_if}>
                            </label>
                        </div>

                        <div class="form-check form-check-inline  scanner_check_box">
                            <input type="checkbox" class="form-check-input" name="actions" id="checkPermissions"
                                   checked>
                            <label class="form-check-label" for="checkPermissions">
                                <{$lang_scanner_checkbox_set_perm}>
                            </label>
                        </div>
                    </div>
                    <span class="xoopssecure_divider"></span>
                    <div class="row align-items-center">
                        <div class="col"></div>
                        <div class="col-5">
                            <select id="xoopssecure_admin_scanaction_dropdown"
                                    class="xoopssecure_admin_scanaction custom-select custom-select-sm">
                                <option value="0" selected><{$lang_scanner_mallware_dropdown_full}></option>
                                <option value="1"><{$lang_scanner_mallware_dropdown_p}></option>
                                <option value="2"><{$lang_scanner_mallware_dropdown_i}></option>
                                <option value="3"><{$lang_scanner_mallware_dropdown_m}></option>
                                <option value="4"><{$lang_scanner_mallware_dropdown_cs}></option>
                            </select>
                        </div>

                        <div class="col"></div>
                    </div>
                    </p>
                    <div class="row align-items-center">
                        <button type="submit" id="xoopssecure_scanner_start" class="btn btn-primary">
                            <{$lang_scanner_button_start}>
                        </button>
                    </div>
                </fieldset>
            </div>
            <div id="xoopssecure_last_log_div" class="col-9 xoopssecure_last_log_div"></div>
        </div>
    </fieldset>
</div>
<!-- Modal -->
<div class="modal fade" id="xoopssecureScannerGettingFilesWaitModal" tabindex="-1"
     aria-labelledby="xoopssecureScannerGettingFilesWait" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="xoopssecureScannerGettingFilesWait">
                    <{$lang_scanner_modal_gettingfileswait_title}>
                </h1>
            </div>
            <div class="modal-body">
                <{$lang_scanner_modal_gettingfileswait}>
            </div>
            <div id="xoopssecureScannerJsonSizeContainer">
                <{$lang_scanner_modal_jsonstacksize}> <span id="xoopssecureScannerJsonSizeNumber"></span>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
    </div>
</div>

<!-- Large modal -->
<div class="modal fade bd-example-modal-lg" id="xoopssecureScannerFirstTime" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <h4>
                    <{$lang_scanner_modal_firsttimescan}>
                </h4>
                <p>
                    <{$lang_scanner_modal_firsttimescan_desc}>
                </p>
                <br>
                <p>
                    <button type="submit" id="xoopssecure_scanner_healtyInstall" class="btn btn-primary">
                        <{$lang_scaner_modal_firsttimescan_healhty}>
                    </button>
                    <button type="submit" id="xoopssecure_scanner_nothealtyInstall" class="btn btn-primary">
                        <{$lang_scaner_modal_firsttimescan_nohealhty}>
                    </button>
                </p>

            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<{includeq file='db:xoopssecure_admin_footer.tpl' }>
