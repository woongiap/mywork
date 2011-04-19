   import javax.swing.JFrame;
   import java.util.prefs.Preferences;
   import java.util.prefs.PreferenceChangeListener;
   import java.util.prefs.PreferenceChangeEvent;

   public class UserPreferences extends JFrame 
                implements PreferenceChangeListener{
     private Preferences userPrefs;

     public UserPreferences(){
       userPrefs = Preferences.userNodeForPackage(
                              UserPreferences.class);
       userPrefs.addPreferenceChangeListener(this);
       setToPreferredSize();
       setVisible(true);
     }

     public void setToPreferredSize(){
       int width = userPrefs.getInt("width", 100);
       int height = userPrefs.getInt("height", 200);
       setSize(width, height);
       System.out.println("Width = "+ getWidth() +
           " Height = "+ getHeight());
     }

       public void resetDimensionsManyTimes(){
       for (int i = 0; i<10;i++){
         putRandomDimensions();
         try {
           Thread.sleep(1000);
         } catch (InterruptedException e) {
           e.printStackTrace();
         }
       }
     }

     private void putRandomDimensions(){
       userPrefs.putInt("width", getRandomInt());
       userPrefs.putInt("height", getRandomInt());
     }

     private int getRandomInt(){
       return (int)(Math.random()*300+100);
     }

     public void preferenceChange(PreferenceChangeEvent e){
         setToPreferredSize();
     }

     public static void main(String[] args){
       new UserPreferences().resetDimensionsManyTimes();
     }

   }