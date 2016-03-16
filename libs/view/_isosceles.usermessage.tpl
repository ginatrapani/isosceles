{if isset($field)}

<div class="row">
  <div class="col-xs-12">

    {if isset($success_msgs.$field)}
     <div class="alert alert-success alert-dismissible fade in" role="alert">
           {if isset($success_msg_no_xss_filter)}
               {$success_msgs.$field}
           {else}
               {$success_msgs.$field|filter_xss}
           {/if}
     </div>
    {/if}
    {if isset($error_msgs.$field)}
     <div class="alert alert-danger alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
           {if isset($error_msg_no_xss_filter)}
               {$error_msgs.$field}
           {else}
               {$error_msgs.$field|filter_xss}
           {/if}
    </div>
    {/if}
    {if isset($info_msgs.$field)}
    {if isset($success_msgs.$field) OR isset($error_msgs.$field)}<br />{/if}
    <div class="alert alert-info alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
       {if isset($info_msg_no_xss_filter)}
          {$info_msgs.$field|filter_xss}
       {else}
          {$info_msgs.$field|filter_xss}
       {/if}
    </div>
    {/if}
  </div>
</div>

{else}


<div class="row">
  <div class="col-xs-12">

    {if isset($success_msg)}
     <div class="alert alert-success alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
       {if isset($success_msg_no_xss_filter)}
           {$success_msg}
       {else}
           {$success_msg|filter_xss}
       {/if}
     </div>
    {/if}
    {if isset($error_msg)}
     <div class="alert alert-danger alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
       {if isset($error_msg_no_xss_filter)}
           {$error_msg}
       {else}
           {$error_msg|filter_xss}
       {/if}
    </div>
    {/if}
    {if isset($info_msg)}
      {if isset($success_msg) OR isset($error_msg)}<br />{/if}
      <div class="alert alert-info alert-dismissible fade in" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
           {if isset($info_msg_no_xss_filter)}
              {$info_msg}
           {else}
              {$info_msg|filter_xss}
           {/if}
      </div>
    {/if}

  </div>
</div>

{/if}
