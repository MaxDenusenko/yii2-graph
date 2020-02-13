<?php

/* @var $this yii\web\View */

/* @var $summaryData array */

$this->title = 'Graph';

use yii\helpers\Url; ?>

<div class="center-align panel_actions">
    <button id="downloadImage" onclick="createImage()" data-tooltip="Download plot as a png" class="btn blue darken-4 btn-md tooltipped"><i class="material-icons">image</i></button>
    <button id="switch_xAxes_config" data-tooltip="Switch time line mode" onclick="switchXAxesConfig()" class="btn blue darken-4 btn-md tooltipped"><i class="material-icons">alarm</i></button>
    <button id="switch_fill" data-tooltip="Switch fill" onclick="showHideFill()" class="btn blue darken-4 btn-md tooltipped"><i class="material-icons">filter_hdr</i></button>
    <button id="switch_drag_mode" data-tooltip="Drag mode" onclick="toggleDragMode()" class="btn blue darken-4 btn-md tooltipped"><i class="material-icons">pan_tool</i></button>
    <button id="switch_zoom_mode" data-tooltip="Zoom mode" onclick="toggleZoomMode()" class="btn blue darken-4 btn-md tooltipped"><i class="material-icons">zoom_in</i></button>
    <button data-tooltip="Reset zoom" onclick="resetZoom()" class="btn blue darken-4 btn-md tooltipped"><i class="material-icons">home</i></button>
    <button data-tooltip="Increase border width" onclick="increaseBorderWidth()" class="btn blue darken-4 btn-md tooltipped"><i class="material-icons">add</i></button>
    <button data-tooltip="decrease border width" onclick="decreaseBorderWidth()" class="btn blue darken-4 btn-md tooltipped"><i class="material-icons">remove</i></button>
    <a href="<?= Url::to(['datums/view', 'key' => $summaryData['datum_key']]) ?>"
       data-tooltip="Edit Datum" class="btn blue darken-4 btn-md tooltipped"><i class="material-icons">edit</i></a>
</div>

<div id="parent-div">
    <canvas style="overflow: visible" class="m" id="myChart" width="940" height="540" role="img"></canvas>
    <div id="chartjs-tooltip"></div>
</div>

<ul class="collapsible">

    <?php foreach ($summaryData['datum'] as $kd => $datum) : ?>
        <li id="dataTable-<?=$kd?>">
            <div class="collapsible-header"><i class="material-icons">table</i>Table #<?=$kd+1?> (<?=$datum['graphName']?>)</div>
            <div class="collapsible-body">

                <div class="panel-body">
                    <div class="table_data" id="table-<?=$kd?>">
                        <div>
                            <table class="striped centered highlight">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <?php foreach ($datum['headerTrLabelArr'] as $f => $label) : ?>
                                            <th scope="col" class="<?= $datum['balanceIndex'] ==  $f ? 'balance_column' : null; ?>"><?=$label?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($datum['dataTrArr'] as $i => $dataItem) : ?>
                                        <?php if (!isset($dataItem[$datum['firstDataIndex']]) || !isset($dataItem[$datum['balanceIndex']])) continue; ?>
                                        <?php $index = 0; ?>
                                        <tr data-index="<?=$i?>" data-datasetIndex="<?=$kd?>">
                                            <td><?=$i+1?></td>
                                            <?php foreach ($dataItem as $position => $d) : ?>
                                                <?php $index++ ?>
                                                <?php if ($index == $position) : ?>
                                                    <td class="<?= $datum['balanceIndex'] ==  $index ? 'balance_column' : null; ?>"><?=$d?></td>
                                                <?php else: ?>
                                                    <td class="<?= $datum['balanceIndex'] ==  $index ? 'balance_column' : null; ?>"
                                                        colspan="<?=$position - $index + 1?>"><?=$d?></td>

                                                    <!--fix DataTable Js error-->
                                                    <?php for ($ii = 0; $ii <= ($position - $index - 1); $ii++) : ?>
                                                        <td style="display: none"></td>
                                                    <?php endfor; ?>

                                                    <?php $index = $position ?>

                                                <?php endif; ?>

                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </li>
    <?php endforeach; ?>
