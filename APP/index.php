<?php
require_once "../Slim/Slim.php";
require_once "conf/config.inc.php";
Slim\Slim::registerAutoloader ();
$slimAppInstance = new \Slim\Slim ();

/* 
	How the App Works
Firstly each route the app can take is declared, (for each action it can perform)
Each "route" consists of a URL and at least one http method (GET, POST, etc.)
Codewise, these "routes" are just like declaring functions, and they will run only when $slimAppInstance->run() is called
When $slimAppInstance->run() is called; it will look through the previously declared routes for a route with both a matching URL and http method
If it can't find a route with both matching URL and http method, it will return a http status: "404" and a http body with a message (in html) explaining the page was not found: e.g. "the page your looking for could not be found..."
If it does find a route with both matching URL and http method, it will execute the logic within that route

So the routes listed below do the following if ran:
1. Instanciate the Slim App Class (the engine of the app)
2. Decode the requested View type that was passed as a header
3. Run a function containing the logic that will fulfil the request, Passing the names of the necessary Controller, Model and View required for that request

*/


			/*/ Routes for Search Requests /*/
/*
Search requests should allow users to search for specified books or reviews
It will return either the books details for /serach/books or the book reviews for /search/reviews
No authentication is needed when searching for these; so the runSearchLogic() function does not contain authentication logic
Searches can only use the GET method, however the routes accept all http methods at first and return "405 - Method Not Allowed" if not GET
 // this means the user knows that the problem is with the http method they used
Search routes take a parameter in the URL which is a string to search for within title of the book
If nothing is entered it will return all records
*/

// Search Books
$slimAppInstance->map ( "/books/search/(:searchString)", function ($searchString = null) use($slimAppInstance)
{
	$slimAppInstance = \Slim\Slim::getInstance();
	$viewType = checkViewType($slimAppInstance); // Decode the requested View type (json or xml), through the function checkViewType (at the bottom of this file)
	new runSearchLogic("BookModel", "BookController", $viewType, $searchString); // load the search function with the necessary parameters
} )->via ( "GET", "POST", "PUT", "DELETE", "OPTIONS", "HEAD" );

// Search Reviewss
$slimAppInstance->map ( "/reviews/search/(:searchString)", function ($searchString = null) use($slimAppInstance)
{
	$slimAppInstance = \Slim\Slim::getInstance();
	$viewType = checkViewType($slimAppInstance);
	new runSearchLogic("ReviewModel", "ReviewController", $viewType, $searchString);
	return; // end the script
} )->via ( "GET",  "POST", "PUT", "DELETE", "OPTIONS", "HEAD" );



			/*/ Routes for Admin Requests /*/
// Admin requests allow admins to perform CRUD operations on; books, reviews and other admins
// The request must contain authentication parameters as headers (username, password)
// If these details successfully authenticate; the logic continues to fulfil the request
// If not; the app stops and returns a status: 401 Unauthorized
// An Admin request must also pass a parameter in the URL which is the ID of the record to modify e.g. /admin/books/ID_HERE
// Unless it is adding a record (with POST), in that case no URL parameter is passed and the app enerates a new ID for the new recored being added

// Administrate Critics
$slimAppInstance->map ( "/critics/admin/(:id)", "authenticate", function ($id = null) use($slimAppInstance)
{	
	$slimAppInstance = \Slim\Slim::getInstance();
	$viewType = checkViewType($slimAppInstance);
	new runAdminLogic("CriticModel", "CriticController", $viewType, $id);
	return; // end the script
} )->via ( "GET", "POST", "PUT", "DELETE" , "OPTIONS", "HEAD" );

// Administrate Reviews
$slimAppInstance->map ( "/reviews/admin/(:id)", "authenticate", function ($id = null) use($slimAppInstance)
{
	$slimAppInstance = \Slim\Slim::getInstance();
	$viewType = checkViewType($slimAppInstance);
	new runAdminLogic("ReviewModel", "ReviewController", $viewType, $id);
	return; // end the script
} )->via ( "GET", "POST", "PUT", "DELETE", "OPTIONS", "HEAD"  );

// Administrate Books
$slimAppInstance->map ( "/books/admin/(:id)", "authenticate", function ($id = null) use($slimAppInstance)
{	
	$slimAppInstance = \Slim\Slim::getInstance();
	$viewType = checkViewType($slimAppInstance);
	new runAdminLogic("BookModel", "BookController", $viewType, $id);
	return; // end the script
} )->via ( "GET", "POST", "PUT", "DELETE", "OPTIONS", "HEAD"  );


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						// LOGIC FUNCTIONS - Filter clients request and load MVC Components accordingly //
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*
	// Search Logic Flow //

1. 	Correct URL?
	NO 	-> 	404 Not Found	(e.g. /books/searchxx/the)	
	YES -> 	App Continues	(e.g. /books/search/the)

2.	Was the http method GET?
	NO	-> 	405 Method Not Allowed
	YES	->	App Continues
	
