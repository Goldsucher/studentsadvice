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
    <br/>
    <a href="{$SCRIPT_NAME}?timeline={$student.ID}">Timeline</a>
    <br/>
    <a href="{$SCRIPT_NAME}?line_chart={$student.ID}">LineChart</a>
    <br/>
    <a href="{$SCRIPT_NAME}">Zurück</a>
    <table class="table table-sm table-hover">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            {foreach from=$student.columns.noten item=col}
                {if $col neq "ID" and $col neq "origSemester"}
                    <th>{$col}</th>
                {/if}
            {/foreach}
        </tr>
        </thead>
        <tbody>
        {foreach from=$student.noten item=data}
            <tr>
                <th scope="row">{counter}</th>
                {foreach from=$data key=key item=value}
                    {if $key neq "ID" and $key neq "origSemester"}
                        {if $key eq "Titel" || $key eq "Unit"}
                            <td><a href="{$SCRIPT_NAME}?show_studentDetails2={$data.ID}&course={$data.Unit}">{$value}</a></td>
                        {elseif $key eq "Semester"}
                            <td><a href="{$SCRIPT_NAME}?show_studentDetails2={$data.ID}&semester={$data.origSemester}">{$value}</a></td>
                        {else}
                            <td>{$value}</td>
                        {/if}
                    {/if}
                {/foreach}
            </tr>
        {/foreach}
    </table>
    <a href="{$SCRIPT_NAME}">Zurück</a>
</div>