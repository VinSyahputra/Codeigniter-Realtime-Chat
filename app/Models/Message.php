<?php

namespace App\Models;

use CodeIgniter\Model;

class Message extends Model
{
    protected $table            = 'tb_message';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'sender_id',
        'receiver_id',
        'message',
        'created_at',
    ];


    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
}
