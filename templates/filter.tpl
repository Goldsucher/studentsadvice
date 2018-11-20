<div class="container">
    Anzahl gefundener Studenten (Medien-Informatik | Bachelor): {$hzbData.data|@count}
    <table class="table table-striped table-hover">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            {foreach from=$hzbData.columns item=col}
                <th>{$col}</th>
            {/foreach}
        </tr>
        </thead>
        <tbody>
        {foreach from=$hzbData.data item=data}
        <tr>
            <th scope="row">{counter}</th>
            {foreach from=$data key=key item=value}
                {if $key eq "ID"}
                    <td><a href="{$SCRIPT_NAME}?show_studentDetails1={$value}">{$value}</a></td>
                {elseif $key eq "wechsel"}
                    {if $value eq "0"}
                        <td>nein</td>
                     {else}
                        <td>ja</td>
                    {/if}
                {else}
                    <td>{$value}</td>
                {/if}
            {/foreach}
        </tr>
        {/foreach}
    </table>
</div>

