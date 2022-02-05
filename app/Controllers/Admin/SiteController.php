<?php

namespace App\Controllers\Admin;

use App\Models\Admin\Admin;

class SiteController extends BaseController
{

    public function dashboard()
    {
        if ($this->auth->isGuest()) {
            return $this->response->redirect(base_url(ADMIN_LOGIN_URL));
        }
        $this->data['title'] = 'Dashboard';
        return $this->render->view('site/dashboard', $this->data);
    }

    function logout()
    {
        $this->auth->logout();
        return $this->response->redirect(base_url(ADMIN_DIR . '/site/login'));
    }

    public function login()
    {
        if (!$this->auth->isGuest()) {
            return $this->response->redirect(base_url(ADMIN_DASHBOARD_URL));
        }
        if ($this->request->getMethod() == 'post') {
            
            $this->validation->setRule('email', 'Email', 'required|valid_email');
            $this->validation->setRule('password', 'Password', 'required');
            if ($this->validation->withRequest($this->request)->run()) {
                $result = $this->auth->login($this->request->getPost());
                if ($result['status'] == 1) {
                    $this->auth->session->setFlashData('success', 'Welcome ' . $result['data']['name'] . '!');
                    return $this->response->redirect(base_url(ADMIN_DASHBOARD_URL));
                } else {
                    $this->auth->session->setFlashData('error', $result['message']);
                }
            } else {
                $this->auth->session->setFlashData('error', getFirstError($this->validation->getErrors()));
            }
        } else {
            //check remember me
            helper('cookie');
            $authToken = get_cookie(APP_SLUG . '_admin_auth_token');
            if ($authToken) {
                $result = (new Admin())->getOne(['auth_token' => $authToken]);
                if (!empty($result)) {
                    $this->auth->setSession($result);
                    return $this->response->redirect(base_url(ADMIN_DASHBOARD_URL));
                }
            }
        }
        $this->render->layout = 'blank';
        return $this->render->view('site/login', ['title' => 'Login']);
    }

    public function password_forgot()
    {
        if (!$this->auth->isGuest()) {
            return $this->response->redirect(base_url(ADMIN_DASHBOARD_URL));
        }
        if ($this->request->getMethod() == 'post') {
            
            $this->validation->setRule('email', 'Email', 'required|valid_email');
            if ($this->validation->withRequest($this->request)->run()) {
                $admin = new Admin();
                $adminData = $admin->getOne(['email' => $this->request->getPost('email'), 'status' => 1]);
                if ($adminData) {
                    $password_reset_token =$this->auth->genereateToken();
                    $admin->update($adminData['id'], [
                        'password_reset_token' => $password_reset_token,
                    ]);
                    $adminData['password_reset_token'] = $password_reset_token;
                    $this->render->layout = 'email_main';
                    $message = $this->render->view('email/password_forgot', ['adminData'=>$adminData]);
                    sendMail($adminData['email'], 'Reset Password | '.APP_NAME, $message);
                    $this->auth->session->setFlashData('success', 'Check your email for set new password');
                    return $this->response->redirect(base_url(ADMIN_DIR . 'site/password_forgot'));
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
        $admin = new Admin();
        if ($this->request->getMethod() == 'post') {
            
            $this->validation->setRule('password', 'password', 'trim|required');
            $this->validation->setRule('password_confirm', 'Confirm password', 'required|matches[password]');

            if ($this->validation->withRequest($this->request)->run()) {
                $result = $admin->getOne(['password_reset_token' => $this->request->getPost('token'), 'status' => 1]);
                if ($result) {
                    $admin->update($result['id'], [
                        'password' => $this->auth->encryptPassword($this->request->getPost('password')),
                        'password_reset_token' => '',
                    ]);
                    $this->auth->session->setFlashData('success', 'Password successfully changed');
                    return $this->response->redirect(base_url(ADMIN_LOGIN_URL));
                } else {
                    $this->auth->session->setFlashData('error', 'Token not found');
                }
            } else {
                $this->auth->session->setFlashData('error', getFirstError($this->validation->getErrors()));
            }
        }

        $this->data['password_reset_token'] = $this->request->uri->getSegment(4);
        $result = $admin->getOne(['password_reset_token' => $this->data['password_reset_token'], 'status' => 1]);
        if ($result) {
            if($this->auth->checkPasswordResetTokenExpired($this->data['password_reset_token'])){
                $this->auth->session->setFlashData('error', 'Token is expired');
                return $this->response->redirect(base_url(ADMIN_DIR . 'site/password_forgot'));
            }
        } else {
            $this->auth->session->setFlashData('error', 'Token not found');
            return $this->response->redirect(base_url(ADMIN_DIR . 'site/password_forgot'));
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
                $adminData = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                ];
                $admin = new Admin();
                $admin->update($this->auth->id(), $adminData);
                $this->auth->session->setFlashData('success', 'Profile updated successfully.');
                return $this->response->redirect(base_url(ADMIN_DIR . 'site/profile'));

            } else {
                $this->auth->session->setFlashData('error', getFirstError($this->validation->getErrors()));
            }
        }
        $this->data['title'] = 'Profile';
        return $this->render->view('site/profile', $this->data);
    }

    public function password_change()
    {
        if ($this->auth->isGuest()) {
            return $this->response->redirect(base_url(ADMIN_LOGIN_URL));
        }
        $this->data['data'] = $this->auth->identity();
        if ($this->request->getMethod() == 'post') {
            
            $this->validation->setRule('current_password', 'Current password', 'required');
            $this->validation->setRule('password','Password', 'trim|required');
            $this->validation->setRule('confirm_password', 'Confirm password', 'required|matches[password]');
            if ($this->validation->withRequest($this->request)->run()) {
                $userdata = $this->auth->identity();
                $current_password = $this->auth->encryptPassword($this->request->getPost('current_password'));
                if ($current_password == $userdata['password']) {
                    $admin = new Admin();
                    $admin->update($userdata['id'], [
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
