package com.example.frontend.models;

import com.google.gson.annotations.SerializedName;
import java.util.List;

/**
 * Wrapper for Laravel Resource Collection responses
 * Format: { "data": [...] }
 */
public class CostumeListResponse {
    @SerializedName("data")
    private List<Costume> data;

    public List<Costume> getData() {
        return data;
    }

    public void setData(List<Costume> data) {
        this.data = data;
    }
}

