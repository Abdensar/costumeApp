package com.example.frontend.models;

import com.google.gson.annotations.SerializedName;

public class Rental {
    @SerializedName("id")
    private int id;

    @SerializedName("user_id")
    private int userId;

    @SerializedName("costume_id")
    private int costumeId;

    @SerializedName("size")
    private String size;

    @SerializedName("start_date")
    private String startDate;

    @SerializedName("end_date")
    private String endDate;

    @SerializedName("total_price")
    private double totalPrice;

    @SerializedName("status")
    private String status;

    @SerializedName("costume")
    private Costume costume;

    @SerializedName("created_at")
    private String createdAt;

    // Getters
    public int getId() { return id; }
    public int getUserId() { return userId; }
    public int getCostumeId() { return costumeId; }
    public String getSize() { return size; }
    public String getStartDate() { return startDate; }
    public String getEndDate() { return endDate; }
    public double getTotalPrice() { return totalPrice; }
    public String getStatus() { return status; }
    public Costume getCostume() { return costume; }
    public String getCreatedAt() { return createdAt; }

    // Setters
    public void setId(int id) { this.id = id; }
    public void setUserId(int userId) { this.userId = userId; }
    public void setCostumeId(int costumeId) { this.costumeId = costumeId; }
    public void setSize(String size) { this.size = size; }
    public void setStartDate(String startDate) { this.startDate = startDate; }
    public void setEndDate(String endDate) { this.endDate = endDate; }
    public void setTotalPrice(double totalPrice) { this.totalPrice = totalPrice; }
    public void setStatus(String status) { this.status = status; }
    public void setCostume(Costume costume) { this.costume = costume; }
    public void setCreatedAt(String createdAt) { this.createdAt = createdAt; }
}

