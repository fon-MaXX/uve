<?php

namespace Site\BackendBundle\Entity;

class XmlLoad
{

    protected $file;

    public function getFile()
    {
        return $this->file;
    }
    public function setFile($file)
    {
        $this->file = $file;
    }
}
