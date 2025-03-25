<?php

namespace App\Services\Division;

use App\Enums\Division\RoundName;
use App\Models\Division;
use App\Models\Pool;
use App\Models\Round;
use App\Models\Team;
use Illuminate\Support\Collection;

class DivisionBuilder
{
    protected Division $division;

    public function __construct(Division $division)
    {
        $this->division = $division;
    }

    public function generateFirstRound(int $poolSize, int $advanceCount): void
    {
        $this->clearDivision();
        $teams = $this->division->teams()->get();
        $currentRound = $this->getRoundCount($teams->count(), $poolSize, $advanceCount);
        $this->generateRound(RoundName::getRound($currentRound), $teams, $poolSize);
    }

    private function getRoundCount(int $playerCount, int $poolSize, int $advanceCount): int
    {
        $rounds = 0;
        while ($playerCount > $poolSize) {
            $rounds++;
            $pools = (int) ceil($playerCount / $poolSize);
            $playerCount = $pools * $advanceCount;
        }

        return $rounds + 1; // Including the final round
    }

    private function generateRound(RoundName $roundName, Collection $teams, int $poolSize): Round
    {
        $round = $this->division->rounds()->create(['name' => $roundName->getName()]);
        $numberTeams = $teams->count();
        $numPools = (int) ceil($numberTeams / $poolSize);
        $teamsPerPool = (int) ceil($numberTeams / $numPools);
        $teamChunks = $teams->chunk($teamsPerPool);

        $alphabet = range('A', 'Z');
        foreach ($teamChunks as $index => $poolTeams) {
            $pool = $round->pools()->create(['name' => $alphabet[$index]]);
            $pool->teams()->attach($poolTeams->pluck('id')->all());
        }

        return $round;
    }

    /**
     * Move a team between pools manually
     */
    public function moveTeamBetweenPools(Team $team, Pool $fromPool, Pool $toPool): void
    {
        $fromPool->teams()->detach($team->id);
        $toPool->teams()->attach($team->id);
    }

    /**
     * Shuffle teams within a round
     */
    public function shuffleTeamsInRound(Round $round): void
    {
        $allTeams = $round->pools->flatMap(fn ($pool) => $pool->teams)->shuffle();

        // Clear existing teams
        $round->pools->each(fn ($pool) => $pool->teams()->detach());

        // Reassign shuffled teams
        $teamChunks = $allTeams->chunk($round->pool_size);

        foreach ($round->pools as $index => $pool) {
            if (isset($teamChunks[$index])) {
                $pool->teams()->attach($teamChunks[$index]->pluck('id'));
            }
        }
    }

    private function clearDivision(): void
    {
        $this->division->rounds()->each(function (Round $round) {
            $round->pools()->each(fn (Pool $pool) => $pool->teams()->detach());
            $round->pools()->delete();
            $round->delete();
        });
    }
}
