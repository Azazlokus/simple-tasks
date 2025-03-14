<?php

use App\Jobs\AssignUnassignedTasksJob;


Schedule::job(new AssignUnassignedTasksJob())->everyTwoMinutes();
