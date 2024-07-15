<?php

namespace App\Domain\Turn;

use App\Enums\DiscType;

readonly class BoardEntity {
    public const INITIAL_DISCS = [
        [DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY],
        [DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY],
        [DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY],
        [DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::DARK, DiscType::LIGHT, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY],
        [DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::LIGHT, DiscType::DARK, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY],
        [DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY],
        [DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY],
        [DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY, DiscType::EMPTY],
    ];

    private array $walledDiscs;

    public function __construct(private array $discs) {
        $this->walledDiscs = $this->wallDiscs();
    }

    public function place(MoveEntity $move): BoardEntity {
        // 空のマス目ではない場合、置くことはできない
        if ($this->discs[$move->getPoint()->getY()][$move->getPoint()->getX()] !== DiscType::EMPTY) {
            throw new \Error('Selected point is not empty');
        }

        // ひっくり返せる点をリストアップ
        $flipPoints = $this->listFlipPoints($move);

        // ひっくり返せる点がない場合、置くことはできない
        if (count($flipPoints) === 0) {
            throw new \Error('Flip points is empty');
        }

        // 盤面をコピー
        $newDiscs = collect($this->discs)
            ->map(fn($line) => collect($line)->map(fn($disc) => $disc)->all())
            ->all();

        // 石を置く
        $newDiscs[$move->getPoint()->getY()][$move->getPoint()->getX()] = $move->getDisc();

        // ひっくり返す
        foreach ($flipPoints as $p) {
            $newDiscs[$p->getY()][$p->getX()] = $move->getDisc();
        }

        return new BoardEntity($newDiscs);
    }

    private function listFlipPoints(MoveEntity $move): array {
        $flipPoints = [];

        // 上
        $this->checkFlipPoints(0, -1, $move, $flipPoints);
        // 左上
        $this->checkFlipPoints(-1, -1, $move, $flipPoints);
        // 左
        $this->checkFlipPoints(-1, 0, $move, $flipPoints);
        // 左下
        $this->checkFlipPoints(-1, 1, $move, $flipPoints);
        // 下
        $this->checkFlipPoints(0, 1, $move, $flipPoints);
        // 右下
        $this->checkFlipPoints(1, 1, $move, $flipPoints);
        // 右
        $this->checkFlipPoints(1, 0, $move, $flipPoints);
        // 右上
        $this->checkFlipPoints(1, -1, $move, $flipPoints);

        return $flipPoints;
    }

    public function checkFlipPoints(
        int        $xMove,
        int        $yMove,
        MoveEntity $move,
        array      &$flipPoints
    ): void {
        $flipCandidate = [];

        $walledX = $move->getPoint()->getX() + 1;
        $walledY = $move->getPoint()->getY() + 1;

        // 一つ動いた位置から開始
        $cursorX = $walledX + $xMove;
        $cursorY = $walledY + $yMove;

        // 手と逆の色の石がある間、一つずつ見ていく
        while (DiscEntity::isOppositeDisc($move->getDisc(), $this->walledDiscs[$cursorY][$cursorX])) {
            // 番兵を考慮して-1する
            $flipCandidate[] = new PointEntity($cursorX - 1, $cursorY - 1);
            $cursorX += $xMove;
            $cursorY += $yMove;

            // 次の手が同じ色の石なら、ひっくり返す石が確定
            if ($move->getDisc() === $this->walledDiscs[$cursorY][$cursorX]) {
                $flipPoints = [...$flipPoints, ...$flipCandidate];
                break;
            }
        }
    }

    public function existValidMove(int $disc): bool {
        foreach ($this->discs as $y => $line) {
            foreach ($line as $x => $discOnBoard) {
                if ($discOnBoard !== DiscType::EMPTY) {
                    continue;
                }
                $move = new MoveEntity($disc, new PointEntity($x, $y));
                $flipPoints = $this->listFlipPoints($move);

                if (count($flipPoints) !== 0) {
                    return true;
                }
            }
        }
        return false;
    }

    private function wallDiscs(): array {
        $walled = [];

        $top_and_bottom_wall = array_fill(0, count($this->discs) + 2, DiscType::WALL);

        $walled[] = $top_and_bottom_wall;

        collect($this->discs)->each(function ($line) use (&$walled) {
            $walledLine = [DiscType::WALL, ...$line, DiscType::WALL];
            $walled[] = $walledLine;
        });

        $walled[] = $top_and_bottom_wall;

        return $walled;
    }

    public function getDiscs(): array {
        return $this->discs;
    }
}
