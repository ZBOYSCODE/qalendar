<?php
namespace Gabs\Models;
use Phalcon\Mvc\Model;

class BaseModel extends Model
{
   public $db;

   public function initialize()
   {
      $this->db=$this->getDi()->getShared('db');
   }   
} 