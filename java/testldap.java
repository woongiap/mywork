import java.util.Hashtable;

import javax.naming.Context;
import javax.naming.NamingEnumeration;
import javax.naming.NamingException;
import javax.naming.directory.Attributes;
import javax.naming.directory.BasicAttribute;
import javax.naming.directory.BasicAttributes;
import javax.naming.directory.DirContext;
import javax.naming.directory.InitialDirContext;
import javax.naming.directory.SearchControls;
import javax.naming.directory.SearchResult;

public class testldap {
	
	public static void main(String[] args) throws NamingException {
		//Set up the environment for creating the initial context
		Hashtable env = new Hashtable();
		env.put(Context.INITIAL_CONTEXT_FACTORY, "com.sun.jndi.ldap.LdapCtxFactory");
		// my DN CN=Steven Yong,OU=ECS-KL,DC=ecssb,DC=local
		env.put(Context.PROVIDER_URL, "ldap://192.168.1.2:389");


		env.put(Context.SECURITY_AUTHENTICATION, "simple");
		env.put(Context.SECURITY_PRINCIPAL, "CN=Steven Yong,OU=ECS-KL,DC=ecssb,DC=local");
		env.put(Context.SECURITY_CREDENTIALS, "monalisa");

		DirContext ctx = new InitialDirContext(env);
		
		Attributes attributes = ctx.getAttributes("CN=Steven Yong,OU=ECS-KL,DC=ecssb,DC=local");
		System.out.println(attributes);
		
		Attributes matchAttrs = new BasicAttributes(true); // ignore case
	    matchAttrs.put(new BasicAttribute("cn")); // search all entries having attribute named cn
	    
	    SearchControls ctls = new SearchControls();
	    ctls.setReturningAttributes(new String[] {"cN"});       // Return CN attrs
	    //ctls.setSearchScope(SearchControls.SUBTREE_SCOPE); // Search object only

	    
		//NamingEnumeration answer = ctx.search("OU=Ecs-KL,DC=ecssb,DC=local", "(cn=*)", null, ctls); // same result in local ldap
	    //NamingEnumeration answer = ctx.search("OU=ECS-KL,DC=ecssb,DC=local", matchAttrs);
		
		NamingEnumeration answer = ctx.list("OU=Ecs-KL,DC=ecssb,DC=local");
		while (answer.hasMore()) {
		    System.out.println(answer.next());
		}
		ctx.close();
	}

}
