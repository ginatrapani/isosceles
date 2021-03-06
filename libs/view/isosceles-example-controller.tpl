{include file="_isosceles.header.tpl"}

{include file="_isosceles.usermessage.tpl"}

<h1>Welcome to {$app_title}</h1>

<p><a href="{$site_root_path}{$logo_link}">{$app_title}</a>: {$test} | {if isset($logged_in_user)}Signed in as {$logged_in_user} | <a href="{$site_root_path}signout/">Sign out</a>{else}<a href="{$site_root_path}signin/">Sign in</a>{/if}
{if isset($username) and isset($network)}
    <br><br><br>
    Username: {$username}<br>
    Network: {$network}
{/if}
</p>

<ul>
    <li><a href="{$site_root_path}?success=Hooray!">Success message</a></li>
    <li><a href="{$site_root_path}?info=You+should+know">Info message</a></li>
    <li><a href="{$site_root_path}?error=Uh-oh">Error message</a></li>
</ul>

<h2>Custom config values</h2>
<ul>
    <li>{$custom_config_1}</li>
    <li>{$custom_config_2}</li>
</ul>

<h3>JSON examples</h3>
<ul>
    <li><a href="/?json=true">Non-cached JSON</a></li>
    <li><a href="/json/">JSON with caching enabled</a> (In config.inc.php, set cache_pages to true)</li>
</ul>
<p>Hint: In config.inc.php, set enable_profiler to true to see page rendering stats.</p>

<h2>Smarty modifier test - URL encoding</h2>
<p>{'this should be URL encoded'|escape:'url'}

{include file="_isosceles.footer.tpl"}

