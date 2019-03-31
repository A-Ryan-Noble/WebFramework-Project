<?php namespace App\Tests;
use App\Tests\AcceptanceTester;

class HomePageCest
{
    public function homePageContent(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Welcome to the Home page');
    }

    public function homePageLinkToBooksPage(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->click('Books');
        $I->seeInCurrentUrl('/books');
        $I->see('Available books');
    }

    public function homePageToLoginPage(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->click('Login');
        $I->seeInCurrentUrl('/login');
    }

    public function LoginPageToAccountPage(AcceptanceTester $I)
    {
        $this->homePageToLoginPage($I);

        $I->see('Username');
        $I->fillField('#inputUsername', 'user');
        $I->see('Password');
        $I->fillField('#inputPassword', 'user');
        $I->click('Login');
        $I->amOnPage('/account');
    }
}