<?php

// -----------------------------------------
// semplice
// partials/password_form.php
// -----------------------------------------
// $theme 	> black / white
// $submit 	> submit button for static or single page app
// -----------------------------------------

?>

<div class="post-password-form<?php echo $theme; ?>">
	<div class="inner">
		<form action="<?php echo esc_url(site_url('wp-login.php?action=postpass', 'login_post')); ?>" method="post">
			<div class="password-lock"><?php echo get_svg('frontend', 'icons/password_lock'); ?></div>
			<p class="title"><?php echo __('This content is protected.', 'semplice'); ?></p>
			<p class="subtitle"><?php echo __('To view, please enter the password.', 'semplice'); ?></p>
			<div class="input-fields">
				<input name="post_password" class="post-password-input" type="password" size="20" maxlength="20" placeholder="<?php echo __('Enter password', 'semplice'); ?>" /><?php echo $submit; ?>
			</div>
		</form>
	</div>
</div>