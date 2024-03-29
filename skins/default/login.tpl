<ol class="breadcrumb">
	<li><a href="{$site_url}"><span class="glyphicon glyphicon-home"></span></a></li>
	<li class="active">{$l_login}</li>
</ol>

<h1>{$l_login}</h1>
<p>{$l_login_why}</p>
<form method="post" action="{$site_url}/includes/login_process.php" id="login">
	<div class="table_div">
		<div>
			<legend>{$l_username}</legend>
			<div><input type="text" name="user_id" size="35" maxlength="80" required="required" /> <span class="required">&bull;</span></div>
		</div>
		<div>
			<legend>{$l_password}</legend>
			<div><input type="password" name="user_passwd" size="35" maxlength="80" required="required" /> <span class="required">&bull;</span></div>
		</div>
		<!--<div>
			<legend>{$l_enter_captcha}</legend>
			<div><img src="visual.php" alt="captcha" /><br /><input type="text" name="qvc" size="5" maxlength="5" required="required" style="max-width:100px"/> <span class="required">&bull;</span></div>
		</div>-->
	</div>
	<div style="clear:both"></div>
	<p align="center"><button type="submit" class="btn btn-primary">{$l_login}</button></p>
	<p align="center"><a href="{$site_url}/profile.php?mode=lost">{$l_lost_passwd}</a></p>
</form>


<!-- BEGINIF $xpress -->
<h1>Booth Pick up</h1>
<p align="center"><a href="{$site_url}/Checkout.php?step=4" class="btn btn-success">Checkout</a></p>


<!-- BEGINIF $xpress -->
<h1>{$l_express_checkout}</h1>
 <p style="padding-bottom:20px">{$l_express_checkout_why}</p>
<!-- ENDIF -->

<h1>{$l_register}</h1>
<p align="center"><a href="{$site_url}/profile.php?mode=register" class="btn btn-success">{$l_register_now}</a></p>