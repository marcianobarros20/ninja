<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo Kohana::config('config.product_name').' '._('login'); ?></title>
		<link type="text/css" rel="stylesheet" href="<?php echo ninja::add_path('css/default/common.css') ?>" media="all" />
		<link type="text/css" rel="stylesheet" href="<?php echo ninja::add_path('css/default/print.css') ?>" media="print" />
		<link type="text/css" rel="stylesheet" href="<?php echo ninja::add_path('css/default/jquery-ui-custom.css') ?>" />
		<?php
			echo html::link(ninja::add_path('icons/x16/favicon.ico'),'icon','image/icon');
			echo html::script('application/media/js/jquery.js');
		?>
		 <script type="text/javascript">
			var this_page = "<?php echo Kohana::config('config.site_domain').
				Kohana::config('config.index_page').'/'.Kohana::config('routes.log_in_form'); ?>";
			if (window.location.pathname != this_page)
				window.location.replace(this_page);
		</script>
		<script type="text/javascript">
			//<!--
				var _site_domain = '<?php echo Kohana::config('config.site_domain') ?>';
				var _index_page = '<?php echo Kohana::config('config.index_page') ?>';
				$(document).ready(function() {
					$('#login_form').bind('submit', function() {
						$('#loading').show();
						$('#login').attr('disabled', true);
						$('#login').attr('value', '<?php echo _('Please wait...') ?>');
					});
				});
			//-->
		</script>
		<?php
			$v = new View('js_header', array('js' => $js));
			$v->render(true);
		?>
	</head>

	<body>
		<div id="login-table">
			<?php echo form::open($login_page, array('id' => 'login_form')); ?>
			<table border="1">
				<tr>
					<td colspan="2">
						<?php $brand = brand::get();
						echo '<center>' . $brand . '</center>';
						if (isset($error_msg)) {
							echo "<p class='alert error'>" . $error_msg . "</p>";
						}
						?>
					</td>
				</tr>
				<tr><td colspan="2"><hr /></td></tr>
				<tr>
					<td><label for="username"><?php echo _('Username') ?></label></td>
					<td><?php echo form::input(array('name'=>'username', 'class'=>'login_field'),'','class="i160"') ?></td>
				</tr>
				<tr>
					<td><label for="password"><?php echo _('Password') ?></label></td>
					<td><?php echo form::password(array('name'=>'password', 'class'=>'login_field'),'','class="i160"') ?></td>
				</tr>
				<?php
				$auth = Auth::instance();
				$default_auth = $auth->get_default_auth();
				if (!empty($auth_modules) && is_array($auth_modules) && count($auth_modules) > 1) {	?>
				<tr>
					<td><?php echo _('Login method') ?></td>
					<td><?php echo form::dropdown(array('name'=>'auth_method', 'class'=>'login_field'), array_combine( $auth_modules, $auth_modules ), $default_auth ) ?></td>
				</tr>
				<?php
				}?>
				<tr><td colspan="2"><hr /></td></tr>
				<tr>
					<td colspan="2" style="text-align: center">
						<?php
							echo form::submit('login', _('Login'), 'style="margin-left: 5px"');
						?><br /><br />
						<div id="loading" style="display:none;">
							<?php echo html::image('application/media/images/loading.gif') ?>
						</div>
					</td>
				</tr>
			</table>
		<?php echo form::close() ?>
		</div>
	</body>
</html>
