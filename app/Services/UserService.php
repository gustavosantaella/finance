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
    /**
     * Init services and repositories
     *
     * @param UserRepository $userRepo
     * @param CountryService $countryService
     * @param WalletService $walletService
     */
   public function __construct(
        private UserRepository $userRepo,
        private CountryService $countryService,
        private WalletService $walletService
    ) {
    }
    /**
     * Find user by email
     *
     * @param string $email
     * @param boolean $ignoreThrow
     * @return object
     */
    public function findByEmail(string &$email, bool $ignoreThrow = false)
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

    /**
     * Create a new user
     *
     * @param string $email
     * @param string $password
     * @param string $country
     * @return bool
     */
    public function newUser(string $email, string $password, string $country)
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

    /**
     * Authenticate a user by email and password
     *
     * @param string $email
     * @param string $password
     * @return array
     */
    public function login(string $email, string $password){
        try{
            $user = $this->findByEmail($email);
            $passwordHashed = $user->password;
            if(!Hash::check($password, $passwordHashed)){
                throw new Exception('Invalid credentials');

            }
            Log::write($password);
                $token = Auth::guard('api')->attempt(['email' => $email, 'password' => $password]);
                $userId = $user->id;
                return compact('token','user', 'userId');
        }catch(Exception $e){
            throw $e;
        }
    }
}
