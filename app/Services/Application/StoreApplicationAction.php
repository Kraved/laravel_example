<?php

namespace App\Services\Application;

use App\Models\Application;

class StoreApplicationAction
{
    public function exec(array $requestData, int $competitionId)
    {
        $extras = [
            'age' => $requestData['age'],
            'location' => $requestData['location'],
            'contacts' => $requestData['contacts'],
            'video_youtube' => $requestData['video_youtube'],
        ];

        $application = Application::create([
            'competition_id' => $competitionId,
            'name' => $requestData['name'],
            'extras' => json_encode($extras),
            'active' => 1
        ]);

        return $application->id;
    }
}
