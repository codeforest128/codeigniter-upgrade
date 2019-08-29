<div class="span9">
    <h1><?php echo lang('session_title') ?></h1>

    <?php if($message): ?>
        <div class="alert alert-success fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $message;?>
        </div>
    <?php endif; ?>

    <?php if($error): ?>
        <div class="alert alert-error fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $error;?>
        </div>
    <?php endif; ?>

    <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal'));?>

    <div class="control-group<?php echo form_error('date') != '' ? ' error' : '' ?>">
        <?php echo lang('session_date_label', 'date', 'control-label');?>
        <div class="controls">
            <div class="input input-append">
                <?php echo form_input($date);?>
                <span class="add-on"><i class="icon-calendar"></i></span>
            </div>
        </div>
    </div>

    <div class="control-group">
        <?php echo lang('session_start_time_label', 'start_time_hours', 'control-label');?>
        <div class="controls">
            <?php echo form_dropdown($start_time_hours['name'], $start_time_hours['options'], $start_time_hours['value'], $start_time_hours['attr']);?>
            <?php echo form_dropdown($start_time_mins['name'], $start_time_mins['options'], $start_time_mins['value'], $start_time_mins['attr']);?>
        </div>
    </div>

    <div class="control-group">
        <?php echo lang('session_end_time_label', 'end_time_hours', 'control-label');?>
        <div class="controls">
            <?php echo form_dropdown($end_time_hours['name'], $end_time_hours['options'], $end_time_hours['value'], $end_time_hours['attr']);?>
            <?php echo form_dropdown($end_time_mins['name'], $end_time_mins['options'], $end_time_mins['value'], $end_time_mins['attr']);?>
        </div>
    </div>

    <div class="control-group<?php echo form_error('session_number') != '' ? ' error' : '' ?>">
        <?php echo lang('session_session_number_label', 'session', 'control-label');?>
        <div class="controls"><?php echo form_dropdown($session_number['name'], $session_number['options'], $session_number['value'], $session_number['attr']);?></div>
    </div>

    <div class="control-group">
        <div class="controls">
            <?php echo form_button($save) ?>
            <?php echo form_button($cancel) ?>
            <!-- only show delete button if we are editing -->
            <?php if(!is_null($id) && $id != ''): ?>
                <?php echo form_button($delete) ?>
            <?php endif; ?>
        </div>
    </div>

    <?php echo form_hidden($id) ?>

    <?php echo form_close();?>
</div>