</ul>

<script>

    function createImage() {
        let link = document.createElement('a');
        link.setAttribute('href',document.getElementById('myChart').toDataURL('image/png'));
        link.setAttribute('download','filename.png');
        link.setAttribute('id','link');
        link.click();
    }

    $( document ).ready(function() {

        let tables = document.querySelectorAll('.collapsible table');
        if (tables) {
            tables.forEach(function( table ){
                $(table).DataTable();
            });
        }

        let isChartRendered = false,
            dragOptions = {
                animationDuration: 1000
            },
            chartType = 'line',
            xAxesTimeConfig = [{
                type: 'time',
                distribution: 'linear',
                scaleLabel: {
                    display: true,
                    labelString: "Date"
                },
            }],
            summaryData = <?= json_encode($summaryData['datum']); ?>;

        function showTooltip(chart, index, datasetIndex){
            let segment = chart.getDatasetMeta(datasetIndex).data[index];
            chart.tooltip._active = [segment];
            chart.tooltip.update();
            chart.draw();
        }
        function getRandomRgb(){

            let randomR = Math.floor((Math.random() * 130) + 100);
            let randomG = Math.floor((Math.random() * 130) + 100);
            let randomB = Math.floor((Math.random() * 130) + 100);

            return "rgb("
                + randomR + ", "
                + randomG + ", "
                + randomB + ", "
                + 0.5 + ")";
        }

        $(document).on('click', '.table_data tr', function() {
            let $this = $(this),
                datasetIndex = $this[0].getAttribute('data-datasetIndex'),
                index = $this[0].getAttribute('data-index');

            showTooltip(window.myLine, index, datasetIndex);

            $('body,html').animate({
                scrollTop : 0                       // Scroll to top of body
            }, 500);
        });

        function activateButton(button) {
            button.classList.remove('blue');
            button.classList.remove('darken-4');
            button.classList.add('light-blue');
            button.classList.add('lighten-4');
        }
        function disableButton(button) {
            button.classList.remove('light-blue');
            button.classList.remove('lighten-4');
            button.classList.add('blue');
            button.classList.add('darken-4');
        }
        function updateButton() {

            let chart = window.myLine;

            let button_switch_zoom_mode = document.getElementById('switch_zoom_mode');
            let zoomOptions = chart.options.plugins.zoom.zoom;
            if (zoomOptions.drag) {
                activateButton(button_switch_zoom_mode);
            } else {
                disableButton(button_switch_zoom_mode);
            }

            let button_switch_drag_mode = document.getElementById('switch_drag_mode');
            let panOptions = chart.options.plugins.zoom.pan;
            if (panOptions.enabled) {
                disableButton(button_switch_drag_mode);
            } else {
                activateButton(button_switch_drag_mode);
            }

            let button_switch_fill = document.getElementById('switch_fill');
            if (chart.data.datasets[0].fill) {
                disableButton(button_switch_fill);
            } else {
                activateButton(button_switch_fill);
            }

            let button_switch_xAxes_config = document.getElementById('switch_xAxes_config');
            let scalesOptions = chart.options.scales;
            if (scalesOptions.xAxes[0].type === 'time') {
                disableButton(button_switch_xAxes_config);
            } else {
                activateButton(button_switch_xAxes_config);
            }
        }

        window.switchXAxesConfig = function () {
            let button = document.getElementById('switch_xAxes_config');
            let chart = window.myLine;
            let scalesOptions = chart.options.scales;

            if(scalesOptions.xAxes[0].type === 'time') {
                delete scalesOptions.xAxes[0].type;
            } else {
                scalesOptions.xAxes[0].type = 'time';
            }

            chart.update();

            updateButton();
        };
        window.resetGraph = function() {
            init(chartType);
        };
        window.openTable = function(i) {
            let instance = M.Collapsible.getInstance($('.collapsible'));
            instance.open(i);

            $('html, body').animate({
                scrollTop: $('#dataTable-'+i).offset().top
            }, {
                duration: 370,
                easing: "linear"
            });
        };
        window.resetZoom = function() {
            window.myLine.resetZoom();
        };
        window.toggleDragMode = function() {

            let button = document.getElementById('switch_drag_mode');
            let chart = window.myLine;
            let panOptions = chart.options.plugins.zoom.pan;
            panOptions.enabled = !panOptions.enabled;
            chart.update();

            updateButton()
        };
        window.toggleZoomMode = function() {

            let button = document.getElementById('switch_zoom_mode');
            let chart = window.myLine;
            let zoomOptions = chart.options.plugins.zoom.zoom;
            let panOptions = chart.options.plugins.zoom.pan;
            zoomOptions.drag = zoomOptions.drag ? false : dragOptions;
            panOptions.enabled = !zoomOptions.drag;
            chart.update();

            updateButton()
        };
        window.showHideAll = function() {
            window.myLine.data.datasets.forEach(function(ds) {
                ds.hidden = !ds.hidden;
            });
            window.myLine.update();
        };
        window.showHideXGridLines = function() {
            let chart = window.myLine;
            let xAxes = chart.options.scales.xAxes;

            xAxes[0].gridLines.display = !xAxes[0].gridLines.display;
            chart.update();
        };
        window.showHideYGridLines = function() {
            let chart = window.myLine;
            let yAxes = chart.options.scales.yAxes;

            yAxes[0].gridLines.display = !yAxes[0].gridLines.display;
            chart.update();
        };
        window.showHideFill = function() {

            let button = document.getElementById('switch_fill');
            window.myLine.data.datasets.forEach(function(ds) {
                ds.fill = !ds.fill;
            });
            window.myLine.update();

            updateButton()
        };
        window.increaseBorderWidth = function() {
            window.myLine.data.datasets.forEach(function(ds) {
                ds.borderWidth = ds.borderWidth + 1;
            });
            window.myLine.update();
        };
        window.decreaseBorderWidth = function() {
            window.myLine.data.datasets.forEach(function(ds) {
                ds.borderWidth = ds.borderWidth > 1 ? ds.borderWidth -1 : 1 ;
            });
            window.myLine.update();
        };

        let ctx = document.getElementById('myChart').getContext('2d');

        let data = {
            labels: <?= json_encode($summaryData['labels']); ?>,
            datasets: [
                <?php foreach ($summaryData['datum'] as $k => $datum) : ?>
                {
                    label: "#<?=$k+1?> <?=$datum['graphName']?>",
                    backgroundColor: getRandomRgb(),
                    borderColor: getRandomRgb(),
                    data: <?= json_encode($datum['compactChartData']); ?>,
                    fill: true,
                    lineTension: 0,
                    borderWidth: 1,
                    pointRadius: 2
                },
                <?php endforeach; ?>
            ]
        };

        let options = {
            bezierCurve : false,
            // animation: {
            //     onComplete: createImage
            // },
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: xAxesTimeConfig,
                yAxes: [{
                    ticks: {
                        beginAtZero:true,
                        callback: function (value, index, values) {
                            return Math.round(value) + ' $';
                        }
                    }
                }]
            },

            plugins: {
                zoom: {
                    pan: {
                        enabled: true,
                        mode: "xy",
                        speed: 10,
                        threshold: 10
                    },
                    zoom: {
                        enabled: true,
                        drag: false,
                        mode: "xy",
                        limits: {
                            max: 10,
                            min: 0.5
                        }
                    }
                }
            },

            tooltips: {
                enabled: false,
                custom: function(tooltip) {
                    // Tooltip Element
                    let tooltipEl = $('#chartjs-tooltip');

                    if (tooltip.opacity === 0) {
                        tooltipEl.css('visibility', 'hidden');
                        return;
                    } else {
                        tooltipEl.css('visibility', 'visible');
                    }

                    if (tooltip.body) {
                        let innerHtml = [
                            (tooltip.body[0].lines || []).join('\n')
                        ];

                        let datasetIndex = tooltip.dataPoints[0].datasetIndex;
                        let index = tooltip.dataPoints[0].index;
                        let xLabel = tooltip.dataPoints[0].xLabel;
                        let yLabel = tooltip.dataPoints[0].yLabel;
                        data = [];

                        let tooltipModelValue = parseFloat(yLabel);
                        tooltipModelValue = tooltipModelValue.toFixed(2);
                        tooltipModelValue = parseFloat(tooltipModelValue);

                        data.push('#'+(index+1));
                        data.push('<br>');
                        let indexHelp = xLabel +'|-|'+tooltipModelValue+'|-|'+ (index) + '|-|'+datasetIndex;
                        var dataObj = summaryData[datasetIndex].compactChartDataWithData;
                        for (let prop in dataObj) {
                            if (prop === indexHelp) {

                                let props = dataObj[prop];
                                for (let propsLabel in props) {
                                    data.push( '<b>' + propsLabel + '</b>' + ' : '+props[propsLabel]);
                                    data.push('<br>');
                                }
                            }
                        }

                        tooltipEl.html(data);
                    }

                    let position = $(this._chart.canvas)[0].getBoundingClientRect();
                    // Display, position, and set styles for font
                    tooltipEl.css({
                        opacity: 1,
                        width: tooltip.width ? (tooltip.width + 'px') : 'auto',
                        left: position.left + window.pageXOffset + tooltip.caretX + 'px',
                        top: position.top + window.pageYOffset + tooltip.caretY + 'px',
                        fontFamily: tooltip._fontFamily,
                        fontSize: tooltip.fontSize,
                        fontStyle: tooltip._fontStyle,
                        padding: tooltip.yPadding + 'px ' + tooltip.xPadding + 'px',
                    });
                }
            },

            onClick: function(evt, elements) {},

            // animation: {
            //     onComplete: function() {
            //         if (!isChartRendered) {
            //             showTooltip(window.myLine, 0);
            //             isChartRendered = true;
            //         }
            //     }
            // },
        };

        Chart.plugins.register({
            afterDatasetsDraw: function(chart) {
                if (chart.tooltip._active && chart.tooltip._active.length) {
                    var activePoint = chart.tooltip._active[0],
                        ctx = chart.ctx,
                        y_axis = chart.scales['y-axis-0'],
                        x = activePoint.tooltipPosition().x,
                        topY = y_axis.top,
                        bottomY = y_axis.bottom;

                    // draw vertical line
                    ctx.save();
                    ctx.beginPath();
                    ctx.moveTo(x, topY);
                    ctx.lineTo(x, bottomY);
                    ctx.lineWidth = 1;
                    ctx.strokeStyle = '#07C';
                    ctx.stroke();
                    ctx.restore();

                    // draw horizontal line
                    ctx.save();
                    ctx.beginPath();
                    ctx.moveTo(chart.scales['x-axis-0'].left, activePoint.tooltipPosition().y);
                    ctx.lineTo(chart.scales['x-axis-0'].right, activePoint.tooltipPosition().y);
                    ctx.lineWidth = 1;
                    ctx.strokeStyle = '#07C';
                    ctx.stroke();
                    ctx.restore();
                }
            },
        });

        init(chartType);

        function init(chartType) {

            window.myLine = new Chart(ctx, {
                type: chartType,
                data: data,
                options: options
            });
        }

        updateButton();

    });
</script>
