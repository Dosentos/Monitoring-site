<?php
/**
 * Created by PhpStorm.
 * User: Dosentti
 * Date: 9.3.2017
 * Time: 10.03
 */
?>
<nav id="navigation">
        <ul class="bg-blue">
            <?php

            //Tässä haetaan config.php tiedostosta navigaatioelementtien nimet ja isketään ne
            //navigaatioelementeiksi.
            foreach ($content as $i =>$page){
                if($page["name"]!=""){
                    $active = "";
                    if(isset($_GET['page']) && $_GET['page'] == $page["page_id"]){
                        $active = "bg-lightblue";
                    }
                    echo "<li class='$active'><a href='?page=$page[page_id]'><h4>$page[name]</h4></a></li>";
                }
            }
            ?>
</ul>
<div class="clearer"></div>
</nav>