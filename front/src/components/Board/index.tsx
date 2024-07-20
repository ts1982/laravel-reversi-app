import {Alert, CircularProgress} from '@mui/material'
import {DARK, EMPTY} from '../../common/constants'
import React, {useEffect, useState} from 'react'
import {BoardHook} from './hooks'

export const Board = () => {
    const [board, setBoard] = useState<number[][]>([])
    const [previousDisc, setPreviousDisc] = useState<number | null>(null)
    const [nextDisc, setNextDisc] = useState<number>(1)
    const [winnerDisc, setWinnerDisc] = useState<number | null>(null)
    const [turnCount, setTurnCount] = useState<number>(0)
    const [loading, setLoading] = useState<boolean>(true)

    const {
        registerGame, showBoard, discToString, warningMessage, flipDisc
    } = BoardHook({
        nextDisc, turnCount, setBoard, setNextDisc, setWinnerDisc,
        setPreviousDisc, setTurnCount
    })

    const fetchData = async () => {
        await registerGame()
        await showBoard(0, null)
    }

    useEffect(() => {
        fetchData()
            .then(() => setLoading(false))
            .catch((err) => {
                console.error(err)
            })
    }, [])

    if (loading) {
        return <div className="center"><CircularProgress/></div>
    }

    return (<>
        <header>
            <h1>Laravel reversi app</h1>
        </header>
        <main>
            <div className="warning-message-area">
                {warningMessage(previousDisc, nextDisc, winnerDisc) &&
                  <Alert variant="filled"
                         severity={winnerDisc === null ? 'warning' : 'info'}>
                      {warningMessage(previousDisc, nextDisc, winnerDisc)}
                  </Alert>}
            </div>
            <div className="board">
                {board.map((line, y) => {
                    return line.map((square, x) => {
                        let color = ''
                        if (square !== EMPTY) {
                            color = square === DARK ? 'dark' : 'light'
                        }
                        return (<div key={x} className="square"
                                     onClick={() => flipDisc(x, y)}>
                            <div className={`stone ${color}`}></div>
                        </div>)
                    })
                })}
            </div>
            {!winnerDisc &&
              <p>{`次は${discToString(nextDisc)}の番です`}</p>}
        </main>
    </>)
}
