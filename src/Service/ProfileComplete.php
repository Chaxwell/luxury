<?php

namespace App\Service;

use Symfony\Component\Security\Core\User\UserInterface;

class ProfileComplete
{
    public function __construct(UserInterface $candidate)
    {
        $this->candidate = $candidate;
    }

    public function getResult()
    {
        // REFACTOR: Je me sens sale.
        $isProfileComplete = $this->candidate->getEmail() ? true : false;
        $isProfileComplete = $this->candidate->getPassword() ? true : false;
        $isProfileComplete = $this->candidate->getGender() ? true : false;
        $isProfileComplete = $this->candidate->getFirstName() ? true : false;
        $isProfileComplete = $this->candidate->getLastName() ? true : false;
        $isProfileComplete = $this->candidate->getPhoneNumber() ? true : false;
        $isProfileComplete = $this->candidate->getProfilePicture() ? true : false;
        $isProfileComplete = $this->candidate->getPassport() ? true : false;
        $isProfileComplete = $this->candidate->getResume() ? true : false;
        $isProfileComplete = $this->candidate->getCurrentLocation() ? true : false;
        $isProfileComplete = $this->candidate->getAddress() ? true : false;
        $isProfileComplete = $this->candidate->getCountry() ? true : false;
        $isProfileComplete = $this->candidate->getNationality() ? true : false;
        $isProfileComplete = $this->candidate->getBirthPlace() ? true : false;
        $isProfileComplete = $this->candidate->getExperience() ? true : false;
        $isProfileComplete = $this->candidate->getDescription() ? true : false;
        $isProfileComplete = $this->candidate->getJobCategory() ? true : false;
    }
}
