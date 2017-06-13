<html>
 <head>
  <title>Test PHP</title>
 </head>
 <body>
 <p align="right">
	<a href="/auth/logout">Déconnexion&nbsp;&nbsp;&nbsp;</a>
 </p>
 <?php echo '<p>Bonjour '.$_SERVER['REMOTE_USER'].' ou '.$_SERVER['PHP_AUTH_USER'].'</p>'; ?>
 </body>
</html>