<?php

namespace App\Services;

use App\Helpers\Log;
use App\Repositories\PasswordResetRepository;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Lcobucci\JWT\Encoder;
use PharIo\Manifest\Email;
use Tymon\JWTAuth\Claims\JwtId;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTProvider;

class AuthService extends Service{

    public function __construct(
        private UserService $userService,
        private EmailService $emailService,
        private PasswordResetRepository $passwordResetRepository
    ){

    }
    public function forgotPassword(string $email){
        try{

            $arrayMail = explode('@', $email);
            if(str_contains($arrayMail[1],  'test')){
                throw new Exception("This email is just to test");
            }
            $user = $this->userService->findByEmail($email);

            $data = $this->passwordResetRepository->findByEmail($email);
            if($data){
                $this->passwordResetRepository->removeByEmail($email);
            }
            $code = random_int(100000, 999999);

            $this->passwordResetRepository->new($user->email, $code);
            $this->emailService->forgotPassword($user, [
                "code" => $code
            ] );
            return $code;
        }catch(Exception $e){
            throw $e;
        }
    }


    public function resetPassword($password, $emailEncrypted){
        try{
            $email = Crypt::decrypt($emailEncrypted);
            $user = $this->userService->findByEmail($email);

            $this->userService->updatePassword($user->id, $password);

            return true;
        }catch(Exception $e){
            throw $e;
        }
    }

    public function validateCode($code){
        try{
            Log::write(1);
            $data = $this->passwordResetRepository->findByCode($code);
            Log::write(2);
            if(!$data){
                Log::write(3);
                throw new Exception('Code not exists');
            }

            Log::write(4);
            if($data->expired_at < now()->timestamp){
                Log::write(8);
                throw neW Exception("The code is already expired");
            }
            $data->delete();
            return Crypt::encrypt($data->email);
        }catch(Exception $e){
            Log::write("error here");
            Log::write($e->getMessage());
            throw $e;
        }
    }
}
