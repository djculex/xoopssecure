<div id="xoopssecure_errors" style="display:none;">
	<{if count($resERR) gt 0}>
	
	<div class="xoopssecureErrIssueContainer">
		<fieldset class="border border-dark rounded" style="">
		<legend>
				<p>
				<h3 class="xoopssecure_shadow_legend">
					<{$smarty.const._SCAN_XOOPSSECURE_ERRORS_TITLE}>
				</h3>
				</p>
		</legend>
		<div id="Scanfileinfo" class="xoopssecure__errors_fileinfobox">
			<p>
				<font size="+0"><{$smarty.const._SCAN_XOOPSSECURE_INLINEDATESCANNED}></font>
				<font size="-5">
					<{$resERR[0].humantime}>
				</font>
			</p>
		</div>
		<div class="container mt-5 px-2">
		<ul class="list-group list-group-light">
		  <{foreach item=arr from=$resERR}>
		  <li class="list-group-item d-flex justify-content-between align-items-left">
			<div class="d-flex align-items-left">
			  
			  <div class="ms-3">
				<p class="fw-bold mb-1"><{$arr.dirname}></p>
				<p class="text-muted mb-0"><{$arr.description}></p>
			  </div>
			</div>
				<span class="xoopssecure_notfixed"><i class="fa fa-times"></i></span>
		  </li>
		  <{/foreach}>
		</ul>
		</fieldset>
		</div>
	</div>
	
	<{else}>
		<div class="container">
		   <div class="row">
			  <div class="col-md-6 mx-auto mt-5">
				 <div class="xoopssecureContentResultCheckDiv">
					<div class="xoopssecureContentResultCheckDiv_header">
					   <div class="xoopssecureContentResultCheck"><i class="fa fa-check" aria-hidden="true"></i></div>
					</div>
					<div class="xoopssecureContentResultSuccess">
					   <h1><{$smarty.const._LOG_XOOPSSECURE_SCANNER_RESULTCHECKSUCCESS}></h1>
					   <p><{$smarty.const._LOG_XOOPSSECURE_SCANNER_RESULTCHECKSUCCESSH}></p>
					</div>
					
				 </div>
			  </div>
		   </div>
		</div>
	<{/if}>
</div>