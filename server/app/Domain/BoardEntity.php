<?php

namespace App\Domain;

readonly class BoardEntity {
    public function __construct(private array $discs) {
    }

    public function place(MoveEntity $move): BoardEntity {
        // TODO:  盤面に置けるかチェック

        // 盤面をコピー
        $newDiscs = collect($this->discs)
            ->map(fn($line) => collect($line)->map(fn($disc) => $disc)->all())
            ->all();

        // 石を置く
        $newDiscs[$move->getPoint()->getY()][$move->getPoint()->getX()] = $move->getDisc();

        // TODO: ひっくり返す

        return new BoardEntity($newDiscs);
    }

    public function getDiscs(): array {
        return $this->discs;
    }
}
