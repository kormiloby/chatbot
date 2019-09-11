<?php
namespace App\Services;

class AmmoCrmService
{
    public static function checkUserId($userId) {
        $jsonString = file_get_contents(base_path('storage/crm_id.txt'));
        $data = json_decode($jsonString, true);

        foreach ($data as $id) {
            if ($id == $userId) {
                return true;
            }
        }

        return false;
    }
}
