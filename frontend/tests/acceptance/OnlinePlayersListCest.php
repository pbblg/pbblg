<?php

class OnlinePlayersListCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }


    public function tryToLoginAndSeeIamInOnlinePlayersList(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->seeElement('.game-enter');

        $I->fillField('.game-enter-input-login', 'john.silver');
        $I->fillField('.game-enter-input-password', '1q2w3e4r');
        $I->click('.game-enter-submit-button');

        $I->waitForElement('.game-welcome-online-players');
        $I->see('John Silver', '.players-online-list');
    }

    public function tryToSeeOtherPlayerInPlayersList(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->seeElement('.game-enter');

        $I->fillField('.game-enter-input-login', 'john.silver');
        $I->fillField('.game-enter-input-password', '1q2w3e4r');
        $I->click('.game-enter-submit-button');


        $kate = $I->haveFriend('Kate');
        $kate->does(function(AcceptanceTester $I) {
            $I->amOnPage('/');
            $I->seeElement('.game-enter');

            $I->fillField('.game-enter-input-login', 'kate.miller');
            $I->fillField('.game-enter-input-password', '1q2w3e4r');
            $I->click('.game-enter-submit-button');

            $I->waitForElement('.game-welcome-online-players');
            $I->see('John Silver', '.players-online-list');
            $I->see('Kate Miller', '.players-online-list');
        });


        $I->reloadPage();

        $I->see('John Silver', '.players-online-list');
        $I->see('Kate Miller', '.players-online-list');
    }
}
