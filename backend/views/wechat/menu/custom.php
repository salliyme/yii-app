<?php
/**
 * author: salliyme
 * email: 307623183@qq.com
 * Date: 2017/8/27 15:36
 */

$this->title = '自定义菜单';
?>

<div class="row">
    <h4>自定义菜单设置</h4>
    <form action="<?=\yii\helpers\Url::to(['custom'])?>" method="post">
        <input type="text" name="type" value="btn"/>
        <input type="submit" value="确定创建">
    </form>
</div>
