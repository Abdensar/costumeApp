package com.example.frontend.models;

import com.google.gson.annotations.SerializedName;
import java.util.List;

/**
 * Wrapper for Laravel RentalResource::collection response
 * Format: { "data": [...] }
 */
public class RentalListResponse {
    @SerializedName("data")
    private List<Rental> data;

    public List<Rental> getData() {
        return data;
    }

    public void setData(List<Rental> data) {
        this.data = data;
    }
}

