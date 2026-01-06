package com.example.frontend.models;

import com.google.gson.annotations.SerializedName;

/**
 * Login/Register response from Laravel Sanctum
 */
public class AuthResponse {
    @SerializedName("token")
    private String token;

    @SerializedName("user")
    private User user;

    @SerializedName("message")
    private String message;

    public String getToken() { return token; }
    public User getUser() { return user; }
    public String getMessage() { return message; }

    public void setToken(String token) { this.token = token; }
    public void setUser(User user) { this.user = user; }
    public void setMessage(String message) { this.message = message; }
}

