<?php
include "db_query.php";

if(isset($_POST['registration'])){
    header("Location: register.php");
}


 if(isset($_POST['submit'])){  
	$login = $_POST['login'];
	$password = md5($_POST['password']);
 	$db_query= new Db_query();
	$rows=$db_query->Get_IDCustPass_bylog($login);

if($rows['Password'] ==  $password){
   if (session_status() == PHP_SESSION_NONE) {
    session_start();
   }
	$_SESSION['id'] = $rows['ID_Customer'];
    
    header("Location: index.php");
 }
 else {
     echo "Wrong login or password";
 }
 }
?>
<html>
    <head>
        <title>Autorization</title>
    </head>
    <body>
        <form method="POST" action="">
            <table>
                <tr>
                    <td>Login:</td> <td><input name="login" type="text" ><br></td>
                </tr>
                <tr>
                    <td>Password:</td> <td><input name="password" type="password"><br></td>
                </tr>
                <tr>
                     <td><input name="submit" type="submit" value="Log in"></td>
                     <td><input name="registration" type="submit" value="Registration"></td>
                </tr>
        </table>
        </form>
    </body>
</html>
