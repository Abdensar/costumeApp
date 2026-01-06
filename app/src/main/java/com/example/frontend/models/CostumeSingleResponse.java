package com.example.frontend.models;

import com.google.gson.annotations.SerializedName;

/**
 * Wrapper for Laravel single Resource response
 * Format: { "data": {...} }
 */
public class CostumeSingleResponse {
    @SerializedName("data")
    private Costume data;

    public Costume getData() {
        return data;
    }

    public void setData(Costume data) {
        this.data = data;
    }
}

