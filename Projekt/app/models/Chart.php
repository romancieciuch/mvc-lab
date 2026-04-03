<?php

declare(strict_types=1);
namespace App\Models;

class Chart {
	public array $options = [];

	public function __construct (array $args = []) {
		$defaults = [
			"container_class"	=> "chart-container",
			"chart_id"			=> "chart_1",
			"height"			=> "400px",
			"init"				=> true,
			"currency"			=> "PLN"
		];

		$this->options = array_merge($defaults, $args);
	}

	public function draw (array $data = [], $date_field = "log_date", $number_field = "balance") : string {
		if (empty($data)) return '';

		$dates = [];
		$numbers = [];

		foreach ($data as $row) {
			$dates[] = $row[$date_field];
			$numbers[] = $row[$number_field];
		}

		$html = '
			<div class="' . $this->options["container_class"] . '">
				<div id="' . $this->options["chart_id"] . '" style="height: ' . $this->options["height"] . ';"></div>
			</div>
		';

		if (!empty($this->options["init"]))
			$html .= '<script src="/js/echarts.min.js"></script>';

		$html .= '
			<script>
				var dom = document.getElementById("' . $this->options["chart_id"] . '");
				var myChart = echarts.init(dom, null, {
					renderer: "canvas",
					useDirtyRect: false
				});
				var app = {};
				var option;

				const colors = ["#059669", "#EF4444", "#F59E0B"];

				option = {
					color: colors,
					tooltip: {
						trigger: "axis",
						position: "nearest",
						valueFormatter: function (value) {
                            return Number(value).toFixed(2) + " ' . $this->options["currency"] . '";
                        }
					},
					legend: {
						bottom: 0
					},
					grid: {
						top: 30,
						bottom: 60
					},
					xAxis: [
						{
							type: "category",
							axisTick: {
								alignWithLabel: true
							},
							axisLine: {
								onZero: false
							},
							data: ["' . implode("\", \"", $dates) .'"]
						}
					],
					yAxis: [
						{
							type: "value"
						}
					],
					series: [
						{
							name: "Wartość",
							type: "line",
							xAxisIndex: 0,
							smooth: true,
							emphasis: { focus: "series" },
							data: [' . implode(", ", $numbers) . ']
						}
					]
				};

				if (option && typeof option === "object")
					myChart.setOption(option);

				window.addEventListener("resize", myChart.resize);
			</script>
		';

		return $html;
	}
}