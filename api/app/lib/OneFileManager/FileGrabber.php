<?php 
namespace OneFileManager;

use OneFileManager\Common;

/**
 * File Grabber
 * Download a file from specified url
 */
class FileGrabber
{
    // OneFileManager\Console instance that this object belongs to
    private $console;

    // OneFileManager\FileManager instance that this object belongs to
    private $manager;

    // File Url
    private $url;

    /**
     * Contructor
     */
    public function __construct(Console $console)
    {
        $this->console = $console;
        $this->manager = $this->console->manager;
    }

    /**
     * Set file URL
     * @param string $url 
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get file URL
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }


    /**
     * Grab file
     * @return \stdClass 
     */
    public function grab()
    {
        $return = new \stdClass;

        if ($this->url) {
            if (preg_match("/https?:\/\/(www\.)?instagram.com\/p\/([a-z0-9\-_]+)/i", $this->url)) {
                // Grab a file from Instagram
                $return = $this->grabFromInstagram();
            } else if(strpos($this->url, "https://www.googleapis.com/drive/") === 0) {
                // Grab a file Google Drive
                $return = $this->grabFromGoogleDrive();
            } else {
                // Grab a file URL
                $return = $this->grabFromUrl();
            }
        } else {
            $return->code = 191;
        }

        return $return;
    }


    /**
     * Grab a file from Instagram
     * @return [type] [description]
     */
    private function grabFromInstagram()
    {
        preg_match("/https?:\/\/(www\.)?instagram.com\/p\/([a-z0-9\-_]+)/i", $this->url, $match);

        if (!class_exists("\DomDocument")) {
            return Common::error("Enable DomDocument to grab a media from Instagram");
        }

        $doc = new \DomDocument();
        @$doc->loadHTML(file_get_contents($match[0]));
        $xpath = new \DOMXPath($doc);
        $query = '//*/meta[starts-with(@property, \'og:\')]';
        $metas = $xpath->query($query);
        
        $meta_tags = array();
        foreach ($metas as $meta) {
            $property = $meta->getAttribute('property');
            $content = $meta->getAttribute('content');
            $meta_tags[$property] = $content;
        }


        if (empty($meta_tags["og:type"])) {
            return Common::error(__("Missing meta data"));
        }


        switch ($meta_tags["og:type"]) {
            case "instapp:photo":
                $this->setUrl($meta_tags["og:image"]);
                break;

            case "video":
                $this->setUrl($meta_tags["og:video"]);
                break;
            
            default:
                return Common::error(__("Invalid meta data"));
                break;
        }

        return $this->grabFromUrl();
    }


    /**
     * Grab a file from Google Drive
     * @return [type] [description]
     */
    private function grabFromGoogleDrive()
    {
        $parts = parse_url($this->url);
        parse_str($parts['query'], $query);
        
        $file_id = isset($query["id"]) ? $query["id"] : false;
        $token = isset($query["token"]) ? $query["token"] : false;
        $ext = isset($query["ext"]) ? strtolower($query["ext"]) : false;
        $filesize = isset($query["size"]) ? $query["size"] : false;

        if (!$file_id || !$token || !$ext || !$filesize) {
            return Common::error("Missing data");
        }

        
        try {
            $this->console->validateFileSize($filesize);
            $this->console->validateFileExt($ext);
        } catch (\Exception $e) {
            return Common::error($e->getMessage());
        }


        // Download file
        $ch = curl_init("https://www.googleapis.com/drive/v3/files/".$file_id."?alt=media");

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer ".$token
        ));

        $data = curl_exec($ch);
        curl_close($ch);

        if (!$data) {
            return Common::error(__("Couldn't get the file"));
        }


        $filename = uniqid(readableRandomString(8)."-").".".$ext;
        $downres = file_put_contents($this->manager->getOption("path") . $filename, $data);

        if (!$downres) {
            return Common::error(__("Couldn't download the file"));
        }

        // Process the media
        $filename = $this->console->processMedia($filename);
        // File type might be changed, 
        // Get file extension again
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));


        $file = $this->console->addDBRecord([
            "filename" => $filename,
            "filesize" => $filesize
        ]);

        if (!$file) {
            unlink($this->manager->getOption("path") . $filename);
            return Common::error("Couldn't save uploaded file data.");
        }

        
        $this->console->outdata->file = [
            "id" => $file->id,
            "title" => $file->title,
            "info" => $file->info,
            "filename" => $file->filename,
            "filesize" => $file->filesize,
            "ext" => $ext,
            "url" => $this->manager->getOption("url") . $file->filename,
            "date" => $file->date,
            "icon" => Common::isVideo($file->filename) ? "mdi mdi-play" : false
        ];

        $this->console->outdata->max_storage_size = $this->manager->getOption("max_storage_size");
        $this->console->outdata->used_storage_size = $this->console->getUsedStorageSize();

        return Common::success($this->console->outdata);
    }


    /**
     * Grab a file from general URL
     * @return [type] [description]
     */
    private function grabFromUrl()
    {
        $headers = @get_headers(urldecode($this->url), 1);

        if (!$headers) {
            return Common::error(__("Couldn't find the image!"));
        }

        if (empty($headers["Content-Type"])) {
            return Common::error(__("Couldn't get file type!"));
        }

        $mime = $headers["Content-Type"];
        if (is_array($mime)) {
            $mime = array_pop($mime);
        }
        $ext = trim(Common::mimeToExt($mime), ".");

        try {
            $this->console->validateFileExt($ext);
        } catch (\Exception $e) {
            return Common::error($e->getMessage());
        }


        $filename = uniqid(readableRandomString(8)."-").".".$ext;
        $downres = file_put_contents($this->manager->getOption("path") . $filename, @file_get_contents($this->url));

        if (!$downres) {
            return Common::error("Couldn't download the file");
        }

        // Check file size
        $filesize = filesize($this->manager->getOption("path") . $filename);
        try {
            $this->console->validateFileSize($filesize);
        } catch (\Exception $e) {
            return Common::error($e->getMessage());
        }


        // Process the media
        $filename = $this->console->processMedia($filename);
        // File type might be changed, 
        // Get file extension again
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));


        $file = $this->console->addDBRecord([
            "title" => basename($this->url),
            "filename" => $filename,
            "filesize" => $filesize
        ]);

        if (!$file) {
            unlink($this->manager->getOption("path") . $filename);
            return Common::error(__("Couldn't save uploaded file data."));
        }

        
        $this->console->outdata->file = [
            "id" => $file->id,
            "title" => $file->title,
            "info" => $file->info,
            "filename" => $file->filename,
            "filesize" => $file->filesize,
            "ext" => $ext,
            "url" => $this->manager->getOption("url") . $file->filename,
            "date" => $file->date,
            "icon" => Common::isVideo($file->filename) ? "mdi mdi-play" : false
        ];

        $this->console->outdata->max_storage_size = $this->manager->getOption("max_storage_size");
        $this->console->outdata->used_storage_size = $this->console->getUsedStorageSize();

        return Common::success($this->console->outdata);
    }
}
