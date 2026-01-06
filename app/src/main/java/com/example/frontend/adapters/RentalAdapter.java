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
import com.example.frontend.models.Rental;
import com.example.frontend.network.RetrofitClient;

import java.text.NumberFormat;
import java.util.List;
import java.util.Locale;

public class RentalAdapter extends RecyclerView.Adapter<RentalAdapter.RentalViewHolder> {

    private List<Rental> rentals;

    public RentalAdapter(List<Rental> rentals) {
        this.rentals = rentals;
    }

    @NonNull
    @Override
    public RentalViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.item_rental_card, parent, false);
        return new RentalViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull RentalViewHolder holder, int position) {
        holder.bind(rentals.get(position));
    }

    @Override
    public int getItemCount() {
        return rentals != null ? rentals.size() : 0;
    }

    public void updateRentals(List<Rental> newRentals) {
        this.rentals = newRentals;
        notifyDataSetChanged();
    }

    static class RentalViewHolder extends RecyclerView.ViewHolder {
        private final ImageView ivCostume;
        private final TextView tvCostumeName, tvDates, tvSize, tvStatus, tvTotalPrice;

        public RentalViewHolder(@NonNull View itemView) {
            super(itemView);
            ivCostume = itemView.findViewById(R.id.ivCostume);
            tvCostumeName = itemView.findViewById(R.id.tvCostumeName);
            tvDates = itemView.findViewById(R.id.tvDates);
            tvSize = itemView.findViewById(R.id.tvSize);
            tvStatus = itemView.findViewById(R.id.tvStatus);
            tvTotalPrice = itemView.findViewById(R.id.tvTotalPrice);
        }

        public void bind(Rental rental) {
            // Costume name
            if (rental.getCostume() != null) {
                tvCostumeName.setText(rental.getCostume().getName());

                // Load image
                String imageUrl = rental.getCostume().getFirstImageUrl();
                if (imageUrl != null && !imageUrl.isEmpty()) {
                    if (imageUrl.startsWith("/")) {
                        imageUrl = RetrofitClient.getBaseUrl() + imageUrl;
                    }
                    Glide.with(itemView.getContext())
                            .load(imageUrl)
                            .placeholder(R.drawable.ic_costume_placeholder)
                            .into(ivCostume);
                }
            } else {
                tvCostumeName.setText("Costume #" + rental.getCostumeId());
            }

            // Dates
            tvDates.setText(rental.getStartDate() + " - " + rental.getEndDate());

            // Size
            tvSize.setText("Taille: " + rental.getSize());

            // Status
            String status = rental.getStatus();
            tvStatus.setText(getStatusText(status));
            tvStatus.setTextColor(getStatusColor(status));

            // Total price
            NumberFormat nf = NumberFormat.getInstance(Locale.FRANCE);
            nf.setMinimumFractionDigits(2);
            tvTotalPrice.setText(nf.format(rental.getTotalPrice()) + " MAD");
        }

        private String getStatusText(String status) {
            if (status == null) return "Inconnu";
            switch (status.toLowerCase()) {
                case "pending": return "En attente";
                case "confirmed": return "Confirmée";
                case "cancelled": return "Annulée";
                case "returned": return "Retournée";
                default: return status;
            }
        }

        private int getStatusColor(String status) {
            if (status == null) return 0xFF666666;
            switch (status.toLowerCase()) {
                case "pending": return 0xFFFF9800; // Orange
                case "confirmed": return 0xFF4CAF50; // Green
                case "cancelled": return 0xFFF44336; // Red
                case "returned": return 0xFF2196F3; // Blue
                default: return 0xFF666666; // Gray
            }
        }
    }
}

