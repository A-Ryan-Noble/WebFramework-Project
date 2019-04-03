<?php

namespace App\Tests;
use App\Tests\AcceptanceTester;
use Codeception\Example;

class UserAdminLoginCest
{
    /**
     * @example { "username": "user" ,"password":"user"}
     * @example { "username": "user" ,"password":"user"}
     */
    public function staticLoginPages(AcceptanceTester $I, Example $example)
    {
        $I->amOnPage('/login');

        $I->expectTo("Login as ". $example['username']);

        $I->fillField('username',$example['username']);
        $I->fillField('password',$example['password']);
        $I->click('Login');
    }
}