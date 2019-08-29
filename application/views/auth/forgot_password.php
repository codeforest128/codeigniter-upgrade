<div class="span9">
    <h1><?php echo lang('forgot_password_heading');?></h1>
    <p><?php echo sprintf(lang('forgot_password_subheading'), $identity_label);?></p>

    <?php if($message): ?>
        <div class="alert alert-success fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $message;?>
        </div>
    <?php endif; ?>

    <?php echo form_open("auth/forgot_password", array('class' => 'form-horizontal'));?>

    <div class="control-group">
        <label for="email" class="control-label"><?php echo sprintf(lang('forgot_password_email_label'), $identity_label);?></label>
        <div class="controls"><?php echo form_input($email);?></div>
    </div>

    <div class="control-group">
        <div class="controls"><?php echo form_button(array('name' => 'submit', 'content' => lang('forgot_password_submit_btn'), 'class' => 'btn btn-primary', 'type' => 'submit')) ?></div>
    </div>

    <?php echo form_close();?>
</div>