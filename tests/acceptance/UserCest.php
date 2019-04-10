<?php

namespace App\Tests;
use App\Tests\AcceptanceTester;

class UserCest
{
    public function testUsersAreInDatabase(AcceptanceTester $I)
    {
        $I->seeInRepository('App\Entity\User', [
            'username' => 'user'
        ]);

        $I->seeInRepository('App\Entity\User', [
            'username' => 'admin'
        ]);
    }

    public function testingAddingUser(AcceptanceTester $I)
    {
        $I->haveInRepository('App\Entity\User',[
            'username' =>'tempUser',
            'password' =>'pass',
            'roles' => ['ROLE_USER']
        ]);

        $I->seeInRepository('App\Entity\User',[
            'username' => 'tempUser'
        ]);
    }

    public function testingIfTempUserIsNoLongerInDatabase(AcceptanceTester $I)
    {
        $I->dontSeeInRepository('App\Entity\User',[
            'username' => 'tempUser'
        ]);
    }
}