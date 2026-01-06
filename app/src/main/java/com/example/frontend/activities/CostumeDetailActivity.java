package com.example.frontend.activities;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.Toolbar;

import com.bumptech.glide.Glide;
import com.example.frontend.R;
import com.example.frontend.models.Costume;
import com.example.frontend.models.CostumeSingleResponse;
import com.example.frontend.models.Size;
import com.example.frontend.network.RetrofitClient;
import com.google.android.material.button.MaterialButton;
import com.google.android.material.chip.Chip;
import com.google.android.material.chip.ChipGroup;

import java.text.NumberFormat;
import java.util.Locale;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class CostumeDetailActivity extends AppCompatActivity {
    private static final String TAG = "CostumeDetail";
    public static final String EXTRA_COSTUME_ID = "costume_id";

    private ImageView ivCostume;
    private TextView tvName, tvBrand, tvPrice, tvDescription, tvAvailability;
    private ChipGroup chipGroupSizes;
    private MaterialButton btnRentNow;
    private ProgressBar progressBar;

    private Costume costume;
    private int costumeId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_costume_detail);

        // Setup toolbar
        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        if (getSupportActionBar() != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setTitle("Détails du Costume");
        }

        // Get costume ID from intent
        costumeId = getIntent().getIntExtra(EXTRA_COSTUME_ID, -1);
        Log.d(TAG, "Received costume ID: " + costumeId);

        if (costumeId == -1) {
            Toast.makeText(this, "Costume invalide", Toast.LENGTH_SHORT).show();
            finish();
            return;
        }

        initViews();
        loadCostumeDetails();
    }

    private void initViews() {
        ivCostume = findViewById(R.id.ivCostume);
        tvName = findViewById(R.id.tvName);
        tvBrand = findViewById(R.id.tvBrand);
        tvPrice = findViewById(R.id.tvPrice);
        tvDescription = findViewById(R.id.tvDescription);
        tvAvailability = findViewById(R.id.tvAvailability);
        chipGroupSizes = findViewById(R.id.chipGroupSizes);
        btnRentNow = findViewById(R.id.btnRentNow);
        progressBar = findViewById(R.id.progressIndicator);

        btnRentNow.setOnClickListener(v -> navigateToBooking());
    }

    private void loadCostumeDetails() {
        showLoading(true);
        Log.d(TAG, "Loading costume details for ID: " + costumeId);

        Call<CostumeSingleResponse> call = RetrofitClient.getApiService().getCostume(costumeId);
        call.enqueue(new Callback<CostumeSingleResponse>() {
            @Override
            public void onResponse(Call<CostumeSingleResponse> call, Response<CostumeSingleResponse> response) {
                showLoading(false);
                Log.d(TAG, "Response code: " + response.code());

                if (response.isSuccessful() && response.body() != null && response.body().getData() != null) {
                    costume = response.body().getData();
                    Log.d(TAG, "Costume loaded successfully: " + costume.getName());
                    displayCostumeDetails();
                } else {
                    Log.e(TAG, "API Error: " + response.code());
                    try {
                        if (response.errorBody() != null) {
                            Log.e(TAG, "Error body: " + response.errorBody().string());
                        }
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                    showError("Impossible de charger le costume");
                }
            }

            @Override
            public void onFailure(Call<CostumeSingleResponse> call, Throwable t) {
                showLoading(false);
                Log.e(TAG, "Network error: " + t.getMessage(), t);
                showError("Erreur réseau: " + t.getMessage());
            }
        });
    }

    private void displayCostumeDetails() {
        // Name
        String name = costume.getName();
        tvName.setText(name != null && !name.isEmpty() ? name : "Costume sans nom");

        // Brand
        String brand = costume.getBrand();
        tvBrand.setText(brand != null && !brand.isEmpty() ? brand : "Marque inconnue");

        // Price
        double price = costume.getPricePerDay();
        if (price > 0) {
            NumberFormat nf = NumberFormat.getInstance(Locale.FRANCE);
            nf.setMinimumFractionDigits(2);
            nf.setMaximumFractionDigits(2);
            tvPrice.setText(nf.format(price) + " MAD / jour");
        } else {
            tvPrice.setText("Prix non disponible");
        }

        // Description
        String description = costume.getDescription();
        tvDescription.setText(description != null && !description.isEmpty()
                ? description
                : "Aucune description disponible");

        // Availability
        if (costume.isAvailable()) {
            tvAvailability.setText("Disponible");
            tvAvailability.setTextColor(getColor(android.R.color.holo_green_dark));
            btnRentNow.setEnabled(true);
        } else {
            tvAvailability.setText("Non Disponible");
            tvAvailability.setTextColor(getColor(android.R.color.holo_red_dark));
            btnRentNow.setEnabled(false);
        }

        // Sizes
        chipGroupSizes.removeAllViews();
        if (costume.getSizes() != null && !costume.getSizes().isEmpty()) {
            for (Size size : costume.getSizes()) {
                Chip chip = new Chip(this);
                chip.setText(size.getSizeLabel() + " (" + size.getQuantityAvailable() + ")");
                chip.setCheckable(false);
                chip.setEnabled(size.getQuantityAvailable() > 0);
                chipGroupSizes.addView(chip);
            }
        } else {
            Chip noSizeChip = new Chip(this);
            noSizeChip.setText("Aucune taille");
            noSizeChip.setEnabled(false);
            chipGroupSizes.addView(noSizeChip);
        }

        // Image
        loadImage();
    }

    private void loadImage() {
        String imageUrl = costume.getFirstImageUrl();
        if (imageUrl != null && !imageUrl.isEmpty()) {
            // Prepend base URL if it's a relative path
            if (imageUrl.startsWith("/")) {
                imageUrl = RetrofitClient.getBaseUrl() + imageUrl;
            }
            Log.d(TAG, "Loading image: " + imageUrl);

            Glide.with(this)
                    .load(imageUrl)
                    .placeholder(R.drawable.ic_costume_placeholder)
                    .error(R.drawable.ic_costume_placeholder)
                    .centerCrop()
                    .into(ivCostume);
        } else {
            ivCostume.setImageResource(R.drawable.ic_costume_placeholder);
        }
    }

    private void navigateToBooking() {
        if (costume == null || !costume.isAvailable()) {
            Toast.makeText(this, "Ce costume n'est pas disponible", Toast.LENGTH_SHORT).show();
            return;
        }

        Intent intent = new Intent(this, RentalBookingActivity.class);
        intent.putExtra(EXTRA_COSTUME_ID, costumeId);
        startActivity(intent);
    }

    private void showLoading(boolean show) {
        progressBar.setVisibility(show ? View.VISIBLE : View.GONE);
    }

    private void showError(String message) {
        Toast.makeText(this, message, Toast.LENGTH_LONG).show();
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        if (item.getItemId() == android.R.id.home) {
            onBackPressed();
            return true;
        }
        return super.onOptionsItemSelected(item);
    }
}

