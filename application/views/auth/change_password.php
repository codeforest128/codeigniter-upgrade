<div class="span9">
    <h1><?php echo lang('change_password_heading');?></h1>

    <?php if($message): ?>
        <div class="alert alert-success fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $message;?>
        </div>
    <?php endif; ?>

    <?php echo form_open("auth/change_password", array('class' => 'form-horizontal'));?>

    <div class="control-group">
        <?php echo lang('change_password_old_password_label', 'old_password', 'control-label');?>
        <div class="controls"><?php echo form_input($old_password);?></div>
    </div>

    <div class="control-group">
        <label for="new_password" class="control-label"><?php echo sprintf(lang('change_password_new_password_label'), $min_password_length);?></label>
        <div class="controls"><?php echo form_input($new_password);?></div>
    </div>

    <div class="control-group">
        <?php echo lang('change_password_new_password_confirm_label', 'new_password_confirm', 'control-label');?>
        <div class="controls"><?php echo form_input($new_password_confirm);?></div>
    </div>

    <?php echo form_input($user_id);?>
    <div class="control-group">
        <div class="controls">
            <?php echo form_button(array('name' => 'submit', 'content' => lang('change_password_submit_btn'), 'class' => 'btn btn-primary', 'type' => 'submit')) ?>
        </div>
    </div>

    <?php echo form_close();?>

</div>
