package com.example.frontend.utils;

public class Constants {
    public static final String BASE_URL = "http://10.0.2.2:8000/";
    public static final String STORAGE_URL = BASE_URL + "storage/";

    // Rental Status
    public static final String STATUS_PENDING = "pending";
    public static final String STATUS_CONFIRMED = "confirmed";
    public static final String STATUS_CANCELLED = "cancelled";
    public static final String STATUS_RETURNED = "returned";

    // Intent Keys
    public static final String KEY_COSTUME_ID = "costume_id";
    public static final String KEY_RENTAL_ID = "rental_id";
    public static final String KEY_CATEGORY_ID = "category_id";
    public static final String KEY_CATEGORY_NAME = "category_name";

    // Costume Sizes
    public static final String[] SIZES = {"S", "M", "L", "XL", "XXL"};
}

