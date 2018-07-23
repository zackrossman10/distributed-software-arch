package TicTacToe;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.Socket;
import java.util.Scanner;

/**
 * A client for the TicTacToe game modified and extended. I uses a text-based
 * protocal to communicate with the server. Here are the strings that are sent:
 *
 * Client -> Server Server -> Client ---------------- ---------------- MOVE <n>
 * (0 <= n <= 8) WELCOME <char> (char in {X, O}) QUIT VALID_MOVE YOUR_MOVE
 * OTHER_PLAYER_MOVED <n> VICTORY DEFEAT TIE MESSAGE <text>
 */

public class TicTacToeClient {

	private static int PORT = 8901;
	private Socket socket;
	private BufferedReader in;
	private PrintWriter out;

	/**
	 * Constructs the client by connecting to a server, laying out the GUI and
	 * registering GUI listeners.
	 */
	public TicTacToeClient(String serverAddress) throws Exception {

		// Setup networking
		socket = new Socket(serverAddress, PORT);
		in = new BufferedReader(new InputStreamReader(socket.getInputStream()));

		out = new PrintWriter(socket.getOutputStream(), true);

	}

	/**
     * TODO - This thread will listen for messages from the server.
     * (1) The first message will be a "WELCOME" message in which we receive our mark.
     * (2) Then we go into a loop listening for "VALID_MOVE", "OPPONENT_MOVED", "VICTORY",
     * "DEFEAT", "TIE", "OPPONENT_QUIT or "MESSAGE" messages, and handling each message appropriately.
     * (3) The "VICTORY", "DEFEAT" and "TIE" are printed and ask the user whether or not to play
     * another game (this last part is not implemented yet).  If the answer is no, the loop is exited and
     * the server is sent a "QUIT" message.
     * (4) If an OPPONENT_QUIT message is received then the loop will exit and the server
     * will be sent a "QUIT" message also.
     */
    public void play() throws Exception {
        Scanner consoleScanner = new Scanner(System.in);
        try {
        		String response = in.readLine();
            if (response.startsWith("WELCOME")) {
                char mark = response.charAt(8);  // set our mark
            }
            while (true) {

              // TODO - read message received from server
            	// TODO - check the type of message that is received and handle each message appropriately.
            	// TODO - If message starts with "VALID_MOVE"		... simply print to console "Valid move, please wait"
            	// TODO - If message starts with "YOUR_MOVE"		... prompt user for input and send to server
            	// TODO - If message starts with "OPPONENT_MOVED"	... parse response to get cell the opponent choose,
            	//        and print out message "Opponent moved to <CELL>. Its your turn now."
            	// TODO - If message starts with "VICTORY"			... print out "You WIN"
            	// TODO - If message starts with "DEFEAT"			... print out "You LOSE"
            	// TODO - If message starts with "TIE" 				... print out "You TIED"
            	// TODO - If message starts with "MESSAGE"			... print out received message
            	// TODO - If message starts with "OPPONENT_QUIT"	... print out "Your opponent quit" and then send "QUIT" messag
            		response = in.readLine();
            		if(response.startsWith("VALID_MOVE")){
	              System.out.println("Valid move, please wait");
	            }else if(response.startsWith("YOUR_MOVE")){
	              System.out.println("Make a move");
	              String move = consoleScanner.next();
	              System.out.println("Move: "+move);
	              out.println("MOVE "+move);
	            }else if(response.startsWith("OPPONENT_MOVED")){
	              String cell = Character.toString(response.charAt(15));
	              System.out.println("Opponent moved to "+cell+". Its your turn now");
	            }else if(response.startsWith("VICTORY")) {
	            	  System.out.println("You WIN");
	            }else if(response.startsWith("DEFEAT")){
	              System.out.println("You LOSE");
	            }else if(response.startsWith("PLAY_AGAIN")) {
	            	//if player won, ask if they want to play again
	              System.out.println("Do you want to play again? Y/N");
	            	  String playagain = consoleScanner.next();
	            	  //reset if player says yes
	            	  if(playagain.equals("Y")) {
	            		  out.println("RESET");
	            	  }else {
	            		  //otherwise quit
	            		  out.println("QUIT");
	            		  System.out.println("Game Over, Adios1");
	            		  break;
	            	  }
	            }else if(response.startsWith("TIE")) { 
	            		System.out.println("Game was a tie");
	            }else if(response.startsWith("MESSAGE")){
	              System.out.println(response);
	            }else{
	            	  //response = QUIT, let player know game is over
	            	  System.out.println("Game Over, Adios2");
          		  break;
	            }
            	}
        }
        finally {
            socket.close();
        }
    }

	/**
	 * Runs the client as an application.
	 */
	public static void main(String[] args) throws Exception {
		// Game game = new Game();
		String serverAddress = (args.length == 0) ? "localhost" : args[1];
		TicTacToeClient client = new TicTacToeClient(serverAddress);
		client.play();

	}
}
