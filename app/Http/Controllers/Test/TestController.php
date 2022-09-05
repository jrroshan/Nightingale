<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Album\Album;
use App\Models\Artist\Artist;
use App\Models\Song\Song;
use Carbon\Carbon;
use getID3;

class TestController extends Controller
{
    public function __construct(protected getID3 $getid3)
    {
    }

    public function test()
    {
//        return Artist::whereNot('name', 'Unknown Artist')->whereNot('name', 'Various Artists')->whereDoesntHave('songs')->delete();
    }

    public function index()
    {
        $files = $this->filesDetector(public_path('uploads'));
        $results = Song::all('path');
        $db = [];
        foreach ($results as $result) {
            array_push($db, $result->path);
        }
        foreach ($files as $file) {
            $parts = pathinfo($file, PATHINFO_EXTENSION);
            if ($parts == 'mp3') {
                if (!in_array($file, $db)) {
                    $data = $this->getid3->analyze($file);
                    foreach ($data['tags'] as $information) {
                        $artist['name'] = $information['artist'][0];
                        $artistData = $this->checkArtist($artist);
                        $album['name'] = $information['album'][0];
                        $album['artist_id'] = $artistData->id;
                        $albumData = $this->checkAlbum($album);
                        $song['title'] = $information['title'][0];
                        $song['length'] = $data['playtime_seconds'];
                        if (empty($information['track_number'][0])) {
                            $song['track'] = $information['track'][0];
                        } else {
                            $song['track'] = $information['track_number'][0];
                        }
                        $song['path'] = $data['filenamepath'];
                        $song['lyrics'] = '';
                        $song['artist_id'] = $artistData['id'];
                        $song['album_id'] = $albumData['id'];
                        $song['mtime'] = Carbon::now()->timestamp;
                        Song::create($song);
                    }
                } else {
                    $notAvailableFile = array_diff($db, $files);
                    foreach ($notAvailableFile as $item) {
                        Song::where('path', $item)->delete();
                    }
                }
            }
        }
    }

    /**
     * Finds path, relative to the given root folder, of all files and directories in the given directory and its sub-directories non recursively.
     * Will return an array of the form
     * array(
     *   'files' => [],
     *   'dirs'  => [],
     * )
     * @param string $root
     * @result array
     * @author sreekumar
     */

    public function filesDetector(string $root = '.')
    {
        if (is_dir(public_path('uploads'))) {
            $files = array('files' => array());
            $directories = array();
            $last_letter = $root[strlen($root) - 1];
            $root = ($last_letter == '\\' || $last_letter == '/') ? $root : $root . DIRECTORY_SEPARATOR;

            $directories[] = $root;

            while (sizeof($directories)) {
                $dir = array_pop($directories);
                if ($handle = opendir($dir)) {
                    while (false !== ($file = readdir($handle))) {
                        if ($file == '.' || $file == '..') {
                            continue;
                        }
                        $file = $dir . $file;
                        if (is_dir($file)) {
                            $directory_path = $file . DIRECTORY_SEPARATOR;
                            array_push($directories, $directory_path);
                            $files['dirs'][] = $directory_path;
                        } elseif (is_file($file)) {
                            $files['files'][] = $file;
                        }
                    }
                    closedir($handle);
                }
            }

            return $files['files'];
        }
    }

    public function checkArtist($name)
    {
        if (empty($name) && !isset($name)) {
            $name = 'Unknown Artist';
        }
        $artist = Artist::whereName($name['name'])->first('id');
        if (empty($artist)) {
            return Artist::create($name);
        } else {
            return $artist;
        }
    }

    public function checkAlbum($data)
    {
        if (empty($data) && !isset($data)) {
            $data = 'Unknown Album';
        }
        $album = Album::whereName($data['name'])->first('id');
        if (empty($album)) {
            return Album::create($data);
        } else {
            return $album;
        }
    }
}
