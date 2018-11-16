<div class="container">
    gefundene Datensätze: {$moredetails.data|@count}
    <table class="table table-sm table-hover">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            {foreach from=$moredetails.columns item=col}
                {if $col neq "ID" and $col neq "origSemester"}
                    <th>{$col}</th>
                {/if}
            {/foreach}
        </tr>
        </thead>
        <tbody>
        {foreach from=$moredetails.data item=data}
            <tr>
                <th scope="row">{counter}</th>
                {foreach from=$data key=key item=value}
                    {if $key neq "ID" and $key neq "origSemester"}
                        <td>{$value}</td>
                    {/if}
                {/foreach}
            </tr>
        {/foreach}
    </table>
    <a href="{$SCRIPT_NAME}?show_studentDetails1={$data.ID}">Zurück</a>
</div>