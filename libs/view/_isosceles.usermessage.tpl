{if isset($field)}
    {if isset($success_msgs.$field)}
     <div class="alert helpful">
         <p>
           <span class="ui-icon ui-icon-check" style="float: left; margin:.3em 0.3em 0 0;"></span>
           {if isset($success_msg_no_xss_filter)}
               {$success_msgs.$field}
           {else}
               {$success_msgs.$field|filter_xss}
           {/if}
         </p>
     </div>
    {/if}
    {if isset($error_msgs.$field)}
     <div class="alert urgent">
         <p>
           <span class="ui-icon ui-icon-alert" style="float: left; margin:.3em 0.3em 0 0;"></span>
           {if isset($error_msg_no_xss_filter)}
               {$error_msgs.$field}
           {else}
               {$error_msgs.$field|filter_xss}
           {/if}
         </p>
    </div>
    {/if}
    {if isset($info_msgs.$field)}
    {if isset($success_msgs.$field) OR isset($error_msgs.$field)}<br />{/if}
    <div class="alert stats" style="margin-top: 10px; padding: 0.5em 0.7em;"> 
        <p>
             <span class="ui-icon ui-icon-info" style="float: left; margin: 0.3em 0.3em 0pt 0pt;"></span>
             {if isset($info_msg_no_xss_filter)}
                {$info_msg_no_xss_filter}
             {else}
                {$info_msgs.$field|filter_xss}
             {/if}
        </p>
    </div>
    {/if}
{else}
    {if isset($success_msg)}
     <div class="alert helpful" style="">
         <p>
           <span class="ui-icon ui-icon-check" style="float: left; margin:.3em 0.3em 0 0;"></span>
           {if isset($success_msg_no_xss_filter)}
               {$success_msg_no_xss_filter}
           {else}
               {$success_msg|filter_xss}
           {/if}
         </p>
     </div>
    {/if}
    {if isset($error_msg)}
     <div class="alert urgent" style="">
         <p>
           <span class="ui-icon ui-icon-alert" style="float: left; margin:.3em 0.3em 0 0;"></span>
           {if isset($error_msg_no_xss_filter)}
               {$error_msg}
           {else}
               {$error_msg|filter_xss}
           {/if}
         </p>
    </div>
    {/if}
    {if isset($info_msg)}
    {if isset($success_msg) OR isset($error_msg)}<br />{/if}
    <div class="alert helpful" style="margin-top: 10px; padding: 0.5em 0.7em;"> 
        <p>
             <span class="ui-icon ui-icon-info" style="float: left; margin: 0.3em 0.3em 0pt 0pt;"></span>
             {if isset($info_msg_no_xss_filter)}
                {$info_msg_no_xss_filter}
             {else}
                {$info_msg|filter_xss}
             {/if}
        </p>
    </div>
    {/if}
{/if}