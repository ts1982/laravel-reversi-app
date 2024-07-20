import { baseUrl, DARK, LIGHT, WINNER_DRAW } from '../common/constants'

export const BoardHooks = (nextDisc, turnCount, setBoard, setNextDisc,
  setWinnerDisc,
  setPreviousDisc, setTurnCount) => {
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
        return `${discToString(winnerDisc)}の勝ちです!`
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

  return {
    registerGame,
    showBoard,
    discToString,
    warningMessage,
    flipDisc,
  }
}
