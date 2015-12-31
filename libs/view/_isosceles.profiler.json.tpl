

==
{foreach from=$profile_items key=tid item=t name=foo}
{if $smarty.foreach.foo.index == 0}
Profiling enabled: {$t.time} seconds {$t.action} {$t.dao_method}
--
{else}
{$t.time}s {if $t.is_query}{$t.num_rows}{/if} {$t.action} {$t.dao_method}
{/if}
{/foreach}
