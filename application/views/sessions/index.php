<div class="span9">
    <h1><?php echo lang('session_management_title') ?></h1>

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

    <?php if(is_array($sessions) && count($sessions) > 0): ?>
        <table cellpadding="0" cellspacing="10" width="100%" class="table">
            <tr>
                <th><?php echo lang('session_management_date');?></th>
                <th><?php echo lang('session_management_start_time');?></th>
                <th><?php echo lang('session_management_end_time');?></th>
                <th><?php echo lang('session_management_session_number');?></th>
                <th><?php echo lang('session_management_edit');?></th>
                <th><?php echo lang('session_management_delete');?></th>
            </tr>
            <?php foreach ($sessions as $session):?>
                <tr>
                    <td><?php echo date('d/m/Y', strtotime($session->session_start)) ;?></td>
                    <td><?php echo date('H:i', strtotime($session->session_start)) ?></td>
                    <td><?php echo date('H:i', strtotime($session->session_end)) ?></td>
                    <td><?php echo $session->session_number ?></td>
                    <td>
                        <a class="btn-link" href="<?php echo site_url('sessions/edit/' . $session->session_id) ?>">
                            <?php echo lang('session_management_edit') ?>
                        </a>
                    </td>
                    <td><button class="btn-link" type="submit" name="delete" value="<?php echo $session->session_id ?>"><?php echo lang('session_management_delete') ?></button></td>
                </tr>
            <?php endforeach;?>
        </table>
    <?php else: ?>
        <p><?php echo lang('session_management_no_sessions') ?></p>
    <?php endif; ?>

    <div class="pull-left">
        <a class="btn btn-primary" href="<?php echo site_url('sessions/add') ?>">
            <?php echo lang('session_management_add') ?>
        </a>
    </div>

    <?php echo form_close();?>
</div>