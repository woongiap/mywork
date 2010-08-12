/**
 *
 * @author Steven
 */
public class HelloWin32 {
    static {
        Runtime.getRuntime().loadLibrary("javawin32");
    }
    
    static native String GetUserName();
    
    public static void main(String args[]) {
        boolean returnCode;
        String userName = GetUserName();
        System.out.println("userName: " + userName);
    }
}

