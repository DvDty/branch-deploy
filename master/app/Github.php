<?php

namespace App;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class Github
{
    public function fetchBranches(): Collection
    {
        return Http::get('https://api.github.com/repos/DvDty/branch-deploy/branches')
            ->collect()
            ->keyBy('name');
    }
}
