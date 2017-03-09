<?php
/**
 * Created by PhpStorm.
 * User: Dosentti
 * Date: 9.3.2017
 * Time: 10.04
 */
$emailError ="";
$passwordError ="";

$email="";

$isValid = true;


//Jos dataa ollaan lähettämässä post metodilla, niin tarkastetaan datan muoto

if($_SERVER["REQUEST_METHOD"]=="POST"){

    //Tarkistetaan käyttäjätunnus
    if(empty($_POST["email"])){
        $emailError ="Käyttäjänimi on pakollinen";
        $isValid = false;
    }
    else{

        if (checkInput($_POST["email"]) == true){
            $email = $_POST["email"];
        }
        else{
            $emailError = "Sähköpostiosoitteessasi on kiellettyjä merkkejä.";
            $isValid = false;
        }
    }

    //Tarkistetaan salasana
    if(empty($_POST["password"])){
        $passwordError ="Salasana on pakollinen";
        $isValid = false;
    }
    else{
        $password = $_POST["password"];
    }



    if($isValid == true){

        // Tietokantayhteys
        //                       osoite      käyttäjänimi    ss         tietokanta
        $mysqli = new mysqli("mydb.tamk.fi", "e2vketol", "Testi123", "dbe2vketol1");
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: ("
                . $mysqli->connect_errno
                . ") "
                . $mysqli->connect_error;
        }

        //Haetaan käyttäjä tietokannasta
        $res = $mysqli->query(
            "SELECT "
            ."`lastname`, "
            ."`firstname`, "
            ."`email`, "
            ."`password` "
            ."FROM "
            ."`user` "
            ."WHERE "
            ."`email`='$email';");

        //Haetaan saatu rivi
        if($user = $res->fetch_assoc()){

            //Tarkistetaan löydetyn käyttäjän tunnus ja salasana
            //Salasana on hashattynä tietokannassa, joten se pitää 'avata' Password_verify metodilla
            if($user['email'] === $email && password_verify($password, $user['password'])){

                //Jos käyttäjätunnus (email) ja salasana ovat samat kuin tietokannassa, niin
                //Aloitetaan istunto ja käyttäjä pääsee sivustolle.
                $_SESSION['user'] = $user;
                header("Location: ?page=login_success");
                die();
            }
        }

        //Jos salasana tai käyttäjätunnut on väärin, annetaan validointivirhe
        //ja ilmoitetaan virheviestinä, alla oleva merkkijono
        $passwordError ="Käyttäjätunnus tai salasana on väärin!";
    }

}
?>
<article class="container">
    <form method="POST" class="form-horizontal">
        <fieldset>

            <legend class="info">Kirjautuminen</legend>

            <!-- Sähköpostiosoitteen/käyttäjänimen syöttö-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="email">Sähköposti</label>
                <div class="col-md-5">
                    <input id="email" name="email" type="text" value="<?= $email ?>" class="form-control input-md" required=""/>
                    <span class="help-block">Syötä käyttäjänimeksi 'admin@admin.fi'.</span>
                    <div class="alert-danger">
                        <strong><?= $emailError ?></strong>
                    </div>
                </div>
            </div>

            <!-- Salasanan syöttö-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="password">Salasana</label>
                <div class="col-md-5">
                    <input id="password" name="password" type="password" value="salasana" class="form-control input-md" required=""/>
                    <span class="help-block">Kirjoita salasanaksi 'Adminpass1'.</span>
                    <div class="alert-danger">
                        <strong><?= $passwordError ?></strong>
                    </div>
                </div>
            </div>

            <!-- Lähetä nappi -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="submit">Kirjaudu Sisään</label>
                <div class="col-md-4">
                    <input type="submit" id="submit" name="submit" class="form-control btn btn-primary" value="Kirjaudu"/>
                </div>
            </div>
        </fieldset>
    </form>

    <section id="register-link" class="col-md-12">
        <div class="text-container">
            <div class="top bg-grey">
                <h2>Rekisteröidy!</h2>
            </div>
            <div class="bottom">
                <p><a href="index.php?page=register">Voit rekisteröityä demo sivustolle tästä linkistä.</a></p>
                <p><span class="info"><strong>Huom!</strong></span> admin@admin.fi tunnuksilla pääset kirjautumaan ilman rekisteröitymistä. Salasana lukee salasana-kentän alla.</p>
            </div>
        </div>
    </section>

</article>