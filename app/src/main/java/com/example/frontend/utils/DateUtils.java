package com.example.frontend.utils;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;
import java.util.concurrent.TimeUnit;

public class DateUtils {
    private static final SimpleDateFormat API_DATE_FORMAT = new SimpleDateFormat("yyyy-MM-dd", Locale.US);
    private static final SimpleDateFormat DISPLAY_DATE_FORMAT = new SimpleDateFormat("MMM dd, yyyy", Locale.US);
    private static final SimpleDateFormat FULL_DATE_FORMAT = new SimpleDateFormat("MMMM dd, yyyy", Locale.US);

    public static String formatDate(String date) {
        try {
            Date parsedDate = API_DATE_FORMAT.parse(date);
            if (parsedDate != null) {
                return DISPLAY_DATE_FORMAT.format(parsedDate);
            }
        } catch (ParseException e) {
            e.printStackTrace();
        }
        return date;
    }

    public static String formatDateFull(String date) {
        try {
            Date parsedDate = API_DATE_FORMAT.parse(date);
            if (parsedDate != null) {
                return FULL_DATE_FORMAT.format(parsedDate);
            }
        } catch (ParseException e) {
            e.printStackTrace();
        }
        return date;
    }

    public static int calculateDays(String startDate, String endDate) {
        try {
            Date start = API_DATE_FORMAT.parse(startDate);
            Date end = API_DATE_FORMAT.parse(endDate);
            if (start != null && end != null) {
                long diff = end.getTime() - start.getTime();
                return (int) TimeUnit.DAYS.convert(diff, TimeUnit.MILLISECONDS) + 1;
            }
        } catch (ParseException e) {
            e.printStackTrace();
        }
        return 0;
    }

    public static boolean isDateValid(String date) {
        try {
            Date parsedDate = API_DATE_FORMAT.parse(date);
            return parsedDate != null;
        } catch (ParseException e) {
            return false;
        }
    }

    public static String getCurrentDate() {
        return API_DATE_FORMAT.format(new Date());
    }

    public static String formatDateToApi(Date date) {
        return API_DATE_FORMAT.format(date);
    }

    public static Date parseApiDate(String date) {
        try {
            return API_DATE_FORMAT.parse(date);
        } catch (ParseException e) {
            e.printStackTrace();
            return null;
        }
    }

    public static boolean isDateBeforeToday(String date) {
        try {
            Date parsedDate = API_DATE_FORMAT.parse(date);
            Date today = new Date();
            Calendar todayCal = Calendar.getInstance();
            todayCal.setTime(today);
            todayCal.set(Calendar.HOUR_OF_DAY, 0);
            todayCal.set(Calendar.MINUTE, 0);
            todayCal.set(Calendar.SECOND, 0);
            todayCal.set(Calendar.MILLISECOND, 0);

            if (parsedDate != null) {
                return parsedDate.before(todayCal.getTime());
            }
        } catch (ParseException e) {
            e.printStackTrace();
        }
        return false;
    }

    public static boolean isDateAfter(String date1, String date2) {
        try {
            Date d1 = API_DATE_FORMAT.parse(date1);
            Date d2 = API_DATE_FORMAT.parse(date2);
            if (d1 != null && d2 != null) {
                return d1.after(d2);
            }
        } catch (ParseException e) {
            e.printStackTrace();
        }
        return false;
    }
}

