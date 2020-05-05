<?php
namespace app\assets;
use yii\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle
{
    // The files are not web directory accessible, therefore we need
    // to specify the sourcePath property. Notice the @vendor alias used.
    public $sourcePath = '@vendor/fortawesome/font-awesome';
    public $css = [
        'css/all.css',
    ];
    public $js = [
        'js/all.js'
    ];
}