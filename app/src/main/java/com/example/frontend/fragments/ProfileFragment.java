package com.example.frontend.fragments;

import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;

import com.example.frontend.R;
import com.example.frontend.activities.LoginActivity;
import com.example.frontend.models.User;
import com.example.frontend.network.RetrofitClient;
import com.example.frontend.utils.TokenManager;
import com.google.android.material.button.MaterialButton;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ProfileFragment extends Fragment {

    private TextView tvName, tvEmail, tvPhone;
    private MaterialButton btnLogout;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_profile, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        initViews(view);
        loadProfile();
    }

    private void initViews(View view) {
        tvName = view.findViewById(R.id.tvName);
        tvEmail = view.findViewById(R.id.tvEmail);
        tvPhone = view.findViewById(R.id.tvPhone);
        btnLogout = view.findViewById(R.id.btnLogout);

        btnLogout.setOnClickListener(v -> logout());
    }

    private void loadProfile() {
        // First show cached data
        tvName.setText(TokenManager.getUserName(requireContext()));
        tvEmail.setText(TokenManager.getUserEmail(requireContext()));

        // Then try to fetch fresh data
        if (TokenManager.isLoggedIn(requireContext())) {
            String token = TokenManager.getBearerToken(requireContext());

            Call<User> call = RetrofitClient.getApiService().getProfile(token);
            call.enqueue(new Callback<User>() {
                @Override
                public void onResponse(Call<User> call, Response<User> response) {
                    if (response.isSuccessful() && response.body() != null) {
                        User user = response.body();
                        tvName.setText(user.getName());
                        tvEmail.setText(user.getEmail());
                        if (tvPhone != null && user.getPhone() != null) {
                            tvPhone.setText(user.getPhone());
                        }
                    }
                }

                @Override
                public void onFailure(Call<User> call, Throwable t) {
                    // Use cached data - already set above
                }
            });
        }
    }

    private void logout() {
        String token = TokenManager.getBearerToken(requireContext());

        // Call logout API
        if (token != null) {
            RetrofitClient.getApiService().logout(token).enqueue(new Callback<Void>() {
                @Override
                public void onResponse(Call<Void> call, Response<Void> response) {
                    // Clear local data regardless of API response
                    performLocalLogout();
                }

                @Override
                public void onFailure(Call<Void> call, Throwable t) {
                    // Clear local data anyway
                    performLocalLogout();
                }
            });
        } else {
            performLocalLogout();
        }
    }

    private void performLocalLogout() {
        TokenManager.logout(requireContext());
        Toast.makeText(requireContext(), "Déconnexion réussie", Toast.LENGTH_SHORT).show();

        Intent intent = new Intent(requireContext(), LoginActivity.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
        startActivity(intent);
    }
}

