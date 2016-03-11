<?php 
include_once "db_conect.php";
class Db_query
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new Db_conect("localhost","shop","root","");
    }
    public function Get_IDCustPass_bylog($login)
    {
        $query = $this->pdo->prepare("SELECT ID_Customer, Password FROM customers WHERE Login = '".$login."' ");
        $query->execute();
        $rows=$query->fetch(PDO::FETCH_ASSOC);
        return $rows;
    }
    public function Get_NameCust_byid($id_user)
    {
        $query= $this->pdo->prepare("Select Name from customers where ID_Customer='".$id_user."'");
        $query->execute();
        $rows=$query->fetch(PDO::FETCH_ASSOC);
        return $rows;
    }
    public function Insert_customer($data)
    {
        $query = $this->pdo->prepare("INSERT INTO customers (Name,Phone,Address,Login,Password) VALUES (?,?,?,?,?)");
        $query->execute($data);
				
    }
    public function Get_CategName ()
    {
        $query= $this->pdo->prepare("Select Name from category");
        $query->execute();
        $rows=$query->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    public function Get_product($category,$order_by,$search)
    {
    switch ($order_by){
	case 'Name_up': 
		$order_by = ' ORDER BY Name';
		break;
	case 'Name_down': 
		$order_by = ' ORDER BY Name DESC';
		break;
	case 'Prise_up': 
		$order_by = ' ORDER BY Prise';
		break;
	case 'Prise_down': 
		$order_by = ' ORDER BY Prise DESC';
		break;
        default:
            $order_by = ' ORDER BY Name';
		break;
    } 
        if(!empty($search))
            $search=" and products.Name  LIKE '%".$search."%'";
        $query= $this->pdo->prepare("Select products.ID_Product, products.Name, products.Prise, products.Amount,products.Unit from products   join categ_product on  products.ID_Product=categ_product.ID_Product  join category on category.ID_Category=categ_product.ID_Category where products.Amount>0 and category.Name='".$category."'".$search." ".$order_by."");
        $query->execute();
        $rows=$query->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    public function insert_order ($data)
    {
        $query = $this->pdo->prepare("INSERT INTO orders (ID_Product,Amount) VALUES (?,?)");
        $query->execute($data);
        return $this->pdo->lastInsertId();
    }
    public function insert_basket ($data)
    {
        $query = $this->pdo->prepare("INSERT INTO my_basket (ID_Customer,ID_Order) VALUES (?,?)");
        $query->execute($data);
    }
    public function reserve ($id_cust)
    {
        $row = self::Get_basket($id_cust);
        
        foreach($row as $key)
        {
            $amount_row= self::Amount_compare($key['ID_Product']);
            if ($amount_row[0]['Amount']>=$key['Amount']){
                $query = $this->pdo->prepare("UPDATE products SET Amount=Amount-".$key['Amount']." WHERE ID_Product=".$key['ID_Product']);
                $query->execute();
                $query = $this->pdo->prepare("UPDATE my_basket SET Reserved='1' where ID_Customer ='".$id_cust."'");
                $query->execute();
            }
            else{
                echo " wrong value Amount product!!!";
            }
        }
    }
    public function Get_basket ($id_cust)
    {
        $query= $this->pdo->prepare("Select products.ID_Product, products.Name, orders.Amount* products.Prise as Cost,orders.Amount, my_basket.ID_Order  from products join orders on products.ID_Product=orders.ID_Product join my_basket on my_basket.ID_Order=orders.ID_Order where my_basket.Reserved=0 and my_basket.ID_Customer='".$id_cust."' ");
        $query->execute();
        $rows=$query->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    private function Amount_compare ($id_prod)
    {
       $query = $this->pdo->prepare("Select products.Amount from products WHERE ID_Product=".$id_prod);
       $query->execute();
       $rows=$query->fetchAll(PDO::FETCH_ASSOC);
       return $rows;
    }
    public function Delete_basket ($id_cust,$id_order)
    {
       $query = $this->pdo->prepare("Delete from my_basket  WHERE ID_Customer='".$id_cust."'and ID_Order='".$id_order."'");
       $query->execute();
       $query = $this->pdo->prepare("Delete from orders  WHERE  ID_Order='".$id_order."'");
       $query->execute();
    }
    
}
?>