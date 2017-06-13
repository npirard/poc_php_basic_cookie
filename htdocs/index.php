<html>
 <head>
  <title>Test PHP</title>
 </head>
 <body>
 <p align="right">
	<a href="/auth/logout">Disconnect&nbsp;&nbsp;&nbsp;</a>
 </p>
 <?php echo '<p>Hello 
   <ul>
     <li>$_SERVER[\'REMOTE_USER\']&nbsp;:&nbsp;'.$_SERVER['REMOTE_USER'].'</li>
     <li>$_SERVER[\'PHP_AUTH_USER\']&nbsp;:&nbsp;'.$_SERVER['PHP_AUTH_USER'].'</li>
   </ul>'; ?>
 </body>
</html>