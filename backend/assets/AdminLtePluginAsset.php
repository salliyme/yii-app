<?php
/**
 * author: salliyme
 * email: 307623183@qq.com
 * Date: 2017/8/26 17:50
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Class AdminLtePluginAsset
 * @package backend\assets
 */
class AdminLtePluginAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins';
    public $js = [
        'datatables/dataTables.bootstrap.min.js',
        // more plugin Js here
    ];
    public $css = [
        'datatables/dataTables.bootstrap.css',
        // more plugin CSS here
    ];
    public $depends = [
        'dmstr\web\AdminLteAsset',
    ];
}