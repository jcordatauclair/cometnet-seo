
import java.io.*;
import java.util.*;
import java.text.*;
import java.lang.*;
import java.lang.*;

//=============================================================================//
//                                 CLASSE Network
//=============================================================================//
public class Network {
    //------------------------------------------------------
    //                    CHAMPS
    //------------------------------------------------------

    public HashMap les_noeuds;      // hashtable des noeuds du reseau
    public int nombre_connections;     // nombre de connections dans le reseau
    public int nombre_doc;
    public int nbt;
    public float active;
    public boolean goodclass;
    public ClassNode gc;
    public int nb;
    public int specif=3;

    //-------------------------------------------------------
    //                    METHODES
    //-------------------------------------------------------
    //-------------------------------------------------------
    //                    CONSTRUCTEURS - INITIALISATION
    //-------------------------------------------------------
    // ------------------------------------------
    // classe      : Network
    // nom         : Network
    // entree(s)   : .
    // sortie(s)   : le reseau construit
    // description : constructeur de base
    // remarque    : .
    // ------------------------------------------
    public Network(int n) {
        les_noeuds = new HashMap(n);
        nombre_connections = 0;
        nombre_doc = 0;
        active = 20;
        nbt = 0;
        nb=200; // Nombre de termes à renvoyer
        Locale.setDefault(new Locale("fr", "FRANCE"));
        //Locale.setDefault(new Locale("en", "US"));
    }

    // ------------------------------------------
    // classe      : Network
    // nom         : afficher_noeud
    // entree(s)   : .
    // sortie(s)   : .
    // description : affichage detaille du noeud d'identite id
    // remarque    : .
    // ------------------------------------------
    public void afficher_noeud(String id) {
        TermNode n = (TermNode) les_noeuds.get(id);
        if (n != null) {
            n.afficher();
        } else {
            System.out.println("terme inconnu");
        }
    }

    // ------------------------------------------
    // classe      : Network
    // nom         : info
    // entree(s)   :
    // sortie(s)   :
    // description :
    // remarque    :
    // ------------------------------------------
    public void info() {
        System.out.println("nombre de noeuds: " + les_noeuds.size());
        System.out.println("nombre de connections: " + nombre_connections);
        System.out.println("nombre de documents: " + nombre_doc);
    }

    // ------------------------------------------
    // classe      : Network
    // nom         :
    // entree(s)   :
    // sortie(s)   :
    // description :
    // remarque    :
    // ------------------------------------------
    public void construction(String chaine) {

        ArrayList liste_des_termes = new ArrayList();

        int i = 0;
        String docid;
        char reljug;
        String terme;
        gc = new ClassNode(this);
        int taille = chaine.length();
        while (i < taille) {

            //***********************************************
            // Etape 1 --- LECTURE DU DOCUMENT -------------
            //***********************************************
            // lecture de l'identifiant
            docid = "";
            while (chaine.charAt(i) != ' ') {
                docid = docid + chaine.charAt(i);
                i++;
            }
            //System.out.println("docid: "+docid);
            i++; //lecture du blanc
            reljug = chaine.charAt(i);
            //System.out.println("reljug: "+reljug);
            // document pour apprendre
            if (reljug != '0') {
                goodclass = (reljug == '1');
                liste_des_termes.clear();
                // lecture des mots du doc
                i++;
                i++;
                while (chaine.charAt(i) != ';') {
                    terme = "";
                    while ((chaine.charAt(i) == ' ')) {
                        i++;
                    }
                    while ((chaine.charAt(i) != ' ') && (chaine.charAt(i) != ';')) {
                        terme = terme + chaine.charAt(i);
                        i++;
                    }
                    if (terme != "") {
                        liste_des_termes.add(terme);
                    }
                    //System.out.print(" terme: "+terme+"F"+chaine.charAt(i-1));
                    while ((chaine.charAt(i) == ' ')) {
                        i++;
                    }

                }
                //System.out.println();
                //***********************************************
                // Etape 2 --- INTEGRATION DU DOCUMENT -------------
                //***********************************************
                nbt = liste_des_termes.size();
                integrer_document(liste_des_termes);
            } // document à classer
            else {
                i = chaine.indexOf(';', i);
            }
            i++;
        }
    }

    public String test_chaine(String chaine) {
        int i = 0;
        String docid;
        char reljug;
        int taille = chaine.length();
        String error;
        while (i < taille) {

            //***********************************************
            // Etape 1 --- LECTURE DU DOCUMENT -------------
            //***********************************************
            // lecture de l'identifiant
            docid = "";
            while ((i < taille) && (chaine.charAt(i) != ' ')) {
                docid = docid + chaine.charAt(i);
                i++;
            }
            if (i == taille) {
                error = "colonne " + i + " : format des données invalide." + " On attendait un blanc";
                return error;
            }
            //System.out.println("docid: "+docid);
            i++; //lecture du blanc
            reljug = chaine.charAt(i);
            //System.out.println("reljug: "+reljug);
            // document pour apprendre
            if ((reljug != '0') && (reljug != '1') && (reljug != '2')) {
                error = "colonne " + i + " : format des données invalide." + " La classe doit être 0,1 ou 2.";
                return error;
            }

            // lecture des mots du doc
            i++;
            i++;
            while ((i < taille) && (chaine.charAt(i) != ';')) {
                while ((i < taille) && (chaine.charAt(i) == ' ')) {
                    i++;
                }
                while ((i < taille) && (chaine.charAt(i) != ' ') && (chaine.charAt(i) != ';')) {
                    i++;
                }
                while ((i < taille) && (chaine.charAt(i) == ' ')) {
                    i++;
                }

            }

            while ((i < taille) && (chaine.charAt(i) != ';')) {

                i++;
            }//System.out.println("i: "+i);

            if (i == taille) {
                error = "colonne " + i + " : format des données invalide." + " On attendait un ;";
                return error;
            }
            i++;
        }
        return "no";
    }

