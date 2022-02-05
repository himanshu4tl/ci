<?php

namespace App\Controllers;

use App\Models\User;

class SiteController extends BaseController
{

    public function dashboard()
    {
        if ($this->auth->isGuest()) {
            return $this->response->redirect(base_url(LOGIN_URL));
        }
        $this->data['title'] = 'Dashboard';
        return $this->render->view('site/dashboard', $this->data);
    }

    function logout()
    {
        $this->auth->logout();
        return $this->response->redirect(base_url('/site/login'));
    }

    public function login()
    {
        if (!$this->auth->isGuest()) {
            return $this->response->redirect(base_url(DASHBOARD_URL));
        }
        if ($this->request->getMethod() == 'post') {
            
            $this->validation->setRule('email', 'Email', 'required|valid_email');
            $this->validation->setRule('password', 'Password', 'required');
            if ($this->validation->withRequest($this->request)->run()) {
                $result = $this->auth->login($this->request->getPost());
                if ($result['status'] == 1) {
                    $this->auth->session->setFlashData('success', 'Welcome ' . $result['data']['name'] . '!');
                    return $this->response->redirect(base_url(DASHBOARD_URL));
                } else {
                    $this->auth->session->setFlashData('error', $result['message']);
                }
            } else {
                $this->auth->session->setFlashData('error', getFirstError($this->validation->getErrors()));
            }
        } else {
            //check remember me
            helper('cookie');
            $authToken = get_cookie(APP_SLUG . '_auth_token');
            if ($authToken) {
                $userData = (new User())->getOne(['auth_token' => $authToken]);
                if (!empty($userData)) {
                    $this->auth->setSession($userData);
                    return $this->response->redirect(base_url(DASHBOARD_URL));
                }
            }
        }
        $this->render->layout = 'blank';
        return $this->render->view('site/login', ['title' => 'Login']);
    }

