<div class="xoopssecure_system_panel-heading" role="tab" id="headingdownloads">
	<div data-toggle="collapse" data-parent="#accordion" href="#collapsedownloads" 
		aria-expanded="true" aria-controls="collapsedownloads" 
		class="xoopssecure_download_accordionicon_downloads collapsed">
	</div>
	<h4 class="xoopssecure_download_iconheader">
		<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsedownloads" aria-expanded="true" aria-controls="collapsedownloads">
			<img id="xoopssecure_downloadIcon" src="<{$xoops_url}>/modules/xoopssecure/assets/images/zip.png"></img>
			<span style="text-decoration: none;color: initial;"><{$smarty.const.DO_XOOPSSECURE_DOWNLOAD_DOWNLOADTITLE}></span>
		</a>
	</h4>
	<div id="collapsedownloads" class="xoopssecure_system_collapsediv panel-collapse collapse in" role="tabpanel" aria-labelledby="headingdownloads">
		<div class="xoopssecure_backup_collapsepanelbody panel-body">
			
			<table class="table" id="xoopssecure_downloads_downloadtable">
			<thead>
				<tr>
					<th class="type"></th>
					<th class="name truncate"><{$smarty.const.DO_XOOPSSECURE_DOWNLOAD_BACKUPFILENAME}></th>
					<th class="date"><{$smarty.const.DO_XOOPSSECURE_DOWNLOAD_BACKUPFILEDATE}></th>
					<th class="size"><{$smarty.const.DO_XOOPSSECURE_DOWNLOAD_BACKUPFILEABOTTEXT}></th>
					<th class="size"><{$smarty.const.DO_XOOPSSECURE_DOWNLOAD_BACKUPFILEDOWNLOAD}></th>
				</tr>
			</thead>
			<tbody>
				<{foreach name=loop from=$downloadfiles key=id item=i}>
				<tr id="xoopssecure_trdownloads_<{$i.name}>">
					<td class="xoopssecure_downloadtype"><i class="fa fa-archive"></i></td>
					<td class="xoopssecure_downloadname"><{$i.name}></td>
					<td class="xoopssecure_downloaddate"><{$i.date}></td>
					<td class="xoopssecure_downloadtext"><{$i.bodytext}></td>
					<td class="xoopssecure_downloadsize"><a href="<{$i.zip}>"><{$smarty.const.DO_XOOPSSECURE_DOWNLOAD_BACKUPFILEDOWNLOADTEXT}></a></td>
				</tr>
				<{/foreach}>
			</tbody>
			</table>
		</div>
	</div>
</div>