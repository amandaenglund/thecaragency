<?php
    require('header.php');
    require('./classes/products.php');
?>


<?php
// $query = "SELECT * FROM Products ORDER BY id ASC";
// echo $query;
// //$result = mysqli_query($connect, $query);


if(isset($_POST["add_to_cart"])) //namnet på submitknappen 
{
    if(isset($_SESSION["shopping_cart"]))
    {
        //nån kod
    }
    else{
        $item_array = array(
            //nån kod
        );
        $_SESSION["shopping_cart"][0] = $item_array;
    }

}

?>

<?php 
    require('footer.php');
?>