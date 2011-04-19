import java.lang.reflect.Field;

class Person {
  private final String name = "Jacky";

  public String toString() {
    return "name: " + this.name;
  }
}


public class FinalFieldChange {
  public static void main(String[] args) throws Exception {
    Person jacky = new Person();
    Field nameField = Person.class.getDeclaredField("name");
    nameField.setAccessible(true);
    nameField.set(jacky, "Andy");
    System.out.println(jacky);
  }
}