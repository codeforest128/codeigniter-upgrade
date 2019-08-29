<div class="span9">
    <h1><?php echo lang('register_user_heading');?></h1>
    <p><?php echo lang('register_user_subheading');?></p>

    <?php if($message): ?>
        <div class="alert alert-success fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $message;?>
        </div>
    <?php endif; ?>

    <?php echo form_open("auth/register_user", array('class' => 'form-horizontal')) ?>

    <div class="control-group<?php echo form_error('first_name') != '' ? ' error' : '' ?>">
        <?php echo lang('create_user_fname_label', 'first_name', 'control-label');?>
        <div class="controls"><?php echo form_input($first_name);?></div>
    </div>

    <div class="control-group<?php echo form_error('last_name') != '' ? ' error' : '' ?>">
        <?php echo lang('create_user_lname_label', 'last_name', 'control-label');?>
        <div class="controls"><?php echo form_input($last_name);?></div>
    </div>

    <div class="control-group<?php echo form_error('email') != '' ? ' error' : '' ?>">
        <?php echo lang('create_user_email_label', 'email', 'control-label');?>
        <div class="controls"><?php echo form_input($email);?></div>
    </div>

    <div class="control-group<?php echo form_error('password') != '' ? ' error' : '' ?>">
        <?php echo lang('create_user_password_label', 'password', 'control-label');?>
        <div class="controls"><?php echo form_input($password);?></div>
    </div>

    <div class="control-group<?php echo form_error('password_confirm') != '' ? ' error' : '' ?>">
        <?php echo lang('create_user_password_confirm_label', 'password_confirm', 'control-label');?>
        <div class="controls"><?php echo form_input($password_confirm);?></div>
    </div>

    <div class="control-group<?php echo form_error('phone') != '' ? ' error' : '' ?>">
        <?php echo lang('create_user_phone_label', 'phone', 'control-label');?>
        <div class="controls"><?php echo form_input($phone);?></div>
    </div>

    <div class="control-group<?php echo form_error('address_1') != '' ? ' error' : '' ?>">
        <?php echo lang('create_user_address_1', 'address_1', 'control-label');?>
        <div class="controls"><?php echo form_input($address_1);?></div>
    </div>

    <div class="control-group">
        <?php echo lang('create_user_address_2', 'address_2', 'control-label');?>
        <div class="controls"><?php echo form_input($address_2);?></div>
    </div>

    <div class="control-group">
        <?php echo lang('create_user_address_3', 'address_3', 'control-label');?>
        <div class="controls"><?php echo form_input($address_3);?></div>
    </div>

    <div class="control-group<?php echo form_error('city') != '' ? ' error' : '' ?>">
        <?php echo lang('create_user_city', 'city', 'control-label');?>
        <div class="controls"><?php echo form_input($city);?></div>
    </div>

    <div class="control-group<?php echo form_error('postcode') != '' ? ' error' : '' ?>">
        <?php echo lang('create_user_postcode', 'postcode', 'control-label');?>
        <div class="controls"><?php echo form_input($postcode);?></div>
    </div>

    <div class="control-group">
        <?php echo lang('create_user_curling_club', 'curling_club', 'control-label');?>
        <div class="controls"><?php echo form_input($curling_club);?></div>
    </div>

    <div class="control-group">
        <div class="controls">
            <p>
                <label class="checkbox">
                    <?php echo lang('create_user_mailing_list');?>
                    <?php $checked = isset($mailing_list['value']) ?>
                    <?php echo form_checkbox('mailing_list', '1', $checked, 'id="mailing_list"');?>
                </label>
            </p>
            <?php echo form_button(array('name' => 'submit', 'content' => lang('create_user_submit_btn'), 'class' => 'btn btn-primary', 'type' => 'submit')) ?>
        </div>
    </div>

    <?php echo form_close();?>
</div>