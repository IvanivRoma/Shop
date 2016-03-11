<?php
include "db_query.php";
session_start();
$need_autorization=true;
$db_query= new Db_query();
$order_by="";
$search ="";
$sort_options = Array('Name_up' => 'Name (up)', 'Name_down' => 'Name (down)', 'Prise_up' => 'Prise (up)', 'Prise_down' => 'Prise (down)');
if (isset($_SESSION['id']))
{
	$id_user= $_SESSION['id'];
   	$rows= $db_query->Get_NameCust_byid($id_user);
	echo"hello ".$rows['Name']."";
	$need_autorization=false;
}
if (isset($_POST['Log_out']))
{
    session_destroy();
        header("Location: autorization.php");
}
if (isset($_POST['Log_in']))
{
    header("Location: autorization.php");
}
if (isset($_POST['buy']))
{ 
    $data= array ($_POST['ID_Product'],$_POST['count']);
    $order_id=$db_query->insert_order($data);
    $data= array ($id_user,$order_id);
    $db_query->insert_basket($data);
}
if (isset($_POST['buy_all']))
{
     $db_query->reserve($id_user);
}
if (isset($_POST['cancel']))
{
    $db_query->Delete_basket($id_user,$_POST['ID_Order']);
}
?>

<!Doctype HTML>
<html>
    <head>
		<link rel="stylesheet" href="style.css">
    </head>
    <body>
        
		
            <div class= "main">
                <div class="shop">
                    <div class="search">
                        <form method="POST" >
                    <?php 
                    echo "Sort By: ";
                    echo "<select name='sort_option'>\n";
                    echo "<option value=''></option>\n";
                    foreach($sort_options as $sort_key => $caption){
                       $selected = ($_POST['sort_option'] == $sort_key) ? "selected" : "";
                       echo "<option value='{$sort_key}' {$selected}>{$caption}</option>\n";
                    }
                    echo "</select>\n";
                    echo'<input type="submit"  name="sort" value="sort">';
                    if($need_autorization)
                        echo'<input type="submit" id="login" name="Log_in" value="Log_in">';
                    else
                    {  echo'<input type="submit"  name="Basket" value="Basket">';
                        echo'<input type="submit" id="login" name="Log_out" value="Log_out">';
                    }
				    ?>
                        <input name="search_text" type="text" value= "<?php //echo empty($_POST['search_text'])? :$_POST['search_text']; ?>">
                        <input type="submit" id="search_submit" name="search_submit" value="search_submit">
                    </form>
                            </div>
                    
                    <div class="products">
                    <?php 
                        if (isset ($_GET['category'])&& !isset($_POST['sort'])||(isset($_POST['sort'])&& !empty($_GET['category']) )) 
                        {
                            if(isset($_POST['sort_option']))
                                $order_by= $_POST['sort_option'];
                            if (isset($_POST['search_submit'])&&!empty ($_POST['search_text']))
                                $search= $_POST['search_text'];
                            $rows=$db_query->Get_product($_GET['category'],$order_by,$search);
                            echo "<table border=3 >";
                            echo "<th>Name</th><th>Prise</th><th>Amount</th><th>Unit</th><th>Buy</th><th>Amount to buy</th>";

                            foreach($rows  as $row)
                            {
                                echo  "<tr>
                                <form method='POST' >
                                <td>{$row['Name']}</td>
                                <td>{$row['Prise']}</td>
                                <td>{$row['Amount']}</td>
                                <td>{$row['Unit']}</td>";
                                if ($need_autorization)
                                echo "</form>
                                </tr>";
                                else echo"<input type='hidden' name='ID_Product' value='" . $row['ID_Product'] . "' />
                                <td><input type='submit' name='buy' value='Buy' /> </td>
                                <td><input type='number' name='count' min='1' max='".$row['Amount']."' value='1'/></td>
                                </form>
                                </tr>";
                                    
                            }
                            echo "</table>";
                        }
                        if (isset ($_POST['Basket']))
                        {
                             $rows=$db_query->Get_basket ($_SESSION['id']);
                             echo "<table border=3 >";
                             echo "<th>Name</th><th>Amount</th><th>Prise</th>";
                            foreach($rows  as $row)
                            {
                             echo  "<tr><form method='POST' >
                                <td>{$row['Name']}</td>
                                <td>{$row['Amount']}</td>
                                <td>{$row['Cost']}</td>
                                <input type='hidden' name='ID_Order' value='" . $row['ID_Order'] . "' />
                                <td><input type='submit' name='cancel' value='cancel' /> </td>
                                </form></tr>";
                            }
                            echo "</table><br>";
                            echo'<form method="POST"><input type="submit"  name="buy_all" value="buy_all">';
                            echo "</form>";
                        }
                        ?>
                    </div>  
                </div>
				<div class="category">
                    <?php
                    $rows=$db_query->Get_CategName();
                    foreach($rows as $key=>$value)
                    echo '<p><a href="?category='.$value['Name'].'">'.$value['Name'].'</a></p>';
                    ?>
                    
                </div>
			</div>
		
        
    </body>
</html>
