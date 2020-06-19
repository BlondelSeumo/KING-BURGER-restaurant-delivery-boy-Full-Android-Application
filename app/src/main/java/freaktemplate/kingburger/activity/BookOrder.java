package freaktemplate.kingburger.activity;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

import com.bumptech.glide.Glide;

import java.util.List;

import freaktemplate.kingburger.R;
import freaktemplate.kingburger.adapter.BookOrderAdapter;
import freaktemplate.kingburger.observableLayer.AppDatabase;
import freaktemplate.kingburger.observableLayer.Order;
import freaktemplate.kingburger.utils.LanguageSelectore;

public class BookOrder extends AppCompatActivity {

    private List<Order> data;
    private String UserId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        LanguageSelectore.setLanguageIfAR(BookOrder.this);

        setContentView(R.layout.activity_book_order);
        //initializing Views
        initViews(1);


        //   UserId = getSharedPreferences( getString( R.string.shared_pref_name ),MODE_PRIVATE ).getInt( "userId",-1 );
    }

    private void initViews(int position) {
        //setting Background
        ImageView iv_background = findViewById(R.id.iv_background);


        Glide.with(this).load(R.drawable.ezgif).into(iv_background);

        ListView rv_listBookOrder = findViewById(R.id.rv_listBookOrder);
        TextView tv_itemName = findViewById(R.id.tv_itemName);
        TextView tv_noData = findViewById(R.id.tv_noData);
        tv_itemName.setTypeface(Home.tf_main_bold);


        final List<Order> orders = AppDatabase.getAppDatabase(this).cartDao().getAllOrders(getSharedPreferences(getString(R.string.shared_pref_name), MODE_PRIVATE).getInt("userId", -1));

        if (orders != null && orders.size() != 0) {

            BookOrderAdapter bookOrderAdapter = new BookOrderAdapter(orders, BookOrder.this);
            rv_listBookOrder.setAdapter(bookOrderAdapter);
        } else tv_noData.setVisibility(View.VISIBLE);


        rv_listBookOrder.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                startActivity(new Intent(BookOrder.this, OrderDetail.class).putExtra("key", "book").putExtra("orderId", orders.get(position).getOrderId()));
            }
        });

    }
}
