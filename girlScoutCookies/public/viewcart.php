<?php 
// Include the ShoppingCart class.  Since the session contains a
// ShoppingCard object, this must be done before session_start().
require "../application/cart.php";
session_start(); 
//print_r($_SESSION);
//echo "<br>after starting a session in viewcart...";
?>

<!DOCTYPE html>

<?php 

// If this session is just beginning, store an empty ShoppingCart in it.
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = new ShoppingCart();
} 
?>

<html lang="en">

<head>
<title>Girl Scout Cookie Shopping Cart</title>
<meta name="author" content="Nolan McCafferty">
</head>

<body>

<h2>Girl Scout Cookie Shopping Cart</h2>


<?php
if(isSet($_POST['updatecart'])) {
    foreach(ShoppingCart::$cookieTypes as $key => $value) {
        if($_POST[$key]) {
            $_SESSION['cart']->order[$key] = $_POST[$key];
        }
    }
}

if(isSet($_POST['delete'])) {
            unset($_SESSION['cart']->order[$_POST['delete']]); 
}
?>


<form name="updatecart" value="updatecart" method="post">

<p>
<?php
    echo "<table>";
    echo "<tr> <td> </td> <td></td><td> Cookie </td> <td></td><td> Quantity </td><td></td><td> Price</td><td></td> <td></td><td> Edit </td></tr>";
    $total = 0;
    foreach($_SESSION['cart']->order as $key => $value) {
        $price = $value*5;
        $total += $price;
        echo "<tr> <td> <img src=cookies/".$key.".jpg> </td> <td></td><td> $key </td> <td></td><td>$value </td> <td></td>
        <td></td> <td> $$price</td><td></td>
        <td> <input size=7 value=$value name=$key></td> <td> <button type=submit value=$key name=delete >Delete</button></td>" ;
    }
    $totalquantity = $total/5;
    echo "<tr> <td></td><td></td><td><b>Total</b> </td> <td></td><td><b>$totalquantity </b></td><td></td><td></td><td> <b>$$total</b> </td><td></td><td> <input value=Update type=submit name=updatecart> </td>  </tr>";
    echo "</table>";
?></p>

</form>


<p><a href="index4.php">Resume shopping</a></p>

<p><a href="checkout.php">Check out</a></p>
</body>
</html>