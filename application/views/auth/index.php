<div class="span9">
    <h1><?php echo lang('index_heading');?></h1>
    <p><?php echo lang('index_subheading');?></p>

    <?php if($message): ?>
        <div class="alert alert-success fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $message;?>
        </div>
    <?php endif; ?>

    <table cellpadding="0" cellspacing="10" width="100%" class="table">
        <tr>
            <th><?php echo lang('index_fname_th');?></th>
            <th><?php echo lang('index_lname_th');?></th>
            <th><?php echo lang('index_email_th');?></th>
            <th><?php echo lang('index_groups_th');?></th>
            <th><?php echo lang('index_status_th');?></th>
            <th><?php echo lang('index_action_th');?></th>
            <th><?php echo lang('index_action_th');?></th>
        </tr>
        <?php foreach ($users as $user):?>
            <tr>
                <td><?php echo $user->first_name;?></td>
                <td><?php echo $user->last_name;?></td>
                <td><?php echo $user->email;?></td>
                <td>
                    <?php foreach ($user->groups as $group):?>
                        <?php echo anchor("auth/edit_group/".$group->id, $group->name) ;?><br />
                    <?php endforeach?>
                </td>
                <td><?php echo ($user->active) ? anchor("auth/deactivate/".$user->id, lang('index_active_link')) : anchor("auth/activate/". $user->id, lang('index_inactive_link'));?></td>
                <td><?php echo anchor("auth/edit_user/".$user->id, 'Edit') ;?></td>
                <td><?php echo anchor("auth/delete_user/".$user->id, 'Delete') ;?></td>
            </tr>
        <?php endforeach;?>
    </table>

    <p><?php echo anchor('auth/create_user', lang('index_create_user_link'))?> | <?php echo anchor('auth/create_group', lang('index_create_group_link'))?></p>
</div>