<?php

namespace App\Classe;

use App\Entity\Category;

class Search
{
    //les propriétes de recherche qui sera effectue par les utilisateurs sous forme d'objet 
    /**
    * @var String
    */
    public $string='';
    /**
    * @var Category[]
    */
    public $categories = [];
}