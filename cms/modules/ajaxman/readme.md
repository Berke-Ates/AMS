### PHP Functions

#### `AjaxMan::add($name, $func)`
> `$name::String` The name to bind the function to  
`$func::String` The function to add to the callable ajax functions

Adds the function to the possible callable ajax functions.

#### `AjaxMan::ret($msg)`
> `$msg::String` The message to send back

Deletes all the produced output so far and json encodes the message to send back to the calling js.

### JS Functions

#### `getAjax(funcName, formdata, callback)`
> `funcName::String` The name that was used in `AjaxMan::add($name, $func)`  
`formdata::Formdata` The formdata object to send  
`callback::Function` The callback function to call after receiving an answer

Calls the specified function and sends the formdata. On receive calls the callback with the response as an argument (already json decoded).
