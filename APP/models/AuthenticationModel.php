<?php
// this authentication model will need access to the usersDAO as it must check users credentials
require_once "DB/DAO/CriticsDAO.php";
require_once "DB/pdoDbManager.php";

class AuthenticationModel 
{
	private $CriticsDAO; // Critics are admins
	private $dbmanager;
	
	public $authStatus;
	
	public function __construct()
	{
		$this->dbmanager = new pdoDbManager ();
		$this->CriticsDAO = new CriticsDAO ( $this->dbmanager );
		$this->dbmanager->openConnection ();
	}
	
	// the only function needed in this model is for authentication
	public function authenticateUser($username, $password){
		if(!empty ($username) && !empty ($password)){
			if($this->CriticsDAO->authenticate($username, $password)) // if the authenticate function in usersdao returns any rows then a users credintials were matched
			{
				return HTTPSTATUS_OK;
			}
			return HTTPSTATUS_FORBIDDEN;
		}
		else 
			return HTTPSTATUS_UNAUTHORIZED;
	}
	
	// same destruct function as users model
	public function __destruct() {
		$this->CriticsDAO = null;
		$this->dbmanager->closeConnection ();
	}
}
?>