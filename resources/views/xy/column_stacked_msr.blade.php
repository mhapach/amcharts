<div class="amChart"
     id="{{$layoutSettings['id']}}"
     style="width: {{$layoutSettings['width']}}; height: {{$layoutSettings['height']}}">
</div>

@foreach ($libraries as $library)
    <script src="{{$library}}"></script>
@endforeach

<script>
    am5.ready(function () {
        let amChartData = {!! json_encode($data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!};

        // Create root element https://www.amcharts.com/docs/v5/getting-started/#Root_element
        let root = am5.Root.new("{{$layoutSettings['id']}}");
        root.locale = am5locales_ru_RU;

        // Set themes https://www.amcharts.com/docs/v5/concepts/themes/
        root.setThemes([
            am5themes_Animated.new(root),
            am5themes_Responsive.new(root)
        ]);


        // Create chart https://www.amcharts.com/docs/v5/charts/xy-chart/
        var chart = root.container.children.push(am5xy.XYChart.new(root, {
            layout: root.verticalLayout
        }));

        // Add scrollbar
        // https://www.amcharts.com/docs/v5/charts/xy-chart/scrollbars/
        // chart.set("scrollbarX", am5.Scrollbar.new(root, {
        //   orientation: "horizontal"
        // }));

        // Create axes https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
        var xRenderer = am5xy.AxisRendererX.new(root, {minGridDistance: 50});
        var xAxis = chart.xAxes.push(am5xy.GaplessDateAxis.new(root, {
            maxDeviation: 0.1,
            groupData: false,
            baseInterval: {
                timeUnit: "month",
                count: 1
            },
            gridIntervals: [{
                timeUnit: "month",
                count: 1
            }],
            renderer: xRenderer,
//   tooltip: am5.Tooltip.new(root, {})
        }));
        xAxis.get("periodChangeDateFormats")["month"] = "MMM";
        xRenderer.grid.template.setAll({
            forceHidden: true,
        })
        xRenderer.labels.template.setAll({
            fontSize: "0.625rem",
            fontFamily: "Proxima Nova Rg",
            opacity: 0.6,
            fontWeight: 400
        });

        var yRenderer = am5xy.AxisRendererY.new(root, {strokeOpacity: 0});
        yRenderer.grid.template.setAll({
            strokeDasharray: [3],
        })
        yRenderer.labels.template.setAll({
            fontSize: "0.84rem",
            fontFamily: "Proxima Nova Rg",
            opacity: 0.3,
            fontWeight: 400,
            centerY: am5.p100,

        });
        var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            calculateTotals: true,
            min: 0,
//   extraMax: 0.1,
            renderer: yRenderer
        }));


// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
        function makeSeries(name, fieldName, color, opacity = 1, showTotal = false) {
            var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                name: name,
                stacked: true,
                maskBullets: false,
                xAxis: xAxis,
                yAxis: yAxis,
                valueYField: fieldName,
                valueXField: "date",
                // valueYShow: "valueYTotal",

            }));

            series.columns.template.setAll({
                tooltipText: "{name}: {valueY}",
                tooltipY: am5.percent(10)
            });

            series.set("fill", am5.color(color));
            series.set("stroke", am5.color(color));
            series.columns.template.set('fillOpacity', opacity);
            series.columns.template.set('strokeOpacity', opacity);
            if (showTotal) {
                series.bullets.push(function () {
                    return am5.Bullet.new(root, {
                        locationY: 1,
                        sprite: am5.Label.new(root, {
                            text: "+{valueYTotal}",
                            fill: am5.color('#000000'),
                            fontSize: '0.625rem',
                            fontFamily: "Proxima Nova Rg",
                            fontStyle: 'normal',
                            fontWeight: 700,
                            centerY: am5.p100,
                            centerX: am5.p50,
                            populateText: true
                        })
                    });
                });
            } else {

                series.bullets.push(function () {
                    return am5.Bullet.new(root, {
                        sprite: am5.Label.new(root, {
                            text: "+{valueY}",
                            fill: am5.color('#000000'),
                            fontSize: '0.625rem',
                            fontFamily: "Proxima Nova Rg",
                            fontStyle: 'normal',
                            fontWeight: 400,
                            centerY: am5.p50,
                            centerX: am5.p50,
                            populateText: true
                        })
                    });
                });
            }
            series.data.setAll(amChartData);
            // Make stuff animate on load
            // https://www.amcharts.com/docs/v5/concepts/animations/
            series.appear();

        }


        makeSeries("Доставка", "delivery", '#70D4B1');
        makeSeries("Склад", "stock", '#75dbfa', 0.7);
        makeSeries("Производство", "production", '#BD94FF');
        makeSeries("ФОМС", "foms", '#ff6666', 0.7);
        makeSeries("Выверка", "check", '#819EE9', 0.7);
        makeSeries("", "none", "#000000", '0', true);

        // makeSeries("Доставка", "delivery", '#70D4B1');
        // makeSeries("Склад", "stock", '#75dbfa', 0.7);
        // makeSeries("Производство", "production", '#BD94FF');
        // makeSeries("ФОМС", "foms", '#ff6666', 0.7);
        // makeSeries("Выверка", "check", '#819EE9', 0.7);
        // makeSeries("", "none", "#000000", '0', true);
        //
// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
        chart.appear(1000, 100);


    });
</script>
