import java.awt.*;
import javax.swing.*;

// sample code to demonstrate the mix use of heavyweight and lightweight component,
// the problem has been fixed in jdk 1.4
public class MixPopupTest extends JFrame {
    public MixPopupTest() {
       super("Mix Popup Test");
       JMenuBar menubar = new JMenuBar();
       setJMenuBar(menubar);
       // Create lightweight-enabled menu
       JMenu menu = new JMenu("Lite Menu");
       menu.add("Salad");
       menu.add("Fruit Plate");
       menu.add("Water");
       menubar.add(menu);
       // Create lightweight-disabled menu
       JPopupMenu.
          setDefaultLightWeightPopupEnabled
          (false);
       menu = new JMenu("Heavy Menu");
       menu.add("Filet Mignon");
       menu.add("Gravy");
       menu.add("Banana Split");
       menubar.add(menu);
       // Create Heavyweight AWT Button
       Button heavy = new Button
           ("  Heavyweight Button  ");
       // Add heavy button to box
       Box box = Box.createVerticalBox();
       box.add(Box.createVerticalStrut(20));
       box.add(heavy);
       box.add(Box.createVerticalStrut(20));
       getContentPane().add("Center", box);
       pack();
    }
    public static void main(String[] args) {
        MixPopupTest t = new MixPopupTest();
        t.show();
    }
}