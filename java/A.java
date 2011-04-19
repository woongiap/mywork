import java.util.*;
import java.io.*;

public class A {
private static Random rnd = new Random();
	public static void main(String[] args) {
	StringBuffer word = null;
	System.out.println("rnd.nextInt(3) "+rnd.nextInt(3));
	switch(rnd.nextInt(3)) {
		case 1: word = new StringBuffer('P');break;
		case 2: word = new StringBuffer('G');break;
		default: word = new StringBuffer('M');
	}
	word= new StringBuffer('M');
	word.append('a');
	word.append('i');
	word.append('n');
	System.out.println(word);
	
	Collection<?> c = new ArrayList<String>();
	//c.add(new String()); // illegal
	
	Collection<List> ci = new ArrayList<List>();
	ci.add(new LinkedList()); //legal, parameter pass in have to be subtype of Element type
	List<String> stringList = new ArrayList<String>();
	//draw(stringList); //illegal, List<String> is not a subtype of List<Object>
	ArrayList<Object> objectArr = new ArrayList<Object>();
	draw(objectArr); //legal, ArrayList<Object> is subtype of List<Object>
	
	AS as = new AS();
	List<Serializable> asList = new ArrayList<Serializable>();
	asList.add(as);
	say(asList);
	
	Object[] oa = new Object[1];
	Collection<String> cs = new ArrayList<String>();
	
	String[] sa = new String[1];
	Collection<Object> co = new ArrayList<Object>();
	
	//joke(oa, cs); //illegal
	joke(sa, co);
	System.out.println(cs.getClass()==co.getClass());
	
	System.out.println("Locale: "+java.util.Locale.getDefault());
	
	Locale[] locales = { new Locale("en", "US"), new Locale("ja","JP"),
        new Locale("zh", "CN"), new Locale("it", "IT") }; 
	for (Locale loc:locales) { 
    	String displayLanguage = loc.getDisplayLanguage(loc); // use target locale to display this locale
    	String displayLanguage1 = loc.getDisplayLanguage(); // use system's locale to display this locale
    	System.out.println(loc.toString() + "(Target): " + displayLanguage); 
    	System.out.println(loc.toString() + "(Default): " + displayLanguage1); 
	} 
		System.out.println("BigDecimal: " + new java.math.BigDecimal(3).movePointRight(2));

		}
	
	static void draw(List<Object> oList) { // accept only list of OBJECTS
	}
	
	static void talk(List<? extends List> allList) { // accept list of any subclass of List
	}
	
	static void say(List<? extends Serializable> sList) {
	}
	
	static <T> void joke(T[] aS, Collection<T> cS) {
	}
	
}

class AS implements Serializable {
}

class Animal {
	//Animal() {}
	Animal(int n) { }
	public void eat() {}
}

class Cat extends Animal {
	Cat() {}
	Cat(int n) {}
	public void eat() {}
}

class HelloKitty extends Cat {
	public void eat() {}
}



