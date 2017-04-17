## TO DO:
Remove "author name" from book details search author name  - DONE
Add Admins instead of critics  
Remove critics ability to delete or edit a review that isn't theirs (using logged in critic id to match critic id of the review?)  

Allow searching with both numbers and stings i.e. remove is_string  
Changes routes to from item/search and item/admin to search/item and admin/item  
Add function in booksdao to allow checking if the book actually exists before trying to update it (PUT),  
// its curently giving 400 bad request, "invalid body" if the record doesn't exist, regardless if the json body is ok  
// it should instead return 204 - NO CONTENT , then 400 bad request if the body's not right  
Remove encoding of response bodys to json for errors ? e.g. PUT returns json:  {"Message:":"Resource has been updated","updatedID":"67"}  
but non numeric id on an admin request returns: "Request is ok but the wrong type of url parameters were passed". (not json encoded)   
- so either encode all error responses or no error responses  

Add more tests, for the calls themself

