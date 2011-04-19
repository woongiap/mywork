public class InheritanceSample {
    
    public static void main(String args[]) {
        Car car = new Proton();
        car.show();
        car = new Toyota();
        car.show();
    }
}

class Car {
    protected String model = "car";
    
    void show() {
        System.out.println(model);
    }
}

class Proton extends Car {
    
    Proton() {
        model = "Proton";
    }
}

class Toyota extends Car {
    
    Toyota() {
        model = "Toyota";
    }
}