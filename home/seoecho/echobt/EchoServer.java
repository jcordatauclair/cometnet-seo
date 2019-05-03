
import java.io.IOException;
import java.net.*;
import java.io.BufferedWriter;
import java.io.FileWriter;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.UnknownHostException;
import java.io.PrintWriter;
import java.util.concurrent.*;
import java.util.*;
import java.nio.charset.Charset;

public class EchoServer {

    public static void main(String[] zero) {

        // le serveur peut etre appele avec differents parametres :
        // - nombre de threads sur lequel il va tourner
        // - numero de port
        // il y a des valeurs par defaut
        int nbthreads = Runtime.getRuntime().availableProcessors();
        int port = 2022;

        for (int z = 0; z < zero.length; z = z + 2) {
            if (zero[z].compareTo("nbthreads") == 0) {
                nbthreads = Integer.parseInt(zero[z + 1]);
            }
            if (zero[z].compareTo("port") == 0) {
                port = Integer.parseInt(zero[z + 1]);
            }

        }

        ServerSocket socket;
        //int nbthreads=1;
        System.out.println("nombre de threads=" + nbthreads);
        System.setProperty("file.encoding", "UTF-8");
        System.out.println("Default Charset=" + Charset.defaultCharset());
        //socket = new ServerSocket(2009);
        try {
            int nbr = 1;
            //création du serveur
            socket = new ServerSocket(port);
            // création d'un pool 5 threads pour pouvoir répondre à 5 demandes simultanément
            int i = 1;
            ArrayList pool_of_threads = new ArrayList();
            while (i <= nbthreads) {
                Thread t = new Thread(new Controleur(socket, i, pool_of_threads));
                pool_of_threads.add(t);
                t.start();
                i++;
            }

            System.out.println("ready !");
        } catch (IOException e) {

            /*e.printStackTrace();*/
        }
    }
}

class Controleur implements Runnable {

    private ServerSocket socketserver;
    private Socket socket;
    BufferedReader in;
    PrintWriter out;
    private int nb = 0;
    private ArrayList manager;
    private Thread t1;

    public Controleur(ServerSocket s, int nbr, ArrayList p) {
        socketserver = s;
        nb = nbr;
        manager = p;
    }

    public void run() {

        BufferedWriter ficlog; // fichier de log

        try {
            while (true) {

                socket = socketserver.accept(); // Un client se connecte on l'accepte
                //--------------------------------------------------
                // traitement pour interrompre le thread lorsqu'au bout d'un certain
                // temps, le thread n'a pas rendu sa reponse

                Timer timer = new Timer();
                int walltime = 60; // delai autorisé pour l'accomplissement de la tache
                t1 = (Thread) (manager.get(nb - 1));
                timer.schedule(new TimerTask() {
                    public void run() {
                        try {
                            if (!socket.isClosed()) {
                                //System.out.println("dépassement du temps pour "+nb);
                                out = new PrintWriter(socket.getOutputStream());
                                out.println("error");
                                out.flush();
                                //System.out.println("interruption");	
                                t1.interrupt();
                                //System.out.println("fermeture du socket "+nb);
                                socket.close();
                                //System.out.println("nettoyage");	
                                t1.join();
                                //System.out.println("relance de"+nb);
                                Thread t2 = new Thread(new Controleur(socketserver, nb, manager));
                                manager.set(nb - 1, t2);
                                t2.start();
                                BufferedWriter ficlog = new BufferedWriter(new FileWriter("logs", true));
                                String date = new Date().toString();
                                ficlog.write("INTERRUPTION :" + date + " interruption");
                                ficlog.newLine();
                                ficlog.flush();
                                ficlog.close();
                            }
                        } catch (Exception e) {
                            System.out.println("socket fermé");
                            e.printStackTrace();
                        }
                    }
                }, walltime * 1000);
                //}
                // 

                //System.out.println("cette demande est traitée par "+nb);
                //Thread.sleep(10000);
                String error = "no";
                in = new BufferedReader(new InputStreamReader(socket.getInputStream()));
                Network N = new Network(100);
                String chaine = "";
                String s = in.readLine();

                // ------------------------------------------------------
                // ecriture de la commande dans le fichier de log
                // ------------------------------------------------------
                ficlog = new BufferedWriter(new FileWriter("logs", true));
                String date = new Date().toString();
                if ((s.length()) < 80) {
                    ficlog.write("COMMANDE : " + date + " : " + s);
                } else {
                    int l = s.length();
                    ficlog.write("COMMANDE : " + date + " : " + s.substring(0, 80) + "..." + s.substring(l - 80, l));
                }
                ficlog.newLine();
                ficlog.flush();
                ficlog.close();
                //---------------------------------------------------------

                //System.out.println("recup chaine a traiter " + s);

                while (((s == null) || (!(s.endsWith(";mfin"))))) {
                    chaine = chaine + s;
                    s = in.readLine();
                }
                chaine = chaine + s.substring(0, s.length() - 4);
                //System.out.println("chaine a traiter " + chaine);

                error = N.test_chaine(chaine);
                if (error == "no") {
                    N.construction(chaine);
                    //N.info();
                    // retourne les N.nb meilleurs termes
                    String r = N.best_terms();
                    //System.out.println("meilleurs termes " + r);
                    //System.out.println("chaine retournee "+r);
                    //System.out.println("traitement terminée");
                    out = new PrintWriter(socket.getOutputStream());
                    out.println(r);
                    out.flush();
                    date = new Date().toString();
                    ficlog = new BufferedWriter(new FileWriter("logs", true));
                    ficlog.write("RESULTAT :" + date + " traitement terminé");
                    ficlog.newLine();
                    ficlog.flush();
                    ficlog.close();
                } else {
                    System.out.println("error: " + error);
                    ficlog = new BufferedWriter(new FileWriter("logs", true));
                    ficlog.write("RESULTAT : chaine invalide traitement non effectué");
                    ficlog.newLine();
                    ficlog.flush();
                    ficlog.close();
                }

                socket.close();
            }
        } catch (Exception e) {
            System.out.println("interruption ");
            /*e.printStackTrace();*/
        }

    }

}
