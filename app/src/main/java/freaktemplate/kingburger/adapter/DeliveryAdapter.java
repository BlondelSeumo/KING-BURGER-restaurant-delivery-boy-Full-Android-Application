package freaktemplate.kingburger.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import freaktemplate.kingburger.R;
import freaktemplate.kingburger.activity.Home;
import freaktemplate.kingburger.getSet.DeliveryGetSet;

import java.util.ArrayList;


public class DeliveryAdapter extends BaseAdapter {
    private final ArrayList<DeliveryGetSet> dat;
    private final Context context;
    private final LayoutInflater inflater;


    public DeliveryAdapter(ArrayList<DeliveryGetSet> dat, Context context) {
        this.dat = dat;
        this.context = context;
        inflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
    }


    @Override
    public int getCount() {
        return dat == null ? 0 : dat.size();
    }

    @Override
    public Object getItem(int position) {
        return dat.get(position);
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        View vi = convertView;
        if (convertView == null) {
            vi = inflater.inflate(R.layout.cell_custom_delivery_boy, parent, false);
        }

        ImageView img_user = vi.findViewById(R.id.img_user);
        TextView txt_orderNo = vi.findViewById(R.id.txt_orderNo);
        txt_orderNo.setTypeface(Home.tf_main_bold);
        TextView txt_orderAmount = vi.findViewById(R.id.txt_orderAmount);
        TextView txt_orderQuantity = vi.findViewById(R.id.txt_orderQuantity);
        txt_orderQuantity.setTypeface(Home.tf_main_bold);
        TextView txt_orderDateTime = vi.findViewById(R.id.txt_orderDateTime);
        txt_orderDateTime.setTypeface(Home.tf_main_regular);
        txt_orderAmount.setTypeface(Home.tf_main_regular);

        if (dat != null) {
            switch (dat.get(position).getComplete()) {
                case "Order is preparing":
                    img_user.setImageDrawable(context.getResources().getDrawable(R.drawable.img_orderprocess));
                    break;
                case "Order is Dispatched":
                    img_user.setImageDrawable(context.getResources().getDrawable(R.drawable.img_orderprocess));

                    break;
                case "Order is Delivered":
                    img_user.setImageDrawable(context.getResources().getDrawable(R.drawable.img_ordercomplete));
                    break;
            }
            String orderNo = (context.getString( R.string.orderno )+" "+dat.get(position).getOrderNo()  );
            txt_orderNo.setText(orderNo);

            String orderAmount = String.format(context.getString( R.string.order_amount )+ " "+context.getString(R.string.currency)+dat.get(position).getOrderAmount() );
            txt_orderAmount.setText(orderAmount);

            String itemNum = ( context.getString( R.string.item )+ ""+ dat.get(position).getOrderQuantity() );
            txt_orderQuantity.setText(itemNum);

            txt_orderDateTime.setText(dat.get(position).getOrderTimeDate());
        }
        return vi;
    }



}
