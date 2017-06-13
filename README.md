# poc_php_basic_cookie
How to endanger an apache/php app by mixing basic and cookie authentication

This simple docker application contains :  
_ an Apache http server, responsible for the authentication of the user  
_ a PHP backend page, that uses the authentication info  

## The problem
The configuration of the Apache server requires an authentication through a form and a cookie mechanism. But if an Authorization header is present, like it would be in a basic authentication scheme, it may pollute the PHP variables used to identify the connected user, possibly permitting identity fraud, if used incorrectly.

## Test the application
The poc has been setup on an Ubuntu VM. Correct for any other platform (eg remove the `sudo`)

### Pre-requisite
Docker  
cd to the base directory of the repository.

### Build the container
```
sudo docker build -t poc .
```

### Launch the container
```
sudo docker run -d -p 80:80 -v ${PWD}/htdocs:/var/www/html/ -v ${PWD}/htpasswd:/htpasswd/ --name poc poc
```

### View the application
Open a browser and open http://localhost. The authenticaton form will be displayed :
![Alt text](docs/login.png?raw=true "Login page")
Two user/pwd are already set : _toto/totopwd_ and _titi/titipwd_  
In case of error, the form is displayed again.  
In case of success, a "hello" page is displayed, and indicates the identity of the user as of two different PHP variables :  
_ `$_SERVER['REMOTE_USER']`  
_ `$_SERVER['PHP_AUTH_USER']`  

![Alt text](docs/logged.png?raw=true "Authenticated page")  
Both variables contain the id of the authenticated user.

### Trigger the problem
Once authenticated, add to the request a basic authentication header, like :  
`Authorization: Basic RG9uYWxkVHJ1bXA6c3Ryb25ncHdk`  
(Use a proxy of some sort, eg Burp, or the Developer Tools of the browser)
![Alt text](docs/added_header.png?raw=true "Added header")

`RG9uYWxkVHJ1bXA6c3Ryb25ncHdk` is the base64 encoded string for `DonaldTrump:strongpwd`, which would represent the user DonaldTrump with the password strongpwd. 
The response to the request clearly shows the problem :  
```
     <li>$_SERVER['REMOTE_USER']&nbsp;:&nbsp;toto</li>
     <li>$_SERVER['PHP_AUTH_USER']&nbsp;:&nbsp;DonaldTrump</li>
```  
The `$_SERVER['PHP_AUTH_USER']` variable contains the identity present in the Authorization header, without any test of its credentials, even though an other user is currently authenticated (eg toto).  
Any code relying on it can then be fooled.

## Possible mitigations
### Do not use $_SERVER['PHP_AUTH_USER']
[Php documentation](http://php.net/manual/en/reserved.variables.server.php) indicates some subtleties on those two variables :
* 'REMOTE_USER' : The authenticated user.  
* 'PHP_AUTH_USER' : When doing HTTP authentication this variable is set to the username provided by the user.  
We can notice that the latter one does not state that the username is actually authenticated... subtle. Not quite sure why this variable is set when no Authorization header is provided.

### Remove Authorization header
The Authorization header can also be forcibly removed by the Apache http server configuration
```
RequestHeader unset Authorization
```
This requires [mod_headers](https://httpd.apache.org/docs/current/mod/mod_headers.html)

