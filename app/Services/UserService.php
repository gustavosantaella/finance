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
            if ($user) {
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
    public function login(string $email, string $password)
    {
        try {
            $user = $this->findByEmail($email);
            $passwordHashed = $user->password;
            if (!Hash::check($password, $passwordHashed)) {
                throw new Exception('Invalid credentials');
            }
            $token = JWTAuth::attempt(['email' => $email, 'password' => $password]);

            $userId = $user->id;
            return compact('token', 'user', 'userId');
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function deleteAccount()
    {
        try {
            $user = auth()->user();
            $pk = $user->_id;
            $this->userRepo->deleteByPk($pk);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updateInfo($payload)
    {
        try {
            $userPk = auth()->user()->_id;
            $toUpdate = [];
            if (array_key_exists('password', $payload)) {
                $toUpdate['password'] = Hash::make($payload['password']);
            }

            if (array_key_exists('email', $payload)) {
                $toUpdate['email'] = $payload['email'];
            }

            $this->userRepo->update($userPk, $toUpdate);

            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updatePassword($userPk, $password)
    {
        try {
            $this->userRepo->update($userPk, [
                "password" => Hash::make($password)
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function getInfo()
    {
        try {
            $id = auth()->user()->_id;
            $user = $this->userRepo->find($id);
            if (!$user) {
                throw new Exception('User Not found');
            }
            return $user;
        } catch (Exception $e) {
            throw $e;
        }
    }


    public function logout()
    {
        try {
            Log::write(auth()->user());
            if (auth()->user()) {
                auth()->logout();
                Log::write("User logout");
                Log::write(auth()->user());
            } else {
                Log::write("User not found");
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}
