<?php
/* database constants */
define("DB_HOST", "localhost" ); 		// set database host
define("DB_USER", "root" ); 			// set database user
define("DB_PASS", "" ); 				// set database password
define("DB_PORT", 3306);				// set database port
define("DB_NAME", "bookcritic" ); 			// set database name
define("DB_CHARSET", "utf8" ); 			// set database charset
define("DB_DEBUGMODE", true ); 			// set database charset

/* actions for the REST resource */
define("ACTION_SEARCH", 22);
define("ACTION_GET_ALL", 33);
define("ACTION_GET_ONE", 44);
define("ACTION_CREATE", 55);
define("ACTION_UPDATE", 66);
define("ACTION_DELETE", 77);

/* HTTP status codes 2xx*/
define("HTTPSTATUS_OK", 200);
define("HTTPSTATUS_CREATED", 201);
define("HTTPSTATUS_NOCONTENT", 204);

/* HTTP status codes 3xx (with slim the output is not produced i.e. echo statements are not processed) */
define("HTTPSTATUS_NOTMODIFIED", 304);

/* HTTP status codes 4xx */
define("HTTPSTATUS_BADREQUEST", 400);
define("HTTPSTATUS_UNAUTHORIZED", 401);
define("HTTPSTATUS_FORBIDDEN", 403);
define("HTTPSTATUS_NOTFOUND", 404);
define("HTTPSTATUS_METHODNOTALLOWED", 405);
define("HTTPSTATUS_NOTACCEPTABLE", 406);
define("HTTPSTATUS_REQUESTTIMEOUT", 408);
define("HTTPSTATUS_TOKENREQUIRED", 499);

/* HTTP status codes 5xx */
define("HTTPSTATUS_INTSERVERERR", 500);
define("TIMEOUT_PERIOD", 120);

/* general message */
define("GENERAL_MESSAGE_LABEL", "Message:");
define("GENERAL_SUCCESS_MESSAGE", "success");
define("GENERAL_ERROR_MESSAGE", "error");
define("GENERAL_NOCONTENT_MESSAGE", "no-content");
define("GENERAL_NOTMODIFIED_MESSAGE", "not modified");
define("GENERAL_INTERNALAPPERROR_MESSAGE", "internal app error");
define("GENERAL_CLIENT_ERROR", "client error: modify the request");
define("GENERAL_INVALIDTOKEN_ERROR", "Invalid token");
define("GENERAL_APINOTEXISTING_ERROR", "Api is not existing");
define("GENERAL_RESOURCE_CREATED", "Resource has been created");
define("GENERAL_RESOURCE_UPDATED", "Resource has been updated");
define("GENERAL_RESOURCE_DELETED", "Resource has been deleted");
define("GENERAL_FORBIDDEN", "Request is ok but action is forbidden");
define("GENERAL_INVALIDBODY", "Request is ok but transmitted body is invalid");
define("GENERAL_INVALIDURLPARAMS", "Request is ok but the wrong type of URL parameters were passed");
define("GENERAL_INVALID_HTTPMETHOD", "That HTTP Method cannot be used with this request");
define("GENERAL_WELCOME_MESSAGE", "Welcome to DIT web-services");
define("GENERAL_INVALIDROUTE", "Requested route does not exist");

define("RESPONSE_FORMAT_XML", "application/xml");

/* representation of a new critic in the DB */
define("TABLE_CRITIC_NAME_LENGTH", 200);
define("TABLE_CRITIC_SURNAME_LENGTH", 200);
define("TABLE_CRITIC_EMAIL_LENGTH", 200);
define("TABLE_CRITIC_PASSWORD_LENGTH", 200);

/* representation of a new review in the DB */
define("TABLE_REVIEW_BOOKID_LENGTH", 100);
define("TABLE_REVIEW_CRITICID_LENGTH", 100);
define("TABLE_REVIEW_REVIEWTEXT_LENGTH", 2000);
define("TABLE_REVIEW_BOOKTITLE_LENGTH", 200);

/* representation of a new book in the DB */
define("TABLE_BOOK_TITLE_LENGTH", 200);
define("TABLE_BOOK_AUTHOR_LENGTH", 200);
define("TABLE_BOOK_GENRE_LENGTH", 200);
define("TABLE_BOOK_ISBN_LENGTH", 200);


?>