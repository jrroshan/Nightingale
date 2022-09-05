<?php

namespace App\Models\Artist;

use App\Models\Song\Song;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function albums(){
        return $this->hasMany(Artist::class);
    }

    public function songs(){
       return $this->hasMany(Song::class);
    }
}
