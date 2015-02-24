{include file="_isosceles.header.tpl"}

<h1>Welcome to {$app_title}</h1>
<p>

<a href="{$site_root_path}{$logo_link}">{$app_title}</a>: {$test} | {if isset($logged_in_user)}Logged in as {$logged_in_user}{else}Not logged in{/if}
{if isset($username) and isset($network)}
<br><br><br>
Username:{$username}<br>
Network:{$network}
{/if}

</p>

{include file="_isosceles.footer.tpl"}