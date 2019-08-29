<div class="span9">
    <div class="well">
        <?php echo form_open("auth/login", array('class' => 'form-signin'));?>

        <h1 class="form-signin-heading"><?php echo lang('login_heading');?></h1>
        <p><?php echo lang('login_subheading');?></p>

        <?php if($message): ?>
            <div class="alert alert-success fade in">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php echo $message;?>
            </div>
        <?php endif; ?>

        <?php $identity['class'] = 'input-block-level' ?>
        <?php $identity['placeholder'] = lang('login_identity_label') ?>

        <?php echo form_input($identity) ?>

        <?php $password['class'] = 'input-block-level' ?>
        <?php $password['placeholder'] = lang('login_password_label') ?>

        <?php echo form_input($password) ?>

        <label class="checkbox">
            <?php echo lang('login_remember_label');?>
            <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
        </label>

        <?php echo form_button(array('name' => 'submit', 'content' => lang('login_submit_btn'), 'class' => 'btn btn-large btn-primary', 'type' => 'submit')) ?>
        <?php echo form_hidden('return_url', $return_url) ?>
        
        <?php echo form_close(); ?>

        <p><a href="Auth/forgot_password"><?php echo lang('login_forgot_password');?></a></p>
        <?php printf(site_url('Auth/register_user'));exit();?>
        <p><a href="<?php echo site_url('Auth/register_user') ?>"><?php echo lang('login_create_account');?></a></p>
    </div>
</div>