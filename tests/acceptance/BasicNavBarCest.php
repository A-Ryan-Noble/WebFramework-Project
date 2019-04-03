<?php namespace App\Tests;
use App\Tests\AcceptanceTester;
use Codeception\Example;

class NavbarCest
{
    /**
     * @example(url="/", text="Home")
     * @example(url="/books", text="Books")
     */
    public function NavBarLink(AcceptanceTester $I, Example $example)
    {
        $I->amOnPage($example['url']);
        $I->see($example['text']);
    }
}
