package freaktemplate.kingburger.activity;

import androidx.lifecycle.Observer;
import androidx.lifecycle.ViewModelProviders;

import android.app.Dialog;
import android.content.Intent;
import android.os.Bundle;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.RecyclerView;
import android.util.Log;
import android.view.View;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.bumptech.glide.Glide;

import java.text.DecimalFormat;
import java.util.List;
import java.util.Locale;

import freaktemplate.kingburger.R;
import freaktemplate.kingburger.adapter.CustomCompleteOrderAdapter;
import freaktemplate.kingburger.observableLayer.Cart;
import freaktemplate.kingburger.observableLayer.CartListViewModel;
import freaktemplate.kingburger.utils.LanguageSelectore;
import freaktemplate.kingburger.utils.SPmanager;

public class CompleteOrder extends AppCompatActivity {

    private TextView tv_totalAmount,tv_cartTotal, tv_delevery;
    private RecyclerView rv_listOrderItems;
    private List<Cart> data;
    private CustomCompleteOrderAdapter customCompleteOrderAdapter;
    private CartListViewModel cartListViewModel;
    private Boolean isCartEmpty = true;
    private TextView tv_noData;
    private static Double totalPrice = 0.0;
    private String order_status;
    private String del_charge;
    private Double totalprice;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        LanguageSelectore.setLanguageIfAR(CompleteOrder.this);

        setContentView(R.layout.activity_complete_order);

        //assign view model of database for the activity
        cartListViewModel = ViewModelProviders.of(this).get(CartListViewModel.class);

        //initializing Views
        initViews();

    }

    private void initViews() {
        //setting Background
        ImageView iv_background = findViewById(R.id.iv_background);
        Glide.with(this).load(R.drawable.ezgif).into(iv_background);

//        tv_noData = findViewById(R.id.tv_noData);
//        tv_noData.setTypeface(Home.tf_main_bold);

        tv_totalAmount = findViewById(R.id.tv_totalAmount);
        tv_cartTotal = findViewById(R.id.tv_cartTotal);
        tv_delevery = findViewById(R.id.tv_delevery);
        tv_totalAmount.setTypeface(Home.tf_main_bold);

        rv_listOrderItems = findViewById(R.id.rv_listOrderItems);
        TextView tv_itemName = findViewById(R.id.tv_itemName);

        tv_itemName.setTypeface(Home.tf_main_bold);
        del_charge  = SPmanager.getPreference(CompleteOrder.this,"del_charge");

        if (del_charge != null){
            tv_delevery.setText(getString(R.string.currency) + new DecimalFormat("#0.00").format(Double.parseDouble(del_charge)));
        }
        order_status  = SPmanager.getPreference(CompleteOrder.this,"order_status");

        cartListViewModel.getCartList().observe(this, new Observer<List<Cart>>() {
            @Override
            public void onChanged(@Nullable List<Cart> carts) {
                data = carts;
                updateUI(data);
            }
        });


        findViewById(R.id.rl_CompleteOrder).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (isCartEmpty) {
                    Toast.makeText(CompleteOrder.this, getString(R.string.cart_is_empty), Toast.LENGTH_SHORT).show();
                } /*else if(order_status.equals("0")) {
                    showdialog();
                }*/else {
                    Log.e("user_id", String.valueOf(getSharedPreferences(getString(R.string.shared_pref_name), MODE_PRIVATE).getInt("userId", -1)));
                    String userID = SPmanager.getPreference(CompleteOrder.this, "user_id");
                    if (userID != null) {
                        if (userID.equals("null")) {
                            Intent intent = new Intent(CompleteOrder.this, LoginActivity.class);
                            startActivity(intent);

                        } else {
                            Intent intent = new Intent(CompleteOrder.this, PlaceOrderInfo.class);
                            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                            intent.putExtra("CartDetail", customCompleteOrderAdapter.getJsonOrderFormat());
                            intent.putExtra("CartTotalPrice", String.valueOf(totalprice));
                            startActivity(intent);
                        }
                    } else {
                        Intent intent = new Intent(CompleteOrder.this, LoginActivity.class);
                        startActivity(intent);
                    }


                }
            }
        });
    }

    private void showdialog() {
        Dialog d = new Dialog(CompleteOrder.this);
        d.setContentView(R.layout.status_dailog);
        d.getWindow().setBackgroundDrawableResource(android.R.color.transparent);
        d.show();
        d.getWindow().setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_STATE_ALWAYS_HIDDEN);

        Button buttonCancel;
        buttonCancel = d.findViewById(R.id.buttonCancel);

        buttonCancel.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(CompleteOrder.this, Home.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                startActivity(intent);
            }
        });
    }
    public static double round(double Rval, int numberOfDigitsAfterDecimal) {
        double p = (float) Math.pow(10, numberOfDigitsAfterDecimal);
        Rval = Rval * p;
        double tmp = Math.floor(Rval);
        System.out.println("~~~~~~tmp~~~~~" + tmp);
        return (double) tmp / p;
    }
    private void updateUI(List<Cart> data) {
        isCartEmpty = false;
        customCompleteOrderAdapter = new CustomCompleteOrderAdapter(CompleteOrder.this, data);
        rv_listOrderItems.setAdapter(customCompleteOrderAdapter);
        totalPrice = customCompleteOrderAdapter.getTotalPrice();
        tv_cartTotal.setText(getString(R.string.currency) + String.valueOf(totalPrice));
//        tv_totalAmount.setText(String.format(Locale.ENGLISH, "%s%s %.2f", getString(R.string.total_amount), getString(R.string.currency), totalPrice));
        if (del_charge != null){
            Double text = Double.parseDouble(del_charge);
            Double total = text + totalPrice;
            totalprice = round(total, 1);
            tv_totalAmount.setText(getString(R.string.currency) + total);
        }else {
//            Double text = Double.parseDouble(del_charge);
            Double total =  0.00 + totalPrice;
            totalprice = round(total, 1);
            tv_totalAmount.setText(getString(R.string.currency) + total);
        }
       /* if (data.size() > 0) {
            tv_noData.setVisibility(View.GONE);
        } else {
            isCartEmpty = true;
            tv_noData.setVisibility(View.VISIBLE);
        }*/
    }
}
