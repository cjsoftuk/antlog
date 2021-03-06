<?php

namespace tests\codeception\functional;

use tests\codeception\_pages\SignupPage;
use app\models\User;

class SignupCest
{
	protected $captcha = 'testme';

    /**
     * This method is called before each cest class test method
     * @param \Codeception\Event\TestEvent $event
     */
    public function _before($event)
    {
    }

    /**
     * This method is called after each cest class test method, even if test failed.
     * @param \Codeception\Event\TestEvent $event
     */
    public function _after($event)
    {
        User::deleteAll([
            'email' => 'user2@test.com',
            'username' => 'test_user2',
        ]);
    }

    /**
     * This method is called when test fails.
     * @param \Codeception\Event\FailEvent $event
     */
    public function _fail($event)
    {

    }

    /**
     *
     * @param \codeception_frontend\FunctionalTester $I
     * @param \Codeception\Scenario $scenario
     */
    public function testUserSignup($I, $scenario)
    {
        $I->wantTo('ensure that signup works');

        $signupPage = SignupPage::openBy($I);
        $I->see('Signup', 'h1');
        $I->see('Please fill out the following fields to signup:');

        $I->amGoingTo('submit signup form with no data');

        $signupPage->submit([]);

        $I->expectTo('see validation errors');
        $I->see('Username cannot be blank.', '.help-block');
        $I->see('Email Address cannot be blank.', '.help-block');
        $I->see('Password cannot be blank.', '.help-block');
		$I->see('Team cannot be blank.', '.help-block');

        $I->amGoingTo('submit signup form with not correct email');
        $signupPage->submit([
            'username' => 'test_user2',
            'email' => 'tester.email',
            'password' => 'password',
			'team_name' => 'Team Test 2',
        	'captcha' => $this->captcha,
        ]);

        $I->expectTo('see that email address is wrong');
        $I->dontSee('Username cannot be blank.', '.help-block');
        $I->dontSee('Password cannot be blank.', '.help-block');
		$I->dontSee('Team cannot be blank.', '.help-block');
        $I->see('Email Address is not a valid email address.', '.help-block');

        $I->amGoingTo('submit signup form with username that is already in use');
        $signupPage->submit([
            'username' => 'test_user',
            'email' => 'user@test.com',
            'password' => 'password',
			'team_name' => 'New Team Test',
        	'captcha' => $this->captcha,
        ]);

        $I->expectTo('see that username has already been taken');
        $I->see('Username "test_user" has already been taken.', '.help-block');
        $I->dontSee('Password cannot be blank.', '.help-block');
		$I->dontSee('Team cannot be blank.', '.help-block');
        $I->dontSee('Email Address is not a valid email address.', '.help-block');

        $I->amGoingTo('submit signup form with team name that is already in use');
        $signupPage->submit([
            'username' => 'new_test_user',
            'email' => 'user@test.com',
            'password' => 'password',
			'team_name' => 'Team Test',
        	'captcha' => $this->captcha,
        ]);

        $I->expectTo('see that  team name has already been taken');
		$I->see('Team name "Team Test" has already been taken.', '.help-block');
        $I->dontSee('Username "test_user" has already been taken.', '.help-block');
        $I->dontSee('Password cannot be blank.', '.help-block');
        $I->dontSee('Email Address is not a valid email address.', '.help-block');

        $I->amGoingTo('submit signup form with correct email');
        $signupPage->submit([
            'username' => 'test_user2',
            'email' => 'user2@test.com',
            'password' => 'password',
			'team_name' => 'Team Test 2',
        	'captcha' => $this->captcha,
        ]);

        $I->expectTo('see that user is created');
        $I->seeRecord('app\models\User', [
            'username' => 'test_user2',
            'email' => 'user2@test.com',
			'team_name' => 'Team Test 2',
        ]);

        $I->expectTo('see that user logged in');
        $I->seeLink('Logout (test_user2)');

        $I->amGoingTo('logout newly created user');
        $I->sendAjaxPostRequest('/site/logout');

        $I->expectTo('see that user is logged out');
        $I->seeInCurrentUrl('/site/logout');
        //$I->see('Login', 'li');
    }
}
