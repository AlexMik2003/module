<?php


namespace App\Auth;


use App\Models\Profile;
use App\Models\User;
use App\Helpers\Session;

/**
 * Class User for user authorization
 *
 * @package App\User
 */
class Auth
{
    /**
     * Verify user password hashes
     *
     * @param string $login User login
     *
     * @param string $password User password
     *
     * @return bool
     */
    public function attempt($login,$password)
    {
        $user = User::where("login",$login)->first();

        if(!$user){
            return false;
        }

        if(password_verify($password,$user->password))
        {
            Session::set("id", $user->id);
            Session::set("user", $user->login);
            return true;
        }

        return false;
    }

    /**
     * Get user from session
     *
     * @return mixed
     */
    public function check()
    {
        return Session::get("user");
    }

    /**
     * Get user data
     *
     * @return bool
     */
    public function user()
    {
        return Session::get("user")
            ? User::find(Session::get("id"))
            : false;
    }

    /**
     * Get user privilege
     *
     * @return bool
     */
    public function status()
    {
        return Session::get("user")
            ? User::find(Session::get("id"))->privilege
            : false;
    }

}