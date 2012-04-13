<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span class="mws-i-24 i-alert">Import Logs</span>
	</div>
	<div class="mws-panel-body">
		<div class="mws-panel-content">
			<p>
				This option will allow you to re-import existing SNAPSHOT log files. Typically this is used for recovering after a database restore or for importing missed 
				SNAPSHOT's due to server communication issues. Importing LARGE numbers of log files will seriously impact the performance of your web server.
				<br /><br />
				By clicking "Import Unprocessed Logs"... ALL previously un-processed log files found in your "/ASP/system/snapshots/incomplete/" directory 
				will be imported into the database (recommended).
				<br />				
				By clicking "Re-Import ALL Logs"... ALL logs including previouly processed logs will be Re-imported into the database. (not recomended unless restoring database)
				<br /><br />
				<font color="red">Note:</font> Please ensure you a have a full backup of your existing database before proceeding! 
				<br /><br />
				<center>
					<input type="button" id="import-up" class="mws-button blue" value="Import Unprocessed Logs" />
					<input type="button" id="import-all" class="mws-button red" value="Re-Import ALL Logs" />
				</center>
			</p>
		</div>
		
		<!-- Hidden Ajax Thing -->
		<div id="ajax-dialog">
			<div class="mws-dialog-inner">
				<p>
					<center>
						<img src="frontend/images/core/loading32.gif" />
						<br />
						<br />
						Importing Logs... Please allow up to 30 seconds for this process to complete.
						<br />
						<br /><font color="red">DO NOT</font> close or refresh this window.
					</center>
				</p>
			</div>
		</div>
	</div>
</div>