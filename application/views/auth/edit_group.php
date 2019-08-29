<div class="span9">
    <h1><?php echo lang('edit_group_heading');?></h1>
    <p><?php echo lang('edit_group_subheading');?></p>

    <?php if($message): ?>
        <div class="alert alert-success fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $message;?>
        </div>
    <?php endif; ?>

    <?php echo form_open(current_url(), array('class' => 'form-horizontal'));?>

    <div class="control-group<?php echo form_error('group_name') != '' ? ' error' : '' ?>">
        <?php echo lang('create_group_name_label', 'group_name', 'control-label');?>
        <div class="controls"><?php echo form_input($group_name);?></div>
    </div>

    <div class="control-group">
        <?php echo lang('edit_group_desc_label', 'description', 'control-label');?>
        <div class="controls"><?php echo form_input($group_description);?></div>
    </div>

    <div class="control-group">
        <div class="controls">
            <?php echo form_button(array('name' => 'submit', 'content' => lang('edit_group_submit_btn'), 'class' => 'btn btn-primary', 'type' => 'submit')) ?>
        </div>
    </div>

    <?php echo form_close();?>
</div>