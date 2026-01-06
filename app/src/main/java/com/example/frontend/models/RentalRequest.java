package com.example.frontend.models;

import com.google.gson.annotations.SerializedName;

/**
 * Create rental request body
 */
public class RentalRequest {
    @SerializedName("costume_id")
    private int costumeId;

    @SerializedName("size")
    private String size;

    @SerializedName("start_date")
    private String startDate;

    @SerializedName("end_date")
    private String endDate;

    public RentalRequest(int costumeId, String size, String startDate, String endDate) {
        this.costumeId = costumeId;
        this.size = size;
        this.startDate = startDate;
        this.endDate = endDate;
    }

    public int getCostumeId() { return costumeId; }
    public String getSize() { return size; }
    public String getStartDate() { return startDate; }
    public String getEndDate() { return endDate; }
}

