<?php

namespace App\Providers;

use App\Domain\Game\GameRepository;
use App\Domain\GameResult\GameResultRepository;
use App\Domain\Turn\TurnRepository;
use App\Infrastructure\Game\GameMySQLRepository;
use App\Infrastructure\GameResult\GameResultMySQLRepository;
use App\Infrastructure\Turn\TurnMySQLRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        $this->app->bind(GameRepository::class, GameMySQLRepository::class);
        $this->app->bind(TurnRepository::class, TurnMySQLRepository::class);
        $this->app->bind(GameResultRepository::class, GameResultMySQLRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        //
    }
}
