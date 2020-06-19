package freaktemplate.kingburger.activity;

import android.Manifest;
import android.app.Activity;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.location.Address;
import android.location.Geocoder;
import android.os.AsyncTask;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.CompoundButton;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.ScrollView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.widget.AppCompatSpinner;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;
import androidx.fragment.app.FragmentActivity;
import androidx.lifecycle.ViewModelProviders;

import com.android.volley.DefaultRetryPolicy;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.bumptech.glide.Glide;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.MapsInitializer;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.model.LatLng;
import com.paypal.android.sdk.payments.PayPalConfiguration;
import com.paypal.android.sdk.payments.PayPalPayment;
import com.paypal.android.sdk.payments.PayPalService;
import com.paypal.android.sdk.payments.PaymentActivity;
import com.paypal.android.sdk.payments.PaymentConfirmation;
import com.stripe.android.Stripe;
import com.stripe.android.TokenCallback;
import com.stripe.android.model.Card;
import com.stripe.android.model.Token;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.lang.ref.WeakReference;
import java.math.BigDecimal;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import freaktemplate.kingburger.R;
import freaktemplate.kingburger.getSet.cityGetSet;
import freaktemplate.kingburger.observableLayer.AppDatabase;
import freaktemplate.kingburger.observableLayer.CartListViewModel;
import freaktemplate.kingburger.observableLayer.Order;
import freaktemplate.kingburger.utils.Constants;
import freaktemplate.kingburger.utils.GPSTracker;
import freaktemplate.kingburger.utils.InternetDialog;
import freaktemplate.kingburger.utils.LanguageSelectore;
import freaktemplate.kingburger.utils.SPmanager;
import freaktemplate.kingburger.utils.WorkaroundMapFragment;

import static android.Manifest.permission.ACCESS_FINE_LOCATION;
import static freaktemplate.kingburger.utils.PaypalConfig.PAYPAL_CLIENT_ID;

public class PlaceOrderInfo extends FragmentActivity implements OnMapReadyCallback, TextWatcher {

    private static final String TAG = "PlaceOrderInfo";
    private static final int PAYPAL_REQUEST_CODE = 999;
    private static String address1,city1,country1,pincode;
    private static String state1;
    private List<cityGetSet> cityList;
    public static final String PUBLISHABLE_KEY = "pk_test_yFUNiYsEESF7QBY0jcZoYK9j00yHumvXho";
    private static GoogleMap mMap;
    private final String[] permission_location = {android.Manifest.permission.ACCESS_FINE_LOCATION, Manifest.permission.ACCESS_COARSE_LOCATION};
    private static double latitudeCurrent;
    private static double longitudeCurrent;
    private final int PERMISSION_REQUEST_CODE = 1001;
    private static int count = 0;
    private static String address = "";
    private Boolean isCameraMoved;
    private CartListViewModel cartListViewModel;
    private String totalPrice;
    private static ProgressDialog pd;
    private EditText et_fullName, et_address, et_email, et_phoneNumber, et_notes;
    private RadioGroup rg_paymentType;
    private AppCompatSpinner spinner_select_city;
    private Activity activity;
    private String cartOrderDetail = "";
    private ProgressDialog progressDialog;
    private RadioButton rb_stripe, rb_cash;
    private RadioButton rb_card;
    private ScrollView sv_inputs;
    private EditText et_cvv, et_year, et_month, et_code,et_name;
    private Button buttonRegister, buttonCancel;
    private Card card;
    private Token token;
    private PayPalConfiguration payPalConfiguration;
    private Intent mservices;
    int mpaypalrequestcode = 999;
    private String paymentId;
    private String payment_type = "cash";
    private String del_charge;

