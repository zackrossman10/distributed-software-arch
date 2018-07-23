package TicTacToe;

import java.awt.Color;
import java.awt.GridLayout;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.Socket;

import javax.swing.Icon;
import javax.swing.JFrame;
import javax.swing.JLabel;
import javax.swing.JPanel;

/** A two-player game. */
public class Game {

	private JFrame frame = new JFrame("Tic Tac Toe");
	private JLabel messageLabel = new JLabel("");

	/**
	 * A board has nine squares. Each square is either empty or marked by a player.
	 * Its an array of player object references. If null, the corresponding square
	 * is empty, otherwise the array cell stores a reference to the player that owns
	 * it (marked it).
	 */
	private Player[] board = { null, null, null, // cells 0 - 3
			null, null, null, // cells 4 - 5
			null, null, null }; // cells 6 - 8

	/** The current player. */
	Player currentPlayer;

	/**
	 * Returns True if the board reflects a winner, False otherwise.
	 */
	public boolean hasWinner() {
		return (board[0] != null && board[0] == board[1] && board[0] == board[2])
				|| (board[3] != null && board[3] == board[4] && board[3] == board[5])
				|| (board[6] != null && board[6] == board[7] && board[6] == board[8])
				|| (board[0] != null && board[0] == board[3] && board[0] == board[6])
				|| (board[1] != null && board[1] == board[4] && board[1] == board[7])
				|| (board[2] != null && board[2] == board[5] && board[2] == board[8])
				|| (board[0] != null && board[0] == board[4] && board[0] == board[8])
				|| (board[2] != null && board[2] == board[4] && board[2] == board[6]);
	}

