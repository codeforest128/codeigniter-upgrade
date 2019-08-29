<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//sidebar nav links
$lang['ice_diary_link'] = 'Ice diary';
$lang['ice_availability_link'] = 'Advanced search';
$lang['user_management_link'] = 'User management';
$lang['session_management_link'] = 'Session management';
$lang['approval_link'] = 'Booking approval';

//sidebar login
$lang['login_feature_title'] = 'Login';
$lang['login_feature_welcome'] = 'Welcome';
$lang['login_feature_logout'] = 'Logout';
$lang['logout'] = 'Logout';
$lang['my_account'] = 'My Account';

//diary
$lang['diary_book_now_link'] = 'Book now';
$lang['diary_booked_link'] = 'Edit booking';
$lang['diary_provisionally_booked_link'] = 'Approve/Edit booking';
$lang['diary_click_to_edit'] = '(click to edit)';
$lang['diary_previous_week'] = '&laquo; Previous week';
$lang['diary_today'] = 'Today';
$lang['diary_next_week'] = 'Next week &raquo;';

// Calendar
$lang['calendar_submit'] = 'Submit';

// Book an event
$lang['book_event_title'] = 'Book a session';
$lang['book_event_details_title'] = 'Booking detais';
$lang['book_title_label'] = 'Title';
$lang['book_sdate_label'] = 'Start date';
$lang['book_edate_label'] = 'End date';
$lang['book_start_time_label'] = 'Start time';
$lang['book_end_time_label'] = 'End time';
$lang['book_session_label'] = 'Session';
$lang['book_sheet_label'] = 'Sheet';
$lang['book_team1_label'] = 'Team/Individual name 1';
$lang['book_team2_label'] = 'Team/Individual name 2';
$lang['book_score1_label'] = 'Score 1';
$lang['book_score2_label'] = 'Score 2';
$lang['book_provisional_label'] = 'Provisional booking';
$lang['book_paid_label'] = 'Paid';
$lang['book_invoiced_label'] = 'Invoiced';
$lang['book_repeats_title'] = 'Repeat';
$lang['book_repeats_label'] = 'Repeats';
$lang['book_repeats_every_label'] = 'Repeat every';
$lang['book_repeats_on_label'] = 'Repeat on';
$lang['book_repeats_by_label'] = 'Repeat by';
$lang['book_repeat_by_month_label'] = 'Day of the month';
$lang['book_repeat_by_week_label'] = 'Day of the week';
$lang['book_repeats_on_mon_label'] = 'M';
$lang['book_repeats_on_tue_label'] = 'T';
$lang['book_repeats_on_wed_label'] = 'W';
$lang['book_repeats_on_thu_label'] = 'T';
$lang['book_repeats_on_fri_label'] = 'F';
$lang['book_repeats_on_sat_label'] = 'S';
$lang['book_repeats_on_sun_label'] = 'S';
$lang['book_repeat_ends_label'] = 'Repeat ends';
$lang['book_repeat_ends_never_label'] = 'Never';
$lang['book_repeat_ends_on_label'] = 'On';
$lang['book_repeat_ends_after_label'] = 'After';
$lang['book_save_button'] = 'Save';
$lang['book_cancel_button'] = 'Cancel';
$lang['book_delete_button'] = 'Delete';
$lang['book_repeat_warning'] = '<strong>Warning!</strong> It is not possible to determine duplicate bookings with repeats so be careful not to override another event';

$lang['book_success'] = 'Booking was saved successfully';
$lang['book_failure'] = 'Booking could not be saved at this time';
$lang['book_duplicate_found'] = 'This booking clashes with another, please select a different date, session  or sheet';

$lang['delete_booking_success'] = 'Booking was deleted successfully';
$lang['delete_booking_failure'] = 'Booking could not be deleted at this time';

$lang['update_booking_success'] = 'Booking was successfully updated';
$lang['update_booking_failure'] = 'Booking could not be updated, this could be becauase nothing has changed';

