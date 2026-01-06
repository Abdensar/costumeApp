package com.example.frontend.activities;

import android.app.DatePickerDialog;
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
import com.example.frontend.models.Rental;
import com.example.frontend.models.RentalRequest;
import com.example.frontend.models.Size;
import com.example.frontend.network.RetrofitClient;
import com.example.frontend.utils.TokenManager;
import com.google.android.material.button.MaterialButton;
import com.google.android.material.chip.Chip;
import com.google.android.material.chip.ChipGroup;

import java.text.NumberFormat;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Locale;
import java.util.concurrent.TimeUnit;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class RentalBookingActivity extends AppCompatActivity {
    private static final String TAG = "RentalBooking";

    private ImageView ivCostume;
    private TextView tvCostumeName, tvPricePerDay, tvStartDate, tvEndDate, tvTotalDays, tvTotalPrice;
    private ChipGroup chipGroupSizes;
    private MaterialButton btnSelectStartDate, btnSelectEndDate, btnConfirmBooking;
    private ProgressBar progressBar;

    private int costumeId;
    private Costume costume;
    private String selectedSize = null;
    private Calendar startDate = null;
    private Calendar endDate = null;
    private SimpleDateFormat displayFormat = new SimpleDateFormat("dd/MM/yyyy", Locale.FRANCE);
    private SimpleDateFormat apiFormat = new SimpleDateFormat("yyyy-MM-dd", Locale.US);

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_rental_booking);

        // Setup toolbar
        Toolbar toolbar = findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        if (getSupportActionBar() != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setTitle("Réservation");
        }

        costumeId = getIntent().getIntExtra(CostumeDetailActivity.EXTRA_COSTUME_ID, -1);
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
        tvCostumeName = findViewById(R.id.tvCostumeName);
        tvPricePerDay = findViewById(R.id.tvPricePerDay);
        tvStartDate = findViewById(R.id.tvStartDate);
        tvEndDate = findViewById(R.id.tvEndDate);
        tvTotalDays = findViewById(R.id.tvTotalDays);
        tvTotalPrice = findViewById(R.id.tvTotalPrice);
        chipGroupSizes = findViewById(R.id.chipGroupSizes);
        btnSelectStartDate = findViewById(R.id.btnSelectStartDate);
        btnSelectEndDate = findViewById(R.id.btnSelectEndDate);
        btnConfirmBooking = findViewById(R.id.btnConfirmBooking);
        progressBar = findViewById(R.id.progressBar);

        btnSelectStartDate.setOnClickListener(v -> showDatePicker(true));
        btnSelectEndDate.setOnClickListener(v -> showDatePicker(false));
        btnConfirmBooking.setOnClickListener(v -> confirmBooking());
    }

    private void loadCostumeDetails() {
        showLoading(true);

        Call<CostumeSingleResponse> call = RetrofitClient.getApiService().getCostume(costumeId);
        call.enqueue(new Callback<CostumeSingleResponse>() {
            @Override
            public void onResponse(Call<CostumeSingleResponse> call, Response<CostumeSingleResponse> response) {
                showLoading(false);
                if (response.isSuccessful() && response.body() != null && response.body().getData() != null) {
                    costume = response.body().getData();
                    displayCostumeInfo();
                } else {
                    Toast.makeText(RentalBookingActivity.this, "Erreur de chargement", Toast.LENGTH_SHORT).show();
                    finish();
                }
            }

            @Override
            public void onFailure(Call<CostumeSingleResponse> call, Throwable t) {
                showLoading(false);
                Toast.makeText(RentalBookingActivity.this, "Erreur réseau", Toast.LENGTH_SHORT).show();
                finish();
            }
        });
    }

    private void displayCostumeInfo() {
        tvCostumeName.setText(costume.getName());

        NumberFormat nf = NumberFormat.getInstance(Locale.FRANCE);
        nf.setMinimumFractionDigits(2);
        tvPricePerDay.setText(nf.format(costume.getPricePerDay()) + " MAD/jour");

        // Load image
        String imageUrl = costume.getFirstImageUrl();
        if (imageUrl != null && !imageUrl.isEmpty()) {
            if (imageUrl.startsWith("/")) {
                imageUrl = RetrofitClient.getBaseUrl() + imageUrl;
            }
            Glide.with(this)
                    .load(imageUrl)
                    .placeholder(R.drawable.ic_costume_placeholder)
                    .into(ivCostume);
        }

        // Setup sizes
        chipGroupSizes.removeAllViews();
        if (costume.getSizes() != null) {
            for (Size size : costume.getSizes()) {
                if (size.getQuantityAvailable() > 0) {
                    Chip chip = new Chip(this);
                    chip.setText(size.getSizeLabel());
                    chip.setCheckable(true);
                    chip.setOnCheckedChangeListener((buttonView, isChecked) -> {
                        if (isChecked) {
                            selectedSize = size.getSizeLabel();
                            updateBookingButton();
                        }
                    });
                    chipGroupSizes.addView(chip);
                }
            }
        }
    }

    private void showDatePicker(boolean isStartDate) {
        Calendar calendar = Calendar.getInstance();
        if (isStartDate && startDate != null) {
            calendar = startDate;
        } else if (!isStartDate && endDate != null) {
            calendar = endDate;
        }

        DatePickerDialog dialog = new DatePickerDialog(this, (view, year, month, dayOfMonth) -> {
            Calendar selected = Calendar.getInstance();
            selected.set(year, month, dayOfMonth);

            if (isStartDate) {
                startDate = selected;
                tvStartDate.setText(displayFormat.format(startDate.getTime()));
            } else {
                endDate = selected;
                tvEndDate.setText(displayFormat.format(endDate.getTime()));
            }
            calculateTotal();
            updateBookingButton();
        }, calendar.get(Calendar.YEAR), calendar.get(Calendar.MONTH), calendar.get(Calendar.DAY_OF_MONTH));

        // Set min date to today
        dialog.getDatePicker().setMinDate(System.currentTimeMillis() - 1000);

        // If selecting end date, set min to start date
        if (!isStartDate && startDate != null) {
            dialog.getDatePicker().setMinDate(startDate.getTimeInMillis());
        }

        dialog.show();
    }

    private void calculateTotal() {
        if (startDate != null && endDate != null && costume != null) {
            long diffMillis = endDate.getTimeInMillis() - startDate.getTimeInMillis();
            long days = TimeUnit.DAYS.convert(diffMillis, TimeUnit.MILLISECONDS) + 1;

            tvTotalDays.setText(days + " jour(s)");

            double total = days * costume.getPricePerDay();
            NumberFormat nf = NumberFormat.getInstance(Locale.FRANCE);
            nf.setMinimumFractionDigits(2);
            tvTotalPrice.setText(nf.format(total) + " MAD");
        }
    }

    private void updateBookingButton() {
        boolean canBook = selectedSize != null && startDate != null && endDate != null;
        btnConfirmBooking.setEnabled(canBook);
    }

    private void confirmBooking() {
        if (!TokenManager.isLoggedIn(this)) {
            Toast.makeText(this, "Veuillez vous connecter", Toast.LENGTH_SHORT).show();
            return;
        }

        if (selectedSize == null || startDate == null || endDate == null) {
            Toast.makeText(this, "Veuillez remplir tous les champs", Toast.LENGTH_SHORT).show();
            return;
        }

        showLoading(true);

        String token = TokenManager.getBearerToken(this);
        RentalRequest request = new RentalRequest(
                costumeId,
                selectedSize,
                apiFormat.format(startDate.getTime()),
                apiFormat.format(endDate.getTime())
        );

        Call<Rental> call = RetrofitClient.getApiService().createRental(token, request);
        call.enqueue(new Callback<Rental>() {
            @Override
            public void onResponse(Call<Rental> call, Response<Rental> response) {
                showLoading(false);
                if (response.isSuccessful()) {
                    Toast.makeText(RentalBookingActivity.this, "Réservation confirmée!", Toast.LENGTH_LONG).show();
                    finish();
                } else {
                    Log.e(TAG, "Booking failed: " + response.code());
                    Toast.makeText(RentalBookingActivity.this, "Erreur de réservation", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<Rental> call, Throwable t) {
                showLoading(false);
                Toast.makeText(RentalBookingActivity.this, "Erreur réseau", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void showLoading(boolean show) {
        progressBar.setVisibility(show ? View.VISIBLE : View.GONE);
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

