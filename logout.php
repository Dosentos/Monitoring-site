<?php
/**
 * Created by PhpStorm.
 * User: Dosentti
 * Date: 9.3.2017
 * Time: 10.03
 */
session_destroy();
header("Location: ?page=login");
die();
echo "successfully logged out.";
?>
<article>
    <h1 class="info">Successfully logged out.</h1>
</article>