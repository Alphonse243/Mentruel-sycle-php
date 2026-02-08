<?php

namespace Alphonse243\BioCycle\Tests;

use Alphonse243\BioCycle\Stats\CycleStats;
use Alphonse243\BioCycle\Collection\CycleHistory;
use Alphonse243\BioCycle\Entity\CycleEntity;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class CycleStatsTest extends TestCase
{
    public function testGenerateStatsWithEmptyHistory(): void
    {
        $history = new CycleHistory();
        $stats = new CycleStats($history);
        $result = $stats->generateStats();

        $this->assertEquals(28, $result['average_cycle']);
        $this->assertEquals(0, $result['cycle_count']);
        $this->assertEquals('Pas de données', $result['trend']);
    }

    public function testGenerateStatsWithCycles(): void
    {
        $history = new CycleHistory();
        $history->addCycle(new CycleEntity(Carbon::parse('2024-01-01'), Carbon::parse('2024-01-29')));
        $history->addCycle(new CycleEntity(Carbon::parse('2024-01-29'), Carbon::parse('2024-02-26')));

        $stats = new CycleStats($history);
        $result = $stats->generateStats();

        $this->assertEquals(28, $result['average_cycle']);
        $this->assertEquals(2, $result['cycle_count']);
        $this->assertEquals('Stable', $result['trend']);
        $this->assertIsArray($result['chart_data']);
    }

    public function testTrendDetection(): void
    {
        $history = new CycleHistory();
        $history->addCycle(new CycleEntity(Carbon::parse('2024-01-01'), Carbon::parse('2024-01-29'))); // 28
        $history->addCycle(new CycleEntity(Carbon::parse('2024-01-29'), Carbon::parse('2024-02-27'))); // 29

        $stats = new CycleStats($history);
        $result = $stats->generateStats();

        $this->assertIsString($result['trend']);
    }
}
