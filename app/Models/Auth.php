<?php

namespace App\Models;

use CodeIgniter\Model;

class Auth extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;

    protected $allowedFields    = ['id', 'username', 'password', 'email'];
}
