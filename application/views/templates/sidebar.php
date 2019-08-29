<div class="span3">
    <div id="sidebar" data-spy="affix" data-offset-top="200">

        <div class="sidebar-nav">
            <ul class="nav nav-tabs nav-stacked">
                <li class="active"><a href="<?php echo site_url('/') ?>"><i class="icon-calendar"></i><?php echo lang('ice_diary_link') ?></a></li>
                <?php if(isset($is_logged) && $is_logged && $is_admin): ?>
                    <li><a href="<?php echo site_url('search') ?>"><i class="icon-search"></i><?php echo lang('ice_availability_link') ?></a></li>
                    <li><a href="<?php echo site_url('sessions') ?>"><i class="icon-align-justify"></i><?php echo lang('session_management_link') ?></a></li>
                    <li>
                        <a href="<?php echo site_url('bookings/approval') ?>">
                            <i class="icon-ok"></i>
                            <?php echo lang('approval_link') ?>
                            <?php if($approval_count > 0): ?>
                                <span class="badge badge-important pull-right"><?php echo $approval_count ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li><a href="<?php echo site_url('auth') ?>"><i class="icon-user"></i><?php echo lang('user_management_link') ?></a></li>
                <?php endif; ?>
            </ul>
        </div><!--/.sidebar-nav -->

        <?php if(isset($calendar)): ?>
            <div class="well">
                <?php echo form_open('diary/submit_date', array('class' => 'form-inline', 'id' => 'calendar-quickjump'));?>
                    <?php echo form_dropdown($months['name'], $months['options'], $months['value'], $months['attr']) ?>
                    <?php echo form_dropdown($years['name'], $years['options'], $years['value'], $years['attr']) ?>
                    <?php echo form_button($submit) ?>
                <?php echo form_close() ?>
                <?php echo($calendar) ?>
            </div>
            <div class="well">
                <ul class="nav key">
                    <li><span class="booked"></span><?php echo lang('key_booked') ?></li>
                    <li><span class="booked-provisional"></span><?php echo lang('key_booked_provisional') ?></li>
                    <li><span class="available"></span><?php echo lang('key_available') ?></li>
                </ul>
                <?php echo lang('key_how_to_book') ?>
            </div>
        <?php endif; ?>

        <?php if(strpos($this->uri->uri_string(), 'auth/login') === FALSE): ?>
            <div class="well">
                <?php if(isset($is_logged) && $is_logged): ?>
                    <p><?php echo lang('login_feature_welcome') . ' ' . $current_user->first_name . ' ' . $current_user->last_name ?></p>
                    <a href="<?php echo site_url('auth/edit_user/' . $current_user ->id) ?>"><?php echo lang('my_account') ?></a> |
                    <a href="<?php echo site_url('auth/logout') ?>"><?php echo lang('logout') ?></a>
                <?php else: ?>
                    <h2><?php echo lang('login_feature_title') ?></h2>
                    <?php echo form_open("auth/login");?>

                    <?php echo form_input(array('id' => 'identity', 'name' => 'identity', 'type' => 'text', 'placeholder' => 'Email/Username')) ?>

                    <?php echo form_input(array('id' => 'password', 'name' => 'password', 'type' => 'password', 'placeholder' => 'Password')) ?>

                    <p>
                        <label class="checkbox">
                            <?php echo lang('login_remember_label');?>
                            <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
                        </label>
                    </p>
                    <?php echo form_button(array('name' => 'submit', 'content' => 'Submit', 'class' => 'btn', 'type' => 'submit')) ?>

                    <?php echo form_close(); ?>

                    <p><a href="<?php echo site_url('auth/forgot_password') ?>"><?php echo lang('login_forgot_password');?></a></p>
                    <p><a href="<?php echo site_url('auth/register_user') ?>"><?php echo lang('login_create_account');?></a></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
