<?php
/**
 * Created by PhpStorm.
 * User: Dosentti
 * Date: 9.3.2017
 * Time: 10.05
 */

//Tässä tehdään taulukko, johon sijoitetaan tietoa jokaisesta sivusta, jota sivusto käyttää
//Sivusto lataa tämän taulukon mukaan index.php sivun sisältö osuuden.
$content = [
    ["name" => "Etusivu","page_id" => "home","include"=>"home_content.php"],
    ["name" => "Monitorit","page_id" => "b","include"=>"monitor_content.php"],
    ["name" => "Tietoja","page_id" => "c","include"=>"page3_content.php"],
    ["name" => "Kirjaudu ulos","page_id" => "d","include"=>"logout.php"],
    ["name" => "", "page_id" => "login", "include"=> "login.php"],
    ["name" => "", "page_id" => "login_success", "include"=> "login_success.php"],
    ["name" => "", "page_id" => "register", "include" => "register.php"]
];

//Tällä funktiolla tarkastetaan käyttäjän syötteet.
//Metodi palauttaa true, mikäli syöte on ok. Muussa tapauksessa false.
function checkInput($data){
    //Poistaa välilyönnit edestä ja lopusta
    $data = trim($data);

    //Poistaa kenoviivat
    $corrData = stripcslashes($data);

    if ($corrData === $data){
        //Poistaa html
        $corrData = htmlspecialchars($data);

        if($corrData === $data){

            return true;
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }
}
