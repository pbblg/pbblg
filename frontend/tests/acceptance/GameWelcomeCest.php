<?php


class GameWelcomeCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToEnterAndExitToGameWelcome(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Enter your name');
        $I->fillField('.game-enter-input-name', 'John');
        $I->click('.game-enter-submit-button');

        $I->waitForElement('.game-welcome');
        $I->see('You are: John', '.game-welcome-menu-player-name');
        $I->seeElement('.game-welcome-menu-exit-button');

        $I->click('.game-welcome-menu-exit-button');
        $I->waitForElement('.game-enter');
    }
}