3.	A search sting URL parameter was passed?
	NO 	->  200 OK.	Body: "*All records here*"	(e.g. /books/search/)
	YES	->	At least one record exists containing the search string parameter in its name? (e.g. /books/search/the)
				NO 	-> 	204 No Content
				YES -> 	200 OK.	Body: "*All records with that string here*"
*/

class runSearchLogic
{
	public function __construct($modelName, $controllerName, $viewName, $searchString)
	{
    	$slimAppInstance = \Slim\Slim::getInstance();
		$httpMethod = $slimAppInstance->request->getMethod ();
		$action = null;
		$parameters ["searchString"] = $searchString; 			// note: "$parameters" array is defined here, it is not a builtin variable or anything
		
		if ($httpMethod == "GET"){ 								// can only use a get call to search - contine OR halt the app with a bad request status
			if (is_numeric($searchString))						// numeric not allowed must be string - halt the app with a bad request status
				$slimAppInstance->halt(HTTPSTATUS_NOTACCEPTABLE, GENERAL_INVALIDURLPARAMS);
			else if ($searchString === null)					// search string was not passed - set action for controller to get all
				$action = ACTION_GET_ALL;
			else if (is_string($searchString))					// search string was passed - set action for controller to search
				$action = ACTION_SEARCH;
		}
		else
			$slimAppInstance->halt(HTTPSTATUS_METHODNOTALLOWED);
		
		new loadRunMVCComponents ($modelName, $controllerName, $viewName, $action, $slimAppInstance, $parameters);
		//return;
	}
}

/*
	// Admin Logic Flow //
	
1. 	Correct URL?
	NO 	-> 	404 Not Found	(e.g. /books/adminxx/1)	
	YES -> 	App Continues	(e.g. /books/admin/1)

2. 	Authentication Parameters Passed? 
	NO 	-> 	401 Unauthorized
	YES -> 	App Continues

3. 	Authentication Parameters Valid?
	NO 	-> 	403 Forbidden
	YES -> 	App Continues
	
	* If retriving, updating or deleting a record; an ID must be passed
	
4.	An ID URL parameter was passed?
	NO 	->  Was the http method POST?					// You should not include an ID when using POST (ID's are automatically added by the DB as they must be unique)
				NO	->	405 Method Not Allowed 			(e.g. GET /books/admin/)
				YES	->	Is the Request Body Valid?
							NO	->	400 Bad Request		// The body must contain the details of the new recod to be added (in json)
							YES ->	201 Created			// The record was added
	YES -> 	App Continues 								(e.g. GET /books/admin/1)

5.	The ID is numeric? 	// ID must be numeric
	NO	->	406 Not Acceptable
	YES	->	App Continues
	
6. 	A record exists for the numeric ID parameter?
	NO 	-> 	204 No Content	(e.g. GET /books/admin/6272653)
	YES -> 	App Continues	(e.g. GET /books/admin/1)

7. 	Which HTTP Method was used?
	OPTIONS -> 	405 Method Not Allowed.	// The App does not support this HTTP Method for any request
	HEAD 	-> 	405 Method Not Allowed.	// The App does not support this HTTP Method for any request
	
	GET		-> 	200 OK. Body: "*Book details here*"
	DELETE	->	200 OK. Body: "Resource has been deleted"
	
	POST	->	405 Method Not Allowed or 400 Bad Request (see stage 4 above)
	PUT		->	Is the Request Body Valid?
					YES ->	200 OK. 			Body: "Resource has been updated"
					NO	->	400 Bad Request. 	Body: "Request is ok but transmitted body is invalid"
*/

class runAdminLogic
{
	public function __construct($modelName, $controllerName, $viewName, $id)
	{
    	$slimAppInstance = \Slim\Slim::getInstance();
		$httpMethod = $slimAppInstance->request->getMethod ();
		$action = null;
		$parameters ["id"] = $id;
		
		if ($id === null){ 								// if there was no id parameter passed the client is attempting to add a new record
			if ($httpMethod === "POST")					// if the method was post - set action for controller to create record
					$action = ACTION_CREATE;
			else										// if the method was not POST - halt the app (can only use POST to add a new record)
				$slimAppInstance->halt(HTTPSTATUS_METHODNOTALLOWED);
		}
		
		if ($id != null){ 								// if there was an id parameter passed (in the url)
			if (is_numeric($id)){						// if the id was numeric continue. if not halt the app with not acceptable status
				switch ($httpMethod)
				{
					case "GET" :						// if the method was get - set action for controller to get the record with that id
						$action = ACTION_GET_ONE;
						break;
					case "PUT" :						// if the method was put - set action for controller to update the record with that id
						$action = ACTION_UPDATE;
						break;
					case "DELETE" :						// if the method was delete - set action for controller to delete the record with that id
						$action = ACTION_DELETE;
						break;
					case "POST" :						// client is attempting to POST with an id - halt the app
						$slimAppInstance->halt(HTTPSTATUS_METHODNOTALLOWED, GENERAL_INVALID_HTTPMETHOD);
						break;
					case "OPTIONS" :					// this http method id not allowed
						$slimAppInstance->halt(HTTPSTATUS_METHODNOTALLOWED, GENERAL_INVALID_HTTPMETHOD);
					case "HEAD" :						// this http method id not allowed
						$slimAppInstance->halt(HTTPSTATUS_METHODNOTALLOWED, GENERAL_INVALID_HTTPMETHOD);
					default :
				}
			}
			else
				$slimAppInstance->halt(HTTPSTATUS_NOTACCEPTABLE, GENERAL_INVALIDURLPARAMS);
		}
		
		new loadRunMVCComponents ( $modelName, $controllerName, $viewName, $action, $slimAppInstance, $parameters );
		return;
	}
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					// LOAD MVC COMPONENTS - Load the functionality of the app + return a "view" //
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*
	Now this function will load the components for any Search or Admin requests, using an appropriate Model, Controller and View name
	The components when loaded will generate a state in the model which represents a response to the request
	The state generated will then be wrote back to the client, i.e. the response is returned
	The response format can be decided by the user by passing a custom header with the reqest. e.g. "Content-Type: application/xml"
*/

class loadRunMVCComponents 
{	
	public $model, $controller, $view; 

