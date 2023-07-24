<div class="amChart"
     id="{{$layoutSettings['id']}}"
     style="width: {{$layoutSettings['width']}}; height: {{$layoutSettings['height']}}">
</div>

@foreach ($libraries as $library)
    <script src="{{$library}}"></script>
@endforeach

<script>
    am5.ready(function() {
        let amChartData = {!! json_encode($data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!};

        // Create root element https://www.amcharts.com/docs/v5/getting-started/#Root_element
        let root = am5.Root.new("{{$layoutSettings['id']}}");
        // Set responsive class
        let responsive = am5themes_Responsive.new(root);
        // Set themes https://www.amcharts.com/docs/v5/concepts/themes/
        root.setThemes([am5themes_Animated.new(root), responsive]);

        root.locale = am5locales_ru_RU;

        // Create chart
        // https://www.amcharts.com/docs/v5/charts/xy-chart/
        var chart = root.container.children.push(
            am5xy.XYChart.new(root, {})
        );
        // Create axes
        // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
        var xAxis = chart.xAxes.push(
            am5xy.DateAxis.new(root, {
                baseInterval: {
                    timeUnit: "month",
                    count: 1
                },
                gridIntervals: [{
                    timeUnit: "month",
                    count: 1
                }],
                renderer: am5xy.AxisRendererX.new(root, {}),
            })
        );
        xAxis.get("renderer").grid.template.setAll({
            forceHidden: true
        });
        xAxis.get("renderer").labels.template.setAll({
            fontSize: "0.625rem",
            fontFamily: "Proxima Nova Rg",
            opacity: 0.6,
            fontWeight: 400
        });
        xAxis.get("periodChangeDateFormats")["month"] = "MMM";

        var yRenderer = am5xy.AxisRendererY.new(root, {});
        yRenderer.labels.template.setAll({
            visible: false,
            fontSize: "0.625rem",
            fontFamily: "Proxima Nova Rg",
            opacity: 0.6,
            fontWeight: 400
        });
        yRenderer.grid.template.setAll({
            forceHidden: true
        });
        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            min: 0,
            height: am5.percent(70),
            location: 0,
            renderer: yRenderer
        }));

        // Add series
        // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
        var series = chart.series.push(
            am5xy.SmoothedXYLineSeries.new(root, {
                name: "Series",
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: "value",
                valueXField: "date",
                tension: 0.3
            })
        );
        series.strokes.template.set("templateField", "strokeSettings");

        series.data.setAll(amChartData);



        var value = 10;
        var previousValue = value;

        var downColor = am5.color("#E0504D");
        var upColor = am5.color("#20B56D");
        var color;
        var previousColor;
        var previousDataObj;

        function generateData(i) {
            if(i != 5 && i != 6) value = 1;
            else if(i != 5) value = 8;
            else value = 10;

            am5.time.add(date, "month", 1);

            if (value >= previousValue) {
                color = upColor;
            } else {
                color = downColor;
            }
            previousValue = value;

            var dataObj = {
                date: date.getTime(),
                value: value,
                color: color
            }; // color will be used for tooltip background

            // only if changed
            if (color != previousColor) {
                if (!previousDataObj) {
                    previousDataObj = dataObj;
                }
                previousDataObj.strokeSettings = {
                    stroke: color
                };
            }

            previousDataObj = dataObj;
            previousColor = color;

            return dataObj;
        }

        function generateDatas(count) {
            date = new Date(2022, 11, 1);
            date.setHours(0, 0, 0, 0);

            var data = [];
            for (var i = 0; i < count; ++i) {
                data.push(generateData(i));
            }

            return data;
        }

    });
</script>
