<?php

namespace App\Services;

use App\Helpers\Log;
use App\Repositories\UserRepository;
use App\Services\Service;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService extends Service
{
    public function __construct(
        private UserRepository $userRepo,
        private CountryService $countryService,
        private WalletService $walletService
    ) {
    }
    public function findByEmail(String &$email, bool $ignoreThrow = false)
    {
        try {
            $user = $this->userRepo->getByEmail($email);
            if (!$user && $ignoreThrow == false) {
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
             $user =  $this->findByEmail($email, true);
            if($user){
                throw new Exception("Email already exists");
            }
            $passwordHasshed = Hash::make($password);
            $country = $this->countryService->getByNames($country);

            $user = $this->userRepo->create($email, $passwordHasshed, ['customer']);
            $this->walletService->create($user->id, $country['currency']);

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function login($email, $password){
        try{
            $user = $this->findByEmail($email);
            $passwordHashed = $user->password;
            if(Hash::check($password, $passwordHashed)){
                Log::write($password);
                $token = Auth::guard('api')->attempt(['email' => $email, 'password' => $password]);
                $userId = $user->id;
                return compact('token','user', 'userId');

            }
        }catch(Exception $e){
            throw $e;
        }
    }
}
