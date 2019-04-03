<?php namespace App\Tests;
use App\Tests\AcceptanceTester;
use Codeception\Example;

class LoggedInNavLinksCest
{

    /**
     * @example(url="/account", text="Username:")
     * @example(url="/users", text="Password")
     * @example(url="/books", text="Title")
     */
    public function LoggedInNavBarLink(AcceptanceTester $I, Example $example)
    {
        $I->amOnPage($example['url']);
        $I->see($example['text']);
    }

    public function LogoutNavLink(AcceptanceTester $I)
    {
        $I->amOnRoute("app_logout");
        $I->canSee("Please enter your details:");
    }
}