<?php

use App\Models\Competition;



function acceptingApplications()
{
    return Competition::where('active', 1)->acceptingApplications()->exists();
}
