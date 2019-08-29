<div class="span9">

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

    <div class="diary-grid clearfix">
        <div class="row-fluid">
            <div class="span4"><a href="<?php echo site_url('diary/index/' . $previous) ?>"><?php echo lang('diary_previous_week') ?></a></div>
            <div class="span4 text-center"><a href="<?php echo site_url('diary/index') ?>"><?php echo lang('diary_today') ?></a></div>
            <div class="span4 text-right"><a href="<?php echo site_url('diary/index/'. $next) ?>"><?php echo lang('diary_next_week') ?></a></div>
        </div>

        <?php for($i = 0; $i < 7; $i++): ?>
            <?php $date_loop = strtotime('+' . $i . ' days', strtotime($grid_date)) ?>
            <?php $date_sessions = $sessions[date('Y-m-d', $date_loop)] ?>
            <?php $date_bookings = $bookings[date('Y-m-d', $date_loop)] ?>
            <dl class="diary-grid-day<?php echo date('Y-m-d', $date_loop) == $today ? ' today' : '' ?>">
                <dt><?php echo date('D - d M', $date_loop) ?></dt>
                <dd>
                    <ol>
                        <?php for($j = 1; $j <= 6; $j++): ?>
                        <li>
                            <small><?php echo date('h:ia', strtotime($date_sessions[$j]->session_start)) . ' - ' . date('h:ia', strtotime($date_sessions[$j]->session_end)) ?></small>

                            <dl class="clearfix">
                                <?php foreach($date_bookings[$j] as $sheet => $booking): ?>
                                <dt><?php echo $sheet ?></dt>
                                <dd class="<?php echo !is_null($booking) ? $booking->link_class : 'available' ?>">
                                    <?php if($is_logged): ?>
                                    <a <?php echo !is_null($booking) ?
                                        $is_admin ? 'href="' . $booking->link . '"' : '' :
                                        'href="' . site_url('bookings/add/' .
                                                        date('Y', $date_loop) . '/' .
                                                        date('m', $date_loop) . '/' .
                                                        date('d', $date_loop) . '/' .
                                                        $j . '/' .
                                                        $sheet . '"'
                                        ) ?>>
                                        <?php echo !is_null($booking) ? $booking->link_text : lang('diary_book_now_link') ?>
                                    </a>
                                    <?php else: ?>
                                    <a>
                                        <?php echo !is_null($booking) ? $booking->link_text : lang('diary_book_now_link') ?>
                                    </a>
                                    <?php endif; ?>
                                </dd>
                                <?php endforeach; ?>
                            </dl>
                        </li>
                        <?php endfor; ?>
                    </ol>
                </dd>
            </dl>
        <?php endfor; ?>
    </div>

</div>