    public function register()
    {
        if (!$this->auth->isGuest()) {
            return $this->response->redirect(base_url(DASHBOARD_URL));
        }

        if ($this->request->getMethod() == 'post') {
            //p($_POST);
            $this->validation->setRule('name', 'Name', 'required');
            $this->validation->setRule('email', 'Email', 'required|valid_email|is_unique[user.email]');
            $this->validation->setRule('password', 'Password', 'required');
            $this->validation->setRule('password_confirm', 'Confirm password', 'required|matches[password]');
            if ($this->validation->withRequest($this->request)->run()) {
                $userData = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'password' => $this->auth->encryptPassword($this->request->getPost('password')),
                    'auth_token' => $this->auth->genereateAuthToken()
                ];
                $user = new User();
                if (REGISTER_VERIFY_EMAIL) {
                    $userData['email_verified'] = $this->auth->genereateToken();
                }
                $user->insert($userData);
                if (REGISTER_VERIFY_EMAIL) {
                    $this->render->layout = 'email_main';
                    $message = $this->render->view('email/register_verify_email', ['userData' => $userData]);
                    sendMail($userData['email'], 'Verify Email | ' . APP_NAME, $message);
                    $this->auth->session->setFlashData('success', 'Thankyou for registere. Please check your email for email verification. <a href="site/resend_verification_email?email=' . $userData['email'] . '">Click here</a> to resend verification email');
                } else {
                    $this->render->layout = 'email_main';
                    $message = $this->render->view('email/register_welcome', ['userData' => $userData]);
                    sendMail($userData['email'], 'Welcome to ' . APP_NAME, $message);
                    $this->auth->session->setFlashData('success', 'You are registered successfuly!');
                }
                return $this->response->redirect(base_url(LOGIN_URL));
            } else {
                $this->auth->session->setFlashData('error', getFirstError($this->validation->getErrors()));
            }
        }
        $this->render->layout = 'blank';
        return $this->render->view('site/register', ['title' => 'Register']);
    }

    public function resend_verification_email()
    {
        $user = new User();
        $email = $this->request->getGet('email');
        $userData = $user->getOne(['email' => $email, 'status' => 1]);
        if ($userData) {
            $userData['email_verified'] = $this->auth->genereateToken();
            $user->update($userData['id'], [
                'email_verified' => $userData['email_verified'],
            ]);
            $this->render->layout = 'email_main';
            $message = $this->render->view('email/register_verify_email', ['userData' => $userData]);
            sendMail($userData['email'], 'Verify Email | ' . APP_NAME, $message);
            $this->auth->session->setFlashData('success', 'Verification mail sent successfully. <a href="site/resend_verification_email?email=' . $userData['email'] . '">Click here</a> to resend verification email');
        } else {
            $this->auth->session->setFlashData('error', 'Email not valid');
        }
        return $this->response->redirect(base_url(LOGIN_URL));
    }

    public function verify_email()
    {
        $user = new User();
        $verificationToken = $this->request->uri->getSegment(3);
        $userData = $user->getOne(['email_verified' => $verificationToken]);
        if ($userData) {
            if (!$this->auth->checkTokenExpired($verificationToken)) {
                $user->update($userData['id'], [
                    'email_verified' => '1',
                ]);
                $this->auth->session->setFlashData('success', 'Email verified successfully');
            } else {
                $this->auth->session->setFlashData('error', 'Token is expired');
            }
        } else {
            $this->auth->session->setFlashData('error', 'Token not valid');
        }
        return $this->response->redirect(base_url(LOGIN_URL));
    }

    public function password_forgot()
    {
        if (!$this->auth->isGuest()) {
            return $this->response->redirect(base_url(DASHBOARD_URL));
        }
        if ($this->request->getMethod() == 'post') {

            $this->validation->setRule('email', 'Email', 'required|valid_email');
            if ($this->validation->withRequest($this->request)->run()) {
                $user = new User();
                $userData = $user->getOne(['email' => $this->request->getPost('email'), 'status' => 1]);
                if ($userData) {
                    $password_reset_token = $this->auth->genereateToken();
                    $user->update($userData['id'], [
                        'password_reset_token' => $password_reset_token,
                    ]);
                    $userData['password_reset_token'] = $password_reset_token;
                    $this->render->layout = 'email_main';
                    $message = $this->render->view('email/password_forgot', ['userData' => $userData]);
                    sendMail($userData['email'], 'Reset Password | ' . APP_NAME, $message);
                    $this->auth->session->setFlashData('success', 'Check your email for set new password');
                    return $this->response->redirect(base_url('site/password_forgot'));
                } else {
                    $this->auth->session->setFlashData('error', 'Email not found');
                }
            } else {
                $this->auth->session->setFlashData('error', getFirstError($this->validation->getErrors()));
            }
        }
        $this->render->layout = 'blank';
        $this->data['title'] = 'Forgot Password';
        return $this->render->view('site/password_forgot', $this->data);
    }

    public function password_reset()
    {
        $user = new User();
        if ($this->request->getMethod() == 'post') {

            $this->validation->setRule('password', 'password', 'trim|required');
            $this->validation->setRule('password_confirm', 'Confirm password', 'required|matches[password]');

            if ($this->validation->withRequest($this->request)->run()) {
                $userData = $user->getOne(['password_reset_token' => $this->request->getPost('token'), 'status' => 1]);
                if ($userData) {
                    $user->update($userData['id'], [
                        'password' => $this->auth->encryptPassword($this->request->getPost('password')),
                        'password_reset_token' => '',
                    ]);
                    $this->auth->session->setFlashData('success', 'Password successfully changed');
                    return $this->response->redirect(base_url(LOGIN_URL));
                } else {
                    $this->auth->session->setFlashData('error', 'Token not found');
                }
            } else {
                $this->auth->session->setFlashData('error', getFirstError($this->validation->getErrors()));
            }
        }

        $this->data['password_reset_token'] = $this->request->uri->getSegment(3);
        $userData = $user->getOne(['password_reset_token' => $this->data['password_reset_token'], 'status' => 1]);
        if ($userData) {
            if ($this->auth->checkTokenExpired($this->data['password_reset_token'])) {
                $this->auth->session->setFlashData('error', 'Token is expired');
                return $this->response->redirect(base_url('site/password_forgot'));
            }
        } else {
            $this->auth->session->setFlashData('error', 'Token not found');
            return $this->response->redirect(base_url('site/password_forgot'));
        }
        $this->render->layout = 'blank';
        $this->data['title'] = 'Reset Password';
        return $this->render->view('site/password_reset', ['title' => 'Reset Password', 'password_reset_token' => $this->data['password_reset_token']]);
    }


    public function profile()
    {
        if ($this->auth->isGuest()) {
            return $this->response->redirect(base_url(ADMIN_LOGIN_URL));
        }
        $this->data['data'] = $this->auth->identity();
        if ($this->request->getMethod() == 'post') {
            
            $this->validation->setRule('name', ' Name', 'trim|required');
            if ($this->data['data'] != $this->request->getPost('email')) {
                $this->validation->setRule('email', 'Email', 'trim|required[user.email]');
            }
            if ($this->validation->withRequest($this->request)->run()) {
                $userData = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                ];
                $user = new User();
                $user->update($this->auth->id(), $userData);
                $this->auth->session->setFlashData('success', 'Profile updated successfully.');
                return $this->response->redirect(base_url( 'site/profile'));

            } else {
                $this->auth->session->setFlashData('error', getFirstError($this->validation->getErrors()));
            }
        }
        $this->data['title'] = 'Profile';
        return $this->render->view('site/profile', $this->data);
    }


    // public function change_avtar()
    // {

    //     if ($this->request->getMethod() == 'post') {
    //         $result = fileUploadByBase64($this->request->getPost('imageBase64'), 'profile/');
    //         if ($result['status']) {
    //             $userData = $this->auth->identity();
    //             $user = new User();
    //             $user->update(['user_id' => $userData['id']], ['image' => $result['fileName']]);
    //             deleteFile($userData['image']);
    //             $this->auth->session->setFlashData('success', 'Profile updated successfully');
    //             //jsonResponse(['status' => 1, 'message' => 'Profile updated successfully']);
    //         } else {
    //             //jsonResponse(['status' => 0, 'message' => $result['message']]);

    //         }
    //     }
    // }

    public function password_change()
    {
        if ($this->auth->isGuest()) {
            return $this->response->redirect(base_url(LOGIN_URL));
        }
        $this->data['data'] = $this->auth->identity();
        if ($this->request->getMethod() == 'post') {

            $this->validation->setRule('current_password', 'Current password', 'required');
            $this->validation->setRule('password', 'Password', 'trim|required');
            $this->validation->setRule('confirm_password', 'Confirm password', 'required|matches[password]');
            if ($this->validation->withRequest($this->request)->run()) {
                $userdata = $this->auth->identity();
                $current_password = $this->auth->encryptPassword($this->request->getPost('current_password'));
                if ($current_password == $userdata['password']) {
                    $user = new User();
                    $user->update($userdata['id'], [
                        'password' => $this->auth->encryptPassword($this->request->getPost('password')),
                    ]);
                    $this->auth->session->setFlashData('success', 'password change successfully.');
                } else {
                    $this->auth->session->setFlashData('error', 'Current password is wrong');
                }
            } else {
                $this->auth->session->setFlashData('error', getFirstError($this->validation->getErrors()));
            }
        }

        $this->data['title'] = 'Change Password';
        return $this->render->view('site/password_change', $this->data);
    }
}
