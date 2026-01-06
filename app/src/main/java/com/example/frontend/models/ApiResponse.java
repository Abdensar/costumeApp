package com.example.frontend.models;

import java.util.List;

public class ApiResponse<T> {
    private List<T> data;

    public ApiResponse() {
    }

    public List<T> getData() {
        return data;
    }

    public void setData(List<T> data) {
        this.data = data;
    }
}

