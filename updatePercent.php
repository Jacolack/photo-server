<?php
	shell_exec("df | grep /dev/root | awk -F ' ' '{print $5}' | sed 's/%//' > percent.txt");
	echo "Done";
?>