    // ------------------------------------------
    // classe      : Network
    // nom         : info
    // entree(s)   :
    // sortie(s)   :
    // description :
    // remarque    :
    // ------------------------------------------
    public void integrer_document(ArrayList liste_des_termes) {

        // activation des noeuds termes
        //System.out.println("traitement_noeuds_simples");
        NodeList liste_noeuds_actives = traitement_noeuds_simples(liste_des_termes);

        // activation eventuelle du noeud classe
        if (goodclass) {
            gc.activation = 1;
        } else {
            gc.activation = 0;
        }

        // construction eventuelle des connections
        //System.out.println("construire_connections_simple");
        liste_noeuds_actives.construire_connections_simple(gc);

        // mise a jour des connections
        //System.out.println("mise_a_jour_connections");
        liste_noeuds_actives.mise_a_jour_connections();

        // reinitialisation des noeuds termes
        //System.out.println("desactiverL");
        liste_noeuds_actives.desactiverL();

        // reinitialisation du noeud classe
        gc.desactiverL();

        nombre_doc++;
    }

    // ------------------------------------------
    // classe      : reseau
    // nom         : traitement_noeuds_simples
    // entree(s)   : Vector liste_termes, liste des termes presents
    //               dans le nouveau document.
    // sortie(s)   : liste des noeuds simples actives
    // description : Construction des noeuds pour les termes jamais
    //               encore rencontres et constitution de la liste des noeuds
    //               simples actives.
    // remarque    : .
    // ------------------------------------------
    public NodeList traitement_noeuds_simples(ArrayList<String> liste_des_termes) {
        int i = 0;
        int taille = liste_des_termes.size();
        NodeList result = new NodeList(this);
        TermNode nouveau;
        TermNode n;
        String s;

        while (i < taille) {
            s = (String) liste_des_termes.get(i);
            n = (TermNode) les_noeuds.get(s);

            if (n != null) {
                if (n.activation == 0) {
                    result.add(n);
                }
                n.activer();
            } else {
                nouveau = new TermNode(this, s);
                nouveau.activer();
                les_noeuds.put(s, nouveau);
                result.add(nouveau);
            }
            i++;
        }
        return (result);
    }

    // ------------------------------------------
    // classe      : reseau
    // nom         : activer_noeuds
    // entree(s)   :
    // sortie(s)   :
    // description :
    // remarque    :
    // ------------------------------------------
    public NodeList activer_noeuds(ArrayList<String> liste_des_termes) {
        int i = 0;
        int taille = liste_des_termes.size();
        NodeList result = new NodeList(this);
        TermNode n;
        String s;

        while (i < taille) {
            s = (String) liste_des_termes.get(i);
            n = (TermNode) les_noeuds.get(s);

            if (n != null) {
                if (n.activation == 0) {
                    result.add(n);
                }
                n.activer();
            }
            i++;
        }
        return (result);
    }

    // ------------------------------------------
    // classe      : Network
    // nom         :
    // entree(s)   :
    // sortie(s)   :
    // description :
    // remarque    :
    // ------------------------------------------
    public String best_terms() {

        TermNode nc;
        Set elements = les_noeuds.keySet();
        Iterator it = elements.iterator();
        int taille = elements.size();
        int i = 0;
        int[] tab3 = new int[taille];
        float[] tab2 = new float[taille];
        TermNode[] tab = new TermNode[taille];

        while (it.hasNext()) {
            String s = (String) it.next();
            nc = (TermNode) les_noeuds.get(s);
            tab2[i] = nc.get_score();
            tab3[i] = i;
            tab[i] = nc;
            i++;
        }

        int u = 0;
        int temp;
        int min = Math.min(this.nb, taille);
        while (u < min) {
            int v = taille - 2;
            while (v >= u) {
                if (tab2[tab3[v]] < tab2[tab3[v + 1]]) {
                    temp = tab3[v + 1];
                    tab3[v + 1] = tab3[v];
                    tab3[v] = temp;
                }
                v--;
            }
            u++;
        }

        u = 0;
        String res = "";
        while (u < min) {
            nc = tab[tab3[u]];
            //res=res+nc.get_id()+":"+String.format("%.2f", tab2[tab3[u]])+";";
            res = res + nc.id+ " ";
            u++;
        }
        if (res.length() > 0) {
            return res.substring(0, res.length() - 1);
        } else {
            return res;
        }
    }

}
//=============================================================================//