//approve bookings
$lang['booking_approval_title'] = 'Bookings awaiting approval';
$lang['booking_approval_no_bookings'] = 'There are currently no bookings awaiting approval';
$lang['booking_approval_booking_title'] = 'Booking title';
$lang['booking_approval_username'] = 'Booked by';
$lang['booking_approval_booking_date'] = 'Booking date';
$lang['booking_approval_session'] = 'Session';
$lang['booking_approval_sheet'] = 'Sheet';
$lang['booking_approval_approve'] = 'Approve';
$lang['booking_approval_decline'] = 'Decline';
$lang['booking_approval_success'] = 'Booking successfully approved';
$lang['booking_approval_failure'] = 'Booking could not be approved at this time';
$lang['booking_approve_email_subject'] = 'Your booking at the Inverness Ice Centre has been approved';
$lang['booking_approve_email_message'] = '<p>Your booking \'{title}\' on {date} at {time} has been approved</p>';
$lang['booking_decline_email_subject'] = 'Your booking at the Inverness Ice Centre has been declined';
$lang['booking_decline_email_message'] = '<p>Your booking \'{title}\' on {date} at {time} has been declined</p>';
$lang['booking_decline_success'] = 'Booking was successfully declined, the creator has been notified';
$lang['booking_decline_failure'] = 'Booking could not be declined at this time';

// Cancel a booking
$lang['booking_cancellation_email_subject'] = 'A slot at the Inverness Ice Centre has opened up';
$lang['booking_cancellation_email_message'] = '<p>Dear {first_name}</p>
                                               <p>There has been a cancellation at Inverness Ice Centre and the following session is now available to be booked.</p>
                                               <p>{date}<br />{start_time} - {end_time}</p>
                                               <p>Please contact us or use our online Ice Diary to make a provisional booking.</p>
                                               <p>Regards<br />
                                               Inverness Ice Centre</p>
                                               <p><small>To <a href="{unsubscribe}">unsubscribe</a> to these emails, log in to the website, visit my account and de-select Mailing List</small></p>';

// Notify admin of booking
$lang['booking_notify_admin_email_subject'] = 'A slot at the Inverness Ice Centre has been provisionally booked';
$lang['booking_notify_admin_email_message'] = '<p>The user {user} has provisionally booked a slot at the Inverness Ice Centre at:</p>
                                               <p>{date}<br />{start_time} - {end_time}</p>
                                               <p>To approve or decline this email, please visit the <a href="{link}">Booking Approval page</a> online</p>
                                               <p><small>You are receiving this email automatically because you are an administrator at the Inverness Ice Centre</small></p>';



// Book an event - Validation
$lang['book_validation_title_label'] = 'Title';
$lang['book_validation_sdate_label'] = 'Start date';
$lang['book_validation_edate_label'] = 'End date';
$lang['book_validation_session_label'] = 'Session';
$lang['book_validation_sheet_label'] = 'Sheet';
$lang['book_validation_team1_label'] = 'Team/Individual name 1';
$lang['book_validation_team2_label'] = 'Team/Individual name 2';
$lang['book_validation_score1_label'] = 'Score 1';
$lang['book_validation_score2_label'] = 'Score 2';
$lang['book_validation_provisional_label'] = 'Provisional booking';
$lang['book_validation_paid_label'] = 'Paid';
$lang['book_validation_invoiced_label'] = 'Invoiced';
$lang['book_validation_repeats_end_after_date_label'] = 'Repeat ends on date';
$lang['book_validation_repeats_on_occurences_label'] = 'Repeat ends after';

// Session
$lang['session_title'] = 'Create a session';
$lang['session_date_label'] = 'Session date';
$lang['session_start_time_label'] = 'Session start time';
$lang['session_end_time_label'] = 'Session end time';
$lang['session_session_number_label'] = 'Session number';
$lang['session_save_button'] = $lang['book_save_button'];
$lang['session_cancel_button'] = $lang['book_cancel_button'];
$lang['session_delete_button'] = $lang['book_delete_button'];

