<?php

namespace Webdev\Filmforge;
require_once(__DIR__.'/../config.php');
require_once (__DIR__.'/../vendor/autoload.php');

class UserModel
{
    private GenericQuery $genericQuery;
    public function __construct($conn)
    {
        $this->genericQuery = $conn;
    }

    public function addUser($user)
    {
        error_log(json_encode($user));
        $hashed = hash('sha256', $user['password']);
        $query = "INSERT INTO users(username, hashedpassword) VALUES('$user[username]','$hashed')";
        $rez = $this->genericQuery->insertAndProvideId($query);
        return $rez;
    }

    public function loginUser($user)
    {
        $query = "SELECT id, hashedpassword FROM users WHERE username='$user[username]' LIMIT 1";
        $rez = $this->genericQuery->fetch($query);
        if (sizeof($rez)===0) return null;
        $rez = $rez[0];
        error_log(json_encode($rez));
        if (hash('sha256', $user['password']) !== $rez['hashedpassword']) return null;

        return $rez['id'];
    }

}