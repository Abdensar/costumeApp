package com.example.frontend.fragments;

import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import com.example.frontend.R;
import com.example.frontend.adapters.RentalAdapter;
import com.example.frontend.models.Rental;
import com.example.frontend.models.RentalListResponse;
import com.example.frontend.network.RetrofitClient;
import com.example.frontend.utils.TokenManager;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class MyRentalsFragment extends Fragment {
    private static final String TAG = "MyRentalsFragment";

    private RecyclerView rvRentals;
    private SwipeRefreshLayout swipeRefreshLayout;
    private ProgressBar progressBar;
    private TextView tvEmpty;
    private RentalAdapter rentalAdapter;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_my_rentals, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        initViews(view);
        setupRecyclerView();
        loadRentals();
    }

    private void initViews(View view) {
        rvRentals = view.findViewById(R.id.rvRentals);
        swipeRefreshLayout = view.findViewById(R.id.swipeRefreshLayout);
        progressBar = view.findViewById(R.id.progressBar);
        tvEmpty = view.findViewById(R.id.tvEmpty);

        swipeRefreshLayout.setOnRefreshListener(this::loadRentals);
    }

    private void setupRecyclerView() {
        rvRentals.setLayoutManager(new LinearLayoutManager(requireContext()));
        rentalAdapter = new RentalAdapter(new ArrayList<>());
        rvRentals.setAdapter(rentalAdapter);
    }

    private void loadRentals() {
        if (!TokenManager.isLoggedIn(requireContext())) {
            tvEmpty.setText("Veuillez vous connecter pour voir vos locations");
            tvEmpty.setVisibility(View.VISIBLE);
            rvRentals.setVisibility(View.GONE);
            progressBar.setVisibility(View.GONE);
            return;
        }

        showLoading(true);
        tvEmpty.setVisibility(View.GONE);
        String token = TokenManager.getBearerToken(requireContext());
        Log.d(TAG, "Loading rentals with token: " + (token != null ? "present" : "null"));

        Call<RentalListResponse> call = RetrofitClient.getApiService().getMyRentals(token);
        call.enqueue(new Callback<RentalListResponse>() {
            @Override
            public void onResponse(Call<RentalListResponse> call, Response<RentalListResponse> response) {
                showLoading(false);
                swipeRefreshLayout.setRefreshing(false);

                Log.d(TAG, "Response code: " + response.code());

                if (response.isSuccessful() && response.body() != null && response.body().getData() != null) {
                    List<Rental> rentals = response.body().getData();
                    Log.d(TAG, "Rentals loaded: " + rentals.size());

                    if (rentals.isEmpty()) {
                        tvEmpty.setText("Aucune location");
                        tvEmpty.setVisibility(View.VISIBLE);
                        rvRentals.setVisibility(View.GONE);
                    } else {
                        tvEmpty.setVisibility(View.GONE);
                        rvRentals.setVisibility(View.VISIBLE);
                        rentalAdapter.updateRentals(rentals);
                    }
                } else if (response.code() == 401) {
                    // Unauthorized - token expired or invalid
                    Log.e(TAG, "Unauthorized - token may be expired");
                    tvEmpty.setText("Session expirée. Veuillez vous reconnecter.");
                    tvEmpty.setVisibility(View.VISIBLE);
                    rvRentals.setVisibility(View.GONE);
                } else {
                    Log.e(TAG, "Failed to load rentals: " + response.code());
                    try {
                        if (response.errorBody() != null) {
                            Log.e(TAG, "Error: " + response.errorBody().string());
                        }
                    } catch (Exception e) {
                        e.printStackTrace();
                    }
                    tvEmpty.setText("Aucune location");
                    tvEmpty.setVisibility(View.VISIBLE);
                    rvRentals.setVisibility(View.GONE);
                }
            }

            @Override
            public void onFailure(Call<RentalListResponse> call, Throwable t) {
                showLoading(false);
                swipeRefreshLayout.setRefreshing(false);
                Log.e(TAG, "Network error: " + t.getMessage(), t);

                // Show more helpful message
                tvEmpty.setText("Impossible de se connecter au serveur.\nVérifiez votre connexion.");
                tvEmpty.setVisibility(View.VISIBLE);
                rvRentals.setVisibility(View.GONE);
            }
        });
    }

    private void showLoading(boolean show) {
        progressBar.setVisibility(show ? View.VISIBLE : View.GONE);
    }

    private void showError(String message) {
        if (getContext() != null) {
            Toast.makeText(getContext(), message, Toast.LENGTH_SHORT).show();
        }
    }
}

