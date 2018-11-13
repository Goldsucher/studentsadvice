<div class="container">
    <table class="table table-striped">
        <thead class="thead-dark">
        <tr>
            {foreach from=$student.columns.hzb key=key item=col}
                {if $key neq "ID"}
                    <th>{$col}</th>
                {/if}
            {/foreach}
            {if isset($student.columns.abschluss)}
                {foreach from=$student.columns.abschluss item=col}
                    <th>{$col}</th>
                {/foreach}
            {/if}
        </tr>
        </thead>
        <tbody>
            <tr>
                {foreach from=$student.hzb key=key item=value}
                    {if $key neq "ID"}
                        <td>{$value}</td>
                    {/if}
                {/foreach}
                {if isset($student.abschluss)}
                    {foreach from=$student.abschluss key=key item=value}
                        <td>{$value}</td>
                    {/foreach}
                {/if}
            </tr>
    </table>

    Anzahl insgesamt belegter Kurse: {$student.noten|@count}
    <table class="table table-striped table-sm">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            {foreach from=$student.columns.noten item=col}
                <th>{$col}</th>
            {/foreach}
        </tr>
        </thead>
        <tbody>
        {foreach from=$student.noten item=data}
            <tr>
                <th scope="row">{counter}</th>
                {foreach from=$data key=key item=value}
                    <td>{$value}</td>
                {/foreach}
            </tr>
        {/foreach}
    </table>
</div>