package TicTacToe;
import java.net.ServerSocket;

/**
 * A server for a network multi-player tic tac toe game. 
 * It uses a unique 'protocol' which is plain text.
 * The strings that are sent are:
 *
 *  Client -> Server           Server -> Client
 *  ----------------           ----------------
 *  MOVE <n>  (0 <= n <= 8)    WELCOME <char>  (char in {X, O})
 *  QUIT                       VALID_MOVE
 *  						   YOUR_MOVE 
 *                             OTHER_PLAYER_MOVED <n>
 *                             VICTORY
 *                             DEFEAT
 *                             TIE
 *                             MESSAGE <text>
 */

public class TicTacToeServer {

    /** Runs the application. Pairs up clients that connect. */
    public static void main(String[] args) throws Exception {
        ServerSocket listener = new ServerSocket(8901);
        System.out.println("Tic Tac Toe Server is Running");
        try {
            while (true) { 
                Game game = new Game();
                Game.Player playerX = game.new Player(listener.accept(), "X"); // 1st player that connect is X
                Game.Player playerO = game.new Player(listener.accept(), "O"); // 2nd player that connects is O
                game.currentPlayer = playerX; 
                playerO.setOpponent(playerX);
                playerX.setOpponent(playerO);
                playerX.start(); // start thread
                playerO.start(); // start thread */  
            }
        } 
        catch (Exception e) {
        		System.out.println(e);
        }
        	finally {
            listener.close();
        }
    }
}