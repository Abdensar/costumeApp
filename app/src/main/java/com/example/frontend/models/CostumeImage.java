package com.example.frontend.models;

import com.google.gson.annotations.SerializedName;

public class CostumeImage {
    @SerializedName("id")
    private int id;

    @SerializedName("costume_id")
    private int costumeId;

    @SerializedName("image_url")
    private String imageUrl;

    // Getters
    public int getId() { return id; }
    public int getCostumeId() { return costumeId; }
    public String getImageUrl() { return imageUrl; }

    // Setters
    public void setId(int id) { this.id = id; }
    public void setCostumeId(int costumeId) { this.costumeId = costumeId; }
    public void setImageUrl(String imageUrl) { this.imageUrl = imageUrl; }
}

