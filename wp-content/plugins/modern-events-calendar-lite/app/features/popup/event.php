<?php
/** no direct access **/
defined('MECEXEC') or die();

// MEC Settings
$settings = $this->main->get_settings();

// Post Object
$post = new stdClass();
$post->ID = 0;

// Features
$feature_colors = new MEC_feature_colors();

$allday = 0;
$start_date = date('Y-m-d', strtotime('Tomorrow'));
$start_time_hour = 8;
$start_time_minutes = 0;
$start_time_minutes = 'AM';
$end_date = $start_date;
$end_time_hour = 6;
$end_time_minutes = 0;
$end_time_ampm = 'PM';

$locations = get_terms('mec_location', array('orderby'=>'name', 'hide_empty'=>'0'));
$location_id = 1;
$dont_show_map = 1;

$organizers = get_terms('mec_organizer', array('orderby'=>'name', 'hide_empty'=>'0'));
$organizer_id = 1;
?>
<div id="mec_popup_event" class="lity-hide">
    <div class="mec-steps-container">
        <img src="<?php echo plugin_dir_url(__FILE__ ) . '../../../assets/img/popup/mec-logo.svg'; ?>" />
        <ul>
            <li class="mec-step mec-step-1"><span>1</span></li>
            <li class="mec-step mec-step-2"><span>2</span></li>
            <li class="mec-step mec-step-3"><span>3</span></li>
            <li class="mec-step mec-step-4"><span>4</span></li>
            <li class="mec-step mec-step-5"><span>5</span></li>
            <li class="mec-step mec-step-6"><span>6</span></li>
            <li class="mec-step mec-step-7"><span>7</span></li>
            <li class="mec-step mec-step-8"><span>8</span></li>
        </ul>
    </div>
    <div class="mec-steps-panel">
        <div id="mec_popup_event_form">
            <div class="mec-steps-content-container">
                <div class="mec-steps-header">
                    <div class="mec-steps-header-userinfo">
                        <?php $user = wp_get_current_user(); ?>
                        <span class="mec-steps-header-img"><img src="<?php echo esc_url(get_avatar_url($user->ID)); ?>" /></span>
                        <span class="mec-steps-header-name"><?php echo $user->display_name ; ?></span>
                        <span class="mec-steps-header-add-text"><?php esc_html_e('Adding an Event...', 'modern-events-calendar-lite') ?></span>
                    </div>
                    <div class="mec-steps-header-settings">
                        <a href="<?php echo admin_url('admin.php?page=MEC-settings'); ?>"><i class="mec-sl-settings"></i><?php esc_html_e('Settings', 'modern-events-calendar-lite'); ?></a>
                    </div>
                </div>
                <div class="mec-steps-content mec-steps-content-1">
                    <?php wp_nonce_field('mec_event_popup', '_mecnonce'); ?>
                    <input type="text" name="mec[title]" placeholder="<?php esc_attr_e('Event Name', 'modern-events-calendar-lite'); ?>" id="mec_event_name">
                    <p class="popup-sh-name-required"><?php esc_html_e('Event name is required', 'modern-events-calendar-lite'); ?></p>

                    <?php $feature_colors->meta_box_colors($post); ?>
                </div>
                <div class="mec-steps-content mec-steps-content-2">
                    <div id="mec_meta_box_date_form">
                        <div class="mec-title">
                            <span class="mec-dashicons dashicons dashicons-calendar-alt"></span>
                            <label for="mec_start_date"><?php _e('Start Date', 'modern-events-calendar-lite'); ?></label>
                        </div>
                        <div class="mec-form-row">
                            <div class="mec-col-4">
                                <input type="text" name="mec[date][start][date]" id="mec_start_date"
                                       value="<?php echo esc_attr($start_date); ?>"
                                       placeholder="<?php _e('Start Date', 'modern-events-calendar-lite'); ?>" autocomplete="off"/>
                            </div>
                            <div class="mec-col-6 mec-time-picker">
                                <?php
                                if (isset($settings['time_format']) and $settings['time_format'] == 24) :
                                    if ($start_time_ampm == 'PM' and $start_time_hour != 12) {
                                        $start_time_hour += 12;
                                    }
                                    if ($start_time_ampm == 'AM' and $start_time_hour == 12) {
                                        $start_time_hour += 12;
                                    }
                                    ?>
                                    <select name="mec[date][start][hour]" id="mec_start_hour">
                                        <?php for ($i = 0; $i <= 23; $i++) : ?>
                                            <option
                                                <?php
                                                if ($start_time_hour == $i) {
                                                    echo 'selected="selected"';
                                                }
                                                ?>
                                                    value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <span class="time-dv">:</span>
                                    <select name="mec[date][start][minutes]" id="mec_start_minutes">
                                        <?php for ($i = 0; $i <= 11; $i++) : ?>
                                            <option
                                                <?php
                                                if ($start_time_minutes == ($i * 5)) {
                                                    echo 'selected="selected"';
                                                }
                                                ?>
                                                    value="<?php echo($i * 5); ?>"><?php echo sprintf('%02d', ($i * 5)); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                <?php else : if($start_time_ampm == 'AM' and $start_time_hour == '0') $start_time_hour = 12; ?>
                                    <select name="mec[date][start][hour]" id="mec_start_hour">
                                        <?php for ($i = 1; $i <= 12; $i++) : ?>
                                            <option
                                                <?php
                                                if ($start_time_hour == $i) {
                                                    echo 'selected="selected"';
                                                }
                                                ?>
                                                    value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <span class="time-dv">:</span>
                                    <select name="mec[date][start][minutes]" id="mec_start_minutes">
                                        <?php for ($i = 0; $i <= 11; $i++) : ?>
                                            <option
                                                <?php
                                                if ($start_time_minutes == ($i * 5)) {
                                                    echo 'selected="selected"';
                                                }
                                                ?>
                                                    value="<?php echo($i * 5); ?>"><?php echo sprintf('%02d', ($i * 5)); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <select name="mec[date][start][ampm]" id="mec_start_ampm">
                                        <option
                                            <?php
                                            if ($start_time_ampm == 'AM') {
                                                echo 'selected="selected"';
                                            }
                                            ?>
                                                value="AM"><?php _e('AM', 'modern-events-calendar-lite'); ?></option>
                                        <option
                                            <?php
                                            if ($start_time_ampm == 'PM') {
                                                echo 'selected="selected"';
                                            }
                                            ?>
                                                value="PM"><?php _e('PM', 'modern-events-calendar-lite'); ?></option>
                                    </select>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mec-title">
                            <span class="mec-dashicons dashicons dashicons-calendar-alt"></span>
                            <label for="mec_end_date"><?php _e('End Date', 'modern-events-calendar-lite'); ?></label>
                        </div>
                        <div class="mec-form-row">
                            <div class="mec-col-4">
                                <input type="text" name="mec[date][end][date]" id="mec_end_date"
                                       value="<?php echo esc_attr($end_date); ?>" placeholder="<?php _e('End Date', 'modern-events-calendar-lite'); ?>"
                                       autocomplete="off"/>
                            </div>
                            <div class="mec-col-6 mec-time-picker">
                                <?php
                                if (isset($settings['time_format']) and $settings['time_format'] == 24) :
                                    if ($end_time_ampm == 'PM' and $end_time_hour != 12) {
                                        $end_time_hour += 12;
                                    }
                                    if ($end_time_ampm == 'AM' and $end_time_hour == 12) {
                                        $end_time_hour += 12;
                                    }
                                    ?>
                                    <select name="mec[date][end][hour]" id="mec_end_hour">
                                        <?php for ($i = 0; $i <= 23; $i++) : ?>
                                            <option
                                                <?php
                                                if ($end_time_hour == $i) {
                                                    echo 'selected="selected"';
                                                }
                                                ?>
                                                    value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <span class="time-dv">:</span>
                                    <select name="mec[date][end][minutes]" id="mec_end_minutes">
                                        <?php for ($i = 0; $i <= 11; $i++) : ?>
                                            <option
                                                <?php
                                                if ($end_time_minutes == ($i * 5)) {
                                                    echo 'selected="selected"';
                                                }
                                                ?>
                                                    value="<?php echo($i * 5); ?>"><?php echo sprintf('%02d', ($i * 5)); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                <?php else : if($end_time_ampm == 'AM' and $end_time_hour == '0') $end_time_hour = 12; ?>
                                    <select name="mec[date][end][hour]" id="mec_end_hour">
                                        <?php for ($i = 1; $i <= 12; $i++) : ?>
                                            <option
                                                <?php
                                                if ($end_time_hour == $i) {
                                                    echo 'selected="selected"';
                                                }
                                                ?>
                                                    value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <span class="time-dv">:</span>
                                    <select name="mec[date][end][minutes]" id="mec_end_minutes">
                                        <?php for ($i = 0; $i <= 11; $i++) : ?>
                                            <option
                                                <?php
                                                if ($end_time_minutes == ($i * 5)) {
                                                    echo 'selected="selected"';
                                                }
                                                ?>
                                                    value="<?php echo($i * 5); ?>"><?php echo sprintf('%02d', ($i * 5)); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <select name="mec[date][end][ampm]" id="mec_end_ampm">
                                        <option
                                            <?php
                                            if ($end_time_ampm == 'AM') {
                                                echo 'selected="selected"';
                                            }
                                            ?>
                                                value="AM"><?php _e('AM', 'modern-events-calendar-lite'); ?></option>
                                        <option
                                            <?php
                                            if ($end_time_ampm == 'PM') {
                                                echo 'selected="selected"';
                                            }
                                            ?>
                                                value="PM"><?php _e('PM', 'modern-events-calendar-lite'); ?></option>
                                    </select>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mec-form-row mec-all-day-event">
                            <input type="checkbox" name="mec[date][allday]" id="mec_allday" value="1"
                                   onchange="jQuery('.mec-time-picker').toggle();"/><label
                                   for="mec_allday"><?php _e('All Day Event', 'modern-events-calendar-lite'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="mec-steps-content mec-steps-content-3">
                    <div id="mec-location">
                        <div class="mec-form-row">
                            <select name="mec[location_id]" id="mec_popup_location_id" title="<?php echo esc_attr__($this->main->m('taxonomy_location', __('Location', 'modern-events-calendar-lite')), 'modern-events-calendar-lite'); ?>">
                                <option value="1"><?php _e('Hide location', 'modern-events-calendar-lite'); ?></option>
                                <option value="0" style="display: none;"><?php _e('Insert a new location', 'modern-events-calendar-lite'); ?></option>
                                <?php foreach($locations as $location): ?>
                                    <option <?php if($location_id == $location->term_id) echo 'selected="selected"'; ?> value="<?php echo $location->term_id; ?>"><?php echo $location->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="mec-tooltip">
                                <div class="box top">
                                    <h5 class="title"><?php _e('Location', 'modern-events-calendar-lite'); ?></h5>
                                    <div class="content"><p><?php esc_attr_e('Choose one of saved locations or insert new one.', 'modern-events-calendar-lite'); ?><a href="https://webnus.net/dox/modern-events-calendar/location/" target="_blank"><?php _e('Read More', 'modern-events-calendar-lite'); ?></a></p></div>
                                </div>
                                <i title="" class="dashicons-before dashicons-editor-help"></i>
                            </span>
                            <button type="button" id="mec_popup_add_location"><?php _e('Add Location'); ?></button>
                        </div>
                        <div id="mec_location_new_container">
                            <div class="mec-form-row">
                                <input type="text" name="mec[location][name]" id="mec_location_name" value="" placeholder="<?php _e('Location Name', 'modern-events-calendar-lite'); ?>" />
                                <div class="description"><?php _e('eg. City Hall', 'modern-events-calendar-lite'); ?></div>
                            </div>
                            <div class="mec-form-row">
                                <input type="text" name="mec[location][address]" id="mec_location_address" value="" placeholder="<?php _e('Event Location', 'modern-events-calendar-lite'); ?>" />
                                <div class="description"><?php _e('eg. City hall, Manhattan, New York', 'modern-events-calendar-lite'); ?></div>
                            </div>
                            <div class="mec-form-row mec-lat-lng-row">
                                <input class="mec-has-tip" type="text" name="mec[location][latitude]" id="mec_location_latitude" value="" placeholder="<?php _e('Latitude', 'modern-events-calendar-lite'); ?>" />
                                <input class="mec-has-tip" type="text" name="mec[location][longitude]" id="mec_location_longitude" value="" placeholder="<?php _e('Longitude', 'modern-events-calendar-lite'); ?>" />
                                <span class="mec-tooltip">
                                    <div class="box top">
                                        <h5 class="title"><?php _e('Latitude/Longitude', 'modern-events-calendar-lite'); ?></h5>
                                        <div class="content"><p><?php esc_attr_e('If you leave the latitude and longitude empty, Modern Events Calendar tries to convert the location address to geopoint, Latitude and Longitude are the units that represent the coordinates at geographic coordinate system. To make a search, use the name of a place, city, state, or address, or click the location on the map to find lat long coordinates.', 'modern-events-calendar-lite'); ?><a href="https://latlong.net" target="_blank"><?php _e('Get Latitude and Longitude', 'modern-events-calendar-lite'); ?></a></p></div>
                                    </div>
                                    <i title="" class="dashicons-before dashicons-editor-help"></i>
                                </span>
                            </div>
                        </div>
                        <div class="mec-form-row">
                            <input type="hidden" name="mec[dont_show_map]" value="0" />
                            <input type="checkbox" id="mec_location_dont_show_map" name="mec[dont_show_map]" value="1" <?php echo ($dont_show_map ? 'checked="checked"' : ''); ?> /><label for="mec_location_dont_show_map"><?php echo __("Don't show map in single event page", 'modern-events-calendar-lite'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="mec-steps-content mec-steps-content-4">
                    <div id="mec-organizer">
                        <div class="mec-form-row">
                            <select name="mec[organizer_id]" id="mec_popup_organizer_id" title="<?php echo esc_attr__($this->main->m('taxonomy_organizer', __('Organizer', 'modern-events-calendar-lite')), 'modern-events-calendar-lite'); ?>">
                                <option value="1"><?php _e('Hide organizer', 'modern-events-calendar-lite'); ?></option>
                                <option value="0" style="display: none;"><?php _e('Insert a new organizer', 'modern-events-calendar-lite'); ?></option>
                                <?php foreach($organizers as $organizer): ?>
                                    <option <?php if($organizer_id == $organizer->term_id) echo $selected = 'selected="selected"'; ?> value="<?php echo $organizer->term_id; ?>"><?php echo $organizer->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="mec-tooltip">
                                <div class="box top">
                                    <h5 class="title"><?php _e('Organizer', 'modern-events-calendar-lite'); ?></h5>
                                    <div class="content"><p><?php esc_attr_e('Choose one of saved organizers or insert new one below.', 'modern-events-calendar-lite'); ?><a href="https://webnus.net/dox/modern-events-calendar/organizer-and-other-organizer/" target="_blank"><?php _e('Read More', 'modern-events-calendar-lite'); ?></a></p></div>
                                </div>
                                <i title="" class="dashicons-before dashicons-editor-help"></i>
                            </span>
                            <button type="button" id="mec_popup_add_organizer"><?php _e('Add Organizer'); ?></button>
                        </div>
                        <div id="mec_organizer_new_container">
                            <div class="mec-form-row">
                                <div class="mec-col-6">
                                    <input type="text" name="mec[organizer][name]" id="mec_organizer_name" value="" placeholder="<?php _e('Name', 'modern-events-calendar-lite'); ?>" />
                                    <p class="description"><?php _e('eg. John Smith', 'modern-events-calendar-lite'); ?></p>
                                </div>
                                <div class="mec-col-6">
                                    <input type="text" name="mec[organizer][tel]" id="mec_organizer_contact" value="" placeholder="<?php esc_attr_e('Phone number.', 'modern-events-calendar-lite'); ?>" />
                                    <p class="description"><?php _e('eg. +1 (234) 5678', 'modern-events-calendar-lite'); ?></p>
                                </div>
                            </div>
                            <div class="mec-form-row">
                                <input type="text" name="mec[organizer][email]" id="mec_organizer_contact" value="" placeholder="<?php esc_attr_e('Email address.', 'modern-events-calendar-lite'); ?>" />
                                <p class="description"><?php _e('eg. john@smith.com', 'modern-events-calendar-lite'); ?></p>
                            </div>
                            <div class="mec-form-row">
                                <div class="mec-col-6">
                                    <input type="text" name="mec[organizer][url]" id="mec_organizer_url" value="" placeholder="<?php _e('Link to organizer page', 'modern-events-calendar-lite'); ?>" />
                                    <p class="description"><?php _e('eg. https://webnus.net', 'modern-events-calendar-lite'); ?></p>
                                </div>
                                <div class="mec-col-6">
                                    <div class="mec-form-row mec-thumbnail-row">
                                        <input type="hidden" name="mec[organizer][thumbnail]" id="mec_organizer_thumbnail" value="" />
                                        <button type="button" class="mec_organizer_upload_image_button button" id="mec_organizer_thumbnail_button"><?php echo __('Choose image', 'modern-events-calendar-lite'); ?></button>
                                    </div>
                                </div>
                            </div>
                            <div class="mec-form-row">
                                <div class="mec-col-6">
                                    <div id="mec_organizer_thumbnail_img"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mec-steps-content mec-steps-content-5">
                    <div class="mec-categories-tabs">
                        <ul>
                            <li class="mec-categories-tab-selected" data-type="all"><?php _e('All Categories', 'modern-events-calendar-lite'); ?></li>
                            <li data-type="popular"><?php _e('Most Used', 'modern-events-calendar-lite'); ?></li>
                        </ul>
                    </div>
                    <div class="mec-categories-tab-contents">
                        <ul>
                            <?php wp_terms_checklist(0, array(
                                'taxonomy' => 'mec_category',
                            )); ?>
                        </ul>
                    </div>
                    <div class="mec-categories-add-new">
                        <span id="mec_popup_new_category_button"><?php _e('Add New Category', 'modern-events-calendar-lite'); ?></span>
                        <input type="text" id="mec_popup_new_category" style="display: none;">
                    </div>
                </div>
                <div class="mec-steps-content mec-steps-content-6">
                    <textarea id="mec_popup_content" name="mec[content]" rows="8"></textarea>
                </div>
                <div class="mec-steps-content mec-steps-content-7">
                    <div class="mec-event-popup-featured-image-wrapper">
                        <div id="mec_event_popup_featured_image_preview"></div>
                        <div class="mec-event-popup-featured-image-button">
                            <button type="button" id="mec_popup_upload_image_button"><?php _e('Set Featured Image', 'modern-events-calendar-lite'); ?></button>
                            <input type="hidden" id="mec_event_popup_featured_image_thumbnail" name="mec[featured_image]" value="">
                        </div>
                    </div>
                </div>
                <div class="mec-steps-content mec-steps-content-8">
                    <div class="mec-steps-8-loading"><div class="mec-loader"></div></div>
                    <div class="mec-steps-8-results">
                        <h3><?php _e('Your Event Has Been Created.', 'modern-events-calendar-lite'); ?></h3>
                        <div class="mec-popup-final-buttons">
                            <a class="mec-button-view" href="#"><?php _e('View Event', 'modern-events-calendar-lite'); ?></a>
                            <button class="mec-button-new"><img src="<?php echo plugin_dir_url(__FILE__ ) . '../../../assets/img/popup/popup-new-shortcode-plus.svg'; ?>" /><?php _e('New Event', 'modern-events-calendar-lite'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mec-next-previous-buttons">
            <button class="mec-button-prev"><?php _e('Prev', 'modern-events-calendar-lite'); ?><img src="<?php echo plugin_dir_url(__FILE__ ) . '../../../assets/img/popup/popup-prev-icon.svg'; ?>" /></button>
            <button class="mec-button-next"><?php _e('Next', 'modern-events-calendar-lite'); ?><img src="<?php echo plugin_dir_url(__FILE__ ) . '../../../assets/img/popup/popup-next-icon.svg'; ?>" /></button>
        </div>
    </div>
</div>
<?php if(!isset($settings['event_as_popup']) or (isset($settings['event_as_popup']) && $settings['event_as_popup'] == '1')): ?>
<script>
jQuery(document).ready(function()
{
    var redirect = true;
    var popup_wpeditor = false;
    var current_step;

    var $prev = jQuery('.mec-button-prev');
    var $next = jQuery('.mec-button-next');
    var $new = jQuery('.mec-button-new');
    var $copy = jQuery('.mec-button-copy');
    var $steps = jQuery('.mec-step');
    var $name = jQuery('#mec_event_name');
    var $start_date = jQuery('#mec_start_date');
    var $end_date = jQuery('#mec_end_date');
    var $location_add = jQuery('#mec_popup_add_location');
    var $location_dropdown = jQuery('#mec_popup_location_id');
    var $organizer_add = jQuery('#mec_popup_add_organizer');
    var $organizer_dropdown = jQuery('#mec_popup_organizer_id');
    var $steps_content = jQuery('.mec-steps-content');

    // Add Event Button
    jQuery('.wrap .page-title-action').on('click', function(e)
    {
        e.preventDefault();

        // Open Lightbox
        lity('#mec_popup_event');

        // Do Step
        mec_event_step(1, 'next');
    });

    // Lightbox Close
    jQuery(document).on('lity:close', function(event, instance)
    {
        if(redirect) window.location.href = "<?php echo admin_url('post-new.php?post_type='.$post_type); ?>";
    });

    // Previous
    $prev.on('click', function()
    {
        var new_step = parseInt(current_step)-1;
        if(new_step <= 0) new_step = 1;

        mec_event_step(new_step, 'prev');
    });

    // Next
    $next.on('click', function()
    {
        var new_step = parseInt(current_step)+1;
        if(new_step > 8) new_step = 8;

        mec_event_step(new_step, 'next');
    });

    // New
    $new.on('click', function()
    {
        $name.val('');
        $start_date.val('');
        $end_date.val('');

        mec_event_step(1, 'next');
    });

    // Copy
    $copy.on('click', function()
    {
        var $temp = jQuery("<input>");
        jQuery("body").append($temp);

        $temp.val(jQuery('.mec-popup-event code').text()).select();

        document.execCommand("copy");
        $temp.remove();
    });

    // on Submit of Shortcode Name
    $name.keyup(function(e)
    {
        if(e.keyCode === 13)
        {
            mec_event_step(2, 'next');
        }
    });

    $location_add.on('click', function()
    {
        $location_dropdown.val(0).trigger('change');
        jQuery('#mec_location_new_container').show();
    });

    $location_dropdown.on('change', function()
    {
        jQuery('#mec_location_new_container').hide();
    });

    $organizer_add.on('click', function()
    {
        $organizer_dropdown.val(0).trigger('change');
        jQuery('#mec_organizer_new_container').show();
    });

    $organizer_dropdown.on('change', function()
    {
        jQuery('#mec_organizer_new_container').hide();
    });

    // Category Tabs
    jQuery('.mec-categories-tabs li').on('click', function()
    {
        jQuery('.mec-categories-tabs li').removeClass('mec-categories-tab-selected');
        jQuery(this).addClass('mec-categories-tab-selected');

        var type = jQuery(this).data('type');
        if(type === 'popular')
        {
            jQuery('.mec-categories-tab-contents li').hide();
            jQuery('.mec-categories-tab-contents li.popular-category').show();
        }
        else jQuery('.mec-categories-tab-contents li').show();
    });

    // Add New Category
    var $new_category_button = jQuery('#mec_popup_new_category_button');
    var $new_category = jQuery('#mec_popup_new_category');

    $new_category_button.on('click', function()
    {
        $new_category.toggle();
    });

    $new_category.keyup(function(e)
    {
        if(e.keyCode === 13)
        {
            var category = $new_category.val();

            jQuery.ajax(
            {
                type: "POST",
                url: ajaxurl,
                data: "action=mec_popup_event_category&category="+category,
                dataType: "json",
                success: function(data)
                {
                    if(data.success)
                    {
                        $new_category.val('').hide();
                        jQuery('.mec-categories-tab-contents ul').prepend('<li id="mec_category-'+data.id+'"><label class="selectit"><input value="'+data.id+'" type="checkbox" checked="checked" name="tax_input[mec_category][]" id="in-mec_category-'+data.id+'"> '+data.name+'</label></li>');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                }
            });
        }
    });

    // Featured Image Picker
    jQuery('#mec_popup_upload_image_button').click(function(event)
    {
        event.preventDefault();

        var frame;
        if(frame)
        {
            frame.open();
            return;
        }

        frame = wp.media();
        frame.on('select', function()
        {
            // Grab the selected attachment.
            var attachment = frame.state().get('selection').first();

            jQuery('#mec_event_popup_featured_image_preview').html('<img src="'+attachment.attributes.url+'" />');
            jQuery('#mec_event_popup_featured_image_thumbnail').val(attachment.id);

            frame.close();
        });

        frame.open();
    });

    // Do Step
    function mec_event_step(step, type)
    {
        // Validation
        if(step === 2)
        {
            var name = $name.val();
            if(name === '')
            {
                $name.addClass('mec-required').focus();
                jQuery('.popup-sh-name-required').show();
                return false;
            }
        }
        else if(step === 3)
        {
            var start_date = $start_date.val();
            if(start_date === '')
            {
                $start_date.addClass('mec-required').focus();
                return false;
            }

            var end_date = $end_date.val();
            if(end_date === '')
            {
                $end_date.addClass('mec-required').focus();
                return false;
            }
        }
        else if(step === 4)
        {
            var location_id = $location_dropdown.val();
            var $location_name = jQuery('#mec_location_name');

            if(location_id === '0' && $location_name.val() === '')
            {
                $location_name.addClass('mec-required').focus();
                return false;
            }
        }
        else if(step === 5)
        {
            var organizer_id = $organizer_dropdown.val();
            var $organizer_name = jQuery('#mec_organizer_name');

            if(organizer_id === '0' && $organizer_name.val() === '')
            {
                $organizer_name.addClass('mec-required').focus();
                return false;
            }
        }

        // Init WP Editor
        if(step === 6 && !popup_wpeditor)
        {
            popup_wpeditor = true;
            wp.editor.initialize('mec_popup_content',
            {
                tinymce: {
                    wpautop: true,
                    plugins : 'charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview',
                    toolbar1: 'bold italic underline strikethrough | bullist numlist | blockquote hr wp_more | alignleft aligncenter alignright | link unlink | fullscreen | wp_adv',
                    toolbar2: 'formatselect alignjustify forecolor | pastetext removeformat charmap | outdent indent | undo redo | wp_help'
                },
                quicktags: false,
                mediaButtons: true
            });
        }

        current_step = step;

        // Buttons
        $prev.show();
        $next.show();

        if(step === 1)
        {
            $prev.hide();
        }
        else if(step === 8)
        {
            $prev.hide();
            $next.hide();
        }

        // Disable Redirection
        redirect = (step !== 8);

        // Steps Bar
        $steps.removeClass('mec-step-passed');
        for(var i = 1; i <= step; i++) jQuery('.mec-step-'+i).addClass('mec-step-passed');

        // Content
        $steps_content.hide();
        $steps_content.removeClass('mec-steps-content-active');

        jQuery('.mec-steps-content-'+step).addClass('mec-steps-content-active').show();
        jQuery('.mec-steps-content-container').removeClass('mec-steps-content-1 mec-steps-content-2 mec-steps-content-3 mec-steps-content-4 mec-steps-content-5 mec-steps-content-6 mec-steps-content-7 mec-steps-content-8').addClass('mec-steps-content-'+step);

        // Save event
        if(step === 8) return mec_event_save();
    }

    function mec_event_save()
    {
        // Show Loading
        jQuery(".mec-steps-8-loading").show();
        jQuery(".mec-steps-8-results").hide();

        var form = jQuery("#mec_popup_event_form :input").serialize();
        form += '&mec[content]='+tinyMCE.get('mec_popup_content').getContent();

        jQuery.ajax(
        {
            type: "POST",
            url: ajaxurl,
            data: "action=mec_popup_event&"+form,
            dataType: "json",
            success: function(data)
            {
                if(data.success)
                {
                    var $view = jQuery('.mec-button-view');
                    $view.attr('href', data.link);

                    jQuery(".mec-steps-8-loading").hide();
                    jQuery(".mec-steps-8-results").show();
                }
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
            }
        });
    }
});
</script>
<?php endif; ?>