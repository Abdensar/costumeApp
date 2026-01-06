package com.example.frontend.models;

import com.google.gson.annotations.SerializedName;
import java.util.List;

/**
 * Wrapper for Laravel CategoryResource::collection response
 * Format: { "data": [...] }
 */
public class CategoryListResponse {
    @SerializedName("data")
    private List<Category> data;

    public List<Category> getData() {
        return data;
    }

    public void setData(List<Category> data) {
        this.data = data;
    }
}

