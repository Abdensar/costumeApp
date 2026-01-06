package com.example.frontend.network;

import com.example.frontend.models.*;

import java.util.List;

import retrofit2.Call;
import retrofit2.http.*;

/**
 * Retrofit API Service interface for Laravel Backend
 * Base URL: http://10.0.2.2:8000/api/
 */
public interface ApiService {

    // ==================== AUTHENTICATION ====================

    @POST("login")
    Call<AuthResponse> login(@Body LoginRequest request);

    @POST("register")
    Call<AuthResponse> register(@Body RegisterRequest request);

    @POST("logout")
    Call<Void> logout(@Header("Authorization") String token);

    // ==================== CATEGORIES ====================

    @GET("categories")
    Call<CategoryListResponse> getCategories();

    @GET("categories/{id}")
    Call<Category> getCategory(@Path("id") int id);

    // ==================== COSTUMES ====================

    /**
     * Get all costumes - Returns wrapped in {"data": [...]}
     */
    @GET("costumes")
    Call<CostumeListResponse> getCostumes();

    @GET("costumes")
    Call<CostumeListResponse> getCostumesByCategory(@Query("category") int categoryId);

    @GET("costumes")
    Call<CostumeListResponse> getCostumesFiltered(
            @Query("category") Integer categoryId,
            @Query("min_price") Double minPrice,
            @Query("max_price") Double maxPrice,
            @Query("available") Boolean available
    );

    /**
     * Get single costume - Laravel wraps in {"data": {...}}
     */
    @GET("costumes/{id}")
    Call<CostumeSingleResponse> getCostume(@Path("id") int id);

    @GET("costumes/{id}/sizes")
    Call<List<Size>> getCostumeSizes(@Path("id") int id);

    // ==================== RENTALS (Authenticated) ====================

    @POST("rentals")
    Call<Rental> createRental(
            @Header("Authorization") String token,
            @Body RentalRequest request
    );

    @GET("my-rentals")
    Call<RentalListResponse> getMyRentals(@Header("Authorization") String token);

    // ==================== PROFILE (Authenticated) ====================

    @GET("profile")
    Call<User> getProfile(@Header("Authorization") String token);
}

