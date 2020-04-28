<?php


namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;


class FileUploader {

  public function uploadFile( UploadedFile $file){
    $filename = md5(uniqid()).".".$file->guessClientExtension();

    $file->move(
      $this->getParameter("uploads_directory"),
      $filename
    );
  }

}

?>