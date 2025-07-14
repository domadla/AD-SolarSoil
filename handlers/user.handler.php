<?php
/**
 * User Handler - Demo data for users
 */
class UserHandler
{
    public static function getAllUsers()
    {
        return [
            [
                "id" => 1,
                "firstname" => "Ian",
                "lastname" => "Ramirez",
                "username" => "ian.ramirez",
                "usertype" => "Admin"
            ],
            [
                "id" => 2,
                "firstname" => "kalbo",
                "lastname" => "calleja",
                "username" => "baldy.nigga",
                "usertype" => "User"
            ],
            [
                "id" => 3,
                "firstname" => "roldan",
                "lastname" => "edwardo",
                "username" => "tai.jin",
                "usertype" => "User"
            ],
        ];
    }
}
