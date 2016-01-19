/**
 * This function filters the status page by a given system
 * @param system
 */
function showSystem(system) {
    system = system.replace(/\./g, '_');
    if (system == "_all") {
        $(".row").show();
    } else {
        $(".row").hide();
        $("." + system).show();
    }
}

google.load("visualization", "1", {packages: ["corechart"]});

var loadedStats = new Array;

function drawChart(event_id, div) {

    $("#" + div).toggle();

    if (!loadedStats[event_id]) {
        loadedStats[event_id] = true;

        jsonUrl = Routing.generate('koalamon_stat_day', {'event': event_id});

        var jsonData = $.ajax({
            url: jsonUrl,
            dataType: "json",
            async: false
        }).responseText;

        var data = google.visualization.arrayToDataTable($.parseJSON(jsonData));

        var options = {
            legend: {position: 'top', textStyle: {color: 'black', fontSize: 12}},
            isStacked: true,
            colors: ['#f16059', '#27ae60']
        };

        var chart = new google.visualization.ColumnChart(document.getElementById(div));
        chart.draw(data, options);
    }
}

function checkForNewEvents() {
    console.log(currentProject);
    $.get(Routing.generate('koalamon_rest_project_status_lastchange', {'project': currentProject}), function (data) {
        if (data.created > listCreated) {
            $("#alert").show();
            $("#alert").html("The open incidents have changed. Refreshing in <span class='countdown'>10</span> seconds.");
            setTimeout("location.reload()", 10 * 1000);
            setTimeout("countDown()", 1 * 1000);
        } else {
            setTimeout("checkForNewEvents()", 60 * 1000);
        }
    });
}

function countDown() {
    currentTime = parseInt($(".countdown").html());
    console.log(currentTime);
    $(".countdown").html(Math.max(currentTime - 1, 0));
    setTimeout("countDown()", 1 * 1000);
}

function setFilter(elementType) {
    $("#" + elementType + "List").toggle();
}
