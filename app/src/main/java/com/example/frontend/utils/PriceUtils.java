package com.example.frontend.utils;

import java.text.NumberFormat;
import java.util.Locale;

public class PriceUtils {
    public static String formatPrice(double price) {
        return String.format(Locale.FRANCE, "%.2f", price);
    }

    public static double calculateTotalPrice(double pricePerDay, int days) {
        return pricePerDay * days;
    }

    public static String formatPriceWithCurrency(double price) {
        return String.format(Locale.FRANCE, "%.2f MAD", price);
    }
}

