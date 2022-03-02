<?php

namespace App\Services\Application;

use App\Exceptions\BusinessException;
use App\Models\Attachment;
use App\Models\Competition;
use App\Models\Nomination;

class ShowApplicationAction
{
    public function exec($competitionId)
    {
        $data = [
            'video' => null,
            'video_description' => null,
        ];

        if (old('video')) {
            $data['video'] = Attachment::findOrFail(old('video'));
        }

        if (old('video_description')) {
            $data['video_description'] = Attachment::findOrFail(old('video_description'));
        }

        $data['nomination_list'] = Nomination::getList($competitionId)->toArray();
        if (empty($data['nomination_list'])) {
            throw new BusinessException('Номинации не найдены');
        }

        $data['competition'] = Competition::findOrFail($competitionId);

        return $data;
    }
}
