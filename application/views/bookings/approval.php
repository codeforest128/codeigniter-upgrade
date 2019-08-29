<div class="span9">
    <h1><?php echo lang('booking_approval_title') ?></h1>

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
    
    <?php if(is_array($bookings) && count($bookings) > 0): ?>
        <table cellpadding="0" cellspacing="10" width="100%" class="table">
            <tr>
                <th><?php echo lang('booking_approval_booking_title');?></th>
                <th><?php echo lang('booking_approval_username');?></th>
                <th><?php echo lang('booking_approval_booking_date');?></th>
                <th><?php echo lang('booking_approval_session');?></th>
                <th><?php echo lang('booking_approval_sheet');?></th>
                <th><?php echo lang('booking_approval_approve');?></th>
                <th><?php echo lang('booking_approval_decline');?></th>
            </tr>
            <?php foreach ($bookings as $booking):?>
                <tr>
                    <td><?php echo $booking->title;?></td>
                    <td><?php echo $booking->user->first_name . ' ' . $booking->user->last_name;?></td>
                    <td><?php echo date('d/m/Y', $booking->start_date) ?></td>
                    <td><?php echo $booking->session ?></td>
                    <td><?php echo $booking->sheet ?></td>
                    <td><button class="btn-link" type="submit" name="approve" value="<?php echo $booking->id ?>"><?php echo lang('booking_approval_approve') ?></button></td>
                    <td><button class="btn-link" type="submit" name="decline" value="<?php echo $booking->id ?>"><?php echo lang('booking_approval_decline') ?></button></td>
                </tr>
            <?php endforeach;?>
        </table>
    <?php else: ?>
        <p><?php echo lang('booking_approval_no_bookings') ?></p>
    <?php endif; ?>

    <?php echo form_close();?>
</div>