<div id="timeline" style="height: 200px;"></div>



<script type="text/javascript">

    var datapack = JSON.parse('{$student}');

    var data_keys = datapack.keys;

    console.log(data_keys);


    {literal}google.charts.load('current', {'packages':['timeline']});{/literal}
    google.charts.setOnLoadCallback(drawChart);


    function drawChart() {
        var container = document.getElementById('timeline');
        var chart = new google.visualization.Timeline(container);
        var dataTable = new google.visualization.DataTable();

        console.log(dataTable);

        dataTable.addColumn({ type: 'string', id: 'Semester' });
        dataTable.addColumn({ type: 'string', id: 'Kurs' });
        dataTable.addColumn({ type: 'date', id: 'Jahr' });
        dataTable.addColumn({ type: 'date', id: 'Jahr2' });

        dataTable.addRows([
            [ '2005', 'George Washington', new Date(2005,10,1),  new Date(2006,2,14)],
            [ '2005/2006', 'John Adams', new Date(2005,10,1),  new Date(2006,2,14)],
            [ '2006', 'Thomas Jefferson', new Date(2005,10,1),  new Date(2006,14,2)]]);

        var options = {
            timeline: { showRowLabels: false }
        };
        chart.draw(dataTable,options);
    }
</script>