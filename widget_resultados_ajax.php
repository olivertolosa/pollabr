<?

print_r ($_GET);
   $id=$_GET['id'];
   $type=$_GET['type'];
   $completed=$_GET['completed'];

   $type=$type."&completed=".$completed;
?>
<script type="text/javascript">
<!--
padding = "5";
width = "220px";
bgColor = "#454545";
linkColor = "#FFFFFF";
textColorA = "#7CA726";
textColorB = "#52701B";
border = "1px solid #DDDDDD";
textFont = "12px Arial, Helvetica, Sans serif";
 //-->
</script>
<script language="javascript" src="http://www.resultados-futbol.com/scripts/api/api.php?key=41d55642f6613d82dae41eac2ecc4d65&format=widget&req=<? print $type; ?>&category=<? echo $id; ?>&grated=1&extra=logo&comments=1&group=1"></script>

