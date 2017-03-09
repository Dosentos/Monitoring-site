<?php
/**
 * Created by PhpStorm.
 * User: Dosentti
 * Date: 9.3.2017
 * Time: 10.04
 */

//Mikäli sessiota ei ole asetettu ja tämä sivu koitetaan avata, siirrytään suoraan
//Kirjautumissivullle
if(!isset($_SESSION['user'])){
    header("Location: ?page=login");
    die();
}
//haetaan käyttäjän tiedot session tiedoista
$user = $_SESSION['user'];
?>
<article>
    <section class="info">
        <h3>Kirjautuminen onnistui!</h3>
        <aside>
            <h4>Tervetuloa <?= $user['firstname'] ?>!</h4>
        </aside>
    </section>
</article>