<?php

/** --------------------------------------------------------------------------------
 * [template]
 * This classes renders the [new email] email and stores it in the queue
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Mail\Landlord\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class TeamWelcome extends Mailable {
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
        if (!$template = \App\Models\Landlord\EmailTemplate::on('landlord')->Where('emailtemplate_name', 'New Team Welcome')->first()) {
            return false;
        }

        //validate
        if (!!$this->user instanceof \App\Models\Landlord\Tenant) {
            return false;
        }

        //only active templates
        if ($template->emailtemplate_status != 'enabled') {
            return false;
        }

        //get common email variables
        $payload = config('mail.data');

        //set template variables
        $payload += [
            'name' => $this->user->first_name,
            'username' => $this->user->email,
            'password' => $this->data['password'],
            'crm_url' => url('/app-admin'),
        ];

        //save in the database queue
        $queue = new \App\Models\Landlord\EmailQueue();
        $queue->setConnection('landlord');
        $queue->emailqueue_to = $this->user->email;
        $queue->emailqueue_subject = $template->parse('subject', $payload);
        $queue->emailqueue_message = $template->parse('body', $payload);
        $queue->save();
    }
}