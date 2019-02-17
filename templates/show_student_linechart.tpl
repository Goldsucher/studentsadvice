<div class="col-md-10">
    <canvas id="lineChart"></canvas>
</div>

<script>
    var takenSemester = [];
    var passedSemester = [];
    var takenNumberOfCourses = [];
    var passedNumberOfCourses = [];
    var tmpTakenSemester = [];
    var tmpTakenNumberOfCourses = [];
    var tmpPassedSemester = [];
    var tmpPassedNumberOfCourses = [];
    var tmpdata = {$numberOfCourses};

    tmpTakenSemester.push(tmpdata['numberOfCoursesTaken']['semester']);
    tmpTakenNumberOfCourses.push(tmpdata['numberOfCoursesTaken']['numberOfCourses']);

    tmpPassedSemester.push(tmpdata['numberOfPassedCourses']['semester']);
    tmpPassedNumberOfCourses.push(tmpdata['numberOfPassedCourses']['numberOfCourses']);

    tmpTakenSemester[0].forEach(function(sem) {
        takenSemester.push(sem[0])
    });
    tmpTakenNumberOfCourses[0].forEach(function(course) {
        takenNumberOfCourses.push(course[0])
    });

    tmpPassedSemester[0].forEach(function(sem) {
        passedSemester.push(sem[0])
    });
    tmpPassedNumberOfCourses[0].forEach(function(course) {
        passedNumberOfCourses.push(course[0])
    });



    var chartData = {
        labels: takenSemester,
        datasets: [
            {
                label: "Anzahl belegter Kurse",
                data: takenNumberOfCourses,
                backgroundColor: ['rgba(105, 0, 132, .2)',],
                borderColor: ['rgba(200, 99, 132, .7)',],
                borderWidth: 2
            },
            {
                label: "Anzahl bestandener Kurse",
                data: passedNumberOfCourses,
                backgroundColor: ['rgba(0, 137, 132, .2)',],
                borderColor: ['rgba(0, 10, 130, .7)',],
                borderWidth: 2
            }
        ]
    }

            var ctxL = document.getElementById("lineChart").getContext('2d');
            var lineChart = new Chart(ctxL, {
                type: 'line',
                data: chartData
            });

</script>
