<?php

namespace App\ScheduleTasks;

use App\Mail\AdminMessageMail;
use App\Models\Message;
use App\Models\MessageUser;
use Illuminate\Support\Facades\Mail;

class SendAdminMessagesTask
{
    public function __invoke()
    {
        // Get list of message id having not sent recipients
        $messageIds = Message::query()
            ->where('is_draft', false)
            ->whereHas('notSentRecipients')
            ->pluck('id')
            ->toArray();

        // use the ids list to get 100 the message recipients
        if (count($messageIds) == 0) {
            return;
        }

        $recipients = MessageUser::with(['message', 'user'])
            ->whereIn('message_id', $messageIds)
            ->whereNull('sent_at')
            ->limit(100)
            ->get();

        // Send the message to each recipient
        foreach ($recipients as $recipient) {

            if (
                empty($recipient->user->email)
                || empty($recipient->message->body)
            ) {
                continue;
            }

            Mail::to($recipient->user->email)
                ->send(new AdminMessageMail($recipient->user, $recipient->message));

            $recipient->sent_at = now();
            $recipient->save();
        }

    }
}