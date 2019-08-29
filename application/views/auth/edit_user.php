<div class="span9">
    <h1><?php echo lang('edit_user_heading');?></h1>
    <p><?php echo lang('edit_user_subheading');?></p>

    <?php if($message): ?>
        <div class="alert alert-success fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $message;?>
        </div>
    <?php endif; ?>

    <?php echo form_open(uri_string(), array('class' => 'form-horizontal'));?>

    <div class="control-group">
        <?php echo lang('edit_user_fname_label', 'first_name', 'control-label');?>
        <div class="controls"><?php echo form_input($first_name);?></div>
    </div>

    <div class="control-group">
        <?php echo lang('edit_user_lname_label', 'last_name', 'control-label');?>
        <div class="controls"><?php echo form_input($last_name);?></div>
    </div>

    <div class="control-group">
        <?php echo lang('create_user_email_label', 'email', 'control-label');?>
        <div class="controls"><?php echo form_input($email);?></div>
    </div>

    <div class="control-group">
        <?php echo lang('edit_user_password_label', 'password', 'control-label');?>
        <div class="controls"><?php echo form_input($password);?></div>
    </div>

    <div class="control-group">
        <?php echo lang('edit_user_password_confirm_label', 'password_confirm', 'control-label');?>
        <div class="controls"><?php echo form_input($password_confirm);?></div>
    </div>

    <div class="control-group">
        <?php echo lang('create_user_phone_label', 'phone', 'control-label');?>
        <div class="controls"><?php echo form_input($phone);?></div>
    </div>

    <div class="control-group">
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

    <div class="control-group">
        <?php echo lang('create_user_city', 'city', 'control-label');?>
        <div class="controls"><?php echo form_input($city);?></div>
    </div>

    <div class="control-group">
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
                    <?php echo form_checkbox('mailing_list', '1', $mailing_list['value'], 'id="mailing_list"');?>
                </label>
            </p>
        </div>
    </div>

    <?php if($is_admin): ?>
        <h3><?php echo lang('edit_user_groups_heading');?></h3>
    <?php endif; ?>

    <div class="control-group">
        <div class="controls">
            <?php if($is_admin): ?>
                <?php foreach ($groups as $group):?>
                    <p>
                        <label class="checkbox">
                            <?php
                            $gID=$group['id'];
                            $checked = null;
                            $item = null;
                            foreach($currentGroups as $grp) {
                                if ($gID == $grp->id) {
                                    $checked= ' checked="checked"';
                                    break;
                                }
                            }
                            ?>
                            <input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
                            <?php echo $group['name'];?>
                        </label>
                    </p>
                <?php endforeach?>
            <?php endif; ?>

            <?php echo form_hidden('id', $user->id);?>
            <?php echo form_hidden($csrf); ?>

            <?php echo form_button(array('name' => 'submit', 'content' => lang('edit_user_submit_btn'), 'class' => 'btn btn-primary', 'type' => 'submit')) ?>
        </div>
    </div>

    <?php echo form_close();?>

</div>