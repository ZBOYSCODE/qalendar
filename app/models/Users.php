<?php
namespace Gabs\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Query;


/**
 * Vokuro\Models\Users
 * All the users registered in the application
 */
class Users extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $mustChangePassword;

    /**
     *
     * @var string
     */
    public $profilesId;

    /**
     *
     * @var string
     */
    public $banned;

    /**
     *
     * @var string
     */
    public $suspended;

    /**
     *
     * @var string
     */
    public $active;

    public $puntos;

    /**
     *
     * @var integer
     */
    public $rol_id;

    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        if (empty($this->password)) {

            // Generate a plain temporary password
            $tempPassword = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes(12)));

            // The user must change its password in first login
            $this->mustChangePassword = 'Y';

            // Use this password as default
            $this->password = $this->getDI()
                ->getSecurity()
                ->hash($tempPassword);
        } else {
            // The user must not change its password in first login
            $this->mustChangePassword = 'N';
        }

        // The account must be confirmed via e-mail
        $this->active = 'N';

        // The account is not suspended by default
        $this->suspended = 'N';

        // The account is not banned by default
        $this->banned = 'N';
    }

    /**
     * Send a confirmation e-mail to the user if the account is not active
     */
    public function afterSave()
    {
        if ($this->active == 'N') {

            $emailConfirmation = new EmailConfirmations();

            $emailConfirmation->usersId = $this->id;

            if ($emailConfirmation->save()) {
                $this->getDI()
                    ->getFlash()
                    ->notice('A confirmation mail has been sent to ' . $this->email);
            }
        }
    }

    /**
     * Validate that emails are unique across users
     */
    public function validation()
    {
        $this->validate(new Uniqueness(array(
            "field" => "email",
            "message" => "The email is already registered"
        )));

        return $this->validationHasFailed() != true;
    }

    public function initialize()
    {
		
        $this->belongsTo('rol_id', __NAMESPACE__ . '\Roles', 'rol_id', array(
            'alias' => 'rol',
            'reusable' => true
        ));
		

        $this->hasMany('id', __NAMESPACE__ . '\SuccessLogins', 'usersId', array(
            'alias' => 'successLogins',
            'foreignKey' => array(
                'message' => 'User cannot be deleted because he/she has activity in the system'
            )
        ));

        $this->hasMany('id', __NAMESPACE__ . '\PasswordChanges', 'usersId', array(
            'alias' => 'passwordChanges',
            'foreignKey' => array(
                'message' => 'User cannot be deleted because he/she has activity in the system'
            )
        ));

        $this->hasMany('id', __NAMESPACE__ . '\ResetPasswords', 'usersId', array(
            'alias' => 'resetPasswords',
            'foreignKey' => array(
                'message' => 'User cannot be deleted because he/she has activity in the system'
            )
        ));

        $this->hasMany(
            "id",
            __NAMESPACE__ ."\Disponible",
            "dspn_id",
            array(
            'alias' => 'Disponible'));

        $this->hasMany(
            "id",
            __NAMESPACE__ ."\UserActividad",
            "user_id",
            array(
            'alias' => 'UserActividad'));


    }

    public function getSource()
    {
        return 'users';
    }

    public function getUsers($id)
    {
        $query = new Query("SELECT * FROM  Gabs\Models\Users where user_id = ".$id, $this->getDI() );
        return $query->execute();
    }

    public function getGrupos($parameters = null)
    {
        return $this->getRelated('Grupo', $parameters);
    }    
}
