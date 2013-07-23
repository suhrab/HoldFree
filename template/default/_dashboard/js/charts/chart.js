$(function ()
{
    $.getJSON('/index.php?module=chart&dashboard=1&is_ajax=1', {}, function (response)
    {

        var percentNewVisits    = [];
        var visitors            = [];
        var responseJSON        = $.parseJSON(response);
        var visitorsData        = responseJSON.visitors;
        var newVisitsData       = responseJSON.percentNewVisits;

        for (i = 0; i < visitorsData.length; i++) {
            visitors.push([visitorsData[i].date, visitorsData[i].data]);
        }

        for (i = 0; i < newVisitsData.length; i++) {
            percentNewVisits.push([newVisitsData[i].date, newVisitsData[i].data]);
        }



        var plot = $.plot($(".chart"),
            [
                {data: visitors, label: "Посещения"},
                {data: percentNewVisits, label: "Новые посещения, %"}
            ],
            {
                series: {
                    lines: { show: true },
                    points: { show: true }
                },
                grid: { hoverable: true, clickable: true },
                xaxis: {
                    mode: "time",
                    tickSize: [1, "day"]
                }
            }
        );

        function showTooltip(x, y, contents) {
            $('<div id="tooltip" class="tooltip">' + contents + '</div>').css( {
                position: 'absolute',
                display: 'none',
                top: y + 5,
                left: x + 5,
                'z-index': '9999',
                'color': '#fff',
                'font-size': '11px',
                opacity: 0.8
            }).appendTo("body").fadeIn(200);
        }

        var previousPoint = null;

        $(".chart").bind("plothover", function (event, pos, item) {
            $("#x").text(pos.x.toFixed(2));
            $("#y").text(pos.y.toFixed(2));

            if ($(".chart").length > 0) {
                if (item) {
                    if (previousPoint != item.dataIndex) {
                        previousPoint = item.dataIndex;

                        $("#tooltip").remove();
                        var x = item.datapoint[0];
                        var y = item.series.label == 'Новые посещения, %' ? item.datapoint[1].toFixed(1) + '%' : item.datapoint[1];

                        showTooltip(item.pageX, item.pageY, item.series.label + ": " + y);
                    }
                }
                else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
            }
        });

        $(".chart").bind("plotclick", function (event, pos, item) {
            if (item) {
                $("#clickdata").text("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
                plot.highlight(item.series, item.datapoint);
            }
        });
    }, 'json');

//    visitors.push([1356998400000, 147]);
//    visitors.push([1357084800000, 178]);
//    visitors.push([1357171200000, 202]);
//    visitors.push([1357257600000, 239]);


});
