<?
if (isset($_SESSION['msg'])){
    echo $_SESSION['msg'];
    unset ($_SESSION['msg']);
}
?>
<center>
<form class="form-wrapper" action="logon.php" name="logon" method="post">
  <table class="tabla_simple">
    <!--DWLayoutTable-->
    <tr>
      <td width="115" height="24" align="right" valign="middle"><span class="texto_negro">Usuario</span></th>
      <td width="169" align="left" valign="middle"><input type="text" name="usuario" maxlength="20"></th>

    <tr>
      <td width="115" height="24" align="right" valign="middle"><span class="texto_negro">Contraseña</span></th>
      <td width="169" align="left" valign="middle"><input type="password" name="password"></th>
    </tr>
    <tr>
      <td height="26" colspan="2"><center><input type="submit" class="submit" name="action" value="Ingresar"></center></th>
    </tr>
  </table>
</form>