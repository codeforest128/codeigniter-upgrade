<style type="text/css">
    body { font-family: sans-serif; }
    table th,
    table td { border-top: 1px solid #dddddd; }
</style>

<div class="span9">
    <h1><?php echo lang('search_pdf_title') ?></h1>
    <p><?php echo lang('search_generated_on') . date('d/m/Y H:i') ?></p>
    <p><?php echo lang('search_terms') . $terms_str ?></p>
    
    <?php if(count($bookings) > 0): ?>
        <table cellpadding="10" cellspacing="0" width="100%" class="table">
            <tr>
                <th align="left"><?php echo lang('search_results_title_heading');?></th>
                <th><?php echo lang('search_results_start_date_heading');?></th>
                <th><?php echo lang('search_results_time_heading');?></th>
                <th><?php echo lang('search_results_session_heading');?></th>
                <th><?php echo lang('search_results_sheet_heading');?></th>
                <th><?php echo lang('search_results_status_heading');?></th>
                <th><?php echo lang('search_results_payment_heading');?></th>
            </tr>
            <?php foreach ($bookings as $booking):?>
                <tr>
                    <td><?php echo $booking->title ;?></td>
                    <td align="center"><?php echo date('d/m/Y', $booking->start_date) ;?></td>
                    <td align="center"><?php echo $booking->start_time . ' - ' . $booking->end_time ?></td>
                    <td align="center"><?php echo $booking->session_str ?></td>
                    <td align="center"><?php echo $booking->sheet ?></td>
                    <td align="center"><?php echo $booking->provisional == 0 ? lang('search_results_confirmed') : lang('search_results_provisional') ?></td>
                    <td align="center"><?php echo $booking->paid == 1 ? lang('search_results_paid') : $booking->invoiced == 1 ? lang('search_results_invoiced') : lang('search_results_no_payment_or_invoice') ?></td>
                </tr>
            <?php endforeach;?>
        </table>
    <?php endif; ?>
</div>

<?php if(isset($print)): ?>
    <script type="text/javascript">
        window.print();
    </script>
<?php endif; ?>