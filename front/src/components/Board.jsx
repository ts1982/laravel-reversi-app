import { Alert, CircularProgress } from '@mui/material'
import { DARK, EMPTY } from '../common/constants'
import React, { useEffect, useState } from 'react'
import { BoardHooks } from '../hooks/boardHooks'

export const Board = () => {
  const [board, setBoard] = useState([])
  const [previousDisc, setPreviousDisc] = useState()
  const [nextDisc, setNextDisc] = useState()
  const [winnerDisc, setWinnerDisc] = useState()
  const [turnCount, setTurnCount] = useState(0)
  const [loading, setLoading] = useState(true)

  const {
    registerGame, showBoard, discToString, warningMessage, flipDisc
  } = BoardHooks(
    nextDisc, turnCount, setBoard, setNextDisc, setWinnerDisc, setPreviousDisc,
    setTurnCount)

  const fetchData = async () => {
    await registerGame()
    await showBoard(0)
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
                         onClick={() => flipDisc(square, x, y)}>
              <div className={`stone ${color}`}></div>
            </div>)
          })
        })}
      </div>
      {!winnerDisc && <p className="next-disc-message">{`次は${discToString(
        nextDisc)}の番です`}</p>}
    </main>
  </>)
}