$lang['edit_session_failure'] = 'Session has been successfully updated';
$lang['edit_session_failure'] = 'Session could not be updated at this time, this could be because nothing has changed';
$lang['session_duplicate_found'] = 'This session clashes with another, please select a different date or session number';

$lang['add_session_success'] = 'Session was successfully added';
$lang['add_session_failure'] = 'Session could not be added at this time';

// Session management
$lang['session_management_title'] = 'Session management';
$lang['session_management_date'] = 'Date';
$lang['session_management_start_time'] = 'Start time';
$lang['session_management_end_time'] = 'End time';
$lang['session_management_session_number'] = 'Session number';
$lang['session_management_edit'] = 'Edit';
$lang['session_management_delete'] = 'Delete';
$lang['session_management_add'] = 'Add a session';
$lang['session_management_no_sessions'] = 'No custom session times could be found';
$lang['delete_session_success'] = 'Session was successfully deleted';
$lang['delete_session_failure'] = 'Session could not be deleted at this time';

$lang['session_validation_date_label'] = 'date';
$lang['session_validation_sdate_label'] = 'start date';
$lang['session_validation_session_number_label'] = 'session number';

// Search
$lang['search_title'] = 'Search';
$lang['search_button'] = 'Search';
$lang['search_no_results'] = '<p>No search results were found</p>';
$lang['search_team_label'] = 'Team/Individual name';
$lang['search_validation_edate_label'] = 'end date';
$lang['search_results_title'] = 'Search Results';
$lang['search_results_title_heading'] = 'Title';
$lang['search_results_start_date_heading'] = 'Start Date';
$lang['search_results_time_heading'] = 'Time';
$lang['search_results_session_heading'] = 'Sessions';
$lang['search_results_sheet_heading'] = 'Sheet';
$lang['search_results_status_heading'] = 'Status';
$lang['search_results_payment_heading'] = 'Paid';
$lang['search_results_team1_heading'] = 'Team/<br />Individual 1';
$lang['search_results_score1_heading'] = 'Score';
$lang['search_results_team2_heading'] = 'Team/<br />Individual 2';
$lang['search_results_score2_heading'] = 'Score';
$lang['search_results_confirmed'] = 'Confirmed';
$lang['search_results_provisional'] = 'Provisional';
$lang['search_results_paid'] = 'Paid';
$lang['search_results_invoiced'] = 'Invoiced';
$lang['search_results_no_payment_or_invoice'] = 'None';
$lang['search_results_edit'] = 'Edit';
$lang['search_results_delete'] = 'Delete';
$lang['search_pdf'] = '<i class="icon-download-alt"></i> Save as PDF';
$lang['search_csv'] = '<i class="icon-th"></i> Save as CSV';
$lang['search_csv_filename'] = 'Inverness_Ice_Centre_Search_Results_{date}_{terms}.csv';
$lang['search_print'] = '<i class="icon-print"></i> Print';
$lang['search_again'] = '<i class="icon-arrow-down"></i> Search again';
$lang['search_pdf_title'] = 'Inverness Ice Diary Search Results';
$lang['search_generated_on'] = 'Search generated on: ';
$lang['search_terms'] = 'Search terms used; ';

// Key
$lang['key_booked'] = 'Booked';
$lang['key_booked_provisional'] = 'Provisionally Booked';
$lang['key_available'] = 'Available';
$lang['key_how_to_book'] = '<p>In order to create a booking, you must first be logged in, and then click on an available slot</p>';

$lang['custom_validation_valid_date'] = '%s is not a valid date form, please use the format dd/mm/yyyy';
$lang['custom_validation_is_valid'] = 'If a {field} is specified, you must select an %s';
$lang['custom_validation_repeat_ends_occurences'] = 'If a repeat end is set to \'After\', a number of occurrences must be specified';
$lang['custom_validation_repeat_ends_date'] = 'If a repeat end is set to \'On\', a end date must be specified';