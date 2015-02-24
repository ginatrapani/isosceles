{include file="_isosceles.header.tpl"}

{include file="_isosceles.usermessage.tpl"}

{if (isset($logged_in_user)) }
    <h1>{$logged_in_user} is signed in | <a href="{$site_root_path}signout/">Sign out</a></h1>
{else}
    <h1>Sign into {$app_title}</h1>

    <form role="signin" method="post">
      <div class="form-group">
        <input type="text" name="email" class="form-control" placeholder="Email">
      </div>
      <div class="form-group">
        <input type="password" name="passwd" class="form-control" placeholder="Password">
      </div>
      <button type="submit" class="btn btn-default">Sign in</button>
    </form>
{/if}

{include file="_isosceles.footer.tpl"}