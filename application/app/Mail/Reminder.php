<?php

/** --------------------------------------------------------------------------------
 * [template]
 * This classes renders the [new email] email and stores it in the queue
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class Reminder extends Mailable {
    use Queueable;

    /**
     * The data for merging into the email
     */
    public $data;

    /**
     * Model instance
     */
    public $obj;

    /**
     * Model instance
     */
    public $user;

    public $emailerrepo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user = [], $data = [], $obj = []) {

        $this->data = $data;
        $this->user = $user;
        $this->obj = $obj;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {

        //email template
        if (!$template = \App\Models\EmailTemplate::Where('emailtemplate_name', 'Reminder')->first()) {
            return false;
        }

        //validate
        if (!$this->obj instanceof \App\Models\Reminder || !$this->user instanceof \App\Models\User) {
            return false;
        }

        //only active templates
        if ($template->emailtemplate_status != 'enabled') {
            return false;
        }

        //check if clients emails are disabled
        if ($this->user->type == 'client' && config('system.settings_clients_disable_email_delivery') == 'enabled') {
            return;
        }

        //does the user have notifications enabled
        if($this->user->notifications_reminders != 'yes_email'){
            return;
        }

        //get common email variables
        $payload = config('mail.data');

        //set template variables
        $payload += [
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'reminder_title' => $this->obj->reminder_title,
            'reminder_date' => runtimeDate($this->obj->reminder_datetime),
            'reminder_time' => runtimeTime($this->obj->reminder_datetime),
            'reminder_notes' => $this->obj->reminder_notes ?? '---',
            'linked_item_type' => $this->data['linked_item_type'] ?? '---',
            'linked_item_name' => $this->data['linked_item_name'] ?? '---',
            'linked_item_title' => $this->data['linked_item_title'] ?? '---',
            'linked_item_id' => $this->data['linked_item_id'],
            'linked_item_url' => $this->data['linked_item_url'],
        ];

        //save in the database queue
        $queue = new \App\Models\EmailQueue();
        $queue->emailqueue_to = $this->user->email;
        $queue->emailqueue_subject = $template->parse('subject', $payload);
        $queue->emailqueue_message = $template->parse('body', $payload);
        $queue->save();
    }
}
