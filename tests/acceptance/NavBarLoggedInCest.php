<?php namespace App\Tests;
use App\Tests\AcceptanceTester;
use Codeception\Example;

class NavBarLoggedInCest
{
    /**
     * @example(url="/account", text="Account")
     * @example(url="/users", text="Users")
     * @example(url="/books", text="Books")
     * @example(url="/logout", text="Logout")

    public function AdminRoleNavBarLink(AcceptanceTester $I, Example $example)
    {
        $I->
        $I->amOnPage('/');
        $I->click($example['url']);
        $I->amOnPage($example['url']);
        $I->see($example['text']);
    } */
}
