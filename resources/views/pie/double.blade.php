<div class="amChart"
     id="{{$layoutSettings['id']}}"
     style="width: {{$layoutSettings['width']}}; height: {{$layoutSettings['height']}}">
</div>

@foreach ($libraries as $library)
    <script src="{{$library}}"></script>
@endforeach

<script>
    let amChartData = {!! json_encode($data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!};

    am5.ready(function () {
        // Create root element https://www.amcharts.com/docs/v5/getting-started/#Root_element
        let root = am5.Root.new("{{$layoutSettings['id']}}");
        // Set responsive class
        let responsive = am5themes_Responsive.new(root);
        // Set themes https://www.amcharts.com/docs/v5/concepts/themes/
        root.setThemes([am5themes_Animated.new(root), responsive]);

        //-----------
        let container = root.container.children.push(
            am5.Container.new(root, {
                width: am5.p100,
                height: am5.p100,
                layout: root.horizontalLayout
            })
        );

        // Create main chart https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/
        let chart = container.children.push(
            am5percent.PieChart.new(root, {
                tooltip: am5.Tooltip.new(root, {})
            })
        );

        // Create series https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Series
        let series = chart.series.push(
            am5percent.PieSeries.new(root, {
                valueField: "value",
                categoryField: "category",
                alignLabels: false
            })
        );

        // series.labels.template.setAll({
        //     textType: "circular",
        //     radius: 4
        // });
        series.ticks.template.set("visible", false);
        series.slices.template.set("toggleKey", "none");

        // add events
        series.slices.template.events.on("click", function (e) {
            selectSlice(e.target);
        });

        // Create sub chart https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/
        let subChart = container.children.push(
            am5percent.PieChart.new(root, {
                radius: am5.percent(50),
                tooltip: am5.Tooltip.new(root, {})
            })
        );

        // Create sub series https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Series
        let subSeries = subChart.series.push(
            am5percent.PieSeries.new(root, {
                valueField: "value",
                categoryField: "category"
            })
        );

        //составляем список подкатегорий для второго пая
        let subCategories = subPieCategories(amChartData);
        subSeries.data.setAll(subCategories)
        // subSeries.slices.template.set("toggleKey", "none");

        let selectedSlice;
        series.on("startAngle", function () {
            updateLines();
        });

        container.events.on("boundschanged", function () {
            root.events.on("frameended", function () {
                updateLines();
            })
        })

        function updateLines() {
            if (selectedSlice) {
                let startAngle = selectedSlice.get("startAngle");
                let arc = selectedSlice.get("arc");
                let radius = selectedSlice.get("radius");

                let x00 = radius * am5.math.cos(startAngle);
                let y00 = radius * am5.math.sin(startAngle);

                let x10 = radius * am5.math.cos(startAngle + arc);
                let y10 = radius * am5.math.sin(startAngle + arc);

                let subRadius = subSeries.slices.getIndex(0).get("radius");
                let x01 = 0;
                let y01 = -subRadius;

                let x11 = 0;
                let y11 = subRadius;

                let point00 = series.toGlobal({x: x00, y: y00});
                let point10 = series.toGlobal({x: x10, y: y10});

                let point01 = subSeries.toGlobal({x: x01, y: y01});
                let point11 = subSeries.toGlobal({x: x11, y: y11});

                line0.set("points", [point00, point01]);
                line1.set("points", [point10, point11]);
            }
        }

        // lines
        let line0 = container.children.push(
            am5.Line.new(root, {
                position: "absolute",
                stroke: root.interfaceColors.get("text"),
                strokeDasharray: [2, 2]
            })
        );
        let line1 = container.children.push(
            am5.Line.new(root, {
                position: "absolute",
                stroke: root.interfaceColors.get("text"),
                strokeDasharray: [2, 2]
            })
        );

        // Set data https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Setting_data
        series.data.setAll(amChartData);

        function selectSlice(slice) {
            selectedSlice = slice;
            let dataItem = slice.dataItem;
            let dataContext = dataItem.dataContext;

            if (dataContext) {
                let i = 0;
                subSeries.data.each(function (dataObject) {
                    let dataObj = dataContext.subData[i];
                    if (dataObj) {
                        if (!subSeries.dataItems[i].get("visible")) {
                            subSeries.dataItems[i].show();
                        }
                        subSeries.data.setIndex(i, dataObj);
                    } else {
                        subSeries.dataItems[i].hide();
                    }

                    i++;
                });
            }

            let middleAngle = slice.get("startAngle") + slice.get("arc") / 2;
            let firstAngle = series.dataItems[0].get("slice").get("startAngle");

            series.animate({
                key: "startAngle",
                to: firstAngle - middleAngle,
                duration: 1000,
                easing: am5.ease.out(am5.ease.cubic)
            });
            series.animate({
                key: "endAngle",
                to: firstAngle - middleAngle + 360,
                duration: 1000,
                easing: am5.ease.out(am5.ease.cubic)
            });
        }

        container.appear(1000, 10);

        series.events.on("datavalidated", function () {
            selectSlice(series.slices.getIndex(0));
        });

        function subPieCategories(amChartData) {
            let subCategories = [];
            amChartData.forEach(function (item) {
                if (item.hasOwnProperty('subData') && Array.isArray(item.subData))
                    subCategories.push(...item.subData.map(function (subItem) {
                        return subItem.category;
                    }));
            });
            return [...new Set(subCategories)].map(function (name) {
                return {category: name, value: 0};
            });
        }
    }); // end am5.ready()

</script>