	/**
	 * Returns whether there are no more empty squares.
	 */
	public boolean boardFilledUp() {
		for (int i = 0; i < board.length; i++) {
			if (board[i] == null) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Called by the player threads when a player tries to make a move. This method
	 * checks to see if the move is legal: that is, she is trying to mark a field
	 * that is unoccupied. If the move is legal, the game state is updated (the
	 * square is set and the next player becomes current) and the other player is
	 * notified to make a move
	 */
	public synchronized boolean legalMove(int location, Player player) {

		if (player == currentPlayer && board[location] == null) {
			//notify player that move is valid
			currentPlayer.notifyValid();
			board[location] = currentPlayer; // set cell with player reference (or mark)
			currentPlayer = currentPlayer.opponent; // make opponent as the current player
			currentPlayer.otherPlayerMoved(location);
			drawGUI();
			return true;
		}
		return false;
	}

	/*
	 * Draw GUI to show the state of the board
	 */
	public void drawGUI() {
		// Layout GUI
		System.out.println("hello");
		messageLabel.setBackground(Color.lightGray);
		frame.getContentPane().add(messageLabel, "South");
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		frame.setSize(240, 160);
		frame.setVisible(true);
		frame.setResizable(false);
		Square[] playingBoard = new Square[9];
		JPanel boardPanel = new JPanel();
		boardPanel.setBackground(Color.black);
		boardPanel.setLayout(new GridLayout(3, 3, 2, 2));
		for (int i = 0; i < board.length; i++) {
			playingBoard[i] = new Square();
			if (board[i] != null) {
				playingBoard[i].label.setText(board[i].mark);
			} else {
				playingBoard[i].label.setText("NULL");
			}
			boardPanel.add(playingBoard[i]);
		}
		frame.getContentPane().add(boardPanel, "Center");

	}

	/**
	 * A thread in this multi-threaded server application. A Player is identified by
	 * a 'X' or 'O'. The Player class maintains the client's input and output stream
	 * to send and receive messages. Since we are only sending text, we can use a
	 * reader and a writer.
	 */

	class Player extends Thread {

		String mark; // X or O
		Player opponent; // X or O
		Socket socket;
		BufferedReader input; // reading input from client
		PrintWriter output; // sending messages to client

		/**
		 * Constructs a handler thread for a given socket and initializes the stream
		 * fields.
		 */
		public Player(Socket socket, String mark) {

			this.socket = socket;
			this.mark = mark;

			try {
				input = new BufferedReader(new InputStreamReader(socket.getInputStream()));
				output = new PrintWriter(socket.getOutputStream(), true);

				// TODO - Send message "WELCOME <mark>" which is X or O
				output.println("WELCOME " + mark);
				// TODO - Send message "MESSAGE Waiting for opponent to connect"
				output.println("MESSAGE Waiting for opponent to connect");

			} catch (IOException e) {
				System.out.println("Player died: " + e);
			}
		}

		
		
		/**
		 *  notify the current player that their move is valid
		 */
		 public void notifyValid() {
			 System.out.println("Valid Move");
			 output.println("VALID_MOVE");
		 }
		
		 /**
		  * notify this player that they won
		  */
		 public void otherPlayerLost() {
			 output.println("VICTORY");
		 }
		 
		 /**
		  *  notify this player that the game is starting over
		  */
		 public void gameStartOver() {
			 output.println("MESSAGE Game was reset");
		 }
		 
		 /**
		  * notify other player that game is finished
		  */
		 public void quit() {
			 output.println("QUIT");
		 }
		 
		 /**
		  * notify other player that game is a tie
		  */
		 public void tie() {
			 output.println("TIE");
		 }
		 
		/**
		 * Accepts notification of who the opponent is.
		 */
		public void setOpponent(Player opponent) {
			this.opponent = opponent;

		}

		/** Handles the otherPlayerMoved message. */
		public void otherPlayerMoved(int location) {
			// TODO - Send message "OPPONENT_MOVED <CELL>"
			output.println("OPPONENT_MOVED " + Integer.toString(location));
			// TODO - Since opponent moved, check whether there was a winner or a tie.
			// If there was a winner, it means you lost, then send "DEFEAT" message.
			// If the board is filled, send "TIE" message.
			// Otherwise, send "YOUR_MOVE"
			if (hasWinner()) {
				//notify player they lost
				output.println("DEFEAT");
				//ask player if they want to play again
				output.println("PLAY_AGAIN");
				//notify the other player that they won
				currentPlayer.opponent.otherPlayerLost();
			} else if (boardFilledUp()) {
				//notify both players of a tie
				output.println("TIE");
				currentPlayer.opponent.tie();
				//ask current player to play again
				output.println("PLAY_AGAIN");
			} else {
				output.println("YOUR_MOVE");
			}
		}

		/** The run method of this thread. */
		public void run() {
			try {
				// The thread is only started after everyone connects.
				output.println("MESSAGE All players connected");
				output.println("MESSAGE Your mark is " + mark);

				// Tell the first player that it is their turn
				if (mark.equals("X")) {
					output.println("YOUR_MOVE Your move");
				}

				/*
				 * Repeatedly get commands from the client and process them. Use
				 * "input.readLine()" to read message from client Possible messages received :
				 * MOVE <CELL> QUIT
				 *
				 * Possible messages sent: VALID_MOVE VICTORY TIE DEFEAT MESSAGE <msg> YOUR_MOVE
				 * ...
				 */

				/*
				 * TODO --- Loop, each time read in the message from the client. If message
				 * "MOVE n", then verify its a legal move by calling function legalMove(.) If
				 * the move is legal, it will automatically update the board. You should then
				 * check if there is a winner (call hasWinner()) or if the board is completely
				 * filled (call boardFilledUp())
				 *
				 * If the move is illegal, you should send a message to the client stating so,
				 * and prompt them to try again.
				 *
				 */	
				
				while (true) {
					String response = input.readLine();
					if (response.startsWith("MOVE")) {
						int locate = Character.getNumericValue(response.charAt(5));
						//notify valid in legal
						if (!legalMove(locate, this)) {
							 System.out.println("Not a valid move, try again.");
							 output.println("YOUR_MOVE");
						}
					}else if(response.startsWith("RESET")){
						// reset the bard for another game
						for (int i = 0; i < 9; i++) {
							board[i] = null;
						}
						//redraw the empty board
						drawGUI();
						//notify opponent that game is starting over
						currentPlayer.opponent.gameStartOver();
						//let current player make first move
						output.println("YOUR_MOVE");
					}else {
						//tell the other player the game is over
						currentPlayer.opponent.quit();
					}
				}

			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			} finally {
				try {
					socket.close();
				} catch (IOException e) {
				}
			}
		}
}

}

//////////////////////////////////////////////////////////////////////////////////////////
/**
 * Graphical square in the client window. Each square is a white panel
 * containing. A client calls setIcon() to fill it with an Icon, presumably an X
 * or O.
 */
class Square extends JPanel {
	JLabel label = new JLabel((Icon) null);

	public Square() {
		setBackground(Color.white);
		add(label);
	}

	public void setIcon(Icon icon) {
		label.setIcon(icon);
	}
}