	// this constructor is where the work is done
	public function __construct($modelName, $controllerName, $viewName, $action, $slimAppInstance, $parameters = null) 
	{
		// The correct Model, Controller, and View instances are needed for the request
		// The Model, Controller and View Classes are contained in .php files
		// So first include the necessary .php files, which contain the necessary classes
		include_once "models/" . $modelName . ".php"; 				// load the model .php file of the passed name string
		include_once "controllers/" . $controllerName . ".php";		// load the controller .php file of the passed name string
		include_once "views/" . $viewName . ".php";					// load the view .php file of the passed name string (can only be json or xml)
		
		// Now instanciate the Model, Controller and View classes from each appropriate model.php file
		// For example if the request was for a book to be returned in xml, the following classes will be instanciated here: 
		// BookModel class from the BookModel.php file
		// BookController class from the BookController.php file
		// xmlView class from the xmlView.php
		
		// *Model:
		// The Model class is responsible for:
		// Opening the db connection
		// Performing CRUD operations on records
		// Holding the "state" of the app (body data, status etc.) (which makes up the response)
		// The Model class is now ready to be used by the Controller class to set the correct state in the Model
		$this->model = new $modelName ();
		
		// The constructer witin the Model class will automatically open up a db connection as soon as it's instansiation here
		
		
		// *Controller:
		// The Controller is responsible for performing actions that update the Model class to a specified state
		// To do this the Controller needs a number of things while instanciating:
		// 1. The Model class instance
		// 2. The Action variable - tells the Controller which action it is required to perform this time
		// 3. The Parameters taken from the URL
		// 4. The slim app instance - the engine of the web app allowing halting, writing responses etc.
		$this->controller = new $controllerName ( $this->model, $action, $slimAppInstance, $parameters );
		
		// The constructer witin the Controller class will automatically run the operations as soon as it's instansiation here
		// The Controller is also responsible for setting the status, depending on what happened
		
		// *View
		// The View simply writes the state of the Model back to the user as a response using slimApp->response->write()
		$this->view = new $viewName ($this->model, $slimAppInstance);
		
		// Call the otuput() function to actually write it, this function is not in the constructer in the View classes so it won't automatically run
		$this->view->output (); 
	}
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
										// AUTHENTICATION //
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*
To authenticate; the app 

This function will set the authentication state, using the loadRunMVCComponents function again to process the logic
This "state" is just one variable ("authStatus") in the Authentication Model
It can only be set to 200: HTTPSTATUS_OK, 403: HTTPSTATUS_FORBIDDEN, or 401: HTTPSTATUS_UNAUTHORIZED

*/

function authenticate(\Slim\Route $route)
{
    $slimAppInstance = \Slim\Slim::getInstance();
	
	// Grab the username and password that were passed as headers
	$parameters["username"] = $slimAppInstance->request->headers->get("username");
	$parameters["password"] = $slimAppInstance->request->headers->get("password");
	
	$action = null; // The authentication Controller only does one thing so no action sting necessary
	
	// Run the logic to check the credentials and set the authentication state
	$authViewInstance = new loadRunMVCComponents ( "AuthenticationModel", "AuthenticationController", "authView", $action, $slimAppInstance, $parameters );
	
	// If the authenticate state is not HTTPSTATUS_OK -> Halt the App and write the status code back
	if ($authViewInstance->model->authStatus != HTTPSTATUS_OK) 
		$slimAppInstance->halt($authViewInstance->model->authStatus);
	
	// 	If the authenticate state is HTTPSTATUS_OK the function simply ends allowing the process to continue
}



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
									// CHECK VIEW TYPE - json or xml (passed as header) //
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/** this function will check the "Content-Type" header that was passed, it accepts either "Content-Type: application/xml" or "Content-Type: application/json" **/

function checkViewType($slimAppInstance) {
	$viewType = $slimAppInstance->request->headers->get("Content-Type");
	
	if($viewType == RESPONSE_FORMAT_XML)
		$viewType = "xmlView";
	else
		$viewType = "jsonView";
	
	return $viewType;
}

//var_dump("1");
$slimAppInstance->run();

?>