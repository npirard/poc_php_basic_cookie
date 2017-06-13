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

Two user/pwd are already set : toto/totopwd and titi/titipwd  
In case of error, the form is displayed again.  
In case of success, a "hello" page is displayed, and indicates the identity of the user as of two different PHP variables :  
_ `$_SERVER['REMOTE_USER']`  
_ `$_SERVER['PHP_AUTH_USER']`  
