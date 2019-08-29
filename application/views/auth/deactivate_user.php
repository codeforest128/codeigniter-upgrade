<div class="span9">
    <h1><?php echo lang('deactivate_heading');?></h1>
    <p><?php echo sprintf(lang('deactivate_subheading'), $user->username);?></p>

    <?php echo form_open("auth/deactivate/".$user->id);?>

    <p>
        <label for="confirm" class="radio">
            <?php echo lang('deactivate_confirm_y_label');?>
            <input type="radio" name="confirm" id="confirm_yes" value="yes" checked="checked" />
        </label>
        <label class="radio" for="confirm_no">
            <?php echo lang('deactivate_confirm_n_label');?>
            <input type="radio" name="confirm" id="confirm_no" value="no" />
        </label>
    </p>

    <?php echo form_hidden($csrf); ?>
    <?php echo form_hidden(array('id'=>$user->id)); ?>

    <?php echo form_button(array('name' => 'submit', 'content' => lang('deactivate_submit_btn'), 'class' => 'btn btn-primary', 'type' => 'submit')) ?>

    <?php echo form_close();?>
</div>