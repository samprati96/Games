  <?php

  // Initialize the game state
  session_start();
  if (!isset($_SESSION['board']) || isset($_POST['restart'])) {
      $_SESSION['board'] = [
          ['', '', ''],
          ['', '', ''],
          ['', '', '']
      ];
      $_SESSION['player'] = 'X';
  }
  $winner = checkWinner($_SESSION['board']); // Check for winner on game start

  // Function to check if there's a winner
  function checkWinner($board) {
      // Check rows
      for ($i = 0; $i < 3; $i++) {
          if ($board[$i][0] !== '' && $board[$i][0] === $board[$i][1] && $board[$i][1] === $board[$i][2]) {
              return $board[$i][0];
          }
      }

      // Check columns
      for ($j = 0; $j < 3; $j++) {
          if ($board[0][$j] !== '' && $board[0][$j] === $board[1][$j] && $board[1][$j] === $board[2][$j]) {
              return $board[0][$j];
          }
      }

      // Check diagonals
      if ($board[0][0] !== '' && $board[0][0] === $board[1][1] && $board[1][1] === $board[2][2]) {
          return $board[0][0];
      }
      if ($board[0][2] !== '' && $board[0][2] === $board[1][1] && $board[1][1] === $board[2][0]) {
          return $board[0][2];
      }

      // Check for tie
      $emptyCells = 0;
      foreach ($board as $row) {
          foreach ($row as $cell) {
              if ($cell === '') {
                  $emptyCells++;
              }
          }
      }
      if ($emptyCells === 0) {
          return 'Tie';
      }

      return null;
  }

  // Process player's move
  if (isset($_POST['move'])) {
      $row = $_POST['row'];
      $col = $_POST['col'];

      if ($_SESSION['board'][$row][$col] === '') {
          $_SESSION['board'][$row][$col] = ($_SESSION['player'] === 'X') ? 'X' : 'O';
          // Switch player after each move
          $_SESSION['player'] = ($_SESSION['player'] === 'X') ? 'O' : 'X';
      }

      // Check for winner or tie
      $winner = checkWinner($_SESSION['board']);
  }
  ?>
  <!DOCTYPE html>
  <html>
  <head>
      <title>Tic Tac Toe</title>
      <style>
          body {
              justify-content: center;
              align-items: center;
              height: 100vh;
              margin: 0;
              background: #f7d6e0;
              background-repeat: repeat;
              background-size: 100% 100%, 30% 30%, 100% 100%;
          }

          #container {
              display: flex;
              flex-direction: column;
              align-items: center;
          }


          #game-container {
              max-width: 45rem;
              padding: 2rem;
              border-radius: 6px;
              background: linear-gradient(#383624, #282617);
              box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
              position: relative;
              text-align: center; /* Center text horizontally */
          }

          #game-board {
              display: flex;
              flex-wrap: wrap;
              justify-content: center;
              gap: 2rem;
              margin: 3rem 0;
              padding: 0;
              flex-direction: column;

          }

          #game-board ol {
              display: flex;
              flex-wrap: wrap;
              justify-content: center;
              gap: 2rem;
              margin: 0;
              padding: 0;
          }

          #game-board button {
              width: 8rem;
              height: 8rem;
              border: none;
              background: #f2b5d4;
              color: #eff7f6;
              font-size: 5rem;
              cursor: pointer;
              font-family: 'Caprasimo', cursive;
              padding: 1rem;
          }

          h1 {
              margin-top: 0rem;
              margin-bottom: 1rem;
              text-align: center;
              align-self: flex-start;
              color: #5e548e;
              font-family: 'Caprasimo', cursive;
              font-size: -webkit-xxx-large;
              background-color: #dec9e9;
              border-bottom-left-radius: 50%;
              border-bottom-right-radius: 50%;
          }

          h2 {
              margin-top: 1rem;
              margin-bottom: 1rem;
              text-align: center;
              align-self: flex-start;
              color: #8b687f;
              font-family: 'Caprasimo', cursive;
              font-size: -webkit-xxx-large;
          }

          #restart {
              margin-top: 1rem;
              padding: 0.5rem 1rem;
              border: none;
              border-radius: 4px;
              background-color: #be95c4;
              font-family: 'Caprasimo', cursive;
              color: #5e548e;
              font-size: 1.4rem;
              font-weight: bold;
              cursor: pointer;
              transition: background-color 0.3s, color 0.3s;
              border-radius: 50%;
              width: 100px;
              height: 100px;
          }

          #restart:hover {
              background-color: #9f86c0;
              color: #fff;
          }

          #restart:focus {
              outline: none;
          }

          #restart:active {
              transform: translateY(1px);
          }
      </style>
  </head>
  <body>
      <h1>TIC TAC TOE</h1>
      <div id="container">
      <form method="post">
          <button type="submit" name="restart" value="restart" id="restart">Restart</button>
      </form>

      <table id="game-board">
          <?php for ($i = 0; $i < 3; $i++): ?>
              <tr>
                  <?php for ($j = 0; $j < 3; $j++): ?>
                      <td>
                          <form method="post">
                              <input type="hidden" name="row" value="<?php echo $i; ?>">
                              <input type="hidden" name="col" value="<?php echo $j; ?>">
                              <button type="submit" name="move" value="submit" id="cell-<?php echo $i . '-' . $j; ?>"><?php echo $_SESSION['board'][$i][$j]; ?></button>
                          </form>
                      </td>
                  <?php endfor; ?>
              </tr>
          <?php endfor; ?>
      </table>
      </div>

      <?php if ($winner !== null): ?>
          <?php if ($winner === 'Tie'): ?>
              <h2 id="game-over-message">It's a Tie!</h2>
          <?php else: ?>
              <h2 id="game-over-message"><?php echo $winner; ?> Wins!</h2>
          <?php endif; ?>
      <?php endif; ?>
  </body>
  </html>
