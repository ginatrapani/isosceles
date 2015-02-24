
<div class="panel panel-warning" style="margin: 10px;">

	{foreach from=$profile_items key=tid item=t name=foo}

	{if $smarty.foreach.foo.index == 0}

	<div class="panel-heading">
		<h4>Profiling enabled:</h4>
		<h5 {if $t.time > 0.5}class="text-danger"{/if}>{$t.time} seconds {$t.action}</h5>
	</div>

	<table class="table">
		<tr>
		    <th>Time</th>
		    <th>Rows</th>
		    <th>Action</th>
		    <th>Class and method</th>
		</tr>
	{else}
		<tr>
		    <td style="vertical-align: top;" {if $t.time > 0.5}class="danger"{/if}>{$t.time}s</td>
		    <td style="vertical-align: top;text-align:center;">{if $t.is_query}{$t.num_rows}{/if}</td>
		    <td>{$t.action}</td>
		    <td style="vertical-align: top;">{$t.dao_method}</td>
		</tr>
	{/if}
	{/foreach}
	</table>

</div>