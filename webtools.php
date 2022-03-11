<?php
$output = shell_exec('cat /etc/hosts');
echo "<pre>$output</pre>";
?>