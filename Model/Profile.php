<?php

namespace UAM\Bundle\UserBundle\Model;

use FOS\UserBundle\Model\UserInterface;

interface Profile
{
    public function getUser();

    public function setUser(UserInterface $user);

    public function getGender();

    public function getSurname();

    public function getGivenNames();

    public function getEmail();

    public function getCreatedAt($format = null);

    public function setCreatedAt($datetime);

    public function init(array $options);
}