    @Override
    public void onDestroy() {
        stopService(new Intent(this, PayPalService.class));
        super.onDestroy();
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == PAYPAL_REQUEST_CODE) {
            if (resultCode == RESULT_OK) {
                PaymentConfirmation confirmation = data.getParcelableExtra(PaymentActivity.EXTRA_RESULT_CONFIRMATION);
                if (confirmation != null) {
                    try {
                        //Getting the payment details
                        String paymentDetails = confirmation.toJSONObject().toString(4);
                        JSONObject jsonDetails = new JSONObject(paymentDetails);
                        JSONObject response = jsonDetails.optJSONObject("response");
                        paymentId = response.getString("id");
                        Log.i("paymentExample", paymentId);
                        postData(getSharedPreferences(getString(R.string.shared_pref_name), MODE_PRIVATE).getInt("userId", -1), (String) spinner_select_city.getSelectedItem(), et_fullName.getText().toString(), et_address.getText().toString(), et_email.getText().toString(), et_phoneNumber.getText().toString(), et_notes.getText().toString(), "paypal", cartOrderDetail, token);

                    } catch (JSONException e) {
                        Log.e("paymentExample", getString(R.string.pay_failure) + e.getMessage());
                    }
                }
            } else if (resultCode == Activity.RESULT_CANCELED)
                Toast.makeText(this, "Cancel", Toast.LENGTH_SHORT).show();
        } else if (resultCode == PaymentActivity.RESULT_EXTRAS_INVALID)
            Toast.makeText(this, "Invalid", Toast.LENGTH_SHORT).show();
    }


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        LanguageSelectore.setLanguageIfAR(PlaceOrderInfo.this);
        setContentView(R.layout.activity_place_order_info);

        payPalConfiguration = new PayPalConfiguration().environment(PayPalConfiguration.ENVIRONMENT_SANDBOX).clientId(PAYPAL_CLIENT_ID);
        mservices = new Intent(this, PayPalService.class);
        mservices.putExtra(PayPalService.EXTRA_PAYPAL_CONFIGURATION, payPalConfiguration);
        startService(mservices);

        del_charge  = SPmanager.getPreference(PlaceOrderInfo.this,"del_charge");

        Log.e("lat=long", String.valueOf(latitudeCurrent + "=" + longitudeCurrent));
        Log.e("count", String.valueOf(count));

        //assign view model of database for the activity
        cartListViewModel = ViewModelProviders.of(this).get(CartListViewModel.class);
        getIntents();
        //initializing Views
        initViews();

        pd = ProgressDialog.show(PlaceOrderInfo.this, "", PlaceOrderInfo.this.getString(R.string.txt_load));

    }

    private void getIntents() {
        cartOrderDetail = getIntent().getStringExtra("CartDetail");
        totalPrice = getIntent().getStringExtra("CartTotalPrice");
    }

    private void initViews() {
        activity = PlaceOrderInfo.this;
        ImageView iv_background = findViewById(R.id.iv_background);
        Glide.with(this).load(R.drawable.ezgif).into(iv_background);

        TextView tv_itemName = findViewById(R.id.tv_itemName);
        tv_itemName.setTypeface(Home.tf_main_bold);
        sv_inputs = findViewById(R.id.sv_inputs);

        rb_cash = findViewById(R.id.rb_cash);
        rb_stripe = findViewById(R.id.rb_stripe);
        rb_card = findViewById(R.id.rb_card);
        et_fullName = findViewById(R.id.et_fullName);
        et_address = findViewById(R.id.et_address);
        et_email = findViewById(R.id.et_email);
        et_phoneNumber = findViewById(R.id.et_phoneNumber);
        et_phoneNumber.setText(getSharedPreferences(getString(R.string.shared_pref_name), MODE_PRIVATE).getString("registeredNumber", ""));
        et_notes = findViewById(R.id.et_notes);

        et_address.setTypeface(Home.tf_main_bold);
        et_email.setTypeface(Home.tf_main_bold);
        et_fullName.setTypeface(Home.tf_main_bold);
        et_phoneNumber.setTypeface(Home.tf_main_bold);
        et_notes.setTypeface(Home.tf_main_bold);
        rb_cash.setTypeface(Home.tf_main_bold);
        rb_card.setTypeface(Home.tf_main_bold);
        rb_stripe.setTypeface(Home.tf_main_bold);
        ((TextView) findViewById(R.id.tv_paymentType)).setTypeface(Home.tf_main_bold);

        String name = SPmanager.getPreference(PlaceOrderInfo.this,"name");
        String phone = SPmanager.getPreference(PlaceOrderInfo.this,"phone");

        if (name !=null){
            et_fullName.setText(name);
        }
        if (phone != null) {
            et_phoneNumber.setText(phone);
        }

        rb_card.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                if (isChecked) {
                    payment_type = "paypal";
                }

            }
        });
        rb_cash.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                if (isChecked) {
                    payment_type = "cash";
                }
            }
        });
        rb_stripe.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                if (isChecked) {
                    payment_type = "Stripe";
                }
            }
        });


        rg_paymentType = findViewById(R.id.rg_paymentType);
        spinner_select_city = findViewById(R.id.spinner_select_city);


        if (Home.isNetworkConnected(this))
            getCities();
        else {
            InternetDialog dialog = new InternetDialog(PlaceOrderInfo.this, new InternetDialog.onRetry() {
                @Override
                public void onReload() {
                    getCities();
                }
            });
            dialog.show();
        }

        MapsInitializer.initialize(getApplicationContext());
        WorkaroundMapFragment mapFragment = (WorkaroundMapFragment) getSupportFragmentManager().findFragmentById(R.id.map);
        mapFragment.getMapAsync(this);
        mapFragment.setListener(new WorkaroundMapFragment.OnTouchListener() {
            @Override
            public void onTouch() {
                sv_inputs.requestDisallowInterceptTouchEvent(true);
            }
        });
        //initialize google map
        if (checkPermission()) {
            gettingLocation();

        } else requestPermission();
        findViewById(R.id.rl_CompleteOrder).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
