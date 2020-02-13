<?php


namespace app\assets;


use yii\web\AssetBundle;

class ChartJsAssetBundle extends AssetBundle
{
    public $sourcePath = '@app/widgets/Graph/assets/';
    public $css = [
        'css/semantic.min.css',
        'css/style.css',
    ];
    public $js = [
        'js/semantic.min.js',
        'js/moment.min.js',
        'js/Chart.bundle.js',
        'js/hammer.min.js',
        'js/chartjs-plugin-zoom.min.js',
        'js/datatables.min.js',
        'js/FileSaver.js',
        'js/canvas-toBlob.js',
        'js/main.js',

    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}
