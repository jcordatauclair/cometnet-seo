import java.util.*;
import java.io.*;
//=============================================================================//
//                                 CLASSE ClassNode
//=============================================================================//
public class ClassNode extends Node
{
    public float bup;   // bottom-up propagation
    public float tdp;   // top-down propagation
    public float echo; //  le score finale
    public ArrayList tp; // liste ordonnee des scores des documents appartenant à la classe
    public ArrayList tn; // liste ordonnee des scores des documents n'appartenant pas à la classe

    // ------------------------------------------
    // classe      : ClassNode
    // nom         : ClassNode
    // entree(s)   : 
    // sortie(s)   : le noeud construit 
    // description : constructeur de base
    // remarque    : 
    // ------------------------------------------
    public ClassNode(Network n)
    {
        super(n);
        bup=0;tdp=0;echo=0;
        tp=new ArrayList();
        tn=new ArrayList();
    }

    // ------------------------------------------
    // classe      : ClassNode
    // nom         : afficher
    // entree(s)   : 
    // sortie(s)   : affiche les caracteristiques du noeud
    // description : 
    // remarque    : 
    // ------------------------------------------
    public void afficher()
    {
        System.out.println("classe");
        super.afficher();
        System.out.println(tp);
        System.out.println(tn);
    }



    // ------------------------------------------
    // classe      : ClassNode
    // nom         : activer
    // entree(s)   : 
    // sortie(s)   : augmente l'activation du noeud
    // description : 
    // remarque    : 
    // ------------------------------------------
    public void activer()
    {
        activation=1;
    }


    // ------------------------------------------
    // classe      : TermNode
    // nom         : desactiver
    // entree(s)   : 
    // sortie(s)   : 
    // description : 
    // remarque    : 
    // ------------------------------------------
    public void desactiver()
    {
        super.desactiver();
        bup=0;
        tdp=0;
        echo=0;
    }


    // ------------------------------------------
    // classe      : ClassNode
    // nom         : calcul_echo
    // entree(s)   : 
    // sortie(s)   : 
    // description : 
    // remarque    : 
    // ------------------------------------------
    public void calcul_echo()
    {
        echo = bup*tdp;
    }
 


    // ------------------------------------------
    // classe      : ClassNode
    // nom         : range_echo
    // entree(s)   : 
    // sortie(s)   : 
    // description : 
    // remarque    : 
    // ------------------------------------------
    public void range_echo()
    {
        int j=0;
        Float fl=new Float(echo);
        if (net.goodclass)
        {
            while ((j<tp.size())&& ((fl.floatValue()>((Float)tp.get(j)).floatValue())))
            {
                j++;
            }
            tp.add(j,fl);
        }
        else
        {
            while ((j<tn.size())&& ((fl.floatValue()>((Float)tn.get(j)).floatValue())))
            {
                j++;
            }
            tn.add(j,fl);
        }
    }




    // ------------------------------------------
    // classe      : ClassNode
    // nom         : proba
    // entree(s)   : un score
    // sortie(s)   : la proba qu'un document avec ce score appartienne à la classe
    // description : 
    // remarque    : 
    // ------------------------------------------
    public float proba(float a) 
    {
        // si il n'y a pas de document de la classe
        // donné en exemple
        // on est plutôt pessimiste   
        int taille_tp=tp.size(); 
        if (taille_tp==0) 
        {
            return (float)0.05;
        }
       
        // on compte le nombre de documents de la classe
        // avec un score inférieur à a
        
        int i=0;
        float f;
        float np=0;
        f =(((Float) tp.get(i)).floatValue());
		//System.out.println("a:"+a);
        while ((i<(taille_tp-1))&&(f<=a))
        {   
           np++;
		   i++;
           f =(((Float) tp.get(i)).floatValue());
        }
		if (f<=a) {np++;}
		//System.out.println("np:"+np);
        
        // on compte le nombre de documents n'appartenant pas à la classe
        // avec un score supérieur à a
        int j=tn.size()-1;
        float nbp=0;
        if (j>=0)
        {
        f =(((Float) tn.get(j)).floatValue());
        while ((j>0)&&(f>a))
        {
            nbp++;
            j--;
			f= ((Float) tn.get(j)).floatValue();
        }
		if (f>a) {nbp++;}
        }
		//System.out.println("nbp:"+nbp);
        
        
        // S'il n'y a pas de documents n'appartenant pas à la classe
        // dans cette zone de score, on est très optimiste
        float prec;
        if (nbp==0) { prec=(float)1.0; }
        else
        {
            // sinon on retourne la densité de documents
            // appartenant à la classe dans cette zone
            // (en fait une estimation heuristique)
            prec= np/(nbp+np);
        }
        
        // la valeur minimale retournée est 0.05
        if (prec<0.05) {prec=(float)0.05;}
        //System.out.println("prec:"+prec);
        return (prec);
    }

//=============================================================================//

    // ------------------------------------------
    // classe      : ClassNode
    // nom         : proba
    // entree(s)   : un score
    // sortie(s)   : la proba qu'un document avec ce score appartienne à la classe
    // description : 
    // remarque    : 
    // ------------------------------------------
    public int position(float a) 
    {
        // on compte le nombre de documents de la classe
        // avec un score supérieur à a
        
        int i=0;
		float f;
		i=tp.size()-1;
        int np=0;
		if (i>=0)
        {
        f =(((Float) tp.get(i)).floatValue());
        while ((i>0)&&(f>=a))
        {   
           np++;
		   i--;
           f =(((Float) tp.get(i)).floatValue());
        }
		if (f>=a) {np++;}
		}
        
        // on compte le nombre de documents n'appartenant pas à la classe
        // avec un score supérieur à a
        int j=tn.size()-1;
        int nbp=0;
        if (j>=0)
        {
        f =(((Float) tn.get(j)).floatValue());
        while ((j>0)&&(f>=a))
        {
			j--;
            f= ((Float) tn.get(j)).floatValue();
            nbp++;
        }
		if (f>=a) {nbp++;}
        }
        
		return(np+nbp+1);
       
    }

    
}
//=============================================================================//