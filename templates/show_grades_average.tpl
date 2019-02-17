{foreach from=$grades key=gradekey item=grade}
    <div id="container" style="width: 100%;">
        <canvas id="canvas{$gradekey}"></canvas>

        {foreach from=$grade item=item}
            {$item.Unit_id}: {$item.Titel}
        {if $item.Wahlpflicht eq 1}
            - WP
        {else}
            - P
        {/if}
            <br/>
        {/foreach}
    </div>
{/foreach}

    <script>
        {foreach from=$grades key=gradekey item=grade}
        var xLabels{$gradekey} = [];
        var commonCore{$gradekey} = [];
        var elective{$gradekey} = [];
        {foreach from=$grade item=item}
            {$item.Unit_id}
                xLabels{$gradekey}.push({$item.Unit_id});
            {if $item.Wahlpflicht eq 1}
                elective{$gradekey}.push({$item.Durchschnittsnote});
                commonCore{$gradekey}.push(0);
            {else}
                commonCore{$gradekey}.push({$item.Durchschnittsnote});
                elective{$gradekey}.push(0);
            {/if}
        {/foreach}

        var color{$gradekey} = Chart.helpers.color;
        var barChartData{$gradekey} = {
            labels: xLabels{$gradekey},
            datasets: [{
                label: 'Pflicht',
                backgroundColor: color{$gradekey}(window.chartColors.red).alpha(0.5).rgbString(),
                borderColor: window.chartColors.red,
                borderWidth: 1,
                data: commonCore{$gradekey}
            }, {
                label: 'Wahlpflicht',
                backgroundColor: color{$gradekey}(window.chartColors.blue).alpha(0.5).rgbString(),
                borderColor: window.chartColors.blue,
                borderWidth: 1,
                data: elective{$gradekey}
            }]

        };
            var ctx{$gradekey} = document.getElementById('canvas{$gradekey}').getContext('2d');
            window.myBar = new Chart(ctx{$gradekey}, {
                type: 'bar',
                data: barChartData{$gradekey},
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Durchschnittsnote Plansemester {$gradekey}'
                    }
                }
            });

        {/foreach}
    </script>