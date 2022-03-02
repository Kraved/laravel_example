<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationRequest;
use App\Models\Application;
use App\Models\Attachment;
use App\Models\Competition;
use App\Services\Application\ApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public $competition;
    private ApplicationService $applicationService;

    public function __construct(ApplicationService $applicationService)
    {
        $this->competition = Competition::active()->acceptingApplications()->firstOrFail();
        $this->applicationService = $applicationService;
    }

    public function create()
    {
        $data = $this->applicationService->create();

        return view('applications.create', compact('data'));
    }

    public function store(ApplicationRequest $request)
    {
        $requestData = $request->only([
            'name',
            'age',
            'location',
            'contacts',
            'video_youtube',
            'video',
            'nomination',
            'video_description'
        ]);

        if ($this->applicationService->store($requestData)) {
            return back()->with(['success' => true]);
        }

    }


}
