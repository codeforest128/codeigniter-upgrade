<div class="span9">

    <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal'));?>

    <?php if(count($bookings) > 0): ?>

        <h1><?php echo lang('search_results_title') ?></h1>

        <fieldset>
            <table cellpadding="0" cellspacing="10" width="100%" class="table">
                <tr>
                    <th><?php echo lang('search_results_title_heading');?></th>
                    <th><?php echo lang('search_results_start_date_heading');?></th>
                    <th><?php echo lang('search_results_time_heading');?></th>
                    <th><?php echo lang('search_results_session_heading');?></th>
                    <th><?php echo lang('search_results_sheet_heading');?></th>
                    <th><?php echo lang('search_results_status_heading');?></th>
                    <th><?php echo lang('search_results_team1_heading');?></th>
                    <th><?php echo lang('search_results_score1_heading');?></th>
                    <th><?php echo lang('search_results_team2_heading');?></th>
                    <th><?php echo lang('search_results_score2_heading');?></th>
                    <th><?php echo lang('search_results_payment_heading');?></th>
                    <th><?php echo lang('search_results_edit') ?></th>
                    <th><?php echo lang('search_results_delete') ?></th>
                </tr>
                <?php foreach ($bookings as $booking):?>
                    <tr>
                        <td><?php echo $booking->title ;?></td>
                        <td><?php echo date('d/m/Y', $booking->start_date) ;?></td>
                        <td><?php echo $booking->start_time . ' - ' . $booking->end_time ?></td>
                        <td><?php echo $booking->session_str ?></td>
                        <td><?php echo $booking->sheet ?></td>
                        <td><?php echo $booking->provisional == 0 ? lang('search_results_confirmed') : lang('search_results_provisional') ?></td>
                        <td><?php echo $booking->team_name_1 ?></td>
                        <td><?php echo $booking->score_1 ?></td>
                        <td><?php echo $booking->team_name_2 ?></td>
                        <td><?php echo $booking->score_2 ?></td>
                        <td>
                            <?php if($booking->paid == "1"): ?>
                                <?php echo lang('search_results_paid') ?>   
                            <?php elseif($booking->invoiced == "1"): ?>
                                <?php echo lang('search_results_invoiced') ?>
                            <?php else: ?>
                                <?php echo lang('search_results_no_payment_or_invoice') ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a class="btn-link" href="<?php echo site_url('bookings/edit/' . $booking->id) ?>">
                                <?php echo lang('search_results_edit') ?>
                            </a>
                        </td>
                        <td>
                            <a class="btn-link" href="<?php echo site_url('bookings/delete/' . $booking->id) ?>">
                                <?php echo lang('search_results_delete') ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach;?>
            </table>
            <div class="control-group">
                <?php echo form_button($pdf) ?>
                <?php echo form_button($csv) ?>
                <?php echo form_button($print) ?>
                <a class="btn" href="#search"><?php echo lang('search_again') ?></a>
            </div>
        </fieldset>

        <hr />

    <?php endif; ?>

    <?php if(count($bookings) == 0 && isset($_POST['search'])): ?>
        <h1><?php echo lang('search_results_title') ?></h1>
        <?php echo lang('search_no_results') ?>
    <?php endif; ?>

    <h1 id="search"><?php echo lang('search_title') ?></h1>

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

    <fieldset>
        <div class="control-group">
            <?php echo lang('book_title_label', 'title', 'control-label');?>
            <div class="controls"><?php echo form_input($title);?></div>
        </div>

        <div class="control-group<?php echo form_error('start_date') != '' ? ' error' : '' ?>">
            <?php echo lang('book_sdate_label', 'start_date', 'control-label');?>
            <div class="controls">
                <div class="input input-append">
                    <?php echo form_input($start_date);?>
                    <span class="add-on"><i class="icon-calendar"></i></span>
                </div>
            </div>
        </div>

        <div class="control-group<?php echo form_error('end_date') != '' ? ' error' : '' ?>">
            <?php echo lang('book_edate_label', 'end_date', 'control-label');?>
            <div class="controls">
                <div class="input input-append">
                    <?php echo form_input($end_date);?>
                    <span class="add-on"><i class="icon-calendar"></i></span>
                </div>
            </div>
        </div>

        <div class="control-group">
            <?php echo lang('book_start_time_label', 'start_time_hours', 'control-label');?>
            <div class="controls">
                <?php echo form_dropdown($start_time_hours['name'], $start_time_hours['options'], $start_time_hours['value'], $start_time_hours['attr']);?>
                <?php echo form_dropdown($start_time_mins['name'], $start_time_mins['options'], $start_time_mins['value'], $start_time_mins['attr']);?>
            </div>
        </div>

        <div class="control-group">
            <?php echo lang('book_end_time_label', 'end_time_hours', 'control-label');?>
            <div class="controls">
                <?php echo form_dropdown($end_time_hours['name'], $end_time_hours['options'], $end_time_hours['value'], $end_time_hours['attr']);?>
                <?php echo form_dropdown($end_time_mins['name'], $end_time_mins['options'], $end_time_mins['value'], $end_time_mins['attr']);?>
            </div>
        </div>

        <div class="control-group">
            <?php echo lang('session_session_number_label', 'session', 'control-label');?>
            <div class="controls"><?php echo form_dropdown($session_number['name'], $session_number['options'], $session_number['value'], $session_number['attr']);?></div>
        </div>

        <div class="control-group">
            <?php echo lang('book_sheet_label', 'sheet', 'control-label');?>
            <div class="controls"><?php echo form_dropdown($sheet['name'], $sheet['options'], $sheet['value'], $sheet['attr']);?></div>
        </div>

        <div class="control-group">
            <?php echo lang('search_team_label', 'title', 'control-label');?>
            <div class="controls"><?php echo form_input($team);?></div>
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

        <div class="control-group">
            <div class="controls">
                <?php echo form_button($search) ?>
            </div>
        </div>
    </fieldset>

    <?php echo form_close();?>
</div>