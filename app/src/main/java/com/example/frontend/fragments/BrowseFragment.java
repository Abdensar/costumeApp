package com.example.frontend.fragments;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ProgressBar;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.GridLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import com.example.frontend.R;
import com.example.frontend.activities.CostumeDetailActivity;
import com.example.frontend.adapters.CostumeAdapter;
import com.example.frontend.models.Costume;
import com.example.frontend.models.CostumeListResponse;
import com.example.frontend.network.RetrofitClient;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class BrowseFragment extends Fragment {
    private static final String TAG = "BrowseFragment";

    private RecyclerView rvCostumes;
    private SwipeRefreshLayout swipeRefreshLayout;
    private ProgressBar progressBar;
    private CostumeAdapter costumeAdapter;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_browse, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        initViews(view);
        setupRecyclerView();
        setupSwipeRefresh();
        loadCostumes();
    }

    private void initViews(View view) {
        rvCostumes = view.findViewById(R.id.rvCostumes);
        swipeRefreshLayout = view.findViewById(R.id.swipeRefreshLayout);
        progressBar = view.findViewById(R.id.progressBar);
    }

    private void setupRecyclerView() {
        rvCostumes.setLayoutManager(new GridLayoutManager(requireContext(), 2));
        costumeAdapter = new CostumeAdapter(new ArrayList<>(), costume -> {
            Intent intent = new Intent(requireContext(), CostumeDetailActivity.class);
            intent.putExtra(CostumeDetailActivity.EXTRA_COSTUME_ID, costume.getId());
            startActivity(intent);
        });
        rvCostumes.setAdapter(costumeAdapter);
    }

    private void setupSwipeRefresh() {
        swipeRefreshLayout.setOnRefreshListener(this::loadCostumes);
    }

    private void loadCostumes() {
        showLoading(true);
        Log.d(TAG, "Loading all costumes...");

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
                Log.e(TAG, "Network error: " + t.getMessage());
                showError("Erreur réseau: " + t.getMessage());
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

