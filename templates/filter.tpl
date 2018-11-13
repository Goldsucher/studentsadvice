

{*
<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Dropdown button
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="#">Action</a>
        <a class="dropdown-item" href="#">Another action</a>
        <a class="dropdown-item" href="#">Something else here</a>
    </div>
</div>*}
<div class="container">
    Anzahl gefundener Studenten (Medien-Informatik | Bachelor)): {$hzbData.data|@count}
    <table class="table table-striped">
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
                    <td><a href="{$SCRIPT_NAME}?show_id={$value}">{$value}</a></td>
                {else}
                    <td>{$value}</td>
                {/if}
            {/foreach}
        </tr>
        {/foreach}
    </table>
</div>

