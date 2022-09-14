<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\MailingListSubscriber;

class EmailSubscriberAdded extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $subscriber;
    public function __construct(MailingListSubscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("+1 Gwiki mailing list")->markdown('emails.mailing-list-subscriber-added');
    }
}
