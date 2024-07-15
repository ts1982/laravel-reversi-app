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

    private array $walled_discs;

    public function __construct(private array $discs) {
        $this->walled_discs = $this->wallDiscs();
    }

    public function place(MoveEntity $move): BoardEntity {
        // 空のマス目ではない場合、置くことはできない
        if ($this->discs[$move->getPoint()->getY()][$move->getPoint()->getX()] !== DiscType::EMPTY) {
            throw new \Error('Selected point is not empty');
        }

        // ひっくり返せる点をリストアップ
        $flip_points = $this->listFlipPoints($move);

        // ひっくり返せる点がない場合、置くことはできない
        if (count($flip_points) === 0) {
            throw new \Error('Flip points is empty');
        }

        // 盤面をコピー
        $new_discs = collect($this->discs)
            ->map(fn($line) => collect($line)->map(fn($disc) => $disc)->all())
            ->all();

        // 石を置く
        $new_discs[$move->getPoint()->getY()][$move->getPoint()->getX()] = $move->getDisc();

        // ひっくり返す
        foreach ($flip_points as $p) {
            $new_discs[$p->getY()][$p->getX()] = $move->getDisc();
        }

        return new BoardEntity($new_discs);
    }

    private function listFlipPoints(MoveEntity $move): array {
        $flip_points = [];

        // 上
        $this->checkFlipPoints(0, -1, $move, $flip_points);
        // 左上
        $this->checkFlipPoints(-1, -1, $move, $flip_points);
        // 左
        $this->checkFlipPoints(-1, 0, $move, $flip_points);
        // 左下
        $this->checkFlipPoints(-1, 1, $move, $flip_points);
        // 下
        $this->checkFlipPoints(0, 1, $move, $flip_points);
        // 右下
        $this->checkFlipPoints(1, 1, $move, $flip_points);
        // 右
        $this->checkFlipPoints(1, 0, $move, $flip_points);
        // 右上
        $this->checkFlipPoints(1, -1, $move, $flip_points);

        return $flip_points;
    }

    public function checkFlipPoints(
        int        $xMove,
        int        $yMove,
        MoveEntity $move,
        array      &$flip_points
    ): void {
        $flip_candidate = [];

        $walled_x = $move->getPoint()->getX() + 1;
        $walled_y = $move->getPoint()->getY() + 1;

        // 一つ動いた位置から開始
        $cursor_x = $walled_x + $xMove;
        $cursor_y = $walled_y + $yMove;

        // 手と逆の色の石がある間、一つずつ見ていく
        while (DiscEntity::isOppositeDisc($move->getDisc(), $this->walled_discs[$cursor_y][$cursor_x])) {
            // 番兵を考慮して-1する
            $flip_candidate[] = new PointEntity($cursor_x - 1, $cursor_y - 1);
            $cursor_x += $xMove;
            $cursor_y += $yMove;

            // 次の手が同じ色の石なら、ひっくり返す石が確定
            if ($move->getDisc() === $this->walled_discs[$cursor_y][$cursor_x]) {
                $flip_points = [...$flip_points, ...$flip_candidate];
                break;
            }
        }
    }

    public function existValidMove(int $disc): bool {
        foreach ($this->discs as $y => $line) {
            foreach ($line as $x => $disc_on_board) {
                if ($disc_on_board !== DiscType::EMPTY) {
                    continue;
                }
                $move = new MoveEntity($disc, new PointEntity($x, $y));
                $flip_points = $this->listFlipPoints($move);

                if (count($flip_points) !== 0) {
                    return true;
                }
            }
        }
        return false;
    }

    public function count(int $disc): int {
        return collect($this->discs)
            ->flatten()
            ->filter(function ($disc_on_board) use ($disc) {
                return $disc_on_board === $disc;
            })
            ->count();
    }

    private function wallDiscs(): array {
        $walled = [];

        $top_and_bottom_wall = array_fill(0, count($this->discs) + 2, DiscType::WALL);

        $walled[] = $top_and_bottom_wall;

        collect($this->discs)->each(function ($line) use (&$walled) {
            $walled_line = [DiscType::WALL, ...$line, DiscType::WALL];
            $walled[] = $walled_line;
        });

        $walled[] = $top_and_bottom_wall;

        return $walled;
    }

    public function getDiscs(): array {
        return $this->discs;
    }
}
