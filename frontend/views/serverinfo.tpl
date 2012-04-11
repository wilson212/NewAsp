<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span class="mws-i-24 i-table-1">Server Info</span>
	</div>
	<div class="mws-panel-body">
		<table class="mws-datatable-fn mws-table">
			<thead>
				<tr>
					<th>Server Ip</th>
					<th>Name</th>
					<th>Prefix</th>
					<th>Port</th>
					<th>Query Port</th>
				</tr>
			</thead>
			<tbody>
				{servers}
					<tr>
						<td>{ip}</td>
						<td>{name}</td>
						<td>{prefix}</td>
						<td>{port}</td>
						<td>{queryport}</td>
					</tr>
				{/servers}
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$(".mws-datatable-fn").dataTable({sPaginationType: "full_numbers"});
});
</script>