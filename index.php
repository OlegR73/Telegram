<?php 
if (function_exists('opcache_reset')) {
    opcache_reset();
}
clearstatcache();
echo "INDEX";
echo  "<a href='hook.php'><h1>HOOK</h1></a>";
echo  "<a href='commands_register.php'><h1>Command</h1></a>";
echo  "<a href='api_cripto.php'><h1>Cripto</h1></a>";
echo  "<a href='test.php'><h1>Webhook-info</h1></a>";
echo  "<a href='hook.log'><h1>hook.log</h1></a>";
echo  "<a href='php-error.log'><h1>php-error</h1></a>";
?>
