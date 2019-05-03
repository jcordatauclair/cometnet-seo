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
        
        
        Socket socket;
        BufferedReader in;
        PrintWriter out;
        try {
                Scanner sc = new Scanner(System.in);
                socket = new Socket(InetAddress.getLocalHost(),2022);   
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
