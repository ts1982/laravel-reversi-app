<?php

namespace App\Services;

use App\Domain\GameResult\GameResultEntity;
use App\Domain\GameResult\GameResultRepository;
use App\Domain\Turn\PointEntity;
use App\Domain\Turn\TurnRepository;
use Illuminate\Http\Request;

class TurnService {
    /**
     * 最新のゲームからターン数に応じたデータを取得
     * @param int $turn_count
     * @return array
     */
    public function findLatestGameTurnByTurnCount(int $turn_count): array {
        $turn_repository = new TurnRepository();
        $game_result_repository = new GameResultRepository();

        $turn_entity = $turn_repository->findForTurnCount($turn_count);

        $game_result = null;
        if ($turn_entity->gameEnded()) {
            $game_result = $game_result_repository->findForGameId($turn_entity->getGameId());
        }

        return [
            'turnCount' => $turn_count,
            'board' => $turn_entity->getBoard()->getDiscs(),
            'nextDisc' => $turn_entity->getNextDisc(),
            'winnerDisc' => $game_result?->getWinnerDisc()
        ];
    }

    /**
     * ターンの登録
     * @param Request $request
     * @return void
     */
    public function registerTurn(Request $request): void {
        $turn_repository = new TurnRepository();
        $game_result_repository = new GameResultRepository();

        // 一つ前のターンを取得する
        $prev_turn_count = $request->input('turnCount') - 1;
        $previous_turn = $turn_repository->findForTurnCount($prev_turn_count);

        // 石を置く
        $new_turn = $previous_turn->place_next(
            $request->input('move.disc'),
            new PointEntity($request->input('move.x'), $request->input('move.y'))
        );

        // ターンを保存する
        $turn_repository->save($new_turn);

        // 勝敗が決した場合、対戦結果を保存
        if ($new_turn->gameEnded()) {
            $winner_disc = $new_turn->winnerDisc();
            $game_result_entity = new GameResultEntity($new_turn->getGameId(), $winner_disc);
            $game_result_repository->save($game_result_entity);
        }
    }
}
