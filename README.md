# Book-Critic

This is a web application for book reviews.  
It uses the REST architecture to allow client/server communication.  

The server holds a list of books (book name, isbn, description)  
Critics can push their reviews of these books to the server.  
Users can then pull the reviews of these books from the server.  
This is done through REST calls using an appropriate HTTP method and URL e.g. GET bookcritic.com/books/search/ulysses  

Slim Framework v2 is used to implement the REST API.  
Slim's Custom Routing is used to allow multiple HTTP methods for each URL.  
Slim will handle returning the response status, header and body.  


## Slim Installation (from http://docs.slimframework.com/start/get-started/):
Manual install (not composer install)  
Download and extract the Slim Framework into your project directory and then "require" it in your application's index.php file:  

 - require 'Slim/Slim.php';

You'll also need to register Slim's autoloader:
 - \Slim\Slim::registerAutoloader();

Instantiate a Slim application:
 - $app = new \Slim\Slim();

Now you can define routes. HTTP GET route:
 - $app->get('/hello/:input', function ($input) {
    echo "Hello, $input"; });

And Run the Slim application:
 - $app->run();

-- GET example.com/hello/world  -> returns -> "Hello World"


## Slim Routing (from http://docs.slimframework.com/routing/overview/)
With Slim you can map resource URIs (aka URLs) to callback functions for specific HTTP request methods (e.g. GET, POST, PUT, DELETE, OPTIONS or HEAD)  

You can load a specific function to do something for a specific http request method, for a specific URI resource  
Example: "GET www.library.com/book/ulysses"  
In this example the http request method is GET and the URI resource is the data stored at the location www.library.com/book/ulysses.  

Through Slim's routing a specific function is ran for this request  
For example; a function to return the data at www.library.com/book/ulysses  
If the Slim application does not find routes that match the URI and HTTP method, it will return a 404 Not Found response.  


## Custom Routing (from http://docs.slimframework.com/routing/custom/)  
You may want a resource URI to responds to multiple HTTP methods.  
Slim's Custom Routes allow this.  

use map-> instead of get-> (or post->, put->, delete-> etc.)  
and then use via-> and add the names of the methods available as a string e.g. "GET", "POST"  

 - $app = new \Slim\Slim();
 - $app->map('/foo/bar', function()
    {
        echo "I respond to multiple HTTP methods!";
    })->via('GET', 'POST');
 - $app->run();



## Use Cases:
Users:  
Retrieve book reviews by book name                  (GET /reviews/search/"book_name")  
Retrieve book details by book name                  (GET /books/search/"book_name")  
Retrieve all book reviews in the database           (GET /reviews/search/)  
Retrieve all books details in the database          (GET /books/search/)  
 
Critics:  
Retrieve book details by db id  (GET /books/admin/"book_id")  
Add a new book details          (POST /books/admin/)  
Edit a book details             (PUT /books/admin/"book_id")  
Delete a book                   (DELETE /books/admin/"book_id")  

Retrieve a review by db id      (GET /books/admin/"review_id")  
Add a new review                (POST /reviews/admin/)  
Edit a review                   (PUT /reviews/admin/"review_id")  
Delete a review                 (DELETE /reviews/admin/"review_id")  

Critics: (change to Admins)  
Retrieve a critic by id         (GET /critics/admin/"critic_id")  
Add a new critic                (POST /critics/admin/)  
Edit a critics details          (PUT /critics/admin/"critic_id")  
Delete a critic                 (DELETE /critics/admin/"critic_id")  


## Checklist
Portability:  
Totally configurable from config.inc.php file  
 
Testing:  
73 validation tests implemented on isEmailValid, isNumberInRangeValid, isLengthStringValid functions  
needs tests for the rest calls  
 
MVC-correctness:  
Yes, throughout   
 
DB Managment:  
DAO objects and PDO manager used  
 
DRY principle:  
Unified "Action" strings that work with all DAOs.  
Single function in index.php used for /search/ and /admin/ routes and each table /reviews/, /books/, /critics/.  
 
Authentication:  
Present and working  
 
Response formats:  
XML + JSON   
 
Response HTTP codes used:  
(BADREQUEST, 400), (UNAUTHORIZED, 401), (FORBIDDEN, 403), (NOTFOUND, 404), (METHODNOTALLOWED, 405),   
(NOTACCEPTABLE, 406), (INTSERVERERR, 500), (OK, 200), (CREATED, 201), (NOCONTENT, 204)  
