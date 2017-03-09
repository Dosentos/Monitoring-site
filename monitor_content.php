<?php
/**
 * Created by PhpStorm.
 * User: Dosentti
 * Date: 9.3.2017
 * Time: 10.04
 */
?>
<article>
    <div class="page-heading">
        <h1>Monitorit</h1>
    </div>

    <div id="alert" class="bg-danger">
        <h2 class="text-danger">Critical Error</h2>
        <p class="text-danger">
            <strong id=error-message></strong>
        </p>
        <p id="email-sent"></p>
    </div>
    <div id="test"></div>
    <div id="monitor-chart" class="chart"></div>

</article>

<script>
    /**
     Tämä on toki aivan uskomattoman surkea tapa käsitellä tietoa, koska javascript
     suoritetaan käyttäjän päässä, jolloin käyttäjä pääsee suoraan käsiksi koodiin.

     Koodin pitäisi olla ohjelmoituna back-end kielellä, jotta sen suorittaminen olisi
     edes jossain määrin turvallista.
     Jos data tulisi tietokannasta, niin se voitaisiin hakea turvallisesti ilman
     vaaraa, että käyttäjä pystyy muuttamaan dataa.

     Tietokannasta saataisiin myös sopivalla kyselyllä irti suoraan esim. viimeiset 10 50 tai 100
     mittaustulosta, jolloin mittaustuloksia olisi aina sopiva määrä kuvaajassa, eikä kuvaaja
     häiritsevästi skaalautuisi kun uutta dataa tulee. Vanhat mittaustulokset menisivät piiloon
     sitä mukaan kun uutta tulee tauluun.

     **/
    /*Näillä globaaleilla muuttujilla voidaan säädellä minkä suuruista dataa monitori näyttää
     ja kuinka herkästi monitori raportoi virheistä.

     Oletuksena näillä arvoilla scripti arpoo lämpötilalle satunnaisen numeroarvon 30 ja 35 väliltä.
     Tämän jälkeen errZone asetettu arvo vähennetään maksimiarvosta ja lisätään nimiarvoon, jolloin
     koneen arpoessa lämpötilalle arvon joka on suurempi kuin 34.9 tai pienempi kuin 30.1, järjestelmä
     näyttää virhe viestin.

     Minimi ja maksimiarvoille ei ole muuta sääntöä, kuin että minimin on aina oltava pienempi kuin maksimi.

     errZone on aina oltava pienempi, kuin maksimiarvon ja minimiarvon erotuksen puolikas. (errZone <= (maxTemp-minTemp)/2)

     */
    maxTemp = 35;
    minTemp = 30;
    maxHum = 40;
    minHum = 15;
    //ALWAYS (errZone <= (maxTemp-minTemp)/2)
    errZoneTemp = 0.1;
    errZoneHum = 0.1;


    google.charts.load('current', {
        callback: function () {
            var chart = new google.visualization.LineChart(document.getElementById('monitor-chart'));

            var options = {'title' : 'Lämpötila ja ilmankosteus','legend':'top',
                animation: {
                    duration: 200,
                    easing: 'out',
                    startup: true
                },
                hAxis: {
                    title: 'Aika',
                    format: 'HH:mm'
                },
                vAxis: {
                    title: 'Lämpötila ja suht. ilmankosteus',
                    //Näillä saadaan muutettua kuvaajan X akselin skaala
                    minValue: 0,
                    maxValue: 100
                },
            };

            var data = new google.visualization.DataTable();
            data.addColumn('datetime', 'Aika');
            data.addColumn('number', 'Lämpötila');
            data.addColumn('number', 'Ilmankosteus');

            var formatDate = new google.visualization.DateFormat({pattern: 'hh:mm'});
            var formatNumber = new google.visualization.NumberFormat({pattern: '#,##0.0'});

            //Tähän taulukkoon säilötään viimeisimmät mittaustiedot, jotta saadaan responsiivinen design taulukkoon
            var tempArray = new Array(3);

            //Päivitetään taulukko viimeisimmällä arvolla, mikäli ikkunan koko muuttuu.
            //Tämä estää taulukon viivan virheellisen piirtämisen ikkunan kokoa muuttaessa.
            //Ei taida olla hyvän ohjelmointitavan mukaista laittaa Jquerya ja javascriptiä sekaisin
            //mutta en tiennyt millä muulla tavalla tämän saa tehtyä.
            $(window).resize(function(){
                drawChart(tempArray[2], tempArray[0], tempArray[1]);
            });
            getTemp();

            //Suoritetaan getTemp metodi 5 sekunnin välein
            setInterval(getTemp, 5000);

            //Metodissa arvotaan lämpötila (30-35), ilmankosteus (15-40) ja otetaan suoritushetken aika ja pvm talteen
            function getTemp() {
                tempArray[0] = (Math.random() * (maxTemp - minTemp) + minTemp);
                tempArray[1]= (Math.random() * (maxHum - minHum) + minHum);
                tempArray[2] = new Date();

                checkValues(tempArray);


                drawChart(tempArray[2], tempArray[0], tempArray[1]);
            }

            //Piirretään taulukko mittaustulosten perusteella
            function drawChart(timestamp, temperature, humidity) {

                data.addRow([timestamp, temperature, humidity]);

                //asetetaan tietyt dataformaatit tiettyihin sarakkaisiin.
                formatDate.format(data, 0);
                formatNumber.format(data, 1);
                formatNumber.format(data, 2);
                //Piirretään taulukko
                chart.draw(data, options);
            }
        },
        packages:['corechart']
    });
    //Tarkistaa lämpötilan ja ilmankosteuden arvot (onko sallittujen rajojen sisäpuolella)
    //Gerenoi virheviestin tulostusta varten
    function checkValues(tempArray){
        var errorMessageTemp = "";
        var errorMessageHum = "";
        if(tempArray[0] > (maxTemp - errZoneTemp)){
            errorMessageTemp = "Lämpötila on liian korkea! <br>Lämpötila: " + tempArray[0];
        }
        if(tempArray[0] < (minTemp + errZoneTemp)){
            errorMessageTemp = "Lämpötila on liian matala! <br> Lämpötila: " + tempArray[0];
        }
        if(tempArray[1] > (maxHum - errZoneHum)){
            errorMessageHum = "Ilmankosteus on liian korkea <br> Ilmankosteus: " + tempArray[1];
        }
        if(tempArray[1] < (minHum + errZoneHum)){
            errorMessageHum = "Ilmankosteus on liian matala <br> Ilmankosteus: " + + tempArray[1];
        }
        if (errorMessageTemp != "" || errorMessageHum != ""){
            showAlert(errorMessageTemp, errorMessageHum, tempArray[2]);
        }
    }

    //Metodi yhdistää virheviestit ja tulostaa näytölle virheviestin.
    function showAlert(errorMessageTemp, errorMessageHum, timestamp){
        var fullErrorMessage = "";

        if (errorMessageTemp != "" && errorMessageHum != ""){
            fullErrorMessage = timestamp + "<br>" + errorMessageTemp + "<br>" + errorMessageHum;
        }
        else if (errorMessageTemp != ""){
            fullErrorMessage = timestamp + "<br>" + errorMessageTemp;
        }
        else if (errorMessageHum != ""){
            fullErrorMessage = timestamp + "<br>" + errorMessageHum;
        }

        document.getElementById('error-message').innerHTML = fullErrorMessage;
        document.getElementById('alert').style.display = "block";
        sendErrMsgPost(fullErrorMessage);
    }

    //Lähetää POST parametrinä dataa, jonka avulla lähetetään sähköposti,
    //mikäli virhe on sattunut.
    function sendErrMsgPost(errMsg){

        $.ajax({
            url: "email.php",
            data: {
                message: errMsg
            },
            type: "POST",
            dataType: "html",


        })
        // Jos lähetys onnistuu
            .done(function(htmlString) {
                document.getElementById('email-sent').innerHTML = "Sähköposti-ilmoitus lähetetty!";
            })
            // Jos koodin lähetys epäonnistuu
            .fail(function( xhr, status, errorThrown ) {
                document.getElementById('email-sent').innerHTML = "Sähköpostin lähetys epäonnistui";
                document.getElementById('email-sent').style.color = 'red';
                console.log( "Error: " + errorThrown );
                console.log( "Status: " + status );
                console.dir( xhr );
            });
    }

</script>