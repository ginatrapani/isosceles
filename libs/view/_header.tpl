<html>
<head>
<title>{$app_title}</title>
{if isset($header_css)}
{foreach from=$header_css item=css}
  <link type="text/css" rel="stylesheet" href="{$site_root_path}{$css}" />
{/foreach}
{/if}
</head>
<body>