<?php
namespace Gabs\Models;
use Phalcon\Mvc\Model;

class UserTecnologia extends Model
{

    
    public function initialize()
    {
        
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'user_tecnologia';
    }
}
