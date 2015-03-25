<div class="xoopssecure_container">
    <div>
        <fieldset class="xoopssecure_fieldset">
        <legend class="xoopssecure_legend"><{$smarty.const._AM_XOOPSSECURE_QUICKSCAN_TITLE}></legend>
        <span>
            <p><{$smarty.const._AM_XOOPSSECURE_QUICKSCAN_DESC}>
            <button class="xoopssecure_actionlink" id="xoopssecure_quickscan" href="#">Press here to do a quick scan</a></p>    
        </span>
        </fieldset>
    </div>
    <{foreach from = $developdirs item = dirs}>
        <button href="#" class = "xoopssecure_dev_dir_sel" id="<{$dirs}>"><{$dirs}></button>
    <{/foreach}>
    
</div>