package com.example.frontend.fragments;

import android.content.Intent;
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
import androidx.recyclerview.widget.GridLayoutManager;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import com.example.frontend.R;
import com.example.frontend.activities.CostumeDetailActivity;
import com.example.frontend.adapters.CategoryAdapter;
import com.example.frontend.adapters.CostumeAdapter;
import com.example.frontend.models.Category;
import com.example.frontend.models.CategoryListResponse;
import com.example.frontend.models.Costume;
import com.example.frontend.models.CostumeListResponse;
import com.example.frontend.network.RetrofitClient;
import com.example.frontend.utils.TokenManager;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class HomeFragment extends Fragment {
    private static final String TAG = "HomeFragment";

    private TextView tvWelcome;
    private RecyclerView rvCategories, rvCostumes;
    private SwipeRefreshLayout swipeRefreshLayout;
    private ProgressBar progressBar;

    private CategoryAdapter categoryAdapter;
    private CostumeAdapter costumeAdapter;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_home, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        initViews(view);
        setupRecyclerViews();
        setupSwipeRefresh();
        loadData();
    }

    private void initViews(View view) {
        tvWelcome = view.findViewById(R.id.tvWelcome);
        rvCategories = view.findViewById(R.id.rvCategories);
        rvCostumes = view.findViewById(R.id.rvCostumes);
        swipeRefreshLayout = view.findViewById(R.id.swipeRefreshLayout);
        progressBar = view.findViewById(R.id.progressBar);

        // Set welcome message
        String userName = TokenManager.getUserName(requireContext());
        tvWelcome.setText("Bienvenue, " + userName + "!");
    }

    private void setupRecyclerViews() {
        // Categories - Horizontal list
        rvCategories.setLayoutManager(new LinearLayoutManager(requireContext(), LinearLayoutManager.HORIZONTAL, false));
        categoryAdapter = new CategoryAdapter(new ArrayList<>(), category -> {
            // Filter costumes by category
            loadCostumesByCategory(category.getId());
        });
        rvCategories.setAdapter(categoryAdapter);

        // Costumes - 2-column grid
        rvCostumes.setLayoutManager(new GridLayoutManager(requireContext(), 2));
        costumeAdapter = new CostumeAdapter(new ArrayList<>(), costume -> {
            // Navigate to costume detail
            Intent intent = new Intent(requireContext(), CostumeDetailActivity.class);
            intent.putExtra(CostumeDetailActivity.EXTRA_COSTUME_ID, costume.getId());
            startActivity(intent);
        });
        rvCostumes.setAdapter(costumeAdapter);
    }

    private void setupSwipeRefresh() {
        swipeRefreshLayout.setOnRefreshListener(this::loadData);
        swipeRefreshLayout.setColorSchemeResources(
                android.R.color.holo_blue_bright,
                android.R.color.holo_green_light,
                android.R.color.holo_orange_light
        );
    }

    private void loadData() {
        loadCategories();
        loadCostumes();
    }

    private void loadCategories() {
        Log.d(TAG, "Loading categories...");

        Call<CategoryListResponse> call = RetrofitClient.getApiService().getCategories();
        call.enqueue(new Callback<CategoryListResponse>() {
            @Override
            public void onResponse(Call<CategoryListResponse> call, Response<CategoryListResponse> response) {
                if (response.isSuccessful() && response.body() != null && response.body().getData() != null) {
                    Log.d(TAG, "Categories loaded: " + response.body().getData().size());
                    categoryAdapter.updateCategories(response.body().getData());
                } else {
                    Log.e(TAG, "Failed to load categories: " + response.code());
                    showError("Échec du chargement des catégories");
                }
            }

            @Override
            public void onFailure(Call<CategoryListResponse> call, Throwable t) {
                Log.e(TAG, "Category load error: " + t.getMessage());
                showError("Échec du chargement des catégories");
            }
        });
    }

    private void loadCostumes() {
        showLoading(true);
        Log.d(TAG, "Loading costumes...");

        Call<CostumeListResponse> call = RetrofitClient.getApiService().getCostumes();
        call.enqueue(new Callback<CostumeListResponse>() {
            @Override
            public void onResponse(Call<CostumeListResponse> call, Response<CostumeListResponse> response) {
                showLoading(false);
                swipeRefreshLayout.setRefreshing(false);

                if (response.isSuccessful() && response.body() != null && response.body().getData() != null) {
                    List<Costume> costumes = response.body().getData();
                    Log.d(TAG, "Costumes loaded: " + costumes.size());
                    costumeAdapter.updateCostumes(costumes);
                } else {
                    Log.e(TAG, "Failed to load costumes: " + response.code());
                    showError("Impossible de charger les costumes");
                }
            }

            @Override
            public void onFailure(Call<CostumeListResponse> call, Throwable t) {
                showLoading(false);
                swipeRefreshLayout.setRefreshing(false);
                Log.e(TAG, "Costume load error: " + t.getMessage());
                showError("Erreur réseau");
            }
        });
    }

    private void loadCostumesByCategory(int categoryId) {
        showLoading(true);
        Log.d(TAG, "Loading costumes for category: " + categoryId);

        Call<CostumeListResponse> call = RetrofitClient.getApiService().getCostumesByCategory(categoryId);
        call.enqueue(new Callback<CostumeListResponse>() {
            @Override
            public void onResponse(Call<CostumeListResponse> call, Response<CostumeListResponse> response) {
                showLoading(false);

                if (response.isSuccessful() && response.body() != null && response.body().getData() != null) {
                    List<Costume> costumes = response.body().getData();
                    Log.d(TAG, "Filtered costumes loaded: " + costumes.size());
                    costumeAdapter.updateCostumes(costumes);
                } else {
                    Log.e(TAG, "Failed to load filtered costumes");
                }
            }

            @Override
            public void onFailure(Call<CostumeListResponse> call, Throwable t) {
                showLoading(false);
                Log.e(TAG, "Filter load error: " + t.getMessage());
            }
        });
    }

    private void showLoading(boolean show) {
        if (progressBar != null) {
            progressBar.setVisibility(show ? View.VISIBLE : View.GONE);
        }
    }

    private void showError(String message) {
        if (getContext() != null) {
            Toast.makeText(getContext(), message, Toast.LENGTH_SHORT).show();
        }
    }
}

