<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@api', dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'api');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'console');
Yii::setAlias('@messages', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'messages');
Yii::setAlias('@static', dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'static');
Yii::setAlias('@upload', Yii::getAlias('@static') . DIRECTORY_SEPARATOR . 'upload');