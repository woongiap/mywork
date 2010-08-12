import java.awt.*;
import java.awt.event.*;
import java.awt.image.*;
import javax.swing.*;
import javax.swing.plaf.metal.*;


public class SysTray {
   public static void main(String[] args) throws Exception {
      TrayIcon icon = new TrayIcon(getImage(), "Java application as a tray icon", 
            createPopupMenu());
      icon.addActionListener(new ActionListener() {
         public void actionPerformed(ActionEvent e) {
            JOptionPane.showMessageDialog(null, "Hey, you activated me!");
         }
      });
      SystemTray.getSystemTray().add(icon);

      Thread.sleep(3000);

      icon.displayMessage("Attention", "Please click here", 
            TrayIcon.MessageType.WARNING);
   }

   private static Image getImage() throws HeadlessException {
      Icon defaultIcon = MetalIconFactory.getTreeHardDriveIcon();
      Image img = new BufferedImage(defaultIcon.getIconWidth(), 
            defaultIcon.getIconHeight(), BufferedImage.TYPE_4BYTE_ABGR);
      defaultIcon.paintIcon(new Panel(), img.getGraphics(), 0, 0);

      return img;
   }

   private static PopupMenu createPopupMenu() throws HeadlessException {
      PopupMenu menu = new PopupMenu();

      MenuItem exit = new MenuItem("Exit");
      exit.addActionListener(new ActionListener() {
         public void actionPerformed(ActionEvent e) {
            System.exit(0);
         }
      });
      menu.add(exit);

      return menu;
   }
}