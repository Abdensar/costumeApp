package com.example.frontend.adapters;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.example.frontend.R;
import com.example.frontend.models.Costume;
import com.example.frontend.network.RetrofitClient;

import java.text.NumberFormat;
import java.util.List;
import java.util.Locale;

public class CostumeAdapter extends RecyclerView.Adapter<CostumeAdapter.CostumeViewHolder> {

    private List<Costume> costumes;
    private final OnCostumeClickListener listener;

    public interface OnCostumeClickListener {
        void onCostumeClick(Costume costume);
    }

    public CostumeAdapter(List<Costume> costumes, OnCostumeClickListener listener) {
        this.costumes = costumes;
        this.listener = listener;
    }

    @NonNull
    @Override
    public CostumeViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.item_costume_card, parent, false);
        return new CostumeViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull CostumeViewHolder holder, int position) {
        holder.bind(costumes.get(position), listener);
    }

    @Override
    public int getItemCount() {
        return costumes != null ? costumes.size() : 0;
    }

    public void updateCostumes(List<Costume> newCostumes) {
        this.costumes = newCostumes;
        notifyDataSetChanged();
    }

    static class CostumeViewHolder extends RecyclerView.ViewHolder {
        private final ImageView ivCostume;
        private final TextView tvName, tvBrand, tvPrice, tvAvailability;

        public CostumeViewHolder(@NonNull View itemView) {
            super(itemView);
            ivCostume = itemView.findViewById(R.id.ivCostume);
            tvName = itemView.findViewById(R.id.tvName);
            tvBrand = itemView.findViewById(R.id.tvCategory); // Using tvCategory for brand
            tvPrice = itemView.findViewById(R.id.tvPrice);
            tvAvailability = itemView.findViewById(R.id.tvAvailability);
        }

        public void bind(Costume costume, OnCostumeClickListener listener) {
            // Name
            tvName.setText(costume.getName() != null ? costume.getName() : "Sans nom");

            // Brand
            if (tvBrand != null) {
                tvBrand.setText(costume.getBrand() != null ? costume.getBrand() : "");
            }

            // Price
            NumberFormat nf = NumberFormat.getInstance(Locale.FRANCE);
            nf.setMinimumFractionDigits(2);
            nf.setMaximumFractionDigits(2);
            tvPrice.setText(nf.format(costume.getPricePerDay()) + " MAD");

            // Availability
            if (tvAvailability != null) {
                if (costume.isAvailable()) {
                    tvAvailability.setText("Disponible");
                    tvAvailability.setVisibility(View.VISIBLE);
                } else {
                    tvAvailability.setText("Indisponible");
                    tvAvailability.setVisibility(View.VISIBLE);
                }
            }

            // Image
            String imageUrl = costume.getFirstImageUrl();
            if (imageUrl != null && !imageUrl.isEmpty()) {
                if (imageUrl.startsWith("/")) {
                    imageUrl = RetrofitClient.getBaseUrl() + imageUrl;
                }
                Glide.with(itemView.getContext())
                        .load(imageUrl)
                        .placeholder(R.drawable.ic_costume_placeholder)
                        .error(R.drawable.ic_costume_placeholder)
                        .centerCrop()
                        .into(ivCostume);
            } else {
                ivCostume.setImageResource(R.drawable.ic_costume_placeholder);
            }

            // Click listener
            itemView.setOnClickListener(v -> listener.onCostumeClick(costume));
        }
    }
}

