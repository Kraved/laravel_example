<?php

namespace App\Services\Application;

use App\Events\ApplicationCreated;
use App\Exceptions\BusinessException;
use App\Models\Application;
use App\Models\Competition;
use Illuminate\Events\Dispatcher;

class ApplicationService
{
    private $competition;
    private Dispatcher $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->competition = Competition::active()->acceptingApplications()->firstOrFail();
    }

    public function create()
    {

        $showAction = app(ShowApplicationAction::class);
        $competitionId = $this->competition->id;

        return $showAction->exec($competitionId);
    }

    public function store(array $requestData)
    {
        try {
            \DB::beginTransaction();

            $storeAction = app(StoreApplicationAction::class);
            $applicationId = $storeAction->exec($requestData, $this->competition->id);

            $application = Application::findOrFail($applicationId);

            $application->nominations()->sync($requestData['nomination']);

            $setAttachment = app(SetAttachmentAction::class);
            if (!empty($requestData['video'])) {
                $setAttachment->exec($requestData['video'], $applicationId);
            }

            if (!empty($requestData['video_description'])) {
                $setAttachment->exec($requestData['video_description'], $applicationId);
            }

            \DB::commit();

            $this->dispatcher->dispatch(new ApplicationCreated($application->id));

            return true;

        } catch (\Exception $exception) {
            \DB::rollBack();

            throw new BusinessException('Не удалось создать заявку');
        }
    }
}
