<?php 
// Include the ShoppingCart class.  Since the session contains a
// ShoppingCard object, this must be done before session_start().
require "../application/cart.php";
session_start(); 
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
<title>Checkout</title>
<meta name="author" content="Nolan McCafferty">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/JavaScript">
    function validate() {
        var valid = true;
    if (!/[a-z]+@[a-z]+\.[(com)|(edu)|(gov)]/.test(document.getElementById('email').value)) {
        document.getElementById('mail').style.color = "red";
        document.getElementById('mail').innerHTML = " Must be a valid email adress";
        valid = false;
    } else {
        document.getElementById('mail').innerHTML = "";
    }
    if (!/\b\d{5}\b/.test(document.getElementById('zipcode').value)) {
        document.getElementById('zcode').style.color = "red";
        document.getElementById('zcode').innerHTML = " Must be a valid zipcode";
        valid = false;
    } else {
        document.getElementById('zcode').innerHTML = "";
    }
    if (document.getElementById('firstname').value == "" || document.getElementById('lastname').value == "" ||
    document.getElementById('address').value == "" || document.getElementById('city').value == "" ||
    document.getElementById('state').value == "" || document.getElementById('zipcode').value == "" || 
    document.getElementById('email').value == "" || document.getElementById('phone').value == "" || 
    document.getElementById('scout').value == "" || document.getElementById('troop').value == "") {
        valid = false;
        document.getElementById('confirm').style.color = "red";
        document.getElementById('confirm').innerHTML = "All fields must be non-empty";
    } else {
        document.getElementById('confirm').innerHTML = "";
    }
    return valid;
}


// ajax call to autopopulate the girl scout troop field based on the name of the girl scout. 
// In this world the grl scout troops are chosen by name 
// and everyone in the troop will have a name that starts with the same letter
function getTroop() {
  $.ajax({
      url: "troops.php",
      type: 'get',
      data: {
          name: $('#scout').val()
      },
      datatype: 'text',
      success: function(data) {
        document.getElementById('troop').value = data;
      },
      error: function() {
          alert("Error retrieving troop name");
      }
  });
}
    
</script>
</head>

<body>

<h1>Checkout</h1>

<?php
echo "<table style=\"float: right; padding-right: 200px\">";
echo "<tr> <td><h2>Here is your order: </h2></td></tr>";
echo "<tr> <td> </td> <td></td><td> Cookie </td> <td></td><td> Quantity </td><td></td><td></td><td> Price</td></tr>";
$total = 0;
foreach($_SESSION['cart']->order as $key => $value) {
    $price = $value*5;
    $total += $price;
    echo "<tr> <td> <img src=cookies/".$key.".jpg> </td> <td></td><td> $key </td> <td></td><td>$value </td> <td></td>
    <td></td> <td> $$price</td><td></td>" ;
}
$totalquantity = $total/5;
echo "<tr> <td></td><td></td><td><b>Total</b> </td> <td></td><td><b>$totalquantity </b></td><td></td><td></td><td> <b>$$total</b> </td><td></td>  </tr>";
echo "</table>";

  // define variables and set to empty values
  $firstnameErr = $lastnameErr = $addressErr = $cityErr = $zipcodeErr = "";
  $emailErr = $phoneErr = $scoutErr = $troopErr = "";
  $error = " This field is required";
  $firstname = $lastname = $address = $city = $state = $zipcode = "";
  $email = $phone = $scout = $troop= "";

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (empty($_POST["scout"])) {
        $scoutErr = $error;
      } 
      else {
        $scout = test_input($_POST["scout"]);
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/",$scout)) {
          $scoutErr = " Only letters and white space allowed"; 
        } if (strlen($scout) < 4) {
            $scoutErr = " Must be more than 3 characters";
        }
      }
      
      if (empty($_POST["email"])) {
        $emailErr = $error;
      } else {
        $email = test_input($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
          $emailErr = " Invalid email format"; 
        }
      }

      if (empty($_POST["firstname"])) {
        $firstnameErr = $error;
      } else {
        $firstname = test_input($_POST["firstname"]);
      }

      if (empty($_POST["lastname"])) {
        $lastnameErr = $error;
      } else {
        $lastname = test_input($_POST["lastname"]);
      }

      if (empty($_POST["address"])) {
        $addressErr = $error;
      } else {
        $address = test_input($_POST["address"]);
        // check if address is valid
        if (!preg_match("/^[a-z0-9 .\-]+$/i", $address)) {
            $addressErr = " Must be a valid address";
        }
      }

      if (empty($_POST["city"])) {
        $cityErr = $error;
      } else {
        $city = test_input($_POST["city"]);
        //check if city is valid
        if (!preg_match("/^[a-zA-Z ]*$/",$city)) {
            $cityErr = " Must be a valid city";
      }
    }
      if (empty($_POST["zipcode"])) {
        $zipcodeErr = $error;
      } else {
        $zipcode = test_input($_POST["zipcode"]);
        // check if zipcode is valid 
        if (!preg_match("/^[0-9]{5}$/",$zipcode)) {
            $zipcodeErr = " Must be a valid zipcode";
        }
      }
      if (empty($_POST["phone"])) {
        $phoneErr = $error;
      } else {
        $phone = test_input($_POST["phone"]);
        // check if phone number is valid 
        if (!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/",$phone)){
            $phoneErr = " Must be a valid phone number (xxx-xxx-xxxx)";
        }
      }
      if (empty($_POST["troop"])) {
        $troopErr = $error;
      } else {
        $troop = test_input($_POST["troop"]);
      }

      if (!empty($_POST["state"])) {
        $state = test_input($_POST["state"]);
      }



  // confirmation button which should be pressed after the information is submitted, and
 // will be hidden until the form is submitted correctly
 $ready = "";
  $ready = $firstnameErr.$lastnameErr.$addressErr.$cityErr.$stateErr.$zipcodeErr.$emailErr.$phoneErr.$scoutErr.$troopErr;
   if($ready == "") {
        echo "<a href=\"confirm.php\"><button id=\"confirmButton\" style=\"font-size: 20px; position: absolute; top: 80%; left: 40%\">Confirm Order</button></a>";
    } 
}
?>

