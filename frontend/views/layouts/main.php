<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="header">
    <div class="navigator">
        <div class="navigator-head">
            <ul>
				<li>
					<p class="navigator-title">首页</p>
				</li>
                <li>
					<p class="navigator-title">编程语言</p>
					<p><a href="">JAVA</a></p>
					<p><a href="">PHP</a></p>
					<p><a href="">Python</a></p>
				</li>
                <li>
					<p class="navigator-title">存储技术</p>
					<p><a href="">Memcache</a></p>
					<p><a href="">redis</a></p>
				</li>
                <li>
					<p class="navigator-title">WEB技能</p>
					<p><a href="">CSS3</a></p>
					<p><a href="">Javascript</a></p>
					<p><a href="">HTML5</a></p>
					<p><a href="">React</a></p>
					<p><a href="">Kissy</a></p>
					<p><a href="">Nodejs</a></p>
				</li>
                <li>
					<p class="navigator-title">给我留言</p>
					<p><a href="">站内留言</a></p>
					<p><a href="">站长信箱</a></p>
				</li>
            </ul>
        </div>
    </div>
</div>
<div class="wrap">
    <div class="container">
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p> Copyright @ 2017-2020 版权所有</p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
