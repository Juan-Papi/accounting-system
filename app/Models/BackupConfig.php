<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupConfig extends Model
{
    use HasFactory;
    protected $fillable = ['frequency', 'time', 'enabled'];

    public static function getSettings()
    {
        return self::firstOrCreate([], [
            'time' => '00:00',
            'enabled' => false
        ]);
    }
}
