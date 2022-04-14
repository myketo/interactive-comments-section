<?php

/* @var $this \yii\web\View */

use app\assets\AppAsset;

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
        <head>
            <meta charset="<?= Yii::$app->charset ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <?php $this->registerCsrfMetaTags() ?>
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;700&display=swap" rel="stylesheet">
            <title>Interactive comments section</title>
            <?php $this->head() ?>
        </head>
        <body>
            <?php $this->beginBody() ?>
            <div id="app"></div>
            <?php $this->endBody() ?>
        </body>
    </html>
<?php $this->endPage() ?>
