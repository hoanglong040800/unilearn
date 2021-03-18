<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 0 );
}

/**
 * Can the activity be edited now or has time passed?
 *
 * @param BP_Activity_Activity $activity activity object.
 *
 * @return boolean
 */
function bp_editable_activity_activity_has_remaining_time( $activity ) {

	// seconds.
	$allowed_time = bp_editable_activity_get_setting( 'activity_allowed_time' ) * 60;
	// if $allowed_time = 0, It means infinite time.
	if ( is_super_admin() || ! $allowed_time ) {
		// no limit on time.
		return true;
	}
	// other wise check time.
	$record_time = strtotime( $activity->date_recorded );

	// check for allowed time.
	$now = strtotime( bp_core_current_time() );

	if ( $now < $record_time + $allowed_time ) {
		return true;
	}

	return false;
}

/**
 * Is this user activity?
 *
 * @param BP_Activity_Activity $activity activity object.
 * @param int                  $user_id user id.
 *
 * @return boolean
 */
function bp_editable_activity_is_user_activity( $activity, $user_id = 0 ) {

	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	if ( $activity->user_id === $user_id ) {
		return true;
	}

	return false;

}

/**
 * Can the User edit this activity?
 *
 * @param BP_Activity_Activity $activity activity object.
 *
 * @return boolean
 */
function bp_editable_activity_can_edit_activity( $activity ) {
	return apply_filters( 'bp_editable_activity_can_edit_activity', bp_editable_activity_has_activity_edit_cap( $activity ), $activity );
}

/**
 * Does the current user has activity editing capability?
 *
 * @param BP_Activity_Activity $activity activity object.
 *
 * @return bool
 */
function bp_editable_activity_has_activity_edit_cap( $activity ) {

	if ( is_super_admin() ) {
		return true;
	}

	// if we are here, let us check for the ownership.
	if ( ! bp_editable_activity_is_user_activity( $activity ) ) {
		return false;
	}

	return true;
}

/**
 * Can the comment be edited now or has time passed?
 *
 * @param BP_Activity_Activity $comment comment object.
 *
 * @return boolean
 */
function bp_editable_activity_comment_has_remaining_time( $comment ) {

	// seconds.
	$allowed_time = bp_editable_activity_get_setting( 'comment_allowed_time' ) * 60;

	if ( is_super_admin() || ! $allowed_time ) {
		return true;// no limit on time.
	}

	// other wise check time.
	$record_time = strtotime( $comment->date_recorded );

	// check for allowed time.
	$now = strtotime( bp_core_current_time() );

	if ( $now < $record_time + $allowed_time ) {
		return true;
	}

	return false;
}

/**
 * Is this User comment?
 *
 * @param BP_Activity_Activity $comment comment object.
 * @param int                  $user_id user id.
 *
 * @return boolean
 */
function bp_editable_activity_is_user_comment( $comment, $user_id = false ) {
	return bp_editable_activity_is_user_activity( $comment, $user_id );
}

/**
 * Can current user comment on this
 *
 * @param BP_Activity_Activity $comment comment object.
 *
 * @return boolean
 */
function bp_editable_activity_can_edit_comment( $comment ) {
	return apply_filters( 'bp_editable_activity_can_edit_comment', bp_editable_activity_has_comment_edit_cap( $comment ), $comment );
}


/**
 * Can current user comment on this
 *
 * @param BP_Activity_Activity $comment comment object.
 *
 * @return boolean
 */
function bp_editable_activity_has_comment_edit_cap( $comment ) {

	if ( is_super_admin() ) {
		return true;
	}

	if ( empty( $comment ) ) {
		return false;
	}

	if ( ! bp_editable_activity_is_user_comment( $comment ) ) {
		return false;
	}

	return true;
}

/**
 * Check if the given type is allowed to be editable
 *
 * @param BP_Activity_Activity $activity activity object.
 *
 * @return boolean
 */
function bp_editable_is_editable_activity( $activity ) {
	// by default allow activity update/ activity comment.
	$allowed = apply_filters( 'bp_editable_activity_allowed_type', array( 'activity_update', 'activity_comment' ) );

	if ( in_array( $activity->type, $allowed ) ) {
		return true;
	}

	return false;
}

/**
 * Get individual settings
 *
 * @param string $option_name option name.
 *
 * @return string
 */
function bp_editable_activity_get_setting( $option_name ) {

	$default  = array(
		'allow_activity_editing' => 'yes',
		'allow_comment_editing'  => 'yes',
		'activity_allowed_time'  => 10,// minutes.
		'comment_allowed_time'   => 10,
		'keep_log'               => 'yes',

	);

	$settings = bp_get_option( 'bp-editable-activity', $default );

	if ( isset( $settings[ $option_name ] ) ) {
		return $settings[ $option_name ];
	}

	return '';
}
