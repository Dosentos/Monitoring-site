<?php
/**
 * Created by PhpStorm.
 * User: Dosentti
 * Date: 9.3.2017
 * Time: 10.05
 */
//Lähetetään viesti, mikäli POST datana saadaan viesti.
if(isset($_POST['message'])){

    $message = $_POST['message'];

    $user = $_SESSION['user'];

    $Receiver = $user['email'];

    //Tähän voidaan tehdä esim. automaattinen postituslista aktiivisen käyttäjän sijaan.
    //Postituslistalla saadaan informoitua myös muita henkilöitä, jos lämpötila tai kosteus
    // on kriittisessä tilassa.


    //
    if(mail($Receiver, "System Critical Condition", $message)){
        echo "Viesti Lähetetty!";
    }
    else {
        echo "Error";
    }

}
else {
    echo "POST 'message' parametri puuttuu...";

}
?>