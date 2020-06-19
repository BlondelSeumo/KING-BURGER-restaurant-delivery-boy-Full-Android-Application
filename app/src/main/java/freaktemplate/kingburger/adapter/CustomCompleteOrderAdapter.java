package freaktemplate.kingburger.adapter;

import android.content.Context;
import android.content.DialogInterface;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;
import androidx.recyclerview.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import freaktemplate.kingburger.R;
import freaktemplate.kingburger.activity.Home;
import freaktemplate.kingburger.observableLayer.AppDatabase;
import freaktemplate.kingburger.observableLayer.Cart;
import freaktemplate.kingburger.observableLayer.CartItemTopping;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.List;
import java.util.Locale;

public class CustomCompleteOrderAdapter extends RecyclerView.Adapter<CustomCompleteOrderAdapter.ViewHolderCompleteOrder> {
    private final Context context;
    private final List<Cart> dataList;
    private final AppDatabase appDatabase;

    public CustomCompleteOrderAdapter(Context context, List<Cart> dataList) {
        this.context = context;
        this.dataList = dataList;
        appDatabase = AppDatabase.getAppDatabase(context);

    }

    @NonNull
    @Override
    public ViewHolderCompleteOrder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        final View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.cell_complete_order_list, parent, false);
        return new ViewHolderCompleteOrder(itemView);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolderCompleteOrder holder, int position) {
        holder.tv_item_name.setTypeface(Home.tf_main_bold);
        holder.tv_item_price.setTypeface(Home.tf_main_bold);
        holder.tv_subItem_list.setTypeface(Home.tf_main_regular);
        holder.tv_item_name.setText(String.format(Locale.ENGLISH,"%s %s %d", dataList.get(position).getItemName(),context.getString(R.string.dash), dataList.get(position).getItemQuantity()));

        double priceWithQuantity = dataList.get(position).getItemPriceWithTopping()*dataList.get(position).getItemQuantity();
        holder.tv_item_price.setText(String.format(Locale.ENGLISH, "%s %.2f",context.getString(R.string.currency), priceWithQuantity));

        holder.iv_delete.setTag(dataList.get(position).getId());

        List<CartItemTopping> cartItemToppings = appDatabase.cartDao().getTopping(dataList.get(position).getId());
        StringBuilder stringBuilder = new StringBuilder();
        if (cartItemToppings.size() > 0) {
            for (CartItemTopping data : cartItemToppings) {
                stringBuilder.append(data.getToppingName()).append(", ");
            }
            holder.tv_subItem_list.setText(stringBuilder.toString().substring(0, stringBuilder.length() - 2));
        } else {
            holder.tv_subItem_list.setText(R.string.no_ingredients);
        }

    }

    @Override
    public int getItemCount() {
        if (dataList == null)
            return 0;
        return dataList.size();
    }

    public double getTotalPrice() {
        Double tPrice = 0.00;
        if (dataList.size() > 0) {
            for (Cart c : dataList) {
                tPrice = tPrice + (c.getItemPriceWithTopping()*c.getItemQuantity());
            }
        }
        return tPrice;
    }

    public String getJsonOrderFormat() {
        JSONObject jsonObject = new JSONObject();
        JSONArray ja_order = new JSONArray();
        JSONObject jo_item_detail;
        JSONArray ja_item_ingredient = null;
        JSONObject jo_item_ingredient;


        if (dataList.size() > 0) {
            for (int i = 0; i < dataList.size(); i++) {
                jo_item_detail = new JSONObject();
                List<CartItemTopping> cartItemToppings = appDatabase.cartDao().getTopping(dataList.get(i).getId());
                if (cartItemToppings.size() > 0) {
                    ja_item_ingredient = new JSONArray();
                    for (int y = 0; y < cartItemToppings.size(); y++) {
                        try {
                            jo_item_ingredient = new JSONObject();
                            jo_item_ingredient.put("id", String.valueOf(cartItemToppings.get(y).getToppingId()));
                            jo_item_ingredient.put("category", String.valueOf(dataList.get(i).getItemCategoryId()));
                            jo_item_ingredient.put("item_name", cartItemToppings.get(y).getToppingName());
                            jo_item_ingredient.put("type", String.valueOf(cartItemToppings.get(y).getType()));
                            jo_item_ingredient.put("price", String.valueOf(cartItemToppings.get(y).getToppingPrice()));
                            jo_item_ingredient.put("menu_id", String.valueOf(cartItemToppings.get(y).getItemId()));
                            ja_item_ingredient.put(jo_item_ingredient);
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                }
                try {
                    jo_item_detail.put("ItemId", String.valueOf(dataList.get(i).getItemId()));
                    jo_item_detail.put("ItemName", String.valueOf(dataList.get(i).getItemName()));
                    jo_item_detail.put("ItemQty", String.valueOf(dataList.get(i).getItemQuantity()));
                    jo_item_detail.put("ItemAmt", String.valueOf(dataList.get(i).getItemPriceWithTopping()));

                    //calculate item price with topping and its quantity
                    double tPrice = dataList.get(i).getItemPriceWithTopping()*dataList.get(i).getItemQuantity();
                    jo_item_detail.put("ItemTotalPrice",String.valueOf(tPrice));

                    if (ja_item_ingredient == null) {
                        jo_item_detail.putOpt("Ingredients",new JSONArray());
                    } else
                        jo_item_detail.putOpt("Ingredients", ja_item_ingredient);
                        ja_item_ingredient=null;

                } catch (JSONException e) {
                    e.printStackTrace();
                }
                ja_order.put(jo_item_detail);
            }
        }
        try {
            jsonObject.put("Order", ja_order);

        } catch (JSONException e) {
            e.printStackTrace();
        }
        return jsonObject.toString();
    }


    class ViewHolderCompleteOrder extends RecyclerView.ViewHolder {
        final TextView tv_item_name;
        final TextView tv_item_price;
        final TextView tv_subItem_list;
        final ImageView iv_delete;

        ViewHolderCompleteOrder(View itemView) {
            super(itemView);
            tv_item_name = itemView.findViewById(R.id.tv_item_name);
            tv_item_price = itemView.findViewById(R.id.tv_item_price);
            tv_subItem_list = itemView.findViewById(R.id.tv_subItem_list);
            iv_delete = itemView.findViewById(R.id.iv_delete);
            iv_delete.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(final View v) {

                    AlertDialog.Builder alert = new AlertDialog.Builder(
                            context);
                    alert.setTitle("Alert!!");
                    alert.setIcon(context.getResources().getDrawable(R.drawable.ic_deletecart));
                    alert.setMessage(R.string.delete_item_cart);
                    alert.setPositiveButton(R.string.yes, new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            //do your work here
                            appDatabase.cartDao().deleteCartItem((Integer) v.getTag());
                            appDatabase.cartDao().deleteCartToppingItem((Integer) v.getTag());
                            dialog.dismiss();
                        }
                    });
                    alert.setNegativeButton(R.string.no, new DialogInterface.OnClickListener() {

                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            dialog.dismiss();
                        }
                    });
                    alert.show();
                }
            });
        }
    }


}
