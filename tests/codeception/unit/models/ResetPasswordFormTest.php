<?php

namespace tests\codeception\unit\models;

use tests\codeception\unit\DbTestCase;
use tests\codeception\fixtures\UserFixture;
use app\models\ResetPasswordForm;

class ResetPasswordFormTest extends DbTestCase
{

    /**
     * @expectedException \yii\base\InvalidParamException
     */
    public function testResetWrongToken()
    {
        new ResetPasswordForm('notexistingtoken_1391882543');
    }

    /**
     * @expectedException \yii\base\InvalidParamException
     */
    public function testResetEmptyToken()
    {
        new ResetPasswordForm('');
    }

    public function testResetCorrectToken()
    {
        $form = new ResetPasswordForm($this->user[0]['password_reset_token']);
        expect('password should be resetted', $form->resetPassword())->true();
    }

    public function fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => '@tests/codeception/unit/fixtures/data/models/user.php'
            ],
        ];
    }

}
