{if $action eq 1 or $action eq 2 or $action eq 8}
    {include file="CRM/HRLeaveAndAbsences/Form/WorkPattern.tpl"}
{else}
    {if $rows}
        <div id="ltype">
            <div class="form-item">
                {strip}
                    {* handle enable/disable actions*}
                    {include file="CRM/common/enableDisableApi.tpl"}
                    <table cellpadding="0" cellspacing="0" border="0" class="hrleaveandabsences-entity-list">
                        <thead class="sticky">
                            <th>{ts}Label{/ts}</th>
                            <th>{ts}Description{/ts}</th>
                            <th>{ts}No. of Weeks{/ts}</th>
                            <th>{ts}No. of Hours{/ts}</th>
                            <th>{ts}Is Default{/ts}</th>
                            <th>{ts}Order{/ts}</th>
                            <th>{ts}Enabled/Disabled{/ts}</th>
                            <th></th>
                        </thead>
                        {foreach from=$rows item=row}
                            <tr id="WorkPattern-{$row.id}" class="crm-entity {cycle values="odd-row,even-row"} {$row.class}{if NOT $row.is_active} disabled{/if}">
                                <td data-field="title">{$row.label}</td>
                                <td>{$row.description}</td>
                                <td>{$row.number_of_weeks}</td>
                                <td>{$row.number_of_hours}</td>
                                <td>{if $row.is_default eq 1}<img src="{$config->resourceBase}i/check.gif" alt="{ts}Default{/ts}" />{/if}</td>
                                <td>{$row.weight}</td>
                                <td>{if $row.is_active eq 1} {ts}Enabled{/ts} {else} {ts}Disabled{/ts} {/if}</td>
                                <td>{$row.action|replace:'xx':$row.id}</td>
                            </tr>
                        {/foreach}
                    </table>
                {/strip}

                {if $action ne 1 and $action ne 2}
                    <div class="action-link">
                        <a href="{crmURL q="action=add&reset=1"}" class="button"><span><div class="icon add-icon"></div>{ts}Add Work Pattern{/ts}</span></a>
                    </div>
                {/if}
            </div>
        </div>
    {literal}
        <script type="text/javascript">
            CRM.$(function() {
                var listPage = new CRM.HRLeaveAndAbsencesApp.ListPage(CRM.$('.hrleaveandabsences-entity-list'));
            });
        </script>
    {/literal}
    {else}
        <div class="messages status no-popup">
            <div class="icon inform-icon"></div>
            {capture assign=crmURL}{crmURL q="action=add&reset=1"}{/capture}
            {ts 1=$crmURL}There are no Work Patterns entered. You can <a href='%1'>add one</a>.{/ts}
        </div>
    {/if}
{/if}
