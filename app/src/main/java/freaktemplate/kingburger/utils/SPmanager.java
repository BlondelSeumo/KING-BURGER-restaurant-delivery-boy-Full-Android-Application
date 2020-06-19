package freaktemplate.kingburger.utils;

import android.content.Context;
import android.content.SharedPreferences;

public class SPmanager {
    private static String preferenceName = "app_values";

    public static void saveValue(Context context, String key, String value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(preferenceName, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString(key, value);
        editor.apply();
    }

    public static String getPreference(Context context, String key) {
        return context.getSharedPreferences(preferenceName, Context.MODE_PRIVATE).getString(key, null);
    }


    public static void setFirsttime(Context context, boolean b) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(preferenceName, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putBoolean("firsttime", b);
        editor.apply();
    }


    public static boolean getFirsttime(Context context) {
        return context.getSharedPreferences(preferenceName, Context.MODE_PRIVATE).getBoolean("firsttime", false);
    }

    public static void setBilling(Context context, boolean b) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(preferenceName, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putBoolean("Billing", b);
        editor.apply();
    }


    public static boolean getBilling(Context context) {
        return context.getSharedPreferences(preferenceName, Context.MODE_PRIVATE).getBoolean("Billing", false);
    }

}
