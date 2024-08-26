<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Auth;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    public function index()
    {
        //
    }
    public function login()
    {
        if (session()->get('logged_in') == TRUE) {
            return redirect()->to('/');
        }
        return view('auth/login');
    }
    public function loginDashboard()
    {
        $model = new Auth();
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $data = $model->where('username', $username)->first();
        if ($data) {
            $pass = $data['password'];
            $verify_pass = password_verify($password, $pass);

            if ($verify_pass) {
                session()->set([
                    'id' => $data['id'],
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'logged_in' => TRUE
                ]);
                return redirect()->to('/')->with('success', 'Login success');
            } else {
                return redirect()->to('/login')->with('error', 'Password is wrong');
            }
        } else {
            return redirect()->to('/login')->with('error', 'Username is wrong');
        }
    }
    public function register()
    {
        return view('auth/register');
    }
    public function registerAdd()
    {
        $model = new Auth();
        $data = [
            'id' => uniqid('user_', true),
            'username' => $this->request->getVar('username'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'email' => $this->request->getVar('email')
        ];

        $model->insert($data);
        return redirect()->to('/login')->with('success', 'Register success');
    }
    public function logout()
    {
        session()->destroy();

        return $this->response->setJSON(['status' => true]);
    }
}
