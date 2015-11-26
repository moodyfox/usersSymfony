<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 24/11/15
 * Time: 15:51
 */

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Session;

class userService
{
    private $user;
    private $password;

    public function newUser($user, $password){
        $this->user = $user;
        $this->password = $password;
    }
}