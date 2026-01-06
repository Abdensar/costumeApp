package com.example.frontend.utils;

import android.content.Context;
import android.content.SharedPreferences;

/**
 * Manages authentication token storage
 */
public class TokenManager {
    private static final String PREFS_NAME = "costume_rental_prefs";
    private static final String KEY_TOKEN = "auth_token";
    private static final String KEY_USER_NAME = "user_name";
    private static final String KEY_USER_EMAIL = "user_email";
    private static final String KEY_USER_ID = "user_id";

    private static SharedPreferences getPrefs(Context context) {
        return context.getSharedPreferences(PREFS_NAME, Context.MODE_PRIVATE);
    }

    // Token methods
    public static void saveToken(Context context, String token) {
        getPrefs(context).edit().putString(KEY_TOKEN, token).apply();
    }

    public static String getToken(Context context) {
        return getPrefs(context).getString(KEY_TOKEN, null);
    }

    public static String getBearerToken(Context context) {
        String token = getToken(context);
        return token != null ? "Bearer " + token : null;
    }

    public static boolean isLoggedIn(Context context) {
        return getToken(context) != null;
    }

    // User info methods
    public static void saveUserInfo(Context context, int id, String name, String email) {
        SharedPreferences.Editor editor = getPrefs(context).edit();
        editor.putInt(KEY_USER_ID, id);
        editor.putString(KEY_USER_NAME, name);
        editor.putString(KEY_USER_EMAIL, email);
        editor.apply();
    }

    public static String getUserName(Context context) {
        return getPrefs(context).getString(KEY_USER_NAME, "Utilisateur");
    }

    public static String getUserEmail(Context context) {
        return getPrefs(context).getString(KEY_USER_EMAIL, "");
    }

    public static int getUserId(Context context) {
        return getPrefs(context).getInt(KEY_USER_ID, -1);
    }

    // Logout - clear all data
    public static void logout(Context context) {
        getPrefs(context).edit().clear().apply();
    }
}

