<?php

namespace App\Services;

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
        $turnRepository = new TurnRepository();

        $turn_entity = $turnRepository->findForTurnCount($turn_count);

        return [
            'turnCount' => $turn_count,
            'board' => $turn_entity->getBoard()->getDiscs(),
            'nextDisc' => $turn_entity->getNextDisc(),
            'winnerDisc' => null
        ];
    }

    /**
     * ターンの登録
     * @param Request $request
     * @return void
     */
    public function registerTurn(Request $request): void {
        $turnRepository = new TurnRepository();

        // 一つ前のターンを取得する
        $prev_turn_count = $request->input('turnCount') - 1;
        $previous_turn = $turnRepository->findForTurnCount($prev_turn_count);

        // 石を置く
        $new_turn = $previous_turn->place_next(
            $request->input('move.disc'),
            new PointEntity($request->input('move.x'), $request->input('move.y'))
        );

        // ターンを保存する
        $turnRepository->save($new_turn);
    }
}
