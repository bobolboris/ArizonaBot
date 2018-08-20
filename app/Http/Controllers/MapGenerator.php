<?php

namespace App\Http\Controllers;


use App\Parsers\ParserMap;
use App\Facade\Arizona;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class MapGenerator
{
    protected $pictures = [];
    protected $map = null;
    protected $imagesPath = 'storage/map/images/';
    protected $cachePath = 'storage/map/cache/';

    protected function getImagePublicPath($path = null)
    {
        return ($path == null) ? public_path($this->imagesPath) : public_path($this->imagesPath . $path);
    }

    protected function openMap()
    {
        if ($this->map != null) {
            $this->map->destroy();
        }
        $this->map = Image::make(public_path('storage/map/map.jpg'));
    }

    protected function loadPicturesFromResources()
    {
        $this->openMap();
        $files = Storage::disk('map')->files('images');
        foreach ($files as $value) {
            $bName = basename($value);
            $this->pictures[$bName] = Image::make($this->getImagePublicPath($bName));
        }
    }

    protected function checkAndLoadPictures($data)
    {
        foreach ($data as $value) {
            $bName = basename($value[3]);
            if (!isset($this->pictures[$bName])) {
                Arizona::sendRequest($value[3], ['sink' => $this->getImagePublicPath($bName)]);
                $this->pictures[$bName] = Image::make($this->getImagePublicPath($bName));
            }
        }
    }

    public function generate($serverNumber)
    {
        $parser = new ParserMap();
        $data = $parser->parse("/map/${serverNumber}");
        if (count($data) == 0) {
            Log::error('Элементы на карте не найдены');
            return;
        }

        $cache = $this->generateCache($data);
        $cacheOld = $this->loadCache($serverNumber);
        if ($cache == $cacheOld && Storage::exists("public/map/maps/${serverNumber}.jpg")) {
            return;
        }
        $this->writeCache($serverNumber, $cache);
        $this->checkAndLoadPictures($data);
        foreach ($data as $value) {
            $this->map->insert($this->pictures[basename($value[3])], 'top-left', intval($value[2]), intval($value[1]));
        }
        $this->map->save(public_path("storage/map/maps/${serverNumber}.jpg"));
        $this->openMap();
    }

    protected function generateCache($data)
    {
        $text = "";
        foreach ($data as $value) {
            $text .= $value[0] . "\n";
        }
        return $text;
    }

    protected function loadCache($serverNumber)
    {
        $path = 'public/map/cache/' . $serverNumber . '.txt';
        return (Storage::exists($path)) ? Storage::get($path) : null;
    }

    protected function writeCache($serverNumber, $cache)
    {
        Storage::put('public/map/cache/' . $serverNumber . '.txt', $cache);
    }

    public function __construct()
    {
        $this->loadPicturesFromResources();
    }
}