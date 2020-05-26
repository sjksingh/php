<?php
 $dbstr = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=clua.dc3.deem.zone)(PORT=1521))(CONNECT_DATA=(SID=clua)))";
 if($conn = oci_connect('readonly','bugz1ll4', $dbstr)):
 print "CONNECTED to CLUD  OK!!";
 else:
 print "WE HAVE A PROBLEM";
 endif;
?>
