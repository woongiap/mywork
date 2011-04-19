import javax.swing.*;
import java.awt.*;
import java.awt.event.*;

public class MagnifyComponent extends JComponent {
    private int factor;
    private Image image;
    private int w;
    private int h;
    private Robot robot;

    MagnifyComponent() {
        try {
            robot = new Robot();
        } catch (AWTException e) {
        }
        this.factor = 6;
        Toolkit.getDefaultToolkit().addAWTEventListener(
            new EventHandler(), AWTEvent.MOUSE_MOTION_EVENT_MASK);
    }

    public void reshape(int x, int y, int w, int h) {
        super.reshape(x, y, w, h);
        updateImageSize();
    }

    private void updateImageSize() {
        int w = getWidth();
        int h = getHeight();
        image = null;
        this.w = w / factor / 2;
        this.h = h / factor / 2;
    }

    public void paintComponent(Graphics g) {
        if (isOpaque()) {
            g.setColor(getBackground());
            g.fillRect(0, 0, getWidth(), getHeight());
        }
        if (image != null) {
            g.drawImage(image, 0, 0, w * factor * 2, h * factor * 2,
                        0, 0, w + w, h + h, null);
        }
    }

    private void updateImage(int x, int y) {
        if (w > 0 && h > 0) {
            image = robot.createScreenCapture(new Rectangle(
                                                  x - w, y - h, w + w, h + h));
        }
        repaint();
    }

    private class EventHandler implements AWTEventListener {
        public void eventDispatched(AWTEvent event) {
            if (event.getID() == MouseEvent.MOUSE_MOVED) {
                MouseEvent me = (MouseEvent)event;
                Component source = me.getComponent();
                int x = me.getX();
                int y = me.getY();
                Point location = source.getLocationOnScreen();
                x += location.x;
                y += location.y;
                updateImage(x, y);
            }
        }
    }
}