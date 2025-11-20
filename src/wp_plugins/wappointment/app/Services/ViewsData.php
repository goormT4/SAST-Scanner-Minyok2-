<?php

namespace Wappointment\Services;

use Wappointment\WP\Helpers as WPHelpers;
use Wappointment\ClassConnect\Carbon;
use Wappointment\Services\Staff;
use Wappointment\WP\WidgetAPI;
use Wappointment\Services\Status;
use Wappointment\Managers\Service as ManageService;
use Wappointment\Managers\Central;
use Wappointment\Models\Service as ModelService;
use Wappointment\Repositories\Availability;
use Wappointment\System\Helpers;
use Wappointment\WP\PluginsDetection;
class ViewsData
{
    public function load($key)
    {
        $values = [];
        if (\method_exists($this, $key)) {
            $values = $this->{$key}();
        }
        return apply_filters('wappointment_viewdata_' . $key, $values);
    }
    private function getCalendarsStaff()
    {
        $calendars = Central::get('CalendarModel')::orderBy('sorting')->fetch();
        $staffs = [];
        foreach ($calendars->toArray() as $calendar) {
            $staffs[] = (new \Wappointment\WP\Staff($calendar))->fullData();
        }
        return $staffs;
    }
    private function regav()
    {
        if (\Wappointment\Services\VersionDB::canServices()) {
            $calendars = $this->getCalendarsStaff();
            $data = ['calendar' => $calendars[0], 'timezones_list' => \Wappointment\Services\DateTime::tz(), 'calendars' => $calendars, 'staffs' => Staff::getWP(), 'staffDefault' => \Wappointment\Services\Settings::staffDefaults()];
        } else {
            $gravatar_img = get_avatar_url(\Wappointment\Services\Settings::get('activeStaffId'), ['size' => 40]);
            $data = ['regav' => \Wappointment\Services\Settings::getStaff('regav'), 'avb' => \Wappointment\Services\Settings::getStaff('availaible_booking_days'), 'staffs' => Staff::getWP(), 'activeStaffId' => (int) \Wappointment\Services\Settings::get('activeStaffId'), 'activeStaffAvatar' => \Wappointment\Services\Settings::getStaff('avatarId') ? wp_get_attachment_image_src(\Wappointment\Services\Settings::getStaff('avatarId'))[0] : $gravatar_img, 'activeStaffGravatar' => $gravatar_img, 'activeStaffName' => Staff::getNameLegacy(), 'activeStaffAvatarId' => \Wappointment\Services\Settings::getStaff('avatarId'), 'timezone' => \Wappointment\Services\Settings::getStaff('timezone'), 'timezones_list' => \Wappointment\Services\DateTime::tz(), 'savedTimezone' => \Wappointment\Services\Settings::hasStaff('timezone')];
        }
        return apply_filters('wappointment_back_regav', $data);
    }
    private function staffCustomField()
    {
        return ['custom_fields' => WPHelpers::getOption('staff_custom_fields', [])];
    }
    private function serverinfo()
    {
        return ['server' => \Wappointment\Services\Server::data()];
    }
    private function calsync()
    {
        return ['calurl' => \Wappointment\Services\Settings::getStaff('calurl')];
    }
    private function service()
    {
        return ['service' => \Wappointment\Services\VersionDB::canServices() ? $this->getConvertedDataServiceNewToLegacy() : \Wappointment\Services\Service::get()];
    }
    protected function getConvertedDataServiceNewToLegacy()
    {
        $service = ModelService::first();
        if (empty($service)) {
            return [];
        }
        $types = [];
        $address = '';
        $video = '';
        $countries = [];
        if (!empty($service->locations)) {
            foreach ($service->locations as $location) {
                $types[] = $location->options['type'];
                if ($location->options['type'] == 'physical') {
                    $address = $location->options['address'];
                }
                if ($location->options['type'] == 'phone') {
                    $countries = $location->options['countries'];
                }
                if ($location->options['type'] == 'zoom') {
                    $video = $location->options['video'];
                }
            }
        }
        $data = ['id' => $service->id, 'name' => $service->name, 'duration' => $service->options['durations'][0]['duration'], 'type' => $types, 'address' => $address, 'options' => ['countries' => $countries, 'video' => $video]];
        if (!empty($service->options['countries'])) {
            $data['options']['countries'] = $service->options['countries'];
            $data['options']['phone_required'] = \true;
        }
        return $data;
    }
    private function wizardwidget()
    {
        return ['booking_page_id' => (int) \Wappointment\Services\Settings::get('booking_page'), 'widget' => (new \Wappointment\Services\WidgetSettings())->get()];
    }
    private function widget()
    {
        $data = ['front_availability' => $this->front_availability(), 'widget' => (new \Wappointment\Services\WidgetSettings())->get(), 'widgetDefault' => (new \Wappointment\Services\WidgetSettings())->defaultSettings(), 'steps' => (new \Wappointment\Services\WidgetSettings())->steps(), 'config' => ['service' => \Wappointment\Services\Service::get(), 'approval_mode' => \Wappointment\Services\Settings::get('approval_mode')], 'bgcolor' => WPHelpers::getThemeBgColor(), 'more' => get_theme_mods(), 'widgetFields' => (new \Wappointment\Services\WidgetSettings())->adminFieldsInfo(), 'booking_page_id' => (int) \Wappointment\Services\Settings::get('booking_page'), 'booking_page_url' => get_permalink((int) \Wappointment\Services\Settings::get('booking_page'))];
        if (\Wappointment\Services\VersionDB::canServices()) {
            $data['config']['services'] = ManageService::all();
            $data['config']['locations'] = \Wappointment\Models\Location::get();
        }
        return apply_filters('wappointment_back_widget_editor', $data);
    }
    private function widgetcancel()
    {
        return ['widget' => (new \Wappointment\Services\WidgetSettings())->get(), 'widgetDefault' => (new \Wappointment\Services\WidgetSettings())->defaultSettings(), 'staff' => Staff::getWP()];
    }
    private function calendar()
    {
        $staff_timezone = \Wappointment\Services\Settings::getStaff('timezone');
        $services = ManageService::all();
        $data = [
            'week_starts_on' => \Wappointment\Services\Settings::get('week_starts_on'),
            'wizard_step' => WPHelpers::getOption('wizard_step'),
            'timezones_list' => \Wappointment\Services\DateTime::tz(),
            'service' => \Wappointment\Services\Service::get(),
            'date_format' => \Wappointment\Services\Settings::get('date_format'),
            'time_format' => \Wappointment\Services\Settings::get('time_format'),
            'date_time_union' => \Wappointment\Services\Settings::get('date_time_union', ' - '),
            'preferredCountries' => \Wappointment\Services\Service::getObject()->getCountries(),
            'buffer_time' => \Wappointment\Services\Settings::get('buffer_time'),
            'widget' => (new \Wappointment\Services\WidgetSettings())->get(),
            'booking_page_id' => (int) \Wappointment\Services\Settings::get('booking_page'),
            'booking_page_url' => get_permalink((int) \Wappointment\Services\Settings::get('booking_page')),
            'showWelcome' => \Wappointment\Services\Settings::get('show_welcome'),
            'subscribe_email' => \Wappointment\Services\Settings::get('email_notifications'),
            'welcome_site' => get_site_url(),
            'preferences' => (new \Wappointment\Services\Preferences())->preferences,
            //'is_dotcom_connected' => Settings::getStaff('dotcom'),
            'services' => $services,
            'durations' => ManageService::extractDurations($services),
            'cal_duration' => (new \Wappointment\Services\Preferences())->get('cal_duration'),
            'buttons' => [['key' => 'book', 'title' => __('Book an appointment', 'wappointment'), 'subtitle' => __('On behalf of your client', 'wappointment'), 'icon' => 'dashicons-admin-users', 'component' => 'BehalfBooking'], ['key' => 'free', 'title' => __('Open this time', 'wappointment'), 'subtitle' => __('Allow new bookings', 'wappointment'), 'icon' => 'dashicons-unlock txt blue', 'component' => 'StatusFreeConfirm'], ['key' => 'busy', 'title' => __('Block this time', 'wappointment'), 'subtitle' => __('Prevent new bookings', 'wappointment'), 'icon' => 'dashicons-lock txt red', 'component' => 'StatusBusyConfirm']],
            'buttons_appointment' => [['key' => 'cancel', 'title' => __('Cancel', 'wappointment'), 'subtitle' => '', 'icon' => 'dashicons-dismiss red', 'component' => 'CancelBooking']],
        ];
        if (\Wappointment\Services\VersionDB::canServices()) {
            $data['staff'] = \Wappointment\Services\Calendars::all();
            if (!isset($data['staff'][0])) {
                throw new \WappointmentException("There is no active calendar change that in Wappointment > Settings > Calendars", 1);
            }
            $data['timezone'] = $data['staff'][0]->options['timezone'];
            $data['locations'] = \Wappointment\Models\Location::get();
            $data['custom_fields'] = Central::get('CustomFields')::get();
            $data['now'] = (new Carbon())->setTimezone($data['timezone'])->format('Y-m-d\\TH:i:00');
            $data['regav'] = $data['staff'][0]->options['regav'];
            $data['availability'] = $data['staff'][0]->availability;
        } else {
            $data['staff'] = (new \Wappointment\WP\StaffLegacy())->toArray();
            $data['regav'] = \Wappointment\Services\Settings::getStaff('regav');
            $data['availability'] = WPHelpers::getStaffOption('availability');
            $data['timezone'] = $staff_timezone;
            $data['now'] = (new Carbon())->setTimezone($staff_timezone)->format('Y-m-d\\TH:i:00');
            $data['legacy'] = \true;
        }
        return apply_filters('wappointment_back_calendar', $data);
    }
    private function settingsadvanced()
    {
        if (!\Wappointment\Services\VersionDB::canServices()) {
            $timezone = \Wappointment\Services\Settings::getStaff('timezone');
        } else {
            $staff = \Wappointment\Services\Calendars::all();
            if (!isset($staff[0])) {
                throw new \WappointmentException("There is no active calendar change that in Wappointment > Settings > Calendars", 1);
            }
            $timezone = $staff[0]->options['timezone'];
        }
        return [
            'debug' => !Helpers::isProd(),
            'video_link_shows' => \Wappointment\Services\Settings::get('video_link_shows'),
            'buffer_time' => \Wappointment\Services\Settings::get('buffer_time'),
            'front_page_id' => (int) \Wappointment\Services\Settings::get('front_page'),
            'front_page' => get_permalink((int) \Wappointment\Services\Settings::get('front_page')),
            'front_page_type' => get_post_type((int) \Wappointment\Services\Settings::get('front_page')),
            // advanced
            'approval_mode' => \Wappointment\Services\Settings::get('approval_mode'),
            'today_formatted' => \Wappointment\Services\DateTime::i18nDateTime(\time(), $timezone),
            'date_format' => \Wappointment\Services\Settings::get('date_format'),
            'time_format' => \Wappointment\Services\Settings::get('time_format'),
            'date_time_union' => \Wappointment\Services\Settings::get('date_time_union', ' - '),
            'allow_cancellation' => \Wappointment\Services\Settings::get('allow_cancellation'),
            'allow_rescheduling' => \Wappointment\Services\Settings::get('allow_rescheduling'),
            'email_footer' => \Wappointment\Services\Settings::get('email_footer'),
            'week_starts_on' => \Wappointment\Services\Settings::get('week_starts_on'),
            'hours_before_booking_allowed' => \Wappointment\Services\Settings::get('hours_before_booking_allowed'),
            'frontend_weekstart' => \Wappointment\Services\Settings::get('frontend_weekstart'),
            'hours_before_cancellation_allowed' => \Wappointment\Services\Settings::get('hours_before_cancellation_allowed'),
            'hours_before_rescheduling_allowed' => \Wappointment\Services\Settings::get('hours_before_rescheduling_allowed'),
            'timezone' => $timezone,
            'config' => ['approval_mode' => \Wappointment\Services\Settings::get('approval_mode')],
            //notifications
            'weekly_summary' => \Wappointment\Services\Settings::get('weekly_summary'),
            'weekly_summary_day' => \Wappointment\Services\Settings::get('weekly_summary_day'),
            'weekly_summary_time' => \Wappointment\Services\Settings::get('weekly_summary_time'),
            'daily_summary' => \Wappointment\Services\Settings::get('daily_summary'),
            'daily_summary_time' => \Wappointment\Services\Settings::get('daily_summary_time'),
            'notify_new_appointments' => \Wappointment\Services\Settings::get('notify_new_appointments'),
            'notify_pending_appointments' => \Wappointment\Services\Settings::get('notify_pending_appointments'),
            'notify_canceled_appointments' => \Wappointment\Services\Settings::get('notify_canceled_appointments'),
            'notify_rescheduled_appointments' => \Wappointment\Services\Settings::get('notify_rescheduled_appointments'),
            'email_notifications' => \Wappointment\Services\Settings::get('email_notifications'),
            'mail_status' => (bool) \Wappointment\Services\Settings::get('mail_status'),
            'allow_staff_cf' => \Wappointment\Services\Settings::get('allow_staff_cf'),
            'calendar_handles_free' => \Wappointment\Services\Settings::get('calendar_handles_free'),
            'calendar_ignores_free' => \Wappointment\Services\Settings::get('calendar_ignores_free'),
            'cache' => \Wappointment\Services\Settings::get('cache'),
            'calendar_roles' => \Wappointment\Services\Settings::get('calendar_roles'),
            'all_roles' => \Wappointment\Services\Permissions::getAllWpRoles(),
            'max_active_bookings' => (int) \Wappointment\Services\Settings::get('max_active_bookings'),
            'max_active_per_staff' => (int) \Wappointment\Services\Settings::get('max_active_per_staff'),
            'autofill' => (int) \Wappointment\Services\Settings::get('autofill'),
            'manager_added' => \Wappointment\Services\Permissions::hasManagerRole(),
            'forceemail' => \Wappointment\Services\Settings::get('forceemail'),
            'allow_refreshavb' => \Wappointment\Services\Settings::get('allow_refreshavb'),
            'refreshavb_at' => \Wappointment\Services\Settings::get('refreshavb_at'),
            'clean_pending_every' => \Wappointment\Services\Settings::get('clean_pending_every'),
            'payment_active' => \Wappointment\Services\Payment::active(),
            'invoice' => \Wappointment\Services\Settings::get('invoice'),
            'invoice_seller' => \Wappointment\Services\Settings::get('invoice_seller'),
            'invoice_num' => \Wappointment\Services\Settings::get('invoice_num'),
            'invoice_client' => \Wappointment\Services\Settings::get('invoice_client'),
            'custom_fields' => Central::get('CustomFields')::get(),
            'wp_remote' => \Wappointment\Services\Settings::get('wp_remote'),
            'jitsi_url' => \Wappointment\Services\Settings::get('jitsi_url'),
            'availability_fluid' => \Wappointment\Services\Settings::get('availability_fluid'),
            'more_st' => \Wappointment\Services\Settings::get('more_st'),
            'starting_each' => \Wappointment\Services\Settings::get('starting_each'),
            'big_prices' => \Wappointment\Services\Settings::get('big_prices'),
        ];
    }
    private function settingsaddons()
    {
        return ['addons' => \Wappointment\Services\Addons::withSettings()];
    }
    private function wizardinit()
    {
        return ['admin_email' => wp_get_current_user()->user_email, 'admin_name' => wp_get_current_user()->display_name];
    }
    private function wizardlast()
    {
        return ['areas' => WidgetAPI::getSidebars(), 'widgets' => WidgetAPI::getWidgets()];
    }
    private function settingsmailer()
    {
        return ['mail_config' => \Wappointment\Services\Settings::get('mail_config'), 'wp_mail_overidden' => PluginsDetection::smtpConfigured(), 'recipient' => wp_get_current_user()->user_email];
    }
    private function TESTprocessAvail($avails)
    {
        foreach ($avails as &$avail) {
            $avail[0] = Carbon::createFromTimestamp($avail[0])->setTimezone($this->timezone)->format('Y-m-d\\TH:i:00 T');
            $avail[1] = Carbon::createFromTimestamp($avail[1])->setTimezone($this->timezone)->format('Y-m-d\\TH:i:00 T');
        }
        return $avails;
    }
    private function front_availability()
    {
        $availability = (new Availability())->get();
        $availability['wpauth'] = WPHelpers::wpUserData();
        return $availability;
    }
}
