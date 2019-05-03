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


		int port=2009;

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
                //out.println("1011 2 quand l’ocde s’intéresse au « bien-être » des gens une étude de l'organisation de coopération et de développement économiques passe au crible deux siècles d’indicateurs sur la qualité de vie dans le monde. si des progrès ont été enregistrés, les inégalités continuent de se creuser. ;1013 2 incendie de morlaix : le bonnet rouge libéré l'homme, âgé d'une quarantaine d'années, reconnaît avoir participé à la manifestation, mais conteste son implication dans les incendies. ;1014 2 feu vert de bruxelles pour la construction de deux centrales nucléaires d'edf en angleterre la commission impose néanmoins des restrictions qui réduiront la rentabilité du projet, déjà fortement entamée depuis l'accident de fukushima. ;1015 1 ebola : « pas de risque zéro » mais « la france est prête à faire face », selon touraine la ministre marisol touraine, a assuré mercredi sur france info que le système de santé français était prêt à faire face à une éventuelle propagation du virus. ;1018 2 l’acidification des océans aura d’importantes conséquences pour la biodiversité si les émissions de co2 se poursuivent au rythme actuel, l’acidité des mers devrait augmenter de 170 % d’ici à 2100, selon un rapport. ;1019 1 l'ue organise une opération aérienne pour les pays d'afrique touchés par le virus ebola face à la progression de la fièvre hémorragique ebola, qui a fait 3 349 morts en afrique de l'ouest, l'ue a décidé d'envoyer une aide sur place. ;1020 2 ségolène royal refuse les forages dans le parc du luberon la ministre de l'écologie a rejeté le permis de recherche d'hydrocarbures demandé par la société suédoise tethys oil ab sur le territoire du parc naturel régional. ;1021 1 ebola : à l’hôpital carlos iii de madrid, le personnel médical « inquiet et en colère » la contamination d’une aide-soignante par un missionnaire infecté par le virus suscite de nombreux questionnements en espagne. ;1022 2 comment mener à bien la transition énergétique ? la loi sur la transition énergétique est entrée en discussion  à l’assemblée nationale. chercheurs et experts prennent parti sur les modalités d’un texte soumis aux pressions de nombreux lobbies. ;1023 2 transition énergétique : fantasmes français, réalité allemande la transition énergétique, en débat aujourd’hui en france, serait un échec en allemagne. la réalité est plus complexe, expliquent stephen boucher, directeur de programme à l’european climate foundation, et dimitri pescia, associé d’agora energiewende. ;1025 2 le véritable enjeu des débats sur le principe de précaution le principe de précaution est sur la sellette : il pourrait être retiré de la constitution française. mais pour nicolas treich (toulouse school of economics), l’enjeu n’est pas la nécessité de le retirer, ou le reformuler. il a en effet surtout une portée symbolique, et n’est pas opérationnel. ;1027 2 principe de précaution : ne nous trompons pas de combat ! certains décrient le principe de précaution qui entraverait l’activité économique et empêcherait l’innovation. mais pour thierry weil, professeur à mines paristech, la précaution s’avère en général plus rentable que l’inaction. ;1028 2 le chèque énergie : une mesure à perfectionner le chèque-énergie, disposition de la loi de transition énergétique actuellement en discussion à l’assemblée, est à la fois une aide sociale aux ménages modestes et un outil de politique environnementale. a condition que ses modalités soient amplifiées, estiment lucas chancel et mathieu saujot, chercheurs à l'iddri. ;1071 2 climat : les élus locaux déplorent le manque d'ambition de la commission européenne le comité des régions demande une réduction de 50 % des émissions européennes de gaz à effet de serre et 40 % d'énergies renouvelables d'ici à 2030. ;1070 0 ebola : la prise en charge du patient libérien mort aux etats-unis critiquée thomas eric duncan, premier malade a avoir été diagnostiqué aux etats-unis, est mort mardi à dallas. ;1072 0 la france intensifie la sensibilisation au risque ebola après la mort d'un patient aux etats-unis et l'infection d'une aide-soignante en espagne, les autorités sanitaires vont multiplier les messages aux professionnels de santé. ;1073 0 ebola : l'espagnole contaminée aurait touché un gant infecté certains syndicats et membres du personnel de santé dénoncent des défaillances qui ont mené à la contamination de l'aide-soignante. ;1074 0 aux etats-unis, contrôles renforcés dans les aéroports après la mort d'un patient après la mort du patient libérien, les contrôles sont renforcés dans les cinq aéroports où arrivent la plupart des passagers en provenance d'afrique de l'ouest. ;1075 0 le chien de l'espagnole contaminée par ebola a été euthanasié les défenseurs des droits des animaux étaient horrifiés par la décision des autorités espagnoles, qui tentent par tous les moyens d'éviter une contagion à plus grande échelle. ;1010 0 les intempéries vont coûter 320 millions d'euros aux assureurs près de 70 000 sinistres ont été déclarés dans les départements du gard, de l'aveyron et de l'hérault après les intempéries de fin septembre. ;1012 0 liberia : un employé de l'onu contaminé par ebola ni l'identité ni la nationalité de ce membre des services médicaux de la mission des nations unies au liberia n'ont été communiqués. ;1016 0 faut-il euthanasier le chien de l'espagnole contaminée par ebola ? les défenseurs des droits des animaux sont horrifiés par la décision des autorités espagnoles, qui tentent par tous les moyens d'éviter une contagion à plus grande échelle. ;1017 0 notre-dame-des-landes : un document de travail évoque un début de construction france bleu loire océan s'est procuré un document de travail confidentiel des services du premier ministre dans lequel figure le plan de financement de la desserte du futur aéroport. ;1024 0 ebola : au liberia, le déni du risque sanitaire ce pays d'afrique de l'ouest est le plus sévèrement touché par le virus, mais la population de la capitale, monrovia, est de plus en plus défiante vis-à-vis des autorités. reportage en images. ;1026 0 liberia : la vie reprend à monrovia malgré l'épidémie d'ebola l'aide médicale internationale arrive au liberia. christophe châtelot, envoyé spécial dans ce pays, fait le point sur l'épidémie à monrovia, la capitale. ;1029 0 certificats d’économies d’énergie : le principe revisité du « pollueur-payeur » la loi de transition énergétique, en discussion à l'assemblée nationale, devra renforcer et développer le dispositif des certificats d'économie d'énergie, souhaite olivier gene, dirigeant de copeo, société de services en maîtrise de l'énergie. ;mfin");
                out.println(message);
				out.flush();
                in = new BufferedReader (new InputStreamReader (socket.getInputStream()));
                String message_distant = in.readLine();
                System.out.println("recu"+message_distant);

            //socket.close();

        }catch (UnknownHostException e) {

            e.printStackTrace();
        }catch (IOException e) {

            e.printStackTrace();
        }
    }
}
