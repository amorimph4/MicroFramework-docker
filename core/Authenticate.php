<?php

namespace Core;

use App\Models\User;

trait Authenticate
{
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = Container::getModel("User");
    }

    public function login()
    {
        $this->setPageTitle('Login');
        return $this->renderView('user/login', 'layout');
    }

    public function auth($request)
    {
        $result = $this->user->findBy(['email' => $request->post->email]);

        if($result && password_verify($request->post->password, $result->password)){
            $user = [
                'id' => $result->id,
                'name' => $result->name,
                'email' => $result->email
            ];
            Session::set('user', $user);
            return Redirect::route('/');
        }

        return Redirect::route('/login', [
            'errors' => ['Usuário ou senha estão incorretos'],
            'inputs' => ['email' => $request->post->email]
        ]);
    }

    public function logout()
    {
        Session::destroy('user');
        return Redirect::route('/login');
    }
}