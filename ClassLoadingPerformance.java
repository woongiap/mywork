import java.awt.BorderLayout;
import java.awt.FlowLayout;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

import javax.swing.JButton;
import javax.swing.JDialog;
import javax.swing.JFileChooser;
import javax.swing.JFrame;
import javax.swing.JPanel;
import javax.swing.WindowConstants;

/**
 * @author Dieter Krachtus (DieterKrachtus@web.de)
 * 
 */
public class ClassLoadingPerformance {

	public static void main(String[] args) {
		JButton button = new JButton("Invoke Class Loading (show a JFileChooser)");
		final JFrame frame = new JFrame("Willkommen");
		frame.setContentPane(new JPanel(new BorderLayout(10,10)));
		frame.getContentPane().add(button, BorderLayout.CENTER);
		frame.setDefaultCloseOperation(WindowConstants.EXIT_ON_CLOSE);
		frame.pack();
		frame.setLocationRelativeTo(null);
		frame.setVisible(true);
		button.addActionListener(new ActionListener() {

			public void actionPerformed(ActionEvent e) {
				long start = System.currentTimeMillis();
				showJFileChooser(frame);
				long end = System.currentTimeMillis();
				System.out.println(end - start);
			}

			private void showJFileChooser(final JFrame frame) {
				JDialog dialog = new JDialog(frame,
						"ClassLoading Performance Test", false);
				dialog.getContentPane().setLayout(new FlowLayout());

				dialog.add(new JFileChooser());

				dialog.pack();
				dialog.setLocationRelativeTo(null);
				dialog.setVisible(true);
			}

		});
	}

}
