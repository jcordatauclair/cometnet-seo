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
        
		int port=2020;
		
		for (int z=0; z<zero.length; z=z+2) {
			if (zero[z].compareTo("port")==0) { port=Integer.parseInt(zero[z+1]);}
		}
        
        Socket socket;
        BufferedReader in;
        PrintWriter out;
        try {
                socket = new Socket(InetAddress.getLocalHost(),port);   
                System.out.println("Demande de connexion\n");
                out = new PrintWriter(socket.getOutputStream());
                String message = "001 1 carre grand haut gauche ;"
                        + "002 1 carre petit noir bas droite ;"
                        + "003 2 cercle blanc droite grand ;"
                        + "005 2 cercle petit noir bas gauche ;"
                        + "006 1 carre grand bas gauche ;"
                        + "007 1 carre grand noir droite ; "
                        + "009 1 carre grand haut droite ;mfin\n";
				System.out.println("chaine envoyee : "+message);
                out.println(message);
                out.flush();       
                in = new BufferedReader (new InputStreamReader (socket.getInputStream()));
                String message_distant = in.readLine();
				System.out.println("chaine recue : "+message_distant);
                    
            //socket.close();
               
        }catch (UnknownHostException e) {
            
            e.printStackTrace();
        }catch (IOException e) {
            
            e.printStackTrace();
        }
    }
}