//                openstripedialog();
                startActivityAfterValidation();

            }
        });

    }

    private void getPaymentFrompaypal() {
        PayPalPayment payment = new PayPalPayment(new BigDecimal(totalPrice), "USD", getString(R.string.burger_payment), PayPalPayment.PAYMENT_INTENT_SALE);
//default activity for making payments
        Intent intent = new Intent(this, PaymentActivity.class);
        intent.putExtra(PayPalService.EXTRA_PAYPAL_CONFIGURATION, payPalConfiguration);
        intent.putExtra(PaymentActivity.EXTRA_PAYMENT, payment);
        startActivityForResult(intent, PAYPAL_REQUEST_CODE);
    }

    private void getCities() {
        String url = getString(R.string.link) + "api/getcity";
        Log.d(getLocalClassName(), url);
        StringRequest stringRequest = new StringRequest(Request.Method.GET, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                try {
                    Log.d(getLocalClassName(), response);

                    handleResponse(response);
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {

            }
        });
        stringRequest.setRetryPolicy(new DefaultRetryPolicy(6000, 1, DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        stringRequest.setShouldCache(false);
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(stringRequest);

    }

    private void handleResponse(String response) throws JSONException {
        JSONObject jsonObject = new JSONObject(response);

        JSONObject jsonData = jsonObject.optJSONObject("data");
        if (jsonData.optString("success").equals("1")) {

            JSONArray jo_array = jsonData.getJSONArray("city");
            if (jo_array.length() > 0) {
                cityList = new ArrayList<>();
                for (int i = 0; i < jo_array.length(); i++) {
                    cityGetSet tem = new cityGetSet();
                    JSONObject jo = jo_array.optJSONObject(i);
                    tem.setCityName(jo.optString("city_name"));
                    tem.setCityId(jo.optString("id"));
                    cityList.add(tem);
                }

            }
            if (cityList.size() > 0) {
                cityGetSet temp = new cityGetSet();
                temp.setCityName(getString(R.string.hintselect_city));
                cityList.add(temp);

                String[] cities = new String[cityList.size()];
                for (int i = 0; i < cityList.size(); i++) {
                    cities[i] = cityList.get(i).getCityName();
                }
                CustomSpinnerAdapter customSpinnerAdapter = new CustomSpinnerAdapter(PlaceOrderInfo.this, R.layout.custom_spinner_delivery, cities);
                spinner_select_city.setAdapter(customSpinnerAdapter);
                spinner_select_city.setSelection(spinner_select_city.getCount());

            }
        }

    }

    private void startActivityAfterValidation() {

        //validation fields
        if (spinner_select_city.getSelectedItemPosition() != spinner_select_city.getCount()) {
            if (isValid(et_fullName)) {
                if (isValid(et_address)) {
                    if (emailValidator(et_email)) {
                        if (isValid(et_phoneNumber)) {
                            if (isValid(et_notes)) {
                                if (rg_paymentType.getCheckedRadioButtonId() != -1) {
                                    String paymentType = "";
                                    if (rg_paymentType.getCheckedRadioButtonId() == rb_cash.getId())
                                        paymentType = payment_type;
                                    else if (rg_paymentType.getCheckedRadioButtonId() == rb_card.getId())
                                        paymentType = payment_type;
                                    else if (rg_paymentType.getCheckedRadioButtonId() == rb_stripe.getId())
                                        paymentType = payment_type;
                                    if (Home.isNetworkConnected(PlaceOrderInfo.this)) {

                                        switch (payment_type) {
                                            case "Stripe":
                                                openstripedialog();
                                                break;
                                            case "paypal":
                                                getPaymentFrompaypal();
                                                break;
                                            case "cash":
                                                Log.e("user_id", String.valueOf(getSharedPreferences(getString(R.string.shared_pref_name), MODE_PRIVATE).getInt("userId", -1)));
                                                postData(getSharedPreferences(getString(R.string.shared_pref_name), MODE_PRIVATE).getInt("userId", -1), (String) spinner_select_city.getSelectedItem(), et_fullName.getText().toString(), et_address.getText().toString(), et_email.getText().toString(), et_phoneNumber.getText().toString(), et_notes.getText().toString(),paymentType,cartOrderDetail, token);

                                                break;
                                        }

                                       /* if (paymentType.equals("Stripe")) {

                                            openstripedialog();

                                        } else {
                                            Log.e("user_id", String.valueOf(getSharedPreferences(getString(R.string.shared_pref_name), MODE_PRIVATE).getInt("userId", -1)));
                                            postData(getSharedPreferences(getString(R.string.shared_pref_name), MODE_PRIVATE).getInt("userId", -1), (String) spinner_select_city.getSelectedItem(), et_fullName.getText().toString(), et_address.getText().toString(), et_email.getText().toString(), et_phoneNumber.getText().toString(), et_notes.getText().toString(), paymentType, cartOrderDetail);

                                        }*/

                                    } else {
                                        final String finalPaymentType = paymentType;
                                        InternetDialog dialog = new InternetDialog(PlaceOrderInfo.this, new InternetDialog.onRetry() {
                                            @Override
                                            public void onReload() {
                                                postData(getSharedPreferences(getString(R.string.shared_pref_name), MODE_PRIVATE).getInt("userId", -1), (String) spinner_select_city.getSelectedItem(), et_fullName.getText().toString(), et_address.getText().toString(), et_email.getText().toString(), et_phoneNumber.getText().toString(), et_notes.getText().toString(),finalPaymentType, cartOrderDetail, token);

                                            }
                                        });
                                        dialog.show();

                                    }

                                } else {

                                    Toast.makeText(PlaceOrderInfo.this, R.string.error_payment, Toast.LENGTH_SHORT).show();
                                }
                            } else {

                                et_notes.setError(getString(R.string.error_notes));
                                et_notes.requestFocus();
                            }
                        } else {

                            et_phoneNumber.setError(getString(R.string.error_number));
                            et_phoneNumber.requestFocus();
                        }
                    } else {

                        et_email.setError(getString(R.string.error_email));
                        et_email.requestFocus();
                    }
                } else {

                    et_address.setError(getString(R.string.error_address));
                    et_address.requestFocus();

                }
            } else {

                et_fullName.requestFocus();
                et_fullName.setError(getString(R.string.error_name));
            }
        } else {
            Toast.makeText(PlaceOrderInfo.this, R.string.error_city, Toast.LENGTH_SHORT).show();
        }
    }

    private void openstripedialog() {
        final Dialog dialog = new Dialog(this);
        dialog.setContentView(R.layout.stripe_dialog);
        dialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
        dialog.show();
        et_code = dialog.findViewById(R.id.et_code);
        et_month = dialog.findViewById(R.id.et_month);
        et_year = dialog.findViewById(R.id.et_year);
        et_cvv = dialog.findViewById(R.id.et_cvv);
        et_name = dialog.findViewById(R.id.et_name);
        buttonCancel = dialog.findViewById(R.id.buttonCancel);
        buttonRegister = dialog.findViewById(R.id.buttonRegister);


//        progress.setTitle(title);
//        progress.setMessage("Please Wait");
//        progress.show();

        et_code.addTextChangedListener(this);
        et_month.addTextChangedListener(this);
        et_year.addTextChangedListener(this);
        et_cvv.addTextChangedListener(this);
        et_name.addTextChangedListener(this);


        buttonRegister.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                final String card_number = et_code.getText().toString().trim();
                final String card_month = et_month.getText().toString().trim();
                final String card_year = et_year.getText().toString().trim();
                final String card_cvv = et_cvv.getText().toString().trim();
                final String card_name = et_name.getText().toString().trim();
                validation(card_number, card_month, card_year, card_cvv,card_name);

            }
        });
        buttonCancel.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialog.dismiss();
            }
        });


    }

    private void validation(String card_number, String card_month, String card_year, String card_cvv,String card_name) {

        if (card_name.length() != 0) {
            if (card_number.length() == 16) {

                if (card_month.length() == 2 && Integer.parseInt(card_month) <= 12) {
                    if (card_year.length() == 4) {
                        if (card_cvv.length() == 3) {
                            card = new Card(
                                    card_number, //card number
                                    Integer.valueOf(card_month), //expMonth
                                    Integer.valueOf(card_year),//expYear
                                    card_cvv,card_name,et_address.getText().toString(),address1,city1,state1,pincode,country1,"usd"
                            );

                            buy(card_number, card_month, card_year, card_cvv,card_name);

                        } else {
                            et_cvv.setError("Enter Valid cvv");
                        }
                    } else {
                        et_year.setError("Enter valid Year");
                    }

                } else {
                    et_month.setError("Enter valid Month");
                }


            } else {
                et_code.setError("Enter Valid Card Number");
            }
        }else {
            et_name.setError("Enter Valid Card Name");
        }

    }

    private void buy(String card_number, String card_month, String card_year, String card_cvv,String card_name) {
        progressDialog = new ProgressDialog(PlaceOrderInfo.this);
        progressDialog.setMessage(getString(R.string.loading));
        progressDialog.setCancelable(false);
        progressDialog.show();

        boolean validation = card.validateCard();
        if (validation) {
            new Stripe(this).createToken(
                    card,
                    Constants.stripe_key,
                    new TokenCallback() {
                        @Override
                        public void onError(Exception error) {
                            Log.e(TAG, "onError: " + error.getMessage());
                            Toast.makeText(activity, error.getMessage(), Toast.LENGTH_SHORT).show();
                        }

                        @Override
                        public void onSuccess(Token token) {
                            Log.e(TAG, "onSuccess: " + token);
                            progressDialog.dismiss();
                            postData(getSharedPreferences(getString(R.string.shared_pref_name), MODE_PRIVATE).getInt("userId", -1), (String) spinner_select_city.getSelectedItem(), et_fullName.getText().toString(), et_address.getText().toString(), et_email.getText().toString(), et_phoneNumber.getText().toString(), et_notes.getText().toString(),"Stripe", cartOrderDetail, token);

                        }
                    });
        } else if (!card.validateNumber()) {
            Toast.makeText(PlaceOrderInfo.this,
                    "Stripe - The card number that you entered is invalid",
                    Toast.LENGTH_LONG).show();
        } else if (!card.validateExpiryDate()) {
            Toast.makeText(PlaceOrderInfo.this,
                    "Stripe - The expiration date that you entered is invalid",
                    Toast.LENGTH_LONG).show();
        } else if (!card.validateCVC()) {
            Toast.makeText(PlaceOrderInfo.this,
                    "Stripe - The CVC code that you entered is invalid",
                    Toast.LENGTH_LONG).show();
        } else {
            Toast.makeText(PlaceOrderInfo.this,
                    "Stripe - The card details that you entered are invalid",
                    Toast.LENGTH_LONG).show();
        }
    }

    private void postData(final int userId, final String selectedCity, final String selectedName, final String selectedAddress, final String selectedEmail, String selectedNumber, final String selectedNotes,final String selectedPaymentType, final String selectedOrder, final Token stripe_token) {
        progressDialog = new ProgressDialog(PlaceOrderInfo.this);
        progressDialog.setMessage(getString(R.string.loading));
        progressDialog.setCancelable(false);
        progressDialog.show();
        Log.w("userId", userId + "");
        Log.w("selectedCity", selectedCity);
        Log.w("selectedName", selectedName);
        Log.w("selectedAddress", selectedAddress);
        Log.w("selectedEmail", selectedEmail);
        Log.w("selectedNumber", selectedNumber);
        Log.w("selectedPaymentType", selectedPaymentType);
        Log.w("selectedOrder", selectedOrder);
        Log.w("totalPrice", totalPrice);
        final String token_id = getSharedPreferences(getString(R.string.shared_pref_name), MODE_PRIVATE).getString("token", "");
        Log.w("token_id", token_id);
        Log.w("device_type", "android");
        long tsLong = System.currentTimeMillis() / 1000;
        final String ts = Long.toString(tsLong);
        final String userID = SPmanager.getPreference(PlaceOrderInfo.this, "user_id");
        String hp = getString(R.string.link) + "api/food_order";
        StringRequest postRequest = new StringRequest(Request.Method.POST, hp, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                // response
                Log.e("Response", response);
                Log.e("PlaceOrder", "onResponse: " + response);
                if (progressDialog.isShowing()) {
                    progressDialog.dismiss();
                }
                try {
                    Intent i = new Intent(PlaceOrderInfo.this, OrderDetail.class);
                    JSONObject jsonObject = new JSONObject(response);
                    if (jsonObject.getString("success").equals("1")) {
                        cartListViewModel.emptyCart();
                        cartListViewModel.emptyCartTopping();
                        Toast.makeText(PlaceOrderInfo.this, jsonObject.getString("order_details"), Toast.LENGTH_SHORT).show();
                        int orderId = jsonObject.getInt("order_id");
                        i.putExtra("orderId", orderId);
                        i.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                        startActivity(i);
                        //save successful order in database
                        //create order object
                        Order order = new Order();
                        order.setTimestamp(getTimeStamp());
                        order.setOrderId(orderId);
                        order.setUserId(userId);
                        order.setOrderPrice(Double.parseDouble(totalPrice));
                        order.setAddress(selectedAddress);

                        AppDatabase appDatabase = AppDatabase.getAppDatabase(PlaceOrderInfo.this);
                        new addOrderDetailToDatabase(appDatabase).execute(order);

                    } else {
                        Toast.makeText(activity, R.string.try_again, Toast.LENGTH_SHORT).show();
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                    Toast.makeText(activity, R.string.try_again, Toast.LENGTH_SHORT).show();
                }
            }
        },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        // error
                        Log.d("Error.Response", error.toString());
                        Toast.makeText(activity, R.string.try_again, Toast.LENGTH_SHORT).show();
                    }
                }
        ) {
            @Override
            protected Map<String, String> getParams() {

                Map<String, String> params = new HashMap<>();
                params.put("user_id", userID);
                params.put("name", selectedName);
                params.put("email", selectedEmail);
                params.put("address", selectedAddress);
                params.put("latlong", String.valueOf(latitudeCurrent) + "," + String.valueOf(longitudeCurrent));
                params.put("payment_type", selectedPaymentType);
                params.put("city", selectedCity);
                params.put("notes", selectedNotes);
                params.put("food_desc", selectedOrder);
                if (payment_type.equals("Stripe")) {
                    params.put("stripeToken", stripe_token.getId());

                } else if (payment_type.equals("paypal")) {
                    params.put("pay_pal_paymentId", paymentId);
                }
                params.put("total_price", totalPrice);
                params.put("token", token_id);
                params.put("delivery_charges", "0.00");
                params.put("tax", "0.0");
                params.put("delivery_mode", "0");
                params.put("pickup_order_time", ts);

                Log.e("PlaceOrder", "getParams: user_id " + String.valueOf(userId) + " name " + selectedName + " email " + selectedEmail + " address " + address);


                return params;
            }

        };
        Log.e("url", hp);

        postRequest.setShouldCache(false);
        RequestQueue requestQueue = Volley.newRequestQueue(this);
        requestQueue.add(postRequest);

    }

    private String getTimeStamp() {
        Date c = Calendar.getInstance().getTime();
        System.out.println("Current time => " + c);
        SimpleDateFormat df = new SimpleDateFormat("dd-MMM-yyyy hh:mm:ss", Locale.ENGLISH);
        return df.format(c);
    }

    private void gettingLocation() {
        GPSTracker gps = new GPSTracker();
        gps.init(PlaceOrderInfo.this);
        // check if GPS enabled
        if (gps.canGetLocation()) {
            try {
                latitudeCurrent = gps.getLatitude();
                longitudeCurrent = gps.getLongitude();
            } catch (NumberFormatException e) {
                // TODO: handle exception
                e.printStackTrace();
            }
        } else {
            gps.showSettingsAlert();
        }
    }

    private boolean checkPermission() {
        int result = ContextCompat.checkSelfPermission(getApplicationContext(), ACCESS_FINE_LOCATION);
        return result == PackageManager.PERMISSION_GRANTED;
    }

    private void requestPermission() {
        ActivityCompat.requestPermissions(PlaceOrderInfo.this, permission_location, PERMISSION_REQUEST_CODE);
    }

    @Override
    public void onMapReady(GoogleMap googleMap) {
        try {
            // check if map is created successfully or not
            if (googleMap == null) {
                Toast.makeText(getApplicationContext(), getString(R.string.no_map), Toast.LENGTH_SHORT).show();
                return;
            }

            mMap = googleMap;
            mMap.getUiSettings().setCompassEnabled(false);
            mMap.getUiSettings().setRotateGesturesEnabled(false);
            mMap.getUiSettings().setTiltGesturesEnabled(false);
            mMap.getUiSettings().setZoomControlsEnabled(false);
            mMap.getUiSettings().setMyLocationButtonEnabled(true);
            mMap.getCameraPosition();
            mMap.setTrafficEnabled(false);
            mMap.setIndoorEnabled(false);
            mMap.setBuildingsEnabled(false);


/*
            mMap = googleMap;
            mMap.getUiSettings().setCompassEnabled(true);
            mMap.getUiSettings().setRotateGesturesEnabled(true);
            mMap.getUiSettings().setTiltGesturesEnabled(true);
            mMap.getUiSettings().setZoomControlsEnabled(true);
            mMap.getUiSettings().setMyLocationButtonEnabled(true);
            mMap.setTrafficEnabled(true);
            mMap.setIndoorEnabled(true);
            mMap.setBuildingsEnabled(true);*/
            if (checkPermission())
                mMap.setMyLocationEnabled(true);

        } catch (Exception e) {
            e.printStackTrace();
            Log.e("", "onMapReady: " + e.getMessage());
        }
        try {

            isCameraMoved = true;
            LatLng position = new LatLng(latitudeCurrent, longitudeCurrent);
            latitudeCurrent = position.latitude;
            longitudeCurrent = position.longitude;
            if (Home.isNetworkConnected(PlaceOrderInfo.this))
                new GetDataAsyncTask(activity).execute();
            else new InternetDialog(PlaceOrderInfo.this, new InternetDialog.onRetry() {
                @Override
                public void onReload() {
                    new GetDataAsyncTask(activity).execute();
                }
            }).show();

            if (latitudeCurrent == 0 && longitudeCurrent == 0) {
                mMap.setOnCameraIdleListener(new GoogleMap.OnCameraIdleListener() {
                    @Override
                    public void onCameraIdle() {
                        //get latlng at the center by calling
                        LatLng midLatLng = mMap.getCameraPosition().target;
                        latitudeCurrent = midLatLng.latitude;
                        longitudeCurrent = midLatLng.longitude;
                        if (isCameraMoved) {
                            if (Home.isNetworkConnected(PlaceOrderInfo.this)) {
                                new GetDataAsyncTask(activity).execute();
                                isCameraMoved = !isCameraMoved;
                            } else
                                new InternetDialog(PlaceOrderInfo.this, new InternetDialog.onRetry() {
                                    @Override
                                    public void onReload() {
                                        new GetDataAsyncTask(activity).execute();
                                        isCameraMoved = !isCameraMoved;
                                    }
                                }).show();

                        }
                    }

                });
            }
            mMap.setOnCameraMoveListener(new GoogleMap.OnCameraMoveListener() {
                @Override
                public void onCameraMove() {
                    isCameraMoved = true;
                }
            });

        } catch (NullPointerException | NumberFormatException e) {
            // TODO: handle exception
            e.printStackTrace();
            Log.e("", "onMapReady: " + e.getMessage());
        }
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        if (requestCode == PERMISSION_REQUEST_CODE) {
            if (grantResults.length > 0) {
                if (grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                    gettingLocation();

                    MapsInitializer.initialize(getApplicationContext());
                    WorkaroundMapFragment mapFragment = (WorkaroundMapFragment) getSupportFragmentManager()
                            .findFragmentById(R.id.map);
                    mapFragment.getMapAsync(this);
                    mapFragment.setListener(new WorkaroundMapFragment.OnTouchListener() {
                        @Override
                        public void onTouch() {
                            sv_inputs.requestDisallowInterceptTouchEvent(true);
                        }
                    });

                } else requestPermission();
            }
        }
    }

    @Override
    public void beforeTextChanged(CharSequence s, int start, int count, int after) {

    }

    @Override
    public void onTextChanged(CharSequence s, int start, int before, int count) {
        if (et_code.getText().toString().length() == 16) {
            et_month.requestFocus();
        }
        if (et_month.getText().toString().length() == 2) {
            et_year.requestFocus();
        }
        if (et_year.getText().toString().length() == 4) {
            et_cvv.requestFocus();
        }
        if (et_cvv.getText().toString().length() == 3) {
            buttonRegister.requestFocus();
        }
    }

    @Override
    public void afterTextChanged(Editable s) {

    }

    class CustomSpinnerAdapter extends ArrayAdapter<String> {
        final String[] data;

        CustomSpinnerAdapter(@NonNull Context context, int resource, @NonNull String[] objects) {
            super(context, resource, objects);
            data = objects;
        }

        @NonNull
        @Override
        public View getView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {
            View view = super.getView(position, convertView, parent);
            TextView text = view.findViewById(R.id.txt_spnr);
            text.setTypeface(Home.tf_main_bold);
            return view;
        }

        @Override
        public View getDropDownView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {
            return super.getDropDownView(position, convertView, parent);
        }

        public int getCount() {
            int count = super.getCount();
            return count > 0 ? count - 1 : count;

        }
    }


    private static class GetDataAsyncTask extends AsyncTask<Void, Void, Void> {

        private final WeakReference<Activity> weakActivity;

        GetDataAsyncTask(Activity activity) {
            this.weakActivity = new WeakReference<>(activity);
        }

        @Override
        protected void onPreExecute() {
            Log.e("count", String.valueOf(count));
            super.onPreExecute();
//            if (count == 0) {
//            pd = new Pro;
            pd.setCancelable(false);
//                pd.show();
//            }
            count++;
        }

        @Override
        protected Void doInBackground(Void... params) {
            try {
                Activity activity = weakActivity.get();
                if (getAddress(weakActivity.get()) != null)
                    if (getAddress(weakActivity.get()).size() != 0)
                        address = getAddress(activity).get(0).getAddressLine(0);
            } catch (NullPointerException e) {
                e.printStackTrace();
                throw e;
            } finally {
                if (address.isEmpty())
                    address = "";
            }
            return null;
        }

        @Override
        protected void onPostExecute(Void aVoid) {
            super.onPostExecute(aVoid);
            Activity activity = weakActivity.get();
            if (activity == null || activity.isFinishing() || activity.isDestroyed()) {
                // activity is no longer valid, don't do anything!
                return;
            }

            if (pd.isShowing()) {
                pd.dismiss();
            }

            if (address.equals("")) {
                Toast.makeText(activity, R.string.cant_find_location, Toast.LENGTH_SHORT).show();
//                if (pd.isShowing()) {
                pd.cancel();
//                }
            } else {
                LatLng position = new LatLng(latitudeCurrent, longitudeCurrent);
                mMap.moveCamera(CameraUpdateFactory.newLatLngZoom(position, 17.0f));
                ((EditText) (activity.findViewById(R.id.et_address))).setText(address);
            }
        }

    }

    private static List<Address> getAddress(Activity activity) {
        if (latitudeCurrent != 0 && longitudeCurrent != 0) {
            try {
                Geocoder geocoder = new Geocoder(activity);
                List<Address> addresses = geocoder.getFromLocation(latitudeCurrent, longitudeCurrent, 1);
                address1 = addresses.get(0).getAddressLine(0);
                city1 = addresses.get(0).getLocality();
                country1 = addresses.get(0).getCountryName();
                state1 = addresses.get(0).getAdminArea();
                pincode = addresses.get(0).getPostalCode();
                Log.d("TAG", "address = " + address1 + ", city = " + city1 + ", country = " + country1);
                return addresses;
            } catch (Exception e) {
                e.printStackTrace();
            }
        }

        List<Address> temp = new ArrayList<>();
        return temp;
    }

    private boolean isValid(EditText editText) {
        return editText.length() != 0;
    }

    private boolean emailValidator(EditText editText) {
        String email = editText.getText().toString();
        Pattern pattern;
        Matcher matcher;
        final String EMAIL_PATTERN = "^[_A-Za-z0-9-]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$";
        pattern = Pattern.compile(EMAIL_PATTERN);
        matcher = pattern.matcher(email);
        return matcher.matches();
    }

    @Override
    protected void onPause() {
        super.onPause();
        if (pd != null)
            pd.dismiss();
    }

    private static class addOrderDetailToDatabase extends AsyncTask<Order, Void, Void> {

        final AppDatabase appDatabase;

        addOrderDetailToDatabase(AppDatabase appDatabase1) {
            this.appDatabase = appDatabase1;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
        }

        @Override
        protected Void doInBackground(Order... order) {
            appDatabase.cartDao().insertOrder(order[0]);
            return null;
        }

        @Override
        protected void onPostExecute(Void aVoid) {
            super.onPostExecute(aVoid);
        }

    }

    @Override
    public void onBackPressed() {

        Intent intent = new Intent(PlaceOrderInfo.this, Home.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_NO_HISTORY | Intent.FLAG_ACTIVITY_CLEAR_TOP);
        startActivity(intent);
        finish();
    }


}
