<?php

namespace App\Models;

use CodeIgniter\Model;

class CounterModel extends Model
{
    protected $table = 'counters';
    protected $allowedFields = ['count'];
    protected $useTimestamps = false;
}
