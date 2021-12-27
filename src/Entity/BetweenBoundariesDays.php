<?php

namespace App\Entity;

trait BetweenBoundariesDays {
    public function getBetweenBoundariesDays($startDate, $endDate) {
        $timestamps = range(
            $startDate->getTimestamp(),
            $endDate->getTimestamp(),
            24 * 60 * 60
        );

        return array_map(function ($timestamp) {
            return new \DateTime(date('Y-m-d', $timestamp));
        }, $timestamps);
    }
}