<p>
<h2>Customer Information: </h2><br>

<form name="custinfo" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" onsubmit="return validate();">

First name: <input size=30 type="text" id="firstname" value="<?php echo $firstname;?>" name="firstname"><span style="color: red" id="fname"><?php echo $firstnameErr;?></span><br>
Last name: <input size=30 type="text" id="lastname" value="<?php echo $lastname;?>" name="lastname"><span style="color: red" id="lname"><?php echo $lastnameErr;?></span><br>
Address: <input size=50 type="text" value="<?php echo $address;?>" id="address" name="address"><span style="color: red" id="afield"><?php echo $addressErr;?></span><br>
City: <input size=30 type="text" id="city" value="<?php echo $city;?>" name="city"><span style="color: red" id="cfield"><?php echo $cityErr;?></span><br>
State: <input size=10 id="state" type="text" value="<?php echo $state;?>" name="state">
Zipcode: <input size=10 id="zipcode" type="text" value="<?php echo $zipcode;?>" name="zipcode"><span style="color: red" id="zcode"><?php echo $zipcodeErr;?></span><br>
Email: <input size=30 id="email" type="text" value="<?php echo $email;?>" name="email"><span style="color: red" id="mail"><?php echo $emialErr;?></span><br>
Phone: <input size=20 type="text" name="phone" value="<?php echo $phone;?>" id="phone"><span style="color: red" id="pfield"><?php echo $phoneErr;?></span><br><br>

<h2> Girl Scout Information: </h2> <br><br>
Name: <input size=40 type="text" id="scout" onblur="getTroop()" value="<?php echo $scout;?>" name="scout"><span style="color: red" id="scoutfield"><?php echo $scoutErr;?></span><br>
Troop: <input size=40 type="text" id="troop"  value="<?php echo $troop;?>" name="troop"><span style="color: red" id="troopfield"><?php echo $troopErr;?></span><br><br><br>

<input type="submit" value="Submit" style="font-size: 20px"><br><span style="color: red" id="confirm"><?php echo $empty;?></span>
</form></p>
<br>

<p><a href="viewcart.php">Back to cart</a></p>
<p><a href="index4.php">Shop some more!</a></p>
<p id="t"></p>
</body>
</html>