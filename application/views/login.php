<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>{titulo}</title>
	<link rel="stylesheet" href="{url}/css/style.css" type="text/css" media="all">
</head>
<body>
<div id="div_login">
<!--<div><img src="images/logo_header.jpg" width="302" height="106" align="center" /></div>-->
<form id="loginform" action="{action_frm}" method="post" onSubmit="return valida_ingreso()">

        <p>
                <label>Usuario<br />
                <input type="text" name="user" id="user" class="input" value="" size="20" tabindex="20" /></label>
        </p>
        <p>
                <label>Password<br />
                <input type="password" name="user_pass" id="user_pass" class="input" value="" size="20" tabindex="20" /></label>
        </p>

        <p>
                <input type="submit" name="wp-submit" id="wp-submit" value="Ingresar" tabindex="100" />
        </p>
</form>
</div>
</body>

</html>
