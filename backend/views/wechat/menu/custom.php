<?php
/**
 * author: salliyme
 * email: 307623183@qq.com
 * Date: 2017/8/27 15:36
 */

$this->title = '自定义菜单';
?>

<div class="row">
    <div class="col-md-6">
        <h4>自定义菜单设置</h4>
        <form action="<?=\yii\helpers\Url::to(['custom'])?>" method="post">
            <label>菜单数据JSON格式：</label>
            <textarea name="menu" cols="80" rows="15"><?=$json?></textarea>
            <input type="submit" value="保存">
        </form>
    </div>
</div>
