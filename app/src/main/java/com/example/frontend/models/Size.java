package com.example.frontend.models;

import com.google.gson.annotations.SerializedName;

public class Size {
    @SerializedName("id")
    private int id;

    @SerializedName("costume_id")
    private int costumeId;

    @SerializedName("size_label")
    private String sizeLabel;

    @SerializedName("quantity_available")
    private int quantityAvailable;

    // Getters
    public int getId() { return id; }
    public int getCostumeId() { return costumeId; }
    public String getSizeLabel() { return sizeLabel; }
    public int getQuantityAvailable() { return quantityAvailable; }

    // Setters
    public void setId(int id) { this.id = id; }
    public void setCostumeId(int costumeId) { this.costumeId = costumeId; }
    public void setSizeLabel(String sizeLabel) { this.sizeLabel = sizeLabel; }
    public void setQuantityAvailable(int quantityAvailable) { this.quantityAvailable = quantityAvailable; }
}

