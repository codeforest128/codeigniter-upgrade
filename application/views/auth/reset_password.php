<div class="span9">
    <h1><?php echo lang('reset_password_heading');?></h1>

    <?php if($message): ?>
        <div class="alert alert-success fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $message;?>
        </div>
    <?php endif; ?>

    <?php echo form_open('auth/reset_password/' . $code, array('class' => 'form-horizontal'));?>

    <div class="control-group">
        <label for="new_password" class="control-label"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label> <br />
        <div class="controls"><?php echo form_input($new_password);?></div>
    </div>

    <div class="control-group">
        <?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm', 'control-label');?> <br />
        <div class="controls"><?php echo form_input($new_password_confirm);?></div>
    </div>

    <?php echo form_input($user_id);?>
    <?php echo form_hidden($csrf); ?>

    <div class="control-group">
        <?php echo form_submit('submit', lang('reset_password_submit_btn'));?>
    </div>

    <?php echo form_close();?>
</div>
