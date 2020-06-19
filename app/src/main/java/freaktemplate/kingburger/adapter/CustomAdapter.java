package freaktemplate.kingburger.adapter;

import android.content.Context;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import freaktemplate.kingburger.R;
import freaktemplate.kingburger.activity.Home;
import freaktemplate.kingburger.observableLayer.CartFav;

import java.util.List;
import java.util.Locale;

public class CustomAdapter extends RecyclerView.Adapter<CustomAdapter.ViewHolderCompleteOrder> {
    private final List<CartFav> dataList;
    private final onClickListen onClickListen;
    private Context context;

    public CustomAdapter(List<CartFav> dataList, onClickListen onClickListen) {
        this.dataList = dataList;
        this.onClickListen = onClickListen;
    }

    @NonNull
    @Override
    public ViewHolderCompleteOrder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        final View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.cell_custom_order, parent, false);
        context = parent.getContext();
        final ViewHolderCompleteOrder viewHolderCompleteOrder = new ViewHolderCompleteOrder(itemView);
        itemView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                onClickListen.onItemClick(v, viewHolderCompleteOrder.getAdapterPosition());
            }
        });
        return viewHolderCompleteOrder;
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolderCompleteOrder holder, int position) {
        holder.tv_item_name.setTypeface(Home.tf_main_bold);
        holder.tv_item_price.setTypeface(Home.tf_main_bold);
        holder.tv_subItem_list.setTypeface(Home.tf_main_regular);
        holder.tv_item_name.setText(dataList.get(position).getItemName());
        holder.tv_item_price.setText(String.format(Locale.ENGLISH, "%s %.2f", context.getString(R.string.currency), dataList.get(position).getItemPrice()));
        holder.tv_subItem_list.setText(dataList.get(position).getItemDescription());

    }

    @Override
    public int getItemCount() {
        return dataList == null ? 0 : dataList.size();
    }


    class ViewHolderCompleteOrder extends RecyclerView.ViewHolder {
        final TextView tv_item_name;
        final TextView tv_item_price;
        final TextView tv_subItem_list;
        final ImageView iv_next;

        ViewHolderCompleteOrder(View itemView) {
            super(itemView);
            tv_item_name = itemView.findViewById(R.id.tv_item_name);
            tv_item_price = itemView.findViewById(R.id.tv_item_price);
            tv_subItem_list = itemView.findViewById(R.id.tv_subItem_list);
            iv_next = itemView.findViewById(R.id.iv_next);
        }
    }

    public interface onClickListen {
        void onItemClick(View v, int position);

    }


}
