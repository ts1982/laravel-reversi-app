import React from "react";

export type BoardHookProps = {
    nextDisc: number;
    turnCount: number;
    setBoard: React.Dispatch<React.SetStateAction<number[][]>>;
    setNextDisc: React.Dispatch<React.SetStateAction<number>>;
    setWinnerDisc: React.Dispatch<React.SetStateAction<number | null>>;
    setPreviousDisc: React.Dispatch<React.SetStateAction<number | null>>;
    setTurnCount: React.Dispatch<React.SetStateAction<number>>;
};
