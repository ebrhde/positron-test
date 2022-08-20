<?php
/**
 * Created by PhpStorm.
 * User: babl
 * Date: 01.11.17
 * Time: 14:52
 */
namespace console\controllers;

use Yii;
use common\models\User;

class RbacController extends \yii\console\Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->getAuthManager();
        $auth->removeAll();

        $admin = $auth->createRole(User::ROLE_ADMIN);

        $auth->add($admin);

        $adminUser = User::find()->andWhere(['email' => 'admin@positron-it.ru'])->one();

        $auth->assign($admin, $adminUser->id);
    }

    public function actionTest()
    {

    }
}
