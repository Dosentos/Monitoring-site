<?php
/**
 * Created by PhpStorm.
 * User: Dosentti
 * Date: 9.3.2017
 * Time: 10.02
 */
require_once 'config.php';
$firstnameError ="";
$lastnameError ="";
$emailError ="";
$passwordError ="";
$password2Error ="";
$agreementError ="";

$firstname ="";
$lastname ="";
$email ="";
$password ="";
$password2 ="";
$accept_rules ="";

$isValid = true;

if($_SERVER["REQUEST_METHOD"]=="POST"){

    if(empty($_POST["firstname"])){
        $firstnameError = "Etunimi on täyttämättä";
        $isValid = false;
    }
    else{
        if ($isValid = checkInput($_POST["firstname"])){
            $firstname = ($_POST['firstname']);

            //Tarkastetaan onko nimessä kiellettyjä kirjaimia
            if (!preg_match("/^[a-öA-Ö ]*$/", $firstname)){
                $firstnameError = "Etunimessä on kiellettyjä merkkejä";
            }
        }
        else{
            $firstnameError = "Etunimessä on kiellettyjä merkkejä";
        }
    }


    if(empty($_POST["lastname"])){
        $lastnameError = "Sukunimi on täyttämättä";
        $isValid = false;
    }
    else{
        if ($isValid = checkInput($_POST["lastname"])){
            $lastname = ($_POST['lastname']);
            if (!preg_match("/^[a-öA-Ö ]*$/", $lastname)){
                $lastnameError = "Sukunimessä on kiellettyjä merkkejä";
            }
        }
        else{
            $lastnameError = "Sukunimessä on kiellettyjä merkkejä";
        }
    }

    if(empty($_POST["email"])){
        $emailError = "Sähköposti on pakollinen";
        $isValid = false;
    }
    else{
        if($isValid = checkInput($_POST["email"])){
            $email = ($_POST['email']);
            //Tarkastetaan onko syötetty sähköposti oikean muotoinen
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $emailError = "Sähköposti ei ole oikean muotoinen";
                $isValid = false;
            }
        }
        else{
            $emailError = "Sähköpostissa on kiellettyjä merkkejä";
        }
    }


    if(empty($_POST["password"])){
        $passwordError ="Salasana on pakollinen";
        $isValid = false;
    }
    else{
        $password = $_POST["password"];
        if (!preg_match("^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$^", $password)){
            $passwordError = "Salasanassa pitää olla vähintään 6 merkkiä, joista yksi tulee olla numero!";
            $isValid = false;
        }
    }
    if(empty($_POST["password2"])){
        $password2Error ="Kirjoita salasana uudelleen";
        $isValid = false;
    }else{
        $password2 = $_POST["password2"];
        // Tähän voidaan lisätä muita tarkastuksia
        if ($password2 != $password){
            $password2Error = "Salasanat eivät täsmää";
            $isValid = false;
        }
    }

    if(empty($_POST["accept_rules"])){
        $agreementError = "Hyväksy palvelun käyttöehdot";
        $isValid = false;
    }




    // Jos kaikki tiedot ovat oikein, siirrytään asettamaan tiedot tietokantaan
    if($isValid){

        // Tietokantayhteys
        $mysqli = new mysqli("mydb.tamk.fi", "e2vketol", "Testi123", "dbe2vketol1");
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: ("
                . $mysqli->connect_errno
                . ") "
                . $mysqli->connect_error;
        }

        //Hashataan salasana
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        //Asetetaan käyttäjän tiedot tietokantaan
        $res = $mysqli->query(
            "INSERT INTO user (lastname, firstname, email, password) "
            . "VALUES ('$lastname', '$firstname', '$email', '$password_hash')"
        );



        if($res === TRUE){
            //Uudelleenohjaus login sivulle
            header("Location: ?page=login");
            die();
        }
        else{
            echo "Query failed, try again later..";
            die();
        }
    }
}


?>
<section class="container">
    <form method="post" class="form-horizontal">
        <fieldset>
            <legend class="info">Rekisteröi uusi käyttäjä</legend>
            <div class="form-group">
                <label class="col-md-4 control-label" for="firstname">Etunimi</label>
                <div class="col-md-5">
                    <input type="text" class="form-control" id="firstname" name="firstname" value="<?= $firstname ?>">
                    <div class="alert-danger">
                        <strong><?= $firstnameError ?></strong>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="lastname">Sukunimi</label>
                <div class="col-md-5">
                    <input type="text" class="form-control" id="lastname" name="lastname" value="<?= $lastname ?>">
                    <div class="alert-danger">
                        <strong><?= $lastnameError ?></strong>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="email">Sähköposti</label>
                <div class="col-md-5">
                    <input type="text" class="form-control" name="email" id="email" value="<?= $email ?>">
                    <div class="alert-danger">
                        <strong><?= $emailError ?></strong>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="password">Salasana</label>
                <div class="col-md-5">
                    <input type="password" class="form-control" id="password" name="password">
                    <div class="alert-danger">
                        <strong><?= $passwordError ?></strong>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="password2">Salasana uudelleen</label>
                <div class="col-md-5">
                    <input type="password" class="form-control" id="password2" name="password2">
                    <div class="alert-danger">
                        <strong><?= $password2Error ?></strong>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="agreement">Hyväksyn käyttöehdot</label>
                <div class="col-md-5">
                    <input type="checkbox" class="form-control" id="agreement" name="accept_rules"name="accept_rules" <?= $accept_rules ?> />
                    <div class="alert-danger">
                        <strong><?= $agreementError ?></strong>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-4 control-label" for="submit"></label>
                <div class="col-md-5">
                    <input type="submit" id="submit" class="form-control" name="submit" value="Lähetä">
                </div>
            </div>
        </fieldset>
    </form>
</section>


