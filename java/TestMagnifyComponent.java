import java.awt.*;
import javax.swing.*;

public class TestMagnifyComponent {
	public static void main(String[] args) {
		JFrame jf = new TestFrame();
		
	}
}

class TestFrame extends JFrame {
	TestFrame() {
		super("Test");
		getContentPane().add(new MagnifyComponent());
		setVisible(true);
		setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
	}
}