package com.example.frontend.models;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class Costume {
    @SerializedName("id")
    private int id;

    @SerializedName("name")
    private String name;

    @SerializedName("slug")
    private String slug;

    @SerializedName("brand")
    private String brand;

    @SerializedName("description")
    private String description;

    @SerializedName("category")
    private Category category;

    @SerializedName("category_id")
    private int categoryId;

    @SerializedName("price_per_day")
    private double pricePerDay;

    @SerializedName("featured_image_url")
    private String featuredImageUrl;

    @SerializedName("is_active")
    private boolean isActive;

    @SerializedName("available")
    private boolean available;

    @SerializedName("created_at")
    private String createdAt;

    @SerializedName("images")
    private List<CostumeImage> images;

    @SerializedName("sizes")
    private List<Size> sizes;

    // Getters
    public int getId() { return id; }
    public String getName() { return name; }
    public String getSlug() { return slug; }
    public String getBrand() { return brand; }
    public String getDescription() { return description; }
    public Category getCategory() { return category; }
    public int getCategoryId() { return categoryId; }
    public double getPricePerDay() { return pricePerDay; }
    public String getFeaturedImageUrl() { return featuredImageUrl; }
    public boolean isActive() { return isActive; }
    public boolean isAvailable() { return available; }
    public String getCreatedAt() { return createdAt; }
    public List<CostumeImage> getImages() { return images; }
    public List<Size> getSizes() { return sizes; }

    // Setters
    public void setId(int id) { this.id = id; }
    public void setName(String name) { this.name = name; }
    public void setSlug(String slug) { this.slug = slug; }
    public void setBrand(String brand) { this.brand = brand; }
    public void setDescription(String description) { this.description = description; }
    public void setCategory(Category category) { this.category = category; }
    public void setCategoryId(int categoryId) { this.categoryId = categoryId; }
    public void setPricePerDay(double pricePerDay) { this.pricePerDay = pricePerDay; }
    public void setFeaturedImageUrl(String featuredImageUrl) { this.featuredImageUrl = featuredImageUrl; }
    public void setActive(boolean active) { isActive = active; }
    public void setAvailable(boolean available) { this.available = available; }
    public void setCreatedAt(String createdAt) { this.createdAt = createdAt; }
    public void setImages(List<CostumeImage> images) { this.images = images; }
    public void setSizes(List<Size> sizes) { this.sizes = sizes; }

    // Helper method to get first image URL
    public String getFirstImageUrl() {
        if (featuredImageUrl != null && !featuredImageUrl.isEmpty()) {
            return featuredImageUrl;
        }
        if (images != null && !images.isEmpty()) {
            return images.get(0).getImageUrl();
        }
        return null;
    }
}

