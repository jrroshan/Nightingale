<?php

namespace App\Models\Song;

use App\Models\Album\Album;
use App\Models\Artist\Artist;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'length',
        'lyrics',
        'path',
        'mtime',
        'artist_id',
        'album_id'
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
