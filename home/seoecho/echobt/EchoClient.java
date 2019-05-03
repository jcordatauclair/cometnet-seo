import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.InetAddress;
import java.net.Socket;
import java.net.UnknownHostException;
import java.io.IOException;
import java.util.Scanner;

public class EchoClient {
    
    public static void main(String[] zero) {
        
		int port=2020;
		
		for (int z=0; z<zero.length; z=z+2) {
			if (zero[z].compareTo("port")==0) { port=Integer.parseInt(zero[z+1]);}
		}
        
        Socket socket;
        BufferedReader in;
        PrintWriter out;
        try {
                Scanner sc = new Scanner(System.in);
                socket = new Socket(InetAddress.getLocalHost(),port);   
                System.out.println("Demande de connexion");
                out = new PrintWriter(socket.getOutputStream());
                System.out.println("Votre message: ");
                String message = sc.nextLine();
                out.println(message);
                out.flush();       
                in = new BufferedReader (new InputStreamReader (socket.getInputStream()));
                String message_distant = in.readLine();
                System.out.println(message_distant);
                    
            //socket.close();
               
        }catch (UnknownHostException e) {
            
            e.printStackTrace();
        }catch (IOException e) {
            
            e.printStackTrace();
        }
    }
}