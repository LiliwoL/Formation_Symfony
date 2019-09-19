<?php

// src/Service/Slugifier.php
namespace App\Service;

class Slugifier
{
    public function slugify( $string )
    {
        $slug = "SLUG+" . $string . "+SLUG";

        return $slug;
    }
}