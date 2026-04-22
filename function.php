<?php

function login($data) {
    $email = $data['email'];
    $password = $data['password'];

    if ($email == "admin@gmail.com" && $password == "123") {
        return [
            "id_user" => 1,
            "nama" => "Admin",
            "role" => "admin"
        ];
    }

    if ($email == "user@gmail.com" && $password == "123") {
        return [
            "id_user" => 2,
            "nama" => "User",
            "role" => "user"
        ];
    }

    return false;
}