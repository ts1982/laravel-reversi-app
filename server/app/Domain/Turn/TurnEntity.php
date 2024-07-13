<?php

namespace App\Domain\Turn;

use App\Enums\DiscType;

readonly class TurnEntity {
    public function __construct(
        private int             $game_id,
        private int             $turn_count,
        private int             $next_disc,
        private MoveEntity|null $move,
        private BoardEntity     $board,
    ) {
    }

    public function place_next(int $disc, PointEntity $point): TurnEntity {
        // 打とうとした石が、次の石ではない場合、置くことはできない
        if ($disc !== $this->next_disc) {
            throw new \Error('Invalid disc');
        }

        $move_entity = new MoveEntity($disc, $point);

        $next_board = $this->board->place($move_entity);

        // TODO: 次の石が置けない場合はスキップする処理

        $next_disc = $disc === DiscType::DARK ? DiscType::LIGHT : DiscType::DARK;

        return new TurnEntity(
            $this->game_id,
            $this->turn_count + 1,
            $next_disc,
            $move_entity,
            $next_board
        );
    }

    public static function firstTurn(int $game_id): TurnEntity {
        return new TurnEntity(
            $game_id,
            0,
            DiscType::DARK,
            null,
            new BoardEntity(BoardEntity::INITIAL_DISCS)
        );
    }

    public function getGameId(): int {
        return $this->game_id;
    }

    public function getTurnCount(): int {
        return $this->turn_count;
    }

    public function getNextDisc(): int {
        return $this->next_disc;
    }

    public function getMove(): ?MoveEntity {
        return $this->move;
    }

    public function getBoard(): BoardEntity {
        return $this->board;
    }
}
