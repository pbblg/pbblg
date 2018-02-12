<?php

namespace App\ViewHelper;

use Zend\View\Helper\AbstractHelper;

class IsAuthorizedHelper extends AbstractHelper
{
    /**
     * @var array|null
     */
    private $authorizedUser;

    public function __invoke()
    {
        if ($this->authorizedUser) {
            return true;
        }

        return false;
    }

    public function setAuthorizedUser(array $user)
    {
        $this->authorizedUser = $user;
    }
}