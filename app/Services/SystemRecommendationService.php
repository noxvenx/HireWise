<?php

namespace App\Services;

class SystemRecommendationService
{
    public function determine(int $rank, int $quota, int $reserveQuota): string
    {
        if ($rank <= $quota) {
            return 'recommended';
        }

        if ($rank <= ($quota + $reserveQuota)) {
            return 'reserve';
        }

        return 'not_recommended';
    }
}