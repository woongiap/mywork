/******************************************************************************/
/*                                                                            */
/*                                                       FILE: JDBCQuery.java */
/*                                                                            */
/*  A simple JDBC QUERY                                                       */
/*  ===================                                                       */
/*                                                                            */
/*  V1.00   24-JAN-1999   Te          http://www.heimetli.ch/                 */
/*                                                                            */
/*  ------------------------------------------------------------------------- */
/*                                                                            */
/*  This example was coded and tested with JDK1.2.2                           */
/*                                                                            */
/*  Access was used as database, over the JDBC-ODBC bridge which comes        */
/*  with the JDK.                                                             */
/*                                                                            */
/*  To run this example, you need a database with the following properties:   */
/*  => no username                                                            */
/*  => no password                                                            */
/*  => a table called "Cust"                                                  */
/*  => a system DSN called "Database"                                         */
/*                                                                            */
/******************************************************************************/

import java.sql.* ;

class JDBCQuery
{
 public static void main( String args[] )
 {
  try
     {
      // Load the database driver
      Class.forName( "sun.jdbc.odbc.JdbcOdbcDriver" ) ;

      // Get a connection to the database
      Connection conn = DriverManager.getConnection( "jdbc:odbc:blog" ) ;

      // Print all warnings
      for( SQLWarning warn = conn.getWarnings(); warn != null; warn = warn.getNextWarning() )
         {
          System.out.println( "SQL Warning:" ) ;
          System.out.println( "State  : " + warn.getSQLState()  ) ;
          System.out.println( "Message: " + warn.getMessage()   ) ;
          System.out.println( "Error  : " + warn.getErrorCode() ) ;
         }

      // Get a statement from the connection
      Statement stmt = conn.createStatement() ;

      // Execute the query
      ResultSet rs = stmt.executeQuery( "SELECT * FROM author" ) ;

      // Loop through the result set
      while( rs.next() )
         System.out.println( rs.getString(2) ) ;

      // Close the result set, statement and the connection
      rs.close() ;
      stmt.close() ;
      conn.close() ;
     }
  catch( SQLException se )
     {
      System.out.println( "SQL Exception:" ) ;

      // Loop through the SQL Exceptions
      while( se != null )
         {
          System.out.println( "State  : " + se.getSQLState()  ) ;
          System.out.println( "Message: " + se.getMessage()   ) ;
          System.out.println( "Error  : " + se.getErrorCode() ) ;

          se = se.getNextException() ;
         }
     }
  catch( Exception e )
     {
      System.out.println( e ) ;
     }
 }
}