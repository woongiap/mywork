import java.io.*;
import javax.xml.transform.*;
import javax.xml.transform.stream.*;

public class Transform {

  public static void main(String args[]) {

    if (args.length != 2) {
      System.err.println("Usage: java Transform xmlfile stylesheet");
      System.exit(-1);
    }

    try {
      StreamSource source = new StreamSource(args[0]);
      StreamSource stylesource = new StreamSource(args[1]);

      TransformerFactory factory = TransformerFactory.newInstance();
      Transformer transformer = factory.newTransformer(stylesource);

      StreamResult result = new StreamResult(System.out);
      transformer.transform(source, result);
    } catch (Exception e) {
      e.printStackTrace();
    }
  }
}
