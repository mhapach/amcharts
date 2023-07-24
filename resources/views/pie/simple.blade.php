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

        // Create chart https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/
        let chart = root.container.children.push(am5percent.PieChart.new(root, {
            layout: root.verticalLayout
        }));

        // Create series https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Series
        let series = chart.series.push(am5percent.PieSeries.new(root, {
            valueField: "value",
            categoryField: "category"
        }));

        // Set data https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Setting_data
        series.data.setAll(amChartData);

        // Play initial series animation https://www.amcharts.com/docs/v5/concepts/animations/#Animation_of_series
        series.appear(1000, 100);
    });
</script>
