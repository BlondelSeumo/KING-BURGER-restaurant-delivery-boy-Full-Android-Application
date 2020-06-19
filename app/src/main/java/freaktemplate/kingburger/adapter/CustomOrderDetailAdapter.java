package freaktemplate.kingburger.adapter;

import android.content.Context;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import android.text.SpannableString;
import android.text.style.RelativeSizeSpan;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import freaktemplate.kingburger.R;
import freaktemplate.kingburger.activity.Home;
import freaktemplate.kingburger.getSet.OrderDetailGetSet;
import freaktemplate.kingburger.observableLayer.CartItemTopping;
import freaktemplate.kingburger.observableLayer.CombinedCart;

import java.util.ArrayList;
import java.util.Locale;

public class CustomOrderDetailAdapter extends RecyclerView.Adapter<RecyclerView.ViewHolder> {

    private final ArrayList<CombinedCart> dataList;
    private final OrderDetailGetSet dataMain;
    private static final int TYPE_HEADER = 0;
    private static final int TYPE_CELL = 1;
    private final Context context;


    public CustomOrderDetailAdapter(ArrayList<CombinedCart> dataList, OrderDetailGetSet dataMain, Context context) {
        this.dataList = dataList;
        this.dataMain = dataMain;
        this.context = context;
    }

    @NonNull
    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        if (viewType == TYPE_HEADER) {
            View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.cell_order_main, parent, false);
            return new mainDataViewHolder(itemView);

        }
        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.cell_custom_order, parent, false);
        return new ViewHolderOrderDetail(itemView);


    }

    @Override
    public void onBindViewHolder(@NonNull RecyclerView.ViewHolder holder, int position) {
        if (holder instanceof mainDataViewHolder) {
            mainDataViewHolder maindataviewHolder = ((mainDataViewHolder) holder);
            maindataviewHolder.tv_orderDate.setTypeface(Home.tf_main_bold);
            maindataviewHolder.tv_orderDate.setText(dataMain.getOrderDate());

            String s = String.format("%s %s", context.getString(R.string.currency), dataMain.getOrderTotalAmount());
            SpannableString ss1 = new SpannableString(s);
            ss1.setSpan(new RelativeSizeSpan(0.7f), ss1.toString().indexOf("."), ss1.toString().length(), 0); // set size

            maindataviewHolder.tv_orderTotalPrice.setTypeface(Home.tf_main_bold);
            maindataviewHolder.tv_orderTotalPrice.setText(ss1);


            maindataviewHolder.tv_orderNumber.setTypeface(Home.tf_main_regular);
            maindataviewHolder.tv_orderNumber.setText(String.format("%s Items Order", dataMain.getNumberOfItems()));


            maindataviewHolder.tv_orderAddress.setTypeface(Home.tf_main_regular);
            maindataviewHolder.tv_orderAddress.setText(dataMain.getOrderDeliveryAddress());


            maindataviewHolder.tv_orderPhone.setTypeface(Home.tf_main_regular);
            maindataviewHolder.tv_orderPhone.setText(dataMain.getOrderDeliveryPhone());


        } else if (holder instanceof ViewHolderOrderDetail) {
            position = position - 1;
            ViewHolderOrderDetail viewHolderOrderDetail = ((ViewHolderOrderDetail) holder);
            viewHolderOrderDetail.tv_item_name.setTypeface(Home.tf_main_bold);
            viewHolderOrderDetail.tv_item_price.setTypeface(Home.tf_main_bold);
            viewHolderOrderDetail.tv_subItem_list.setTypeface(Home.tf_main_regular);
            viewHolderOrderDetail.iv_next.setVisibility(View.GONE);

            viewHolderOrderDetail.tv_item_name.setText(String.format(Locale.ENGLISH, "%s %s %d", dataList.get(position).getCart().getItemName(), context.getString(R.string.dash), dataList.get(position).getCart().getItemQuantity()));
            Double tPrice = dataList.get(position).getCart().getItemPrice() * dataList.get(position).getCart().getItemQuantity();
            viewHolderOrderDetail.tv_item_price.setText(String.format(Locale.ENGLISH, "%s %.2f", context.getString(R.string.currency), tPrice));


            //creating item list
            ArrayList<CartItemTopping> toppings = dataList.get(position).getItemToppings();
            StringBuilder toppingsList = new StringBuilder();


            if (dataList.get(position).getItemToppings() != null) {
                for (int i = 0; i < toppings.size(); i++) {
                    toppingsList.append(toppings.get(i).getToppingName()).append(", ");
                }
                if (toppingsList.length() == 0) {
                    viewHolderOrderDetail.tv_subItem_list.setText(context.getString(R.string.no_ingredients));
                } else {
                    viewHolderOrderDetail.tv_subItem_list.setText(toppingsList.toString().substring(0, toppingsList.toString().length() - 2));
                }
            } else
                viewHolderOrderDetail.tv_subItem_list.setText(context.getString(R.string.no_ingredients));
        }
    }

    @Override
    public int getItemCount() {
        return 1 + dataList.size();
    }

    @Override
    public int getItemViewType(int position) {
        if (position == 0)
            return TYPE_HEADER;
        return TYPE_CELL;
    }

    class mainDataViewHolder extends RecyclerView.ViewHolder {
        final TextView tv_orderDate;
        final TextView tv_orderTotalPrice;
        final TextView tv_orderNumber;
        final TextView tv_orderAddress;
        final TextView tv_orderPhone;

        mainDataViewHolder(View itemView) {
            super(itemView);
            tv_orderDate = itemView.findViewById(R.id.tv_orderDate);
            tv_orderTotalPrice = itemView.findViewById(R.id.tv_orderTotalPrice);
            tv_orderNumber = itemView.findViewById(R.id.tv_orderNumber);
            tv_orderAddress = itemView.findViewById(R.id.tv_orderAddress);
            tv_orderPhone = itemView.findViewById(R.id.tv_orderPhone);
        }
    }

    class ViewHolderOrderDetail extends RecyclerView.ViewHolder {
        final TextView tv_item_name;
        final TextView tv_item_price;
        final TextView tv_subItem_list;
        final ImageView iv_next;

        ViewHolderOrderDetail(View itemView) {
            super(itemView);
            tv_item_name = itemView.findViewById(R.id.tv_item_name);
            tv_item_price = itemView.findViewById(R.id.tv_item_price);
            tv_subItem_list = itemView.findViewById(R.id.tv_subItem_list);
            iv_next = itemView.findViewById(R.id.iv_next);
        }
    }
}
