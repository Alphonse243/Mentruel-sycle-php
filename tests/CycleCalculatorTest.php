<?php

namespace Alphonse243\BioCycle\Tests;

use Alphonse243\BioCycle\Calculator\CycleCalculator;
use Alphonse243\BioCycle\Collection\CycleHistory;
use Alphonse243\BioCycle\Entity\CycleEntity;
use Alphonse243\BioCycle\Exception\CycleIrregulierException;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class CycleCalculatorTest extends TestCase
{
    // Test 1 : Cycle parfait de 28 jours
    public function testPerfectCyclePrediction(): void
    {
        $history = new CycleHistory();
        $history->addCycle(new CycleEntity(Carbon::parse('2024-01-01'), Carbon::parse('2024-01-29')));
        $history->addCycle(new CycleEntity(Carbon::parse('2024-01-29'), Carbon::parse('2024-02-26')));

        $calculator = new CycleCalculator($history, Carbon::parse('2024-02-26'));
        $prediction = $calculator->predictNextCycle();

        $this->assertEquals(28, $prediction['durée_cycle_moyenne']);
        $this->assertTrue($prediction['prochaines_règles']->eq(Carbon::parse('2024-03-25')));
    }

    // Test 2 : Recalcul de moyenne après cycle irrégulier
    public function testAverageDurationAfterIrregularCycle(): void
    {
        $history = new CycleHistory();
        $history->addCycle(new CycleEntity(Carbon::parse('2024-01-01'), Carbon::parse('2024-01-29'))); // 28j
        $history->addCycle(new CycleEntity(Carbon::parse('2024-01-29'), Carbon::parse('2024-03-05'))); // 35j

        $average = $history->getAverageDuration();
        $this->assertEquals(31.5, $average);
    }

    // Test 3 : Gestion des passages d'années
    public function testYearBoundaryHandling(): void
    {
        $history = new CycleHistory();
        $history->addCycle(new CycleEntity(Carbon::parse('2023-12-01'), Carbon::parse('2023-12-29')));
        $history->addCycle(new CycleEntity(Carbon::parse('2023-12-29'), Carbon::parse('2024-01-26')));

        $calculator = new CycleCalculator($history, Carbon::parse('2024-01-26'));
        $prediction = $calculator->predictNextCycle();

        $this->assertEquals(28, $prediction['durée_cycle_moyenne']);
        $this->assertTrue($prediction['prochaines_règles']->year === 2024);
    }

    // Test 4 : Exception pour cycle irrégulier (> 7 jours d'écart)
    public function testIrregularCycleException(): void
    {
        $history = new CycleHistory();
        $history->addCycle(new CycleEntity(Carbon::parse('2024-01-01'), Carbon::parse('2024-01-29'))); // 28j
        $history->addCycle(new CycleEntity(Carbon::parse('2024-01-29'), Carbon::parse('2024-03-10'))); // 40j (écart > 7)

        $calculator = new CycleCalculator($history, Carbon::parse('2024-03-10'));

        $this->expectException(CycleIrregulierException::class);
        $calculator->predictNextCycle();
    }

    // Test 5 : Ovulation forcée écrase les calculs
    public function testForcedOvulationDate(): void
    {
        $history = new CycleHistory();
        $history->addCycle(new CycleEntity(Carbon::parse('2024-01-01'), Carbon::parse('2024-01-29')));

        $calculator = new CycleCalculator($history, Carbon::parse('2024-01-29'));
        $forcedDate = Carbon::parse('2024-02-15');
        
        $calculator->forceOvulationDate($forcedDate);
        $prediction = $calculator->predictNextCycle();

        $this->assertTrue($prediction['ovulation']->eq($forcedDate));
        $this->assertTrue($prediction['ovulation_forcée']);
    }
}
