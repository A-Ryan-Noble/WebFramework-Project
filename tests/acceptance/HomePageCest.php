<?php

namespace App\Tests;
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
}