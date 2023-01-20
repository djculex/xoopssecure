<div class="xoopssecure_system_panel-heading" role="tab" id="headingbackups">
	<div data-toggle="collapse" data-parent="#accordion" href="#collapsebackups" 
		aria-expanded="true" aria-controls="collapsebackups" 
		class="xoopssecure_download_accordionicon_backups collapsed">
	</div>
	<h4 class="xoopssecure_download_iconheader">
		<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsebackups" aria-expanded="true" aria-controls="collapsebackups">
			<img id="xoopssecure_downloadIcon" src="<{$xoops_url}>/modules/xoopssecure/assets/images/zip.png"></img>
			<span style="text-decoration: none;color: initial;"><{$smarty.const.DO_XOOPSSECURE_DOWNLOAD_BACKUPTITLE}></span>
		</a>
	</h4>
	<div id="collapsebackups" class="xoopssecure_system_collapsediv panel-collapse collapse in" role="tabpanel" aria-labelledby="headingbackups">
		<div class="xoopssecure_backup_collapsepanelbody panel-body">
			<div class="row">
				<div class="xoopssecure_dobackup col text-center">
					<button id="xoopssecure_domanualbackup" type="button" class="xoopssecure_dobackup_action btn btn-primary text-center" data-toggle="button" aria-pressed="false" autocomplete="off">
						<{$smarty.const.DO_XOOPSSECURE_DOWNLOAD_BACKUPCREATEBACKUP}>
					</button>
				</div>
			</div>
			<table class="table" id="xoopssecure_backup_downloadtable">
			<{if !empty($backupfiles)}>
			<thead>
				<tr>
					<th class="type"></th>
					<th class="name truncate"><{$smarty.const.DO_XOOPSSECURE_DOWNLOAD_BACKUPFILENAME}></th>
					<th class="date"><{$smarty.const.DO_XOOPSSECURE_DOWNLOAD_BACKUPFILEDATE}></th>
					<th class="size"><{$smarty.const.DO_XOOPSSECURE_DOWNLOAD_BACKUPFILEDOWNLOAD}></th>
					<th class="size"><{$smarty.const.DO_XOOPSSECURE_DOWNLOAD_BACKUPFILEACTION}></th>
				</tr>
			</thead>
			<tbody>
				
				<{foreach name=loop from=$backupfiles key=id item=i}>
				<tr id="xoopssecure_trbackup_<{$i.filename}>">
					<td class="type"><i class="fa fa-archive"></i></td>
					<td class="name truncate"><{$i.filename}></td>
					<td class="date"><{$i.time}></td>
					<td class="size"><a href="<{$xoops_url}>/uploads/backup/<{$i.filename}>"><{$smarty.const.DO_XOOPSSECURE_DOWNLOAD_BACKUPFILEDOWNLOADTEXT}></a></td>
					<td class="delete">
						<a role="button" 
							id = "xoopssecure_delete_zip" 
							data-id="<{$i.filename}>" 
							data-tip="delete" 
							data-conftext="<{$smarty.const.DO_XOOPSSECURE_CONDELETEBACKUP_TEXT}>"
							data-confyes="<{$smarty.const.DO_XOOPSSECURE_CONFIRM_YES}>" data-confno="<{$smarty.const.DO_XOOPSSECURE_CONFIRM_NO}>">
							<i class="fa fa-trash"></i>
						</a>
					</td>
				</tr>
				<{/foreach}>
				<{else}>
					<tbody>
					<tr id="xoopssecure_trbackup_0">
						<td class="type"></td>
						<td colspan = "4" class="name truncate" style="font-size: 16px;text-align: center;font-weight: bold;">
							<{$smarty.const.DO_XOOPSSECURE_NOBACKUPYET}></td>
						<td class="date"></td>
					</tr>
				<{/if}>
			</tbody>
			</table>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="xoopssecureBACKUPDoingBackupWaitModal" tabindex="-1" aria-labelledby="xoopssecureBACKUPDoingBackupWaitModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="xoopssecureBACKUPDoingBackupWaitModal"><{$smarty.const.DO_XOOPSSECURE_DOINGBACKUP_TITLE}></h1>
        </div>
		<div class="modal-body">
		<{$smarty.const.DO_XOOPSSECURE_DOINGBACKUP_DESC}>
		</div>
		<div class="modal-footer">
      </div>
    </div>
  </div>
</div>