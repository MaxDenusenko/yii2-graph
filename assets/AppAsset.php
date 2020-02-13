<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use macgyer\yii2materializecss\assets\MaterializeFontAsset;
use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/materialize.min.css',
        'css/site.css',
    ];
    public $js = [
        'js/fontawesome.js',
        'js/materialize.min.js',
        'js/site.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        MaterializeFontAsset::class,
//        'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = array(
        'position' => View::POS_HEAD
    );
}
