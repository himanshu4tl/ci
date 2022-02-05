<?php

namespace App\Models;

use CodeIgniter\Model;

use App\Models\User;

class Auth extends Model
{

    protected $table = 'user';
    public $session;

    public function login($data)
    {
        $user = new User();
        $result = $user->getOne(['email' => $data['email']]);
        
        if ($result) {
            if (!$result['status']) {
                return ['status' => 0, 'message' => 'Your account is blocked'];
            }
            if ($result['login_attempt'] >= LOIGN_MAX_ATTEMPT && $result['login_attempt_time'] > (time() - LOIGN_BAN_TIME)) {
                return ['status' => 0, 'message' => 'Max login attempt exceed. Please Try after ' . ceil((LOIGN_BAN_TIME - (time() - $result['login_attempt_time'])) / 60) . ' Minutes'];
            }
            if ($this->checkPassword($data['password'], $result['password'])) {
                if (REGISTER_VERIFY_EMAIL && $result['email_verified'] != '1') {
                    return ['status' => 0, 'message' => 'Please verify your email, <a href="site/resend_verification_email?email=' . $result['email'] . '">Click here</a> to resend verification email'];
                }
                $this->setSession($result);
                //p($this->setSession($result));
                if (isset($data['remember'])) {
                    $this->setCookie($result);
                }
                if ($result['login_attempt']) {
                    $user->update($result['id'], [
                        'login_attempt' => 0
                    ]);
                }
                return ['status' => 1, 'message' => 'Login succcess', 'data' => $result];
            }  else {
                $user->update($result['id'], [
                    'login_attempt' => $result['login_attempt'] + 1,
                    'login_attempt_time' => time()
                ]);
            }
        }
        return ['status' => 0, 'message' => 'Email or password is invalid'];
    }

    public function checkPassword($password, $encryptedPassword)
    {
        return ($encryptedPassword == $this->encryptPassword($password));
    }

    public function encryptPassword($password)
    {
        return sha1($password);
    }

    public function setCookie($data)
    {
        helper('text');
        $auth_token = random_string('alnum', 32);
        $user = new User();
        $user->update($data['id'], ['auth_token' => $auth_token]);
        helper('cookie');
        setCookie(APP_SLUG . '_user_auth_token', $auth_token, time() + (60 * 60 * 24 * 30));
    }

    public function setSession($data)
    {
        //p($data);
        $this->session->set([
            'user_id' => $data['id'],
        ]);
    }

    public function logout()
    {
        $this->removeSession();
    }

    public function removeSession()
    {
        $user = new User();
        $user->update(['id' => $this->id()], ['auth_token' => '']);
        helper('cookie');
        delete_cookie(APP_SLUG . '_user_auth_token');
        $this->session->set([
            'user_id' => '',
        ]);
    }

    public function isGuest()
    {
        $user_id = $this->session->get('user_id');
        return !empty($user_id) ? false : true;
    }

    public function id()
    {
        return $this->session->get('user_id');
    }

    public function identity()
    {
        if (!$this->isGuest()) {
            return (new User())->where(['id' => $this->id()])->first();
        }
    }

    public function genereateAuthToken()
    {
        helper('text');
        return  random_string('alnum', 64);
    }

    public function genereateToken()
    {
        helper('text');
        return  random_string('alnum', 32) . '_' . time();
    }

    public function checkTokenExpired($token)
    {
        $token = explode('_', $token);
        $token = @$token[1];
        return $token < (time() - (10 * 60));
    }
}