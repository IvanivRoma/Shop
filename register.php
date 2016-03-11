<?php


include "validation.php";
include "db_query.php";
if(isset($_POST['autorization_submit'])){
    header("Location: autorization.php");
}

if (isset($_POST['register_submit']))
{ 
	$user_password=$_POST['password'];
	$user_name= $_POST['name'];
	$user_log= $_POST['login'];
	$user_phone= $_POST['phone'];
	$user_addess= $_POST['address'];
	
	//validation
	$valid = new Valid();
	$valid->Name_valid($user_name);
	$valid->Phone_valid($user_phone);
	$valid->Address_valid($user_addess);
	$valid->Login_valid($user_log);
	$valid->Password_valid($user_password);
	if ($valid->Get_Error_stat ()==false)
	{
        $data= array ($user_name,$user_phone,$user_addess,$user_log,md5($user_password));
        $db_query= new Db_query();
        $db_query->Insert_customer($data);
        echo "Thank you for registering";
	}
}


?>

<html>
    <head>
        <title>Registration</title>
    </head>
    <body>
        <div class="rrgister_block">
        <form method="POST" action="" >
            <table>
                <tr>
                    <td>Login</td>
                    <td><input type="text" name="login"/></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" name="password" /></td>
                </tr>
                <tr>
                    <td>Name</td>
                    <td><input type="text" name="name" /></td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td><input type="text" name="phone" /></td>
				<tr>
					<td>Address</td>
					<td><input type="text" name="address" /></td>
				</tr>
                </tr> 
				
            </table>
			<?php 
				if (isset($_POST['register_submit']))
				{ 
					if ($valid->Get_Error_stat()==true)
					{
						$e_arr=$valid->Get_Error_arr();
						foreach ($e_arr as $key)
						{
							echo"<br>".$key."<br>";
						}
					}
				}
				?>
            <p><input type="submit" name="register_submit" value="Register" /> 
			<br> <br> 
                If you already have an account 
			<br>
			<input type="submit" name="autorization_submit" value="Log in" /></p>
        </form>
        </div>

    </body>
</html>