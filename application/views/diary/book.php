<div class="span9">
    <h1><?php echo lang('book_event_title') ?></h1>

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

    <fieldset>
        <legend><?php echo lang('book_event_details_title') ?></legend>
        <div class="control-group">
            <?php echo lang('book_title_label', 'title', 'control-label');?>
            <div class="controls"><?php echo form_input($title);?></div>
        </div>

        <div class="control-group">
            <?php echo lang('book_sdate_label', 'start_date', 'control-label');?>
            <div class="controls">
                <div class="input input-append">
                    <?php echo form_input($start_date);?>
                    <span class="add-on"><i class="icon-calendar"></i></span>
                </div>
            </div>
        </div>

        <div class="control-group">
            <?php echo lang('book_session_label', 'session', 'control-label');?>
            <div class="controls"><?php echo form_multiselect($session['name'], $session['options'], $session['value'], $session['attr']);?></div>
        </div>

        <div class="control-group">
            <?php echo lang('book_sheet_label', 'sheet', 'control-label');?>
            <div class="controls"><?php echo form_dropdown($sheet['name'], $sheet['options'], $sheet['value'], $sheet['attr']);?></div>
        </div>

        <div class="control-group">
            <?php echo lang('book_team1_label', 'team1', 'control-label');?>
            <div class="controls"><?php echo form_input($team1);?></div>
        </div>

        <div class="control-group">
            <?php echo lang('book_team2_label', 'team2', 'control-label');?>
            <div class="controls"><?php echo form_input($team2);?></div>
        </div>

        <div class="control-group">
            <?php echo lang('book_score1_label', 'score1', 'control-label');?>
            <div class="controls"><?php echo form_input($score1);?></div>
        </div>

        <div class="control-group">
            <?php echo lang('book_score2_label', 'score2', 'control-label');?>
            <div class="controls"><?php echo form_input($score2);?></div>
        </div>

        <div class="control-group">
            <div class="controls">
                <p>
                    <label class="checkbox">
                        <?php echo lang('book_provisional_label');?>
                        <?php echo form_checkbox($provisional);?>
                    </label>
                </p>
                <p>
                    <label class="checkbox">
                        <?php echo lang('book_paid_label');?>
                        <?php echo form_checkbox($paid);?>
                    </label>
                </p>
                <p>
                    <label class="checkbox">
                        <?php echo lang('book_invoiced_label');?>
                        <?php echo form_checkbox($invoiced);?>
                    </label>
                </p>
            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend><?php echo lang('book_repeats_title') ?></legend>
        <div class="control-group">
            <?php echo lang('book_repeats_label', 'repeats', 'control-label');?>
            <div class="controls"><?php echo form_dropdown($repeats['name'], $repeats['options'], $repeats['value'], $repeats['attr']);?></div>
        </div>

        <div class="control-group">
            <?php echo lang('book_repeats_every_label', 'repeats_every', 'control-label');?>
            <div class="controls"><?php echo form_dropdown($repeats_every['name'], $repeats_every['options'], $repeats_every['value'], $repeats_every['attr']);?></div>
        </div>

        <div id="repeats_by" class="control-group">
            <?php echo lang('book_repeats_by_label', 'repeats_by', 'control-label');?>
            <div class="controls">
                <label class="radio inline">
                    <?php echo form_radio($repeat_by_month);?>
                    <?php echo lang('book_repeat_by_month_label');?>
                </label>
                <label class="radio inline">
                    <?php echo form_radio($repeat_by_week);?>
                    <?php echo lang('book_repeat_by_week_label');?>
                </label>
            </div>
        </div>

        <div id="repeats_on" class="control-group">
            <label class="control-label">
                <?php echo lang('book_repeats_on_label');?>
            </label>
            <div class="controls">
                <label class="checkbox inline">
                    <?php echo form_checkbox($repeats_on_mon);?>
                    <?php echo lang('book_repeats_on_mon_label');?>
                </label>
                <label class="checkbox inline">
                    <?php echo form_checkbox($repeats_on_tue);?>
                    <?php echo lang('book_repeats_on_tue_label');?>
                </label>
                <label class="checkbox inline">
                    <?php echo form_checkbox($repeats_on_wed);?>
                    <?php echo lang('book_repeats_on_wed_label');?>
                </label>
                <label class="checkbox inline">
                    <?php echo form_checkbox($repeats_on_thu);?>
                    <?php echo lang('book_repeats_on_thu_label');?>
                </label>
                <label class="checkbox inline">
                    <?php echo form_checkbox($repeats_on_fri);?>
                    <?php echo lang('book_repeats_on_fri_label');?>
                </label>
                <label class="checkbox inline">
                    <?php echo form_checkbox($repeats_on_sat);?>
                    <?php echo lang('book_repeats_on_sat_label');?>
                </label>
                <label class="checkbox inline">
                    <?php echo form_checkbox($repeats_on_sun);?>
                    <?php echo lang('book_repeats_on_sun_label');?>
                </label>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label">Repeat ends</label>
            <div class="controls">
                <p><label class="radio">
                    <?php echo lang('book_repeat_ends_never_label');?>
                    <?php echo form_radio($repeat_ends_never);?>
                </label></p>
                <p><label class="radio">
                    <?php echo lang('book_repeat_ends_after_label');?>
                    <?php echo form_radio($repeat_ends_after);?>
                    <?php echo form_input($repeat_ends_after_occurences); ?>
                </label></p>
                <p><label class="radio">
                    <?php echo lang('book_repeat_ends_on_label');?>
                    <?php echo form_radio($repeat_ends_on);?>
                    <?php echo form_input($repeat_ends_on_date); ?>
                </label></p>
            </div>
        </div>
    </fieldset>

    <div class="control-group">
        <div class="controls">
            <?php echo form_button($save) ?>
            <?php echo form_button($cancel) ?>
            <!-- only show delete button if we are editing -->
            <?php if($this->uri->segment(2) == 'edit'): ?>
                <?php echo form_button($delete) ?>
            <?php endif; ?>
        </div>
    </div>
    
    <?php echo form_hidden($booking_id) ?>

    <?php echo form_close();?>
</div>