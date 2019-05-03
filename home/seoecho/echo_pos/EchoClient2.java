import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.InetAddress;
import java.net.Socket;
import java.net.UnknownHostException;
import java.io.IOException;
import java.util.Scanner;

public class EchoClient2 {
    
    public static void main(String[] zero) {
        
        
        Socket socket;
        BufferedReader in;
        PrintWriter out;
        try {
                socket = new Socket(InetAddress.getLocalHost(),2009);   
                System.out.println("Demande de connexion\n");
                out = new PrintWriter(socket.getOutputStream());
				String message ="001 1 carre grand haut gauche ;002 1 carre petit noir bas droite ;003 1 cercle blanc droite grand ;004 0 cercle grand bas droite ;005 2 cercle petit noir bas gauche ;006 2 cercle grand bas gauche ;007 1 carre blanc ;008 0 cercle grand blanc droite ;009 1 carre grand haut droite ;010 0 carre grand noir droite ;011 0 carre grand haut droite ;012 0 cercle grand haut droite ;013 0 cercle blanc gauche grand ;014 1 carre petit blanc haut droite ;015 1 carre grand haut droite ;mfin\n";		
				System.out.println("chaine envoyee: "+message);
				out.println(message);
				out.flush();       
                in = new BufferedReader (new InputStreamReader (socket.getInputStream()));
                String message_distant = in.readLine();
                System.out.println("chaine recue: "+message_distant);
                    
            //socket.close();
               
        }catch (UnknownHostException e) {
            
            e.printStackTrace();
        }catch (IOException e) {
            
            e.printStackTrace();
        }
    }
}