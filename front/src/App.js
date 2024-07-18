import './App.css'
import { useEffect, useState } from 'react'

function App () {
  const baseUrl = 'http://localhost:8000'

  const EMPTY = 0
  const DARK = 1
  const LIGHT = 2

  const WINNER_DRAW = 0

  const [board, setBoard] = useState([])
  const [previousDisc, setPreviousDisc] = useState()
  const [nextDisc, setNextDisc] = useState()
  const [winnerDisc, setWinnerDisc] = useState()
  const [turnCount, setTurnCount] = useState(0)

  const showBoard = async (turnCount, previousDisc) => {
    const response = await fetch(
      `${baseUrl}/api/games/latest/turns/${turnCount}`)
    const responseBody = await response.json()
    setBoard(responseBody.board)
    setNextDisc(responseBody.nextDisc)
    setWinnerDisc(responseBody.winnerDisc)
    setPreviousDisc(previousDisc)
  }

  const discToString = (disc) => {
    return disc === DARK ? '黒' : '白'
  }

  const warningMessage = (previousDisc, nextDisc, winnerDisc) => {
    if (nextDisc !== null) {
      if (previousDisc === nextDisc) {
        const skipped = nextDisc === DARK ? LIGHT : DARK
        return `${discToString(skipped)}の番はスキップです`
      } else {
        return null
      }
    } else {
      if (winnerDisc === WINNER_DRAW) {
        return '引き分けです'
      } else {
        return `${discToString(winnerDisc)}の勝ちです`
      }
    }
  }

  const registerGame = async () => {
    await fetch(`${baseUrl}/api/games`, {
      method: 'POST'
    })
  }

  const registerTurn = async (turnCount, disc, x, y) => {
    const requestBody = {
      turnCount: turnCount,
      move: {
        disc,
        x,
        y
      }
    }

    await fetch(`${baseUrl}/api/games/latest/turns`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(requestBody)
    })
  }

  const flipDisc = async (square, x, y) => {
    const nextTurnCount = turnCount + 1
    await registerTurn(nextTurnCount, nextDisc, x, y)
    await showBoard(nextTurnCount, nextDisc)
    setTurnCount(nextTurnCount)
  }

  const fetchData = async () => {
    await registerGame()
    await showBoard(0)
  }

  useEffect(() => {
    fetchData()
  }, [])

  return (
    <div className="App">
      <header>
        <h1>Laravel reversi app</h1>
      </header>
      <main>
        <div className="warning-message-area">
          <p className="warning-message">
            {warningMessage(previousDisc, nextDisc, winnerDisc)}
          </p>
        </div>
        <div className="board">
          {board.map((line, y) => {
            return line.map((square, x) => {
              let color = ''
              if (square !== EMPTY) {
                color = square === DARK ? 'dark' : 'light'
              }
              return (
                <div key={x} className="square"
                     onClick={() => flipDisc(square, x, y)}>
                  <div className={`stone ${color}`}></div>
                </div>
              )
            })
          })}
        </div>
        {!winnerDisc && <p>{`次は${discToString(nextDisc)}の番です`}</p>}
      </main>
    </div>
  )
}

export default App
