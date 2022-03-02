<?php

namespace App\Services\Application;

use App\Models\Attachment;

class SetAttachmentAction
{
    public function exec($attachmentId, $applicationId)
    {
        $attachment = Attachment::findOrFail($attachmentId);

        if ($attachment) {
            $attachment->attachable_id = $applicationId;
            $attachment->save();
        }
    }
}
