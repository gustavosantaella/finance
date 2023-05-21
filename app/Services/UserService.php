<?php

namespace App\Services;

use App\Helpers\Log;
use App\Repositories\UserRepository;
use App\Services\Service;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserService extends Service
{
    public function __construct(
        private UserRepository $userRepo,
        private CountryService $countryService
    ) {
    }
    public function findByEmail(String $email): Object
    {
        try {
            $user = $this->userRepo->getByEmail($email);
            if (!$user) {
                throw new Exception("Email not found");
            }

            return $user;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function newUser(String $email, String $password, String $country)
    {
        try {
            $this->findByEmail($email);
            return null;
        } catch (Exception $e) {
            try {
                $passwordHasshed = Hash::make($password);
                $country = $this->countryService->getByNames($country);

                $user = $this->userRepo->create($email, $passwordHasshed, ['customer']);

                return $user;
            } catch (Exception $e) {
                throw $e;
            }
        }
    }
}
