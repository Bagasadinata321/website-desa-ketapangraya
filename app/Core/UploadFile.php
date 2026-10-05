<?php

namespace App\Core;

class UploadFile
{
    protected $file;

    public function __construct(array $file)
    {
        $this->file = $file;
    }

    public function getName()
    {
        return $this->file['name'];
    }

    public function getTmpPath()
    {
        return $this->file['tmp_name'];
    }

    public function getSize()
    {
        return $this->file['size'];
    }

    public function getError()
    {
        return $this->file['error'];
    }



    public function move($destination)
    {
        return move_uploaded_file($this->file['tmp_name'], $destination);
    }
    public function getExtension()
    {
        return strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
    }
}
