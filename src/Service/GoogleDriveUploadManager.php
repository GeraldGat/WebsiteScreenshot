<?php

namespace App\Service;

use \Google_Client;
use \Google_Service_Drive;
use \Google_Service_Drive_DriveFile;
use \Google_Exception;

class GoogleDriveUploadManager
{
    private $client;
    private $file;
    private $fileContent;

    public function setClient(Google_Client $client) {
        $this->client = $client;
    }

    public function getClient() {
        return $this->client;
    }

    public function setFile(Google_Service_Drive_DriveFile $file) {
        $this->file = $file;
    }

    public function getFile() {
        return $this->file;
    }

    //Prepare file to be upload
    public function prepareFile(
        string $fileFullName,
        string $fileMimeType,  
        $parents = null
    ) {
        $file = new Google_Service_Drive_DriveFile();
        $file->setName($fileFullName);
        $file->setMimeType($fileMimeType);
        $file->setParents($parents);

        $this->file = $file;

        return $file;
    }

    //Upload file
    public function upload(string $fileContent) {
        if($this->client != null) {
            $service = new Google_Service_Drive($this->client);
            
            try {
                $service->files->create($this->file, array(
                    'data' => $fileContent,
                    'mimeType' => $this->file->getMimeType(),
                    'uploadType' => 'multipart'
                ));

                return true;
            } catch(Google_Exception $e) {
                return [false, $e->getMessage()];
            }
        } else {
            throw new Exception("No user given");
        }
    